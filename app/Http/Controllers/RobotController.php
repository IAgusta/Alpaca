<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\robot;
use App\Models\Sensor;
use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Str;

class RobotController extends Controller
{
    public function setESP32IP(Request $request)
    {
        $request->validate([
            'esp32_ip' => 'required|ip',
        ]);

        // Store in session
        Session::put('esp32_ip', $request->input('esp32_ip'));

        return response()->json(['message' => 'ESP32 IP saved', 'esp32_ip' => $request->input('esp32_ip')]);
    }

    private function getESP32IP()
    {
        return Session::get('esp32_ip', null);
    }

    public function sendCommand($command, Request $request)
    {
        $ip = $request->query('ip');
        
        if (!$ip) {
            return response()->json(['error' => 'IP not provided'], 400);
        }

        try {
            $response = Http::get("http://$ip/command/$command");
            return response()->json([
                'success' => true,
                'esp32_response' => $response->body()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function handleWebSocket(Request $request)
    {
        $esp32_ip = $this->getESP32IP();
        $command = $request->input('command');
        
        if (!$esp32_ip) {
            return response()->json(['error' => 'ESP32 not connected'], 400);
        }

        try {
            $response = Http::get("http://$esp32_ip/ws-command", [
                'command' => $command
            ]);
            return response()->json(['success' => true, 'data' => $response->json()]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setSpeed(Request $request)
    {
        $esp32_ip = $this->getESP32IP();

        if (!$esp32_ip) {
            return response()->json(['error' => 'ESP32 IP not set'], 400);
        }

        $speed = $request->input('value', 100);
        $response = Http::get("http://$esp32_ip/speed?value=" . $speed);
        return response()->json(['message' => 'Speed updated', 'esp32_response' => $response->body()]);
    }

    public function connect(Request $request)
    {
        try {
            $ip = $request->query('ip');
            $response = Http::timeout(5)->get("http://{$ip}/status");
            
            if ($response->successful()) {
                return response()->json(['success' => true]);
            }
            
            throw new \Exception('Connection failed');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function createApiKey()
    {
        $user = Auth::user();
        $robot = Robot::firstOrCreate(
            ['user_id' => $user->id],
            ['api_key' => Str::random(32)]
        );
        
        return response()->json([
            'api_key' => $robot->api_key,
            'can_reset' => $robot->canResetApiKey(),
            'next_reset' => $robot->api_key_last_reset?->addWeek()
        ]);
    }

    public function generateApiKey(Request $request)
    {
        $user = Auth::user();
        $robot = robot::firstOrCreate(['user_id' => $user->id]);
        
        if (!$robot->canResetApiKey()) {
            return response()->json([
                'error' => 'You can only reset your API key once per week'
            ], 429);
        }

        $apiKey = $robot->generateApiKey();
        return response()->json(['api_key' => $apiKey]);
    }

    public function getApiKey()
    {
        $user = Auth::user();
        $robot = Robot::firstOrCreate(['user_id' => $user->id]);

        $nextReset = $robot->api_key_last_reset ? 
            $robot->api_key_last_reset->addWeek()->format('Y-m-d\TH:i:s\Z') : 
            null;

        return response()->json([
            'api_key' => $robot->api_key,
            'can_reset' => $robot->canResetApiKey(),
            'next_reset' => $nextReset
        ]);
    }

    public function getPendingCommand($apiKey)
    {
        $robot = Robot::where('api_key', $apiKey)->first();

        if (!$robot) {
            return response()->json(['error' => 'Invalid API Key'], 404);
        }

        if ($robot->status == 0 && $robot->command !== null) {
            $robot->status = 1;
            $robot->save();
            return response()->json(['command' => json_decode($robot->command)]);
        }

        return response()->json(['message' => 'No pending commands'], 204);
    }

    public function updateCommandStatusByKey(Request $request, $apiKey)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2'
        ]);

        $robot = Robot::where('api_key', $apiKey)->first();

        if (!$robot) {
            return response()->json(['error' => 'Invalid API key'], 404);
        }

        $robot->status = (int) $request->status;

        if ($robot->status === 2) {
            $robot->command = null;
        }

        $robot->save();

        return response()->json([
            'message' => 'Status updated',
            'status' => $robot->status
        ]);
    }

    public function proxyRequest(Request $request)
    {
        $target = $request->query('target');
        $command = $request->query('command');
        
        if (!$target) {
            return response()->json(['error' => 'No target IP provided'], 400);
        }

        try {
            // Parse command string into endpoint and parameters
            [$endpoint, $params] = $this->parseCommand($command);
            
            // Make request to ESP32
            $response = Http::timeout(5)->get("http://{$target}/{$endpoint}", $params);
            
            return response()->json([
                'success' => true,
                'data' => $response->json() ?? $response->body()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function proxySensor(Request $request)
    {
        $target = $request->query('target');
        if (!$target) {
            return response()->json(['error' => 'No target IP provided'], 400);
        }
        try {
            // Assume your ESP32 exposes /sensor for sensor data as JSON
            $response = Http::timeout(5)->get("http://{$target}/sensor");
            return response($response->body(), $response->status())
                    ->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function parseCommand($command)
    {
        if (!$command) return ['status', []];

        $parts = explode(':', $command);

        switch ($parts[0]) {
            // Support direct commands like "forward", "left", etc.
            case 'move':
                return ['move', ['dir' => $parts[1] ?? 'stop']];
            case 'forward':
            case 'backward':
            case 'left':
            case 'right':
            case 'stop':
                return ['move', ['dir' => $parts[0]]];

            // Complexities commands like "linefollower", "wall avoider", "speed", etc.
            case 'line':
                return ['line', ['active' => $parts[1] === 'on' ? 1 : 0]];
            case 'wall':
                return ['wall', ['active' => $parts[1] === 'on' ? 1 : 0]];
            case 'speed':
                return ['speed', ['value' => $parts[1] ?? 100]];
            case 'wallspeed':
                return ['speed', ['value' => $parts[1] ?? 100, 'mode' => 'wall']];
            case 'distance':
                return ['distance', ['value' => $parts[1] ?? 25]];
            default:
                return ['status', []];
        }
    }

    public function storeCommand(Request $request)
    {
        $user = Auth::user();
        $robot = Robot::where('user_id', $user->id)->first();

        if (!$robot || !$robot->api_key) {
            return response()->json(['error' => 'No robot or API key found'], 404);
        }

        $command = $request->input('command');
        if (!isset($command['action'])) {
            return response()->json(['error' => 'Invalid command format'], 400);
        }

        $robot->command = json_encode($command);
        $robot->status = 0;
        $robot->save();

        return response()->json([
            'success' => true,
            'message' => 'Command stored successfully',
            'command' => $command
        ]);
    }

    public function storeSensorData(Request $request)
    {
        $apiKey = $request->input('api_key');
        $sensorType = $request->input('sensor_type');
        $value = $request->input('value');

        $robot = Robot::where('api_key', $apiKey)->first();
        if (!$robot) {
            return response()->json(['error' => 'Invalid API key'], 403);
        }

        // Create new log entry
        Sensor::create([
            'robot_id' => $robot->id,
            'sensor_type' => $sensorType,
            'value' => $value
        ]);

        return response()->json(['success' => true]);
    }
}
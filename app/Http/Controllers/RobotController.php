<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\robot;
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

    public function proxyRequest(Request $request)
    {
        $target = $request->query('target');
        $command = $request->query('command', 'status');
        
        if (!$target) {
            return response()->json(['error' => 'No target IP provided'], 400);
        }

        try {
            $response = Http::timeout(5)->get("http://{$target}/{$command}");
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
}
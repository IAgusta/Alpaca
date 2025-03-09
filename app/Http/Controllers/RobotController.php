<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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

        // Implement the logic to send the command to the ESP32
        // For example, you can send an HTTP request to the ESP32

        // Assuming the command is sent successfully
        return response()->json(['success' => true]);
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
        $ip = $request->query('ip');

        // Implement the logic to test the connection to the ESP32
        $response = Http::get("http://$ip");

        if ($response->successful() && $response->json('status') === 'ESP32 Robot Server Active') {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 500);
    }

    public function updateSpeed(Request $request)
    {
        $ip = $request->query('ip');
        $value = $request->query('value');

        // Implement the logic to update the motor speed on the ESP32
        // For example, you can send an HTTP request to the ESP32

        // Assuming the speed is updated successfully
        return response()->json(['success' => true]);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class RobotConfigController extends Controller
{
    /**
     * Store or update the user's robot configuration in Redis.
     */
    public function store(Request $request)
    {
        $userId = Auth::id() ?? $request->input('user_id');

        // Basic validation
        $validated = $request->validate([
            'controller'   => 'required|string|in:ESP32,ESP8266',
            'components'   => 'required|array|min:1',
            'components.*.type' => 'required|string',
            'components.*.pins' => 'required|array',
            'isPublic'     => 'required|boolean',
        ]);

        $key = "user:{$userId}:robot";

        // Clear existing data first
        Redis::del($key);

        // Data structure for storage
        $data = [
            'user_id'    => $userId,
            'controller' => $validated['controller'],
            'show'       => !$validated['isPublic'],
            'component'  => collect($validated['components'])->mapWithKeys(function ($item) {
                $pins = collect($item['pins'])->filter()->values()->implode(',');
                return [$item['type'] => $pins];
            }),
        ];

        // Save as JSON with NX option removed to allow updates
        Redis::set($key, json_encode($data));

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Get current user's robot configuration from Redis.
     */
    public function show(Request $request)
    {
        $userId = Auth::id() ?? $request->input('user_id');
        $key = "user:{$userId}:robot";
        $robot = Redis::get($key);

        if (!$robot) {
            return response()->json(['success' => false, 'message' => 'No config found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => json_decode($robot, true),
        ]);
    }
}
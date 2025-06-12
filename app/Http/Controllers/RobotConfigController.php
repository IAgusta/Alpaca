<?php

namespace App\Http\Controllers;

use App\Models\robot;
use App\Models\robotDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RobotConfigController extends Controller
{
    /**
     * Store or update the user's robot configuration in the database.
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

        // Get or create robot record
        $robot = robot::firstOrCreate(['user_id' => $userId]);

        // Format components data
        $components = collect($validated['components'])->mapWithKeys(function ($item) {
            $pins = collect($item['pins'])->filter()->values()->implode(',');
            return [$item['type'] => $pins];
        })->toJson();

        // Update or create robot detail
        $robotDetail = robotDetail::updateOrCreate(
            ['robot_id' => $robot->id],
            [
                'controller' => $validated['controller'],
                'components' => $components,
                'isPublic' => $validated['isPublic']
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $userId,
                'controller' => $validated['controller'],
                'show' => !$validated['isPublic'],
                'component' => json_decode($components, true)
            ],
        ]);
    }

    /**
     * Get current user's robot configuration from the database.
     */
    public function show(Request $request)
    {
        $userId = Auth::id() ?? $request->input('user_id');
        $robot = robot::where('user_id', $userId)->first();
        
        if (!$robot) {
            return response()->json(['success' => false, 'message' => 'No config found'], 404);
        }

        $detail = $robot->robotDetail;
        if (!$detail) {
            return response()->json(['success' => false, 'message' => 'No config found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $userId,
                'controller' => $detail->controller,
                'show' => !$detail->isPublic,
                'component' => json_decode($detail->components, true)
            ],
        ]);
    }
}
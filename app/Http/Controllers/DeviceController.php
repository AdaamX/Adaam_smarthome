<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function index(): JsonResponse
    {
        $devices = Device::all()->pluck('state', 'name')->toArray();
        return response()->json($devices);
    }

    public function toggle(Request $request): JsonResponse
    {
        Log::debug('Toggle request received:', $request->all());

        $request->validate([
            'name' => 'required|string',
            'state' => 'required|boolean'
        ]);

        $device = Device::where('name', $request->name)->firstOrFail();

        $device->state = $request->state;
        $saved = $device->save();

        Log::debug('Device state saved:', ['name' => $device->name, 'state' => $device->state, 'saved' => $saved]);

        return response()->json([
            'success' => true,
            'name' => $device->name,
            'state' => $device->state
        ]);
    }
}

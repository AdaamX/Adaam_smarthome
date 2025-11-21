<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function index(): JsonResponse
    {
        $devices = Device::all()->pluck('state', 'name')->toArray();
        return response()->json($devices);
    }

    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|in:lamp,fan,door,window,pc,tv'
        ]);

        $device = Device::where('name', $request->name)->first();
        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $device->state = !$device->state;
        $device->save();

        return $this->index();
    }
}

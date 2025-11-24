<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['name' => 'lamp', 'label' => 'Lamp', 'icon' => '💡', 'state' => 0],
            ['name' => 'fan', 'label' => 'Fan', 'icon' => '🌀', 'state' => 0],
            ['name' => 'door', 'label' => 'Door', 'icon' => '🚪', 'state' => 0],
            ['name' => 'window', 'label' => 'Window', 'icon' => '🪟', 'state' => 0],
            ['name' => 'pc', 'label' => 'PC', 'icon' => '💻', 'state' => 0],
            ['name' => 'tv', 'label' => 'TV', 'icon' => '📺', 'state' => 0],
        ];

        foreach ($devices as $device) {
            Device::create($device);
        }
    }
}

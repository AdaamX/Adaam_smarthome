<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = ['lamp', 'fan', 'door', 'window', 'pc', 'tv'];

        foreach ($devices as $device) {
            Device::create([
                'name' => $device,
                'state' => 0
            ]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();   // contoh: lamp, fan, pc, tv
            $table->string('label');            // contoh: Lamp, Fan, PC
            $table->string('icon')->nullable(); // emoji atau path icon
            $table->boolean('state')->default(0); // 0 = off, 1 = on
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

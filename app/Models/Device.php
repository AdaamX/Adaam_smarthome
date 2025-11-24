<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
    /**
     * The attributes that should be cast to native types.
     * Casting 'state' to boolean ensures it is always treated as a boolean value.
     *
     * @var array
     */
    protected $casts = [
        'state' => 'boolean',
    ];
    protected $fillable = ['name', 'state'];

}

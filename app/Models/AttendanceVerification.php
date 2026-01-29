<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceVerification extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'verified_at',
        'payload',
    ];

    protected $casts = [
        'payload'    => 'array',
        'expires_at' => 'datetime',
        'verified_at'=> 'datetime',
    ];
}

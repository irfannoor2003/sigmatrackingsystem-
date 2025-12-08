<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'salesman_id',
        'date',
        'clock_in',
        'clock_out',
        'lat',
        'lng'
    ];

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}

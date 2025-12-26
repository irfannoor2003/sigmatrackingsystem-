<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyOffDay extends Model
{
    protected $fillable = ['off_date', 'reason'];

    protected $casts = [
        'off_date' => 'date',
    ];
}

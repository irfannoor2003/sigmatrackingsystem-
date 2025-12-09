<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'salesman_id',
        'purpose',
        'notes',
        'status',
        'started_at',
        'completed_at',

    ];

    // Cast timestamps to Carbon instances
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'images' => 'array',

    ];

    // Relationships
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function salesman() {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}

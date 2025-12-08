<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Add this fillable property
    protected $fillable = [
        'name',
        'contact_person',
        'phone1',
        'phone2',
        'email',
        'city_id',
        'salesman_id',
    ];

    // Relationships (optional)
    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }
}

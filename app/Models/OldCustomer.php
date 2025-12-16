<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class OldCustomer extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'address',
        'email',
        'contact',
        'salesman_id',
    ];

    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}

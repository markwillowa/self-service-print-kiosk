<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'avatar',
        'kiosk_name',
        'name',
        'owner',
        'address',
        'email',
        'contact_number',
    ];
}

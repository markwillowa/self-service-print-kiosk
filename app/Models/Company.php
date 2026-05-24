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
        'black_price_per_page',
        'color_price_per_page',
        'allow_custom_pricing',
    ];

    protected $casts = [
        'black_price_per_page' => 'integer',
        'color_price_per_page' => 'integer',
        'allow_custom_pricing' => 'boolean',
    ];
}

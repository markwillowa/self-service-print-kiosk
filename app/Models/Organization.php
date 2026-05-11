<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Organization extends Model
{
    protected $fillable = [
        'uuid',
        'school_name',
        'school_code',
        'contact_person',
        'contact_number',
        'email',
        'address',
        'city',
        'province',
        'region',
        'country',
        'kiosk_name',
        'unit_serial_number',
        'is_registered',
        'registered_at',
    ];

    protected static function booted(): void
    {
        static::creating(function (Organization $organization) {
            if (! $organization->uuid) {
                $organization->uuid = (string) Str::uuid();
            }
        });
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class);
    }
}

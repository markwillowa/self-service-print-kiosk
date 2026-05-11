<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'username',
        'password',
        'pin_code',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'pin_code',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}

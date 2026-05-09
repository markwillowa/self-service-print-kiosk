<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    protected $fillable = [
        'original_filename',
        'file_path',
        'pages',
        'price_per_page',
        'total_amount',
        'paid_amount',
        'status',
    ];

    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }
}

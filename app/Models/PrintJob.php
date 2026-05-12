<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PrintJob extends Model
{
    protected $fillable = [
        'uuid',
        'original_filename',
        'original_file_path',
        'converted_pdf_path',
        'original_extension',
        'conversion_status',
        'source',
        'file_path',
        'page_selection',
        'selected_pages_count',
        'filtered_pdf_path',
        'preview_pdf_path',
        'orientation',
        'paper_size',
        'pages',
        'print_mode',
        'black_price_per_page',
        'color_price_per_page',
        'price_per_page',
        'total_amount',
        'paid_amount',
        'status',
        'expires_at',
        'cancelled_at',
    ];

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    protected static function booted(): void
    {
        static::creating(function (PrintJob $printJob) {
            if (! $printJob->uuid) {
                $printJob->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}

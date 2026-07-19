<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    // Disables mass assignment protection so our controller can handle the inputs
    protected $guarded = [];

    // Explicitly cast attributes to ensure smooth data binding with Oracle DB structures
    protected $casts = [
        'total_pos' => 'integer',
        'total_po_value' => 'decimal:2',
        'pending_payment' => 'decimal:2',
        'rating' => 'integer',
        'deleted_at' => 'datetime'
    ];

    /**
     * Boot function to enforce dynamic custom sequence-based ID generation
     * and handle automated values before row compilation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            // Generates sequential code e.g., VND-2026-0001
            $year = Carbon::now()->format('Y');
            $latest = static::withTrashed()
                ->where('vendor_code', 'LIKE', "VND-{$year}-%")
                ->orderBy('id', 'desc')
                ->first();

            $sequence = $latest ? intval(substr($latest->vendor_code, -4)) + 1 : 1;
            $vendor->vendor_code = 'VND-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }
}
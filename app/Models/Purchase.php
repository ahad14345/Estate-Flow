<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Purchase extends Model
{
    use HasFactory; // Removed SoftDeletes from here

    protected $fillable = [
        'purchase_order_no', 'purchase_date', 'expected_delivery_date',
        'vendor_name', 'contractor_name', 'project_name', 'category',
        'item_name', 'item_description', 'quantity', 'unit',
        'unit_price', 'discount', 'tax', 'total_amount',
        'payment_method', 'payment_status', 'purchase_status',
        'invoice_attachment', 'remarks', 'created_by'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expected_delivery_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            // 1. Automatic Dynamic PO Serial Code Generation Engine
            $year = Carbon::now()->format('Y');
            
            // Replaced static::withTrashed() with standard static:: query wrapper
            $latestPO = static::where('purchase_order_no', 'LIKE', "PO-{$year}-%")
                ->orderBy('id', 'desc')
                ->first();

            $sequence = $latestPO ? intval(substr($latestPO->purchase_order_no, -4)) + 1 : 1;
            $purchase->purchase_order_no = 'PO-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // 2. Strict Core Financial Formula Enforcement Hook
            $purchase->total_amount = ($purchase->quantity * $purchase->unit_price) + $purchase->tax - $purchase->discount;
        });

        static::updating(function ($purchase) {
            // Recalculate financial fields dynamically upon mutation actions
            $purchase->total_amount = ($purchase->quantity * $purchase->unit_price) + $purchase->tax - $purchase->discount;
        });
    }
}
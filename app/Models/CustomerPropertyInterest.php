<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPropertyInterest extends Model
{
    protected $table = 'customer_property_interests';

    protected $fillable = [
        'customer_id',
        'property_name',
        'property_reference',
        'interest_level',
        'visit_date',
        'remarks',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_code',
        'customer_id',
        'full_name',
        'email',
        'phone_number',
        'lead_source',
        'priority',
        'status',
        'assigned_employee',
        'budget',
        'notes',
        'converted_at',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'converted_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}

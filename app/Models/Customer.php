<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'full_name',
        'email',
        'phone_number',
        'national_id',
        'address',
        'city',
        'customer_type',
        'preferred_property_type',
        'budget',
        'assigned_employee',
        'status',
        'notes',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function propertyInterests()
    {
        return $this->hasMany(CustomerPropertyInterest::class);
    }

    public function activities()
    {
        return $this->hasMany(CustomerActivity::class)->orderByDesc('occurred_at');
    }
}

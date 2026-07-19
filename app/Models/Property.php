<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'project_id',
        'title',
        'type',
        'block',
        'size_sqft',
        'status',
        'price',
        'details',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationship to Parent Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function properties()
{
    return $this->hasMany(Property::class);
}
}
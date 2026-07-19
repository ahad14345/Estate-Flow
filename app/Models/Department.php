<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model {
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'dept_head', 'status'];
    protected $casts = ['deleted_at' => 'datetime'];

    public function employees() {
        return $this->hasMany(Employee::class);
    }
}
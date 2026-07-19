<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Employee extends Model {
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $hidden = ['password'];
    protected $casts = [
        'joining_date' => 'date',
        'dob' => 'date',
        'salary' => 'decimal:2',
        'deleted_at' => 'datetime'
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($employee) {
            $year = Carbon::now()->format('Y');
            $latest = static::withTrashed()
                ->where('employee_code', 'LIKE', "EMP-{$year}-%")
                ->orderBy('id', 'desc')->first();
            $sequence = $latest ? intval(substr($latest->employee_code, -4)) + 1 : 1;
            $employee->employee_code = 'EMP-'.$year.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
}
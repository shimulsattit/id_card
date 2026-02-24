<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'logo',
        'classes',
        'sections', // Array of sections
        'sessions', // Array of sessions
        'unique_id',
    ];

    protected $casts = [
        'classes' => 'array',
        'sections' => 'array',
        'sessions' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($school) {
            // Get the maximum existing numeric unique_id
            $maxId = \App\Models\School::all()
                ->pluck('unique_id')
                ->filter(fn($id) => is_numeric($id))
                ->max();

            $school->unique_id = $maxId ? $maxId + 1 : 1001;
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}

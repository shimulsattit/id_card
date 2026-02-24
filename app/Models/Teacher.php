<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'designation',
        'joining_date',
        'mobile',
        'blood_group',
        'photo',
        'signature'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

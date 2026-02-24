<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'registration_no',
        'name',
        'class',
        'section',
        'roll',
        'session',
        'father_name',
        'mother_name',
        'dob',
        'blood_group',
        'contact_no',
        'photo',
        'signature'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

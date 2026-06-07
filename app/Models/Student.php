<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'academic_class_id',
        'section_id',
        'student_id',
        'registration_no',
        'name',
        'class', // Keeping old fields for compatibility during migration
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

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }

    public function academicSection()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }


    public function getBloodGroup($bg)
    {
        $groups = [
            'A+' => 'A Positive',
            'A-' => 'A Negative',
            'B+' => 'B Positive',
            'B-' => 'B Negative',
            'O+' => 'O Positive',
            'O-' => 'O Negative',
            'AB+' => 'AB Positive',
            'AB-' => 'AB Negative',
        ];

        return $groups[strtoupper($bg)] ?? $bg;
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

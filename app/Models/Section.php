<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['academic_class_id', 'name', 'capacity'];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class);
    }
}


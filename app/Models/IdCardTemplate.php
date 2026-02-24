<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'background_image',
        'background_image_back',
        'school_id',
        'type',
        'text_color',
        'name_color',
        'data_color',
        'photo_border_color',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}

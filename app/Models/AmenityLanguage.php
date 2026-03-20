<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityLanguage extends Model
{
    use HasFactory;

    protected $table = 'amenity_language';
    public $timestamps = false;

    public function amenities()
    {
        return $this->belongsTo(Amenity::class, 'amenity_id', 'id');
    }
}

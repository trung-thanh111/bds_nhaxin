<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmenityCatalogueLanguage extends Model
{
    use HasFactory;

    protected $table = 'amenity_catalogue_language';
    public $timestamps = false;

    public function amenity_catalogues()
    {
        return $this->belongsTo(AmenityCatalogue::class, 'amenity_catalogue_id', 'id');
    }
}

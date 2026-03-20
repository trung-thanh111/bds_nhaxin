<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealEstateCatalogueLanguage extends Model
{
    use HasFactory;

    protected $table = 'real_estate_catalogue_language';

    protected $fillable = [
        'real_estate_catalogue_id',
        'language_id',
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
        'url'
    ];

    public function real_estate_catalogues(){
        return $this->belongsTo(RealEstateCatalogue::class, 'real_estate_catalogue_id', 'id');
    }

    public function languages(){
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCatalogueLanguage extends Model
{
    use HasFactory;

    protected $table = 'gallery_catalogue_language';

    protected $fillable = [
        'gallery_catalogue_id',
        'language_id',
        'name',
        'description',
        'content',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'canonical',
    ];

    public function gallery_catalogues()
    {
        return $this->belongsTo(GalleryCatalogue::class, 'gallery_catalogue_id', 'id');
    }

    public function languages()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }
}

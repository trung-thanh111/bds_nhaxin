<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasQuery;

class GalleryCatalogue extends Model
{
    use HasFactory, SoftDeletes, HasQuery;

    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id'
    ];

    protected $table = 'gallery_catalogues';

    protected $relationable = [];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'gallery_catalogue_language', 'gallery_catalogue_id', 'language_id')
            ->withPivot(
                'gallery_catalogue_id',
                'language_id',
                'name',
                'canonical',
                'meta_title',
                'meta_keyword',
                'meta_description',
                'description',
                'content'
            )->withTimestamps();
    }

    public function gallery_catalogue_language()
    {
        return $this->hasMany(GalleryCatalogueLanguage::class, 'gallery_catalogue_id', 'id')->where('language_id', '=', 1);
    }

    public static function isNodeCheck($id = 0)
    {
        $galleryCatalogue = GalleryCatalogue::find($id);

        if ($galleryCatalogue->rgt - $galleryCatalogue->lft !== 1) {
            return false;
        }

        return true;
    }

    public function direct_children()
    {
        return $this->hasMany(GalleryCatalogue::class, 'parent_id', 'id');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'gallery_catalogue_id', 'id');
    }
}

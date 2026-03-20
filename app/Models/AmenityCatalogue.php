<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class AmenityCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'publish',
        'order',
        'user_id',
    ];

    protected $table = 'amenity_catalogues';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'amenity_catalogue_language', 'amenity_catalogue_id', 'language_id')
            ->withPivot(
                'amenity_catalogue_id',
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

    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'amenity_catalogue_id', 'id');
    }

    public function amenity_catalogue_language()
    {
        return $this->hasMany(AmenityCatalogueLanguage::class, 'amenity_catalogue_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Amenity extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'amenity_catalogue_id',
        'image',
        'icon',
        'code',
        'publish',
        'order',
        'user_id',
    ];

    protected $table = 'amenities';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'amenity_language', 'amenity_id', 'language_id')
            ->withPivot(
                'amenity_id',
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

    public function amenity_catalogues()
    {
        return $this->belongsTo(AmenityCatalogue::class, 'amenity_catalogue_id', 'id');
    }

    public function real_estates()
    {
        return $this->belongsToMany(RealEstate::class, 'amenity_real_estate', 'amenity_id', 'real_estate_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_amenity', 'amenity_id', 'project_id');
    }
}

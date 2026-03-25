<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class RealEstate extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'real_estates';

    protected $fillable = [
        'code',
        'real_estate_catalogue_id',
        'agent_id',
        'image',
        'old_province_code',
        'old_district_code',
        'old_ward_code',
        'old_province_name',
        'old_district_name',
        'old_ward_name',
        'province_code',
        'province_name',
        'district_code',
        'district_name',
        'ward_code',
        'ward_name',
        'street',
        'iframe_map',
        'area',
        'usable_area',
        'land_area',
        'year_built',
        'floor_count',
        'floor',
        'total_floors',
        'bedrooms',
        'bathrooms',
        'house_direction',
        'balcony_direction',
        'view',
        'ownership_type',
        'interior',
        'land_type',
        'land_width',
        'land_length',
        'road_frontage',
        'road_width',
        'is_corner_lot',
        'is_main_road',
        'has_basement',
        'has_rooftop',
        'has_garage',
        'block_tower',
        'apartment_code',
        'video_url',
        'tour_url',
        'album',
        'price_sale',
        'price_rent',
        'price_unit',
        'transaction_type',
        'publish',
        'order',
        'user_id',
        'follow',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'real_estate_language', 'real_estate_id', 'language_id')
            ->withPivot('name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical')
            ->withTimestamps();
    }

    public function catalogue()
    {
        return $this->belongsTo(RealEstateCatalogue::class, 'real_estate_catalogue_id');
    }

    public function floorplans()
    {
        return $this->hasMany(Floorplan::class, 'real_estate_id', 'id')->orderBy('order', 'asc');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_real_estate', 'real_estate_id', 'amenity_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Project extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $table = 'projects';

    protected $fillable = [
        'code',
        'name',
        'slug',
        'project_catalogue_id',
        'is_project',
        'apartment_count',
        'block_count',
        'area',
        'legal_status',
        'status',
        'publish',
        'is_featured',
        'is_hot',
        'is_urgent',
        'order',
        'view_count',
        'cover_image',
        'has_video',
        'video_url',
        'video_embed',
        'has_virtual_tour',
        'virtual_tour_url',
        'province_code',
        'province_name',
        'district_code',
        'district_name',
        'ward_code',
        'ward_name',
        'old_province_code',
        'old_province_name',
        'old_district_code',
        'old_district_name',
        'old_ward_code',
        'old_ward_name',
        'street',
        'album',
        'iframe_map',
        'lat',
        'long',
        'extra_fields',
        'published_at',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'project_language', 'project_id', 'language_id')
            ->withPivot('name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical')
            ->withTimestamps();
    }

    public function catalogue()
    {
        return $this->belongsTo(ProjectCatalogue::class, 'project_catalogue_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'project_amenity', 'project_id', 'amenity_id');
    }

    public function floorplans()
    {
        return $this->belongsToMany(Floorplan::class, 'project_floorplan', 'project_id', 'floorplan_id');
    }

    public function related_projects()
    {
        return $this->belongsToMany(Project::class, 'project_relation', 'project_id', 'related_project_id');
    }
}

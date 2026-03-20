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
        'real_estate_id',
        'project_catalogue_id',
        'agent_id',
        'type_code',
        'transaction_type',
        'is_project',
        'price',
        'price_unit',
        'price_vnd',
        'price_negotiable',
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
        'extra_fields',
        'published_at',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'project_language', 'project_id', 'language_id')
            ->withPivot('name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical')
            ->withTimestamps();
    }

    public function real_estate()
    {
        return $this->belongsTo(RealEstate::class, 'real_estate_id');
    }

    public function catalogue()
    {
        return $this->belongsTo(ProjectCatalogue::class, 'project_catalogue_id');
    }

    public function listing_real_estates()
    {
        return $this->hasMany(RealEstate::class, 'project_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}

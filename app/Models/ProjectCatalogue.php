<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class ProjectCatalogue extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'lft',
        'rgt',
        'level',
        'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
    ];

    protected $table = 'project_catalogues';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'project_catalogue_language', 'project_catalogue_id', 'language_id')
            ->withPivot(
                'project_catalogue_id',
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

    public function projects()
    {
        return $this->hasMany(Project::class, 'project_catalogue_id');
    }

    public function project_catalogue_language()
    {
        return $this->hasMany(ProjectCatalogueLanguage::class, 'project_catalogue_id', 'id')->where('language_id', '=', 1);
    }

    public static function isNodeCheck($id = 0)
    {
        $projectCatalogue = ProjectCatalogue::find($id);
        if ($projectCatalogue->rgt - $projectCatalogue->lft !== 1) {
            return false;
        }
        return true;
    }

    public function direct_children()
    {
        return $this->hasMany(ProjectCatalogue::class, 'parent_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCatalogueLanguage extends Model
{
    protected $table = 'project_catalogue_language';
    public $timestamps = true;

    protected $fillable = [
        'project_catalogue_id',
        'language_id',
        'name',
        'description',
        'content',
        'canonical',
        'meta_title',
        'meta_keyword',
        'meta_description',
    ];

    public function project_catalogues()
    {
        return $this->belongsTo(ProjectCatalogue::class, 'project_catalogue_id', 'id');
    }
}

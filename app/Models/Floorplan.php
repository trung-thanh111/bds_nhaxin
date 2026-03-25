<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueryScopes;

class Floorplan extends Model
{
    use HasFactory, SoftDeletes, QueryScopes;

    protected $fillable = [
        'real_estate_id',
        'image',
        'publish',
        'order',
        'user_id',
    ];

    protected $table = 'floorplans';

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'floorplan_language', 'floorplan_id', 'language_id')
            ->withPivot(
                'floorplan_id',
                'language_id',
                'name',
                'description'
            )->withTimestamps();
    }

    public function real_estates()
    {
        return $this->belongsTo(RealEstate::class, 'real_estate_id', 'id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_floorplan', 'floorplan_id', 'project_id');
    }
}

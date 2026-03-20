<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class FloorplanRoom extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;

    protected $fillable = [
        'floorplan_id',
        'room_name',
        'area_sqm',
        'sort_order',
        'user_id',
    ];

    protected $casts = [];

    protected $relationable = ['users', 'floorplans'];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function floorplans(): BelongsTo
    {
        return $this->belongsTo(Floorplan::class, 'floorplan_id', 'id');
    }
}

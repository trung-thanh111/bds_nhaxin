<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class PropertyFacility extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;

    protected $fillable = [
        'property_id',
        'icon',
        'name',
        'description',
        'image',
        'sort_order',
        'publish',
        'user_id',
    ];

    protected $casts = [];

    protected $relationable = ['users', 'properties'];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function properties(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}

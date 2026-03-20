<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class Gallery extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;

    protected $fillable = [
        'gallery_catalogue_id',
        'real_estate_id',
        'image',
        'album',
        'publish',
        'user_id',
    ];

    protected $casts = [
        'album' => 'json'
    ];

    protected $relationable = ['users', 'real_estates', 'gallery_catalogues'];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function real_estates(): BelongsTo
    {
        return $this->belongsTo(RealEstate::class, 'real_estate_id', 'id');
    }

    public function gallery_catalogues(): BelongsTo
    {
        return $this->belongsTo(GalleryCatalogue::class, 'gallery_catalogue_id', 'id');
    }
}

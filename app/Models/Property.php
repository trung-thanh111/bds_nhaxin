<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class Property extends Model 
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;
    
    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'description_short',
        'description',
        'price',
        'price_unit',
        'publish',
        'area_sqm',
        'bedrooms',
        'bathrooms',
        'parking_spots',
        'floors',
        'address',
        'district',
        'city',
        'latitude',
        'longitude',
        'year_built',
        'video_tour_url',
        'image',
        'seo_title',
        'seo_description',
        'user_id',
    ];
    
    protected $casts = [
       
    ];
    
    protected $relationable = [
       
    ];
    
    public function getRelationable(){
        return $this->relationable;
    }
    
    public function users(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
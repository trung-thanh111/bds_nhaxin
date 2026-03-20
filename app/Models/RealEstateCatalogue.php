<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueryScopes;

class RealEstateCatalogue extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'parent_id',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'short_name'
    ];

    protected $table = 'real_estate_catalogues';

    public function languages(){
        return $this->belongsToMany(Language::class, 'real_estate_catalogue_language' , 'real_estate_catalogue_id', 'language_id')
        ->withPivot(
            'real_estate_catalogue_id',
            'language_id',
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content',
            'url'
        )->withTimestamps();
    }

    public function real_estates()
    {
        return $this->hasMany(RealEstate::class, 'real_estate_catalogue_id');
    }

    public function real_estate_catalogue_language(){
        return $this->hasMany(RealEstateCatalogueLanguage::class, 'real_estate_catalogue_id', 'id')->where('language_id','=',1);
    }

    public static function isNodeCheck($id = 0){
        $realEstateCatalogue = RealEstateCatalogue::find($id);

        if($realEstateCatalogue->rgt - $realEstateCatalogue->lft !== 1){
            return false;
        } 

        return true;
    }
    
    public function direct_children(){
        return $this->hasMany(RealEstateCatalogue::class, 'parent_id', 'id');
    }
}

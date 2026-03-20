<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RealEstateLanguage extends Model
{
    use HasFactory;

    protected $table = 'real_estate_language';

    public function real_estates()
    {
        return $this->belongsTo(RealEstate::class, 'real_estate_id');
    }
}

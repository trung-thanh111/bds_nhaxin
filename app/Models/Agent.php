<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class Agent extends Model 
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;
    
    protected $fillable = [
        'full_name',
        'title',
        'phone',
        'email',
        'avatar',
        'bio',
        'zalo',
        'is_primary',
        'publish',
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

    public function projects() {
        return $this->hasMany(Project::class, 'agent_id');
    }
}
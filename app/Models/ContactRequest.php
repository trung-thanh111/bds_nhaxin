<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasQuery;

class ContactRequest extends Model
{
    use HasFactory, Notifiable, SoftDeletes, HasQuery;

    protected $fillable = [
        'project_id',
        'full_name',
        'email',
        'phone',
        'subject',
        'content',
        'status',
        'admin_notes',
        'assigned_agent_id',
        'user_id',
    ];

    protected $casts = [];

    protected $relationable = ['users', 'projects', 'agents'];

    public function getRelationable()
    {
        return $this->relationable;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function projects(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function agents(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'assigned_agent_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'suggested_by_user',
        'proposed_start_at',
        'proposed_end_at',
        'confirmed_at',
        'status',
        'admin_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'suggested_by_user' => 'boolean',
        'proposed_start_at' => 'datetime',
        'proposed_end_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'status' => 'string', // Enum in migration
    ];

    /**
     * Get the project that owns the appointment.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that made the appointment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

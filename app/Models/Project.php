<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'initial_request_id',
        'title',
        'description',
        'data',
        'current_status',
        'total_price',
        'is_paid',
        'express_service',
        'estimated_delivery_at',
        'actual_delivery_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
        'total_price' => 'decimal:2',
        'is_paid' => 'boolean',
        'express_service' => 'boolean',
        'estimated_delivery_at' => 'datetime',
        'actual_delivery_at' => 'datetime',
        'current_status' => 'string', // Enum in migration
    ];

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service associated with the project.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the initial request that initiated the project.
     */
    public function initialRequest(): BelongsTo
    {
        return $this->belongsTo(InitialRequest::class);
    }

    /**
     * Get the project files for the project.
     */
    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Get the chat messages for the project.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    /**
     * Get the appointments for the project.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the payments for the project.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

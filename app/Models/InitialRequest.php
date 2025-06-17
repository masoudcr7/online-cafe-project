<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InitialRequest extends Model
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
        'description',
        'initial_file_path',
        'status',
        'admin_message',
        'rejected_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rejected_at' => 'datetime',
        'status' => 'string', // Enum in migration, can be cast to string here
    ];

    /**
     * Get the user that owns the initial request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service associated with the initial request.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the project associated with the initial request (if accepted).
     */
    public function project(): HasOne // <-- این متد حیاتی است
    {
        return $this->hasOne(Project::class, 'initial_request_id', 'id');
    }
}

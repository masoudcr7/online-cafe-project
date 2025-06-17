<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment_file_id',
    ];

    /**
     * Get the project that owns the message.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the parent sender model (User or AdminUser).
     */
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the attachment file for the message.
     */
    public function attachment(): BelongsTo
    {
        return $this->belongsTo(ProjectFile::class, 'attachment_file_id');
    }
}

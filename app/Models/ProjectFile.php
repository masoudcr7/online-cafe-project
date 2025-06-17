<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // Don't forget to use SoftDeletes

class ProjectFile extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id', // ID of the uploader (can be User or AdminUser initially, or we can make it morphable later if needed)
        'path',
        'original_name',
        'mime_type',
        'size',
        'type',
        'available_until',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'available_until' => 'datetime',
        'type' => 'string', // Enum in migration
    ];

    /**
     * The attributes that should be mutated to dates.
     * (Crucial for SoftDeletes)
     *
     * @var array<int, string>
     */
    protected $dates = ['deleted_at']; // Define this for SoftDeletes

    /**
     * Get the project that owns the file.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who uploaded the file.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // Assuming user_id refers to the uploader
        // If an AdminUser can also upload, this might become a polymorphic relation later,
        // but for now, based on the migration, it's User.
    }
}

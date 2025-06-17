<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; // IMPORTANT: Use Authenticatable for admin users

class AdminUser extends Authenticatable // Extend Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The guard used for authentication.
     * This tells Laravel to use the 'admin' guard defined in config/auth.php for this model.
     *
     * @var string
     */
    protected $guard = 'admin'; // Crucial for custom guard for admin

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // For future role-based access control (RBAC)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // If you add email verification for admin
        'password' => 'hashed', // Laravel 10+ handles hashing automatically with this cast
    ];

    // You can define relationships here if needed, e.g., messages sent by this admin
    /*
    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id')->where('sender_type', self::class);
    }
    */
}

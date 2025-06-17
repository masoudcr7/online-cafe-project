<?php

namespace App\Models;

// این namespace ها برای استفاده از توابع و کلاس‌های لاراول ضروری هستند.
use Illuminate\Contracts\Auth\MustVerifyEmail; // اگر در آینده نیاز به تایید ایمیل باشد
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // برای Laravel Sanctum در آینده (اگر API برای موبایل بسازید)

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number', // اضافه شده برای شماره موبایل کاربر
        'password',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ handles hashing automatically with this cast
    ];

    // --- روابط (Relationships) ---

    /**
     * Get the initial requests for the user.
     */
    public function initialRequests(): HasMany
    {
        return $this->hasMany(InitialRequest::class);
    }

    /**
     * Get the projects for the user.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the files uploaded by the user.
     * Note: This assumes 'user_id' in ProjectFile refers to the uploader.
     */
    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(ProjectFile::class, 'user_id');
    }

    /**
     * Get the chat messages sent by the user.
     * (Using polymorphic relationship 'morphMany' as 'sender' in ChatMessage)
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id')->where('sender_type', self::class);
        // Or you can use a morphMany relation if you define it on ChatMessage
        // return $this->morphMany(ChatMessage::class, 'sender');
    }

    /**
     * Get the appointments made by the user.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the payments made by the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}

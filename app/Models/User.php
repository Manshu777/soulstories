<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles,HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
        'status',
    'phone',
    'dob',
    'pronouns',
    'google_id',
    'is_verified',
    'otp',
    'otp_expires_at',
    'reset_otp',
    'reset_otp_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ================= RELATIONSHIPS ================= */

    // Followers (who follow me)
    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'following_id',
            'follower_id'
        );
    }

    // Following (who I follow)
    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'followers',
            'follower_id',
            'following_id'
        );
    }

    // Contents (stories, poems, series, quotes)
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    // Blogs
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    // Promotions
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    // Earn orders
    public function earnOrders()
    {
        return $this->hasMany(EarnOrder::class);
    }

    // Service orders
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    /* ================= SOUL DIARIES ================= */

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    public function library()
    {
        return $this->belongsToMany(Story::class, 'libraries')->withTimestamps();
    }

    public function storyReads()
    {
        return $this->hasMany(StoryRead::class);
    }

    public function storyLikes()
    {
        return $this->hasMany(StoryLike::class);
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }
}

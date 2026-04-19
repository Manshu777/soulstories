<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'description',
        'cover_image',
        'status',
        'is_paid',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function series()
    {
        return $this->hasOne(Series::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

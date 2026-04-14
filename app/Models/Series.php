<?php

class Series extends Model
{
    protected $fillable = ['content_id', 'genre', 'is_completed'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}

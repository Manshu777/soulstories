<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = ['series_id', 'title', 'body', 'chapter_order'];

    public function series()
    {
        return $this->belongsTo(Series::class);
    }
}
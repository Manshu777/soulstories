<?php

namespace App\Http\Resources\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorySearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'story',
            'title' => $this->title,
            'slug' => $this->slug,
            'thumbnail' => $this->cover_image,
            'description' => (string) ($this->description ?? ''),
            'genre' => $this->genre,
            'status' => $this->status,
            'read_time' => (int) ($this->read_time ?? 0),
            'reads' => (int) ($this->story_reads_count ?? 0),
            'likes' => (int) ($this->likes_count ?? 0),
            'author' => [
                'name' => $this->user?->name,
                'username' => $this->user?->username,
                'avatar' => $this->user?->avatar,
            ],
            'url' => route('diary.stories.show', $this->slug),
        ];
    }
}

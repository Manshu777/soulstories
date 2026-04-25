<?php

namespace App\Http\Resources\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogSearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'blog',
            'title' => $this->title,
            'slug' => $this->slug,
            'thumbnail' => $this->cover_image,
            'excerpt' => mb_strimwidth(strip_tags((string) $this->body), 0, 160, '...'),
            'status' => $this->status,
            'author' => [
                'name' => $this->user?->name,
                'username' => $this->user?->username,
                'avatar' => $this->user?->avatar,
            ],
            'url' => '#',
        ];
    }
}

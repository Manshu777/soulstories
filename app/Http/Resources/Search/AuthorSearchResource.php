<?php

namespace App\Http\Resources\Search;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorSearchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'author',
            'title' => $this->name,
            'name' => $this->name,
            'username' => $this->username,
            'thumbnail' => $this->avatar,
            'bio' => $this->bio,
            'url' => route('diary.authors.show', $this->username),
        ];
    }
}

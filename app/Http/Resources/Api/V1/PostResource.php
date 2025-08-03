<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*
        'description' => $this->when(
            $request->routeIs('posts.show'),
            $this->description,
        )
        */
        return [
            'type' => 'post',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => $this->user_id,
                // 'media_url' => asset(Storage::url($this->media_url)),
                'media_url' => asset('storage/images/'. $this->media_url),
                'createAt' => $this->created_at,
                'updateAt' => $this->updated_at,
            ],
            'relationships' => [
                'author'=> [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    'links' => [
                        'self' => route('users.show', ['user'=> $this->user_id])
                    ]
                ],
            ],
            'includes' => new UserResource($this->whenLoaded('author')),
            'links' => [
                'self' => route('posts.show', ['post'=> $this->id])
            ],
            
        ];
    }
}

<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
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
        return [
            'type' => 'post',
            'id' => $this->id,
            'attributes' => [
                'id'=>$this->id,
                'title' => $this->title,
                'description' => $this->description,
                'user_id' => $this->user_id,
                'media_url' => asset('storage/images/' . $this->media_url),
                'createAt' => $this->created_at,
                'updateAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    // this is link for user details
                    'links' => [
                        'self' => route('users.show', ['user' => $this->user_id])
                    ]
                ],
            ],
            // this load users relationships with posts   one user several post
            'includes' => new UserResource($this->whenLoaded('author')),
            /*
            this is for showing the likes in all routes
            'likes' => LikeResource::collection(
                Like::where('post_id', $this->id)->latest()->paginate()
            ),*/

            // for all comments of single post count 
            'comment_count' => Comment::where('post_id', $this->id)->get()->count(),

            // for all likes of single post count
            'like_count' => Like::where('post_id', $this->id)->get()->count(),
            
            // comments
            'comments' => $this->when(
                $request->routeIs('posts.show'),
                CommentResource::collection(
                    Comment::where('post_id', $this->id)->get(),
                )
            ),
            // and this is for showing the likes only for index route
            'likes' => $this->when(
                $request->routeIs('posts.index'),
                LikeResource::collection(
                    Like::where('post_id', $this->id)->latest()->paginate()
                ),
            ),

            'links' => [
                'self' => route('posts.show', ['post' => $this->id])
            ],

        ];
    }
}

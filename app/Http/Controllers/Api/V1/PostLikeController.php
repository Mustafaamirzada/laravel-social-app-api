<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Like;
use App\Models\Post;


class PostLikeController extends ApiController
{
  public function likePost($id)
  {
    $post = Post::where('id', $id)->first();
    if (!$post) {
      return $this->error('Not Found', 404);
    }

    // Unlike post
    $unlike_post = Like::where('user_id', auth()->id())->where('post_id', $id)->delete();
    if ($unlike_post) {
      return $this->ok('Unlike Post', [$unlike_post]);
    }

    // like post
    $like_post = Like::create([
      'user_id' => auth()->id(),
      'post_id' => $id,
    ]);
    if ($like_post) {
      return $this->ok('Liked Post', [$like_post]);
    }
  }
}

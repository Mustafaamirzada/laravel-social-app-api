<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Comment;
use App\Http\Resources\Api\V1\CommentResource;
use App\Http\Resources\Api\V1\CommentCollection;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PhpParser\Node\Stmt\TryCatch;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return new CommentCollection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            $comment = Comment::create($request->validated());
            return new CommentResource($comment);
        } catch (Exception $e) {
            return $this->error('Cant Create Comment on That Post', 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            $comment->update($request->validated());
            return new CommentResource($comment);
        } catch (Exception $e) {
            return $this->error('Comment Not Found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($comment_id)
    {
        try {
            $comment = Comment::findOrFail($comment_id);
            $comment->delete();
            return $this->ok('Comment Deleted Successfuly', 200);
        } catch (ModelNotFoundException $e) {
            return $this->error('Comment Not Fount', 404);
        }
    }
}

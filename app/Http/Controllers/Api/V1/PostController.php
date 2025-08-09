<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Policies\PostPolicy;
use App\Http\Filters\V1\PostFilter;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\Api\V1\PostResource;
use App\Http\Requests\Api\V1\StorePostRequest;
use App\Http\Requests\Api\V1\UpdatePostRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends ApiController
{
    protected $policyClasss = PostPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(PostFilter $filters)
    {
        return PostResource::collection(Post::filter($filters)->latest()->paginate(30));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try{
            $data = $request->all();
            // $this->isAble('store', null);
            if ($request->hasFile('media_url')) { 
                $file = $request->file('media_url');
                $name = Str::uuid() . '.' . $file->extension();
                $file->storeAs('images/', $name, 'public');
                $data['media_url'] = $name;
            }
            
            Log::info('Reached To Create Function');
            $post = Post::create($data);
            return new PostResource($post);
        } catch (Exception $e) {
            return $this->error('Unable to Create Post', 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if ($this->include('author')) {
            return new PostResource($post->load('user'));
        }

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try{
            // $this->isAble('update', $post);
            $data = $request->validated();
            
            if ($request->hasFile('media_url')) { 
                $file = $request->file('media_url');
                $name = Str::uuid() . '.' . $file->extension();
                $file->storeAs('images/', $name, 'public');
                $data['media_url'] = $name;
            }
            
            $post->update($data);
            return new PostResource($post);
        } catch (Exception $e) {
            return $this->error('Can Not Update The Post',404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        try {
            $post = Post::findOrFail($post_id);
            $this->isAble('delete', $post); 
            $post->delete();
            return $this->ok('Post Deleted Successfully', []);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Post Not Found.', 404);
        }
    }
}

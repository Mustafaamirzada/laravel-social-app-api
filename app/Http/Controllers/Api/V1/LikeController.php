<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\Like;
use App\Http\Resources\Api\V1\LikeResource;
use App\Http\Resources\Api\V1\LikeCollection;
use App\Http\Requests\Api\V1\StoreLikeRequest;
use App\Http\Requests\Api\V1\UpdateLikeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LikeController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index():LikeCollection
    {
        $like = Like::all();
        return new LikeCollection($like);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request)    
    {
        try {
            $like = Like::create($request->validated());
            return new LikeResource($like);
        } catch (Exception $e) {
            return $this->error('Unable to Like Post', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLikeRequest $request, Like $like)
    {
        try{
            $like->update($request->validated());
            return new LikeResource($like);
        } catch (Exception $e) {
            return $this->error('Unable to Update the Liked Post',404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        try {
            $like->delete();
            return $this->ok('Unliked Post Successfuly', 404);
        } catch (ModelNotFoundException $e) {
            return $this->error('Liked Post Did Not Found', 404);
        }
    }
}

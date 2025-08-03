<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Follow;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FollowResource;
use App\Http\Resources\Api\V1\FollowCollection;
use App\Http\Requests\Api\V1\StoreFollowRequest;
use App\Http\Requests\Api\V1\UpdateFollowRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FollowController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): FollowCollection
    {
        $follows = Follow::all();
        return new FollowCollection($follows);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowRequest $request)
    {
        try {
            $follow = Follow::create($request->validated());
            return new FollowResource($follow);
        } catch (Exception $e) {
            return $this->error('Unable to Follow User.', 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Follow $follow)
    {
        return new FollowResource($follow);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowRequest $request, Follow $follow)
    {
        try {
            $follow->update($request->validated());
            return new FollowResource($follow);
        } catch (Exception $e) {
            return $this->error('Error Updateing Post',404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Follow $follow)
    {
        try {
            $follow->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
            return $this->error('Follower Did Not Found',404);
        }
    }
}

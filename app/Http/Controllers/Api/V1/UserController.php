<?php

namespace App\Http\Controllers\Api\V1;

use Exception;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use App\Http\Controllers\Controller;
use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        if ($this->include('posts')) {
            return new UserResource(User::load('posts'));
        }
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try{
            $user->update($request->validated());
            return new UserResource($user);
        } catch(Exception $e) {
            return $this->error('Unable to Update the User', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return $this->ok('User Deleted Successfully', 200);;
        } catch (ModelNotFoundException $e) {
            return $this->error('Unable to Find User',404);
        }
    }
}

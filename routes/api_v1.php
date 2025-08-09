<?php

use App\Http\Controllers\Api\V1\AuthorPostController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\FollowController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LikeController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;


Route::middleware('auth:sanctum')->group(function () {
  
  Route::apiResource('/users', UserController::class);

  Route::apiResource('authors.posts', AuthorPostController::class)
    ->only(['index',]);

  Route::apiResource('/posts', PostController::class);

  Route::apiResource('/likes', LikeController::class)
    ->only(['store', 'destroy']);
    
  Route::apiResource('/comment', CommentController::class)
    ->only(['store', 'update', 'destroy']);

  Route::apiResource('/follow', FollowController::class)
    ->only(['store', 'destroy']);


});
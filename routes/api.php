<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);


    Route::get('posts', [PostController::class, 'index']);
    Route::post('posts', [PostController::class, 'store']);

    Route::controller(PostController::class)->middleware('checkPostOwner')
        ->group(function () {
            Route::get('posts/{post}', 'show');
            Route::put('posts/{post}', 'update');
            Route::delete('posts/{post}', 'destroy');

        });


    Route::post('posts/{postId}/comments', [CommentController::class, 'store']);
    Route::get('posts/{postId}/comments', [CommentController::class, 'index']);
});

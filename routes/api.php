<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 Route::post('/register', [AuthController::class, 'register']);
 Route::post('/login', [AuthController::class, 'login']);

 Route::group(['middleware' => ['auth:sanctum']], function () {
     //user
     Route::get('/user', [AuthController::class, 'user']);
     Route::put('/user', [AuthController::class, 'update']);
     Route::post('/logout', [AuthController::class, 'logout']);
 
    //Post
    Route::get('/posts', [PostController::class, 'index']); // all posts
    Route::post('/posts', [PostController::class, 'store']); // create post
    Route::get('/posts/{id}', [PostController::class, 'show']); // get single post
    Route::put('/posts/{id}', [PostController::class, 'update']); // update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // delete post

    //Comments
    Route::get('/posts/{id}/comments/', [CommentController::class, 'index']); // all comments
    Route::post('/posts/{id}/comments/', [CommentController::class, 'store']); // create comment
    Route::put('/comments/{id}', [CommentController::class, 'update']); // update comment
    Route::delete('/comment/s{id}', [CommentController::class, 'destroy']); // delete comment

    //Like
    Route::post('/posts/{id}/likes', [LikeController::class, 'LikeOrUnlike']); // like post
    });
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FollowController;



Route::get('/', [UserController::class, 'CorrectHomepage'])->name('login');

//Usersetup
Route::post('/register', [UserController::class, 'createuser'])->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->middleware('MustBeLoggedIn')->name('logoutna');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->middleware('MustBeLoggedIn');
Route::post('/manage-avatar', [UserController::class, 'storeAvatar'])->middleware('MustBeLoggedIn');


//Users-Profile

Route::get('/my-profile/{user:username}', [UserController::class, 'myProfile'])->middleware('MustBeLoggedIn')->name('profile.show');
Route::get('/my-profile/{user:username}/followers', [UserController::class, 'myFollowers'])->middleware('MustBeLoggedIn');
Route::get('/my-profile/{user:username}/following', [UserController::class, 'myFollowing'])->middleware('MustBeLoggedIn');

//USerRaw//
Route::middleware('cache.headers:public;max_age=20;etag')->group(function() {
    Route::get('/my-profile/{user:username}/raw', [UserController::class, 'profileRaw']);
    Route::get('/my-profile/{user:username}/followers/raw', [UserController::class, 'profileFollowersRaw']);
    Route::get('/my-profile/{user:username}/following/raw', [UserController::class, 'profileFollowingRaw']);
  });



//Blogsetup
Route::get('/new-post', [PostController::class, 'showpostform'])->middleware('MustBeLoggedIn');
Route::post('/new-post', [PostController::class, 'creatnewpost'])->middleware('MustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewpost']);
Route::delete('/post/{post}', [PostController::class, 'delete'])->middleware('can:delete,post');
Route::get('/post/{post}/edit', [PostController::class, 'showEditForm'])->middleware('can:update,post');
Route::put('/post/{post}/edit', [PostController::class, 'actuallyUpdated'])->middleware('can:update,post');


//Follow setup

Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->middleware('MustBeLoggedIn');
Route::post('/remove-follow/{user:username}', [FollowController::class, 'removeFollow']);


//Likes and comment setup

Route::post('/posts/{id}/like', [PostController::class, 'likepost'])->middleware('MustBeLoggedIn')->name('posts.like');
Route::post('/post/{id}/comment', [PostController::class, 'addcomment'])->middleware('MustBeLoggedIn')->name('posts.comment');
Route::get('/post/{id}', [PostController::class, 'showpost'])->middleware('MustBeLoggedIn')->name('post.show');

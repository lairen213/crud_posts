<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Pages that are only available after logging in
Route::middleware("auth")->group(function() {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/posts/{slug}', [PostController::class, 'getOnePost'])->name('getOnePost');
    Route::post('/posts-delete/{slug}', [PostController::class, 'deletePost'])->name('deletePost');

    Route::get('/posts-add-update/{slug}', [PostController::class, 'addUpdatePost'])->name('addUpdatePost');
    Route::post('/posts-add-update/{slug}', [PostController::class, 'addUpdatePostSubmit'])->name('addUpdatePostSubmit');
    Route::post('/comments-add/{post_slug}', [CommentController::class, 'addComment'])->name('addComment');

    Route::post('/comments-add-reaction/{comment_id}', [CommentController::class, 'addReaction'])->name('addReaction');
    Route::post('/comments-delete-reaction/{comment_id}', [CommentController::class, 'deleteReaction'])->name('deleteReaction');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


//Pages login and registration available only when client don't authenticated
Route::middleware("guest")->group(function() {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('loginProcess');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'registerProcess'])->name('registerProcess');
});

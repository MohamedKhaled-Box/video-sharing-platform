<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.main');
    })->name('dashboard');
});
Route::resource('/videos', VideoController::class);
Route::get('/video/search', [VideoController::class, 'search'])->name('video.search');
Route::post('/view', [VideoController::class, 'addView'])->name('view');

Route::post('/like', [LikeController::class, 'LikeVideo'])->name('like');

Route::post('/comment', 'App\Http\Controllers\CommentController@saveComment')->name('comment');
Route::get('/comment/{id}/edit', 'App\Http\Controllers\CommentController@edit')->name('comment.edit');
Route::patch('/comment/{id}', 'App\Http\Controllers\CommentController@update')->name('comment.update');
Route::get('/comment/{id}', 'App\Http\Controllers\CommentController@destroy')->name('comment.destroy');

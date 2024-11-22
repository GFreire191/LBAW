<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;

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

// Home

Route::get('/', 'App\Http\Controllers\HomeController@redirect');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/about_us', 'App\Http\Controllers\HomeController@show_about');

// Admin / Moderator routes

Route::get('/admin/reports', 'App\Http\Controllers\AdminController@reports')->name('admin.reports');
Route::get('/admin/tags', 'App\Http\Controllers\AdminController@tags')->name('admin.tags');

// Questions

Route::get('/question/create', 'App\Http\Controllers\QuestionController@create')->name('create.question');
Route::post('/question', 'App\Http\Controllers\QuestionController@store')->name('store.question');
Route::get('/question/{question}/show', 'App\Http\Controllers\QuestionController@show')->name('show.question');
Route::get('/question/{question}/edit', 'App\Http\Controllers\QuestionController@edit_form')->name('edit_form.question');
Route::put('/question/{question}/edit', 'App\Http\Controllers\QuestionController@update')->name('update.question');
Route::delete('/question/{question}/delete', 'App\Http\Controllers\QuestionController@delete')->name('delete.question');
Route::get('/top_questions', 'App\Http\Controllers\QuestionController@show_top')->name('show.top_question');

// Follow Question

Route::post('/question/{question}/follow', 'App\Http\Controllers\UserController@followQuestion')->name('follow.question');
Route::post('/question/{question}/unfollow', 'App\Http\Controllers\UserController@unfollowQuestion')->name('unfollow.question');

// Reports

Route::get('/report/create', 'App\Http\Controllers\ReportController@create')->name('create.report');
Route::post('/report/{parentType}/{parentId}', 'App\Http\Controllers\ReportController@store')->name('store.report');
Route::get('/report/{report}/show', 'App\Http\Controllers\ReportController@show')->name('show.report');
Route::delete('/report/{report}/delete', 'App\Http\Controllers\ReportController@delete')->name('delete.report');

// Tags

Route::get('/tag/create', 'App\Http\Controllers\TagController@create')->name('create.tag');
Route::post('/tag', 'App\Http\Controllers\TagController@store')->name('store.tag');
Route::get('/tag/{question}', 'App\Http\Controllers\TagController@show')->name('show.tags');
Route::delete('/tag/{tag}/delete', 'App\Http\Controllers\TagController@delete')->name('delete.tag');

// Notifications

Route::get('/notification/{user}/show', 'App\Http\Controllers\NotificationController@show')->name('show.notification');
Route::delete('/notification/{notification}/delete', 'App\Http\Controllers\NotificationController@delete')->name('delete.notification');

// Answers

Route::post('/question/{question}/answer', 'App\Http\Controllers\AnswerController@store')->name('store.answer');
Route::get('/answer/{answer}/show', 'App\Http\Controllers\AnswerController@show')->name('show.answer');
Route::get('/answer/{answer}/edit', 'App\Http\Controllers\AnswerController@edit_form')->name('edit_form.answer');
Route::put('/answer/{answer}/edit', 'App\Http\Controllers\AnswerController@update')->name('update.answer');
Route::delete('/answer/{answer}/delete', 'App\Http\Controllers\AnswerController@delete')->name('delete.answer');


// Comments

Route::get('/comment/create', 'App\Http\Controllers\CommentController@create')->name('create.comment');
Route::get('/comment/{type}/{parent}/show', 'App\Http\Controllers\CommentController@show')->name('show.comment');
Route::delete('/comment/{comment}/delete', 'App\Http\Controllers\CommentController@delete')->name('delete.comment');
Route::post('/comment/{parentType}/{parentId}', 'App\Http\Controllers\CommentController@store')->name('store.comment');
Route::get('/comment/{comment}/edit', 'App\Http\Controllers\CommentController@edit_form')->name('edit_form.comment');
Route::put('/comment/{comment}/edit', 'App\Http\Controllers\CommentController@update')->name('update.comment');


// Profile
Route::get('/user/{id}/profile', 'App\Http\Controllers\UserController@showProfile')->name('profile.show');
Route::put('/user/{id}/edit', 'App\Http\Controllers\UserController@store')->name('profile.edit');
Route::get('/profile/edit', 'App\Http\Controllers\UserController@create')->name('profile.create');



// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

//Search
Route::get('/search', 'App\Http\Controllers\SearchController@search')->name('search');


//Votes
Route::post('/question/{question}/vote', 'App\Http\Controllers\VoteController@vote_question')->name('question.vote');
Route::post('/answer/{answer}/vote', 'App\Http\Controllers\VoteController@vote_answer')->name('answer.vote');

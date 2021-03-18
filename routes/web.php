<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

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

Auth::routes();

Route::get('/', [BookController::class, 'index'])
    ->name('books.index');

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::resource('books', BookController::class)
        ->except('show', 'index');

    Route::resource('users', UserController::class)
        ->except(['show', 'destroy']);

});

<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/', 'dashboard');

/**
 * App Routes
 */

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


//Route::middleware(['auth:sanctum', 'verified'])->get('/profile', function () {
//    return view('profile');
//})->name('profile');

Route::get('/profile' , App\Http\Livewire\Profile::class)->middleware('auth');;

//Route::get('/dashboard' , App\Http\Livewire\Dashboard::class)->layout('');
//
//Route::middleware('auth')->group(function () {
//    Route::livewire('/dashboard', 'dashboard');
//    Route::livewire('/profile', 'profile');
//});


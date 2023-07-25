<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

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



// Categories

Route::resource('api/category', 'CategoryController');

Route::resource('api/products', 'ProductController');

Route::resource('api/orders', 'OrderController');

Route::get('api/products/category/{id}', 'ProductController@related');

// Route::get('/api/category', 'CategoryController@index');

// Route::get('/api/category/{id}', 'CategoryController@show');

// Route::post('/api/category', 'CategoryController@create');

// Route::put('/api/category/{id}', 'CategoryController@update');

// Route::delete('/api/category/{id}', 'CategoryController@destroy');


// Products

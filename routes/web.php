<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|


Route::get('/', function () {
    return ('The Vinyl Shop');
});

Route::get('contact-us', function () {
    return view('contact');
});
*/
Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    route::redirect('/', 'records');
    Route::get('records', 'Admin\RecordController@index');
});
//Route::get('/home', 'HomeController@index')->name('home');
Route::view('/', 'home');
Route::get('shop', 'ShopController@index');
Route::get('shop/{id}', 'ShopController@show');
Route::get('shop_alt', 'ShopController@alt');

Route::view('contact-us', 'contact');
Route::get('contact-us', 'ContactUsController@show');
Route::post('contact-us', 'ContactUsController@sendEmail');





<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/


// Produccts
Route::group(['prefix' => '/'], function() {
    Route::get('/', 'ProductsController@index')->name('home');
    Route::get('/deals', 'ProductsController@index')->name('deals');
    Route::get('/product/{id}', 'ProductsController@product')->name('product');
    Route::get('/search', 'ProductsController@search')->name('search');
    Route::get('/category/{id?}/{sub_id?}', 'ProductsController@category')->name('category');
});

// Blog
Route::group(['prefix' => 'blog'], function() {
    Route::get('/', 'BlogController@index')->name('blog-home');
    Route::get('/article/{slug}', 'BlogController@showSinglePost')->name('show-blog');
    Route::get('/article/category/{category}', 'BlogController@showCategories')->name('show-categories');
    Route::post('/kw', 'BlogController@byKeyWords')->name('blog-kw');
});

Route::get('pac17907900.html', 'ProductsController@pac')->name('pac');

// Search
Route::group(['prefix' => 'search'], function() {
//    Route::get('/',)->name('search-main');
    Route::get('/all/{search?}', 'SearchApiController@searchAll')->name('search-all');
    Route::get('/categories/{search}', 'SearchApiController@searchCategories')->name('search-cat');
    Route::get('/merchants/{search}', 'SearchApiController@searchMerchants')->name('search-merch');
    Route::get('/offers/{search}', 'SearchApiController@searchOffers')->name('search-offers');
    Route::get('/apicat.json/{search}', 'SearchApiController@searchApiCategories')->name('search-apicat');
    Route::get('/products/{search}', 'SearchApiController@searchApiProducts')->name('search-apiprod');
});

// About
Route::group(['prefix' => 'about'], function() {
    Route::get('/', 'HomeController@about')->name('about');
    Route::get('/privacy', 'HomeController@terms_privacy')->name('privacy');
    Route::get('/terms', 'HomeController@terms_privacy')->name('terms');
});
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
*/



Route::get('/', 'LandingPageController@index')->name('landing-page.index');

Route::get('/shop', 'ShopController@index')->name('shop.index');
//Route::resource('cart', 'CartController')->name('cart.index');
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart', 'CartController@store')->name('cart.store');

// Updating quatity of items
Route::patch('/cart/{product}', 'CartController@update')->name('cart.update');

Route::post('/cart/saveForLater/{product}', 'CartController@saveForLater')->name('cart.saveForLater');
Route::delete('/cart/{product}', 'CartController@destroy')->name('cart.destroy');

Route::delete('/saveForLater/{product}', 'SaveForLaterController@destroy')->name('saveforlater.destroy');
Route::post('/saveForLater/switchToCart/{product}', 'SaveForLaterController@switchToCart')->name('saveforlater.movetocart');



Route::get('/shop/{slug}', 'ShopController@show')->name('shop.show');

//  Checkout
Route::get('/checkout', 'CheckoutController@index')->name('checkout.index')->middleware('auth');
Route::post('/checkout', 'CheckoutController@store')->name('checkout.store');

//Guest Checkout
Route::get('/guestcheckout', 'CheckoutController@index')->name('guestcheckout.index');


Route::get('/empty', function(){
   Cart::instance('saveForLater')->destroy();
});

// THANK YOU PAGE
Route::get('/thankyou', 'ThankyouController@index')->name('thankyou.index');

// COUPON

Route::post('/coupon', 'CouponController@store')->name('coupon.store');
Route::delete('/coupon', 'CouponController@destroy')->name('coupon.destroy');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

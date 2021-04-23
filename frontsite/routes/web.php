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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/search', 'SearchController@index')->name('search');
Route::get('/build-search', 'SearchController@buildContent')->name('BuildSearch');

Route::get('/cache/refresh', 'HomeController@refreshCache');

Route::get('test/sendmail', 'TestController@mailsender');
Route::get('import/city', 'ProductSouvenirController@getCity');

// Store Page content 
Route::get('/store', 'StoreController@index')->name('store');

// order transaction
Route::group(['prefix' => 'order'], function() {
	Route::post('{productType}/addcart',	'TransactionController@ajaxAddToCart')->name('addcart');
	Route::post('{productType}/addcartshipment',	'TransactionController@ajaxAddShipmentToCart')->name('addcartshipment');
	Route::post('{productType}/addcartpickup',	'TransactionController@ajaxAddPickupToCart')->name('addcartpickup');
	Route::get('summary', 			'TransactionController@summary');
	Route::get('checkout', 			'TransactionController@checkout');
	Route::get('payment-method/{cartKey}', 	'PaymentController@paymentMethod')->name('paymentMethod');
	Route::get('confirmation/{orderCode}', 	'PaymentController@confirmation')->name('confirmation');
	Route::get('form-pemesanan/{cartKey}', 'TransactionController@formOrder')->name('formorder');
	Route::get('form-pengiriman/{cartKey}', 'ProductSouvenirController@shipment')->name('formShipment');
	Route::get('form-pickup/{cartKey}', 	'ProductTransportasiController@pickup')->name('formPickup');
	Route::post('submit-checkout/{cartKey}',	'TransactionController@submitCheckout')->name('submitCheckout');

	// retrieve order
	Route::get('check-order', 'TransactionController@checkOrder')->name('checkOrder');
	Route::post('{orderId}/retrieve','TransactionController@retrieve')->name('retrieve');
});

Route::group(['prefix' => 'payment'], function() {
	Route::post('transaction-charge','PaymentController@ajaxGetCharge')->name('getGetCharge');
	Route::get('pembayaran-selesai', 'PaymentController@finish');
	Route::get('pembayaran-batal', 'PaymentController@unfinish');
	Route::get('pembayaran-gagal', 'PaymentController@error');
	Route::post('notification','PaymentController@notification');
});

// customer
Route::group(['prefix' => 'customer'], function() {
	Route::get('login', 			'CustomerController@login')->name('login');
	Route::get('register', 			'CustomerController@register')->name('register');
	Route::get('forgot-password', 	'CustomerController@forgotPassword')->name('forgotPassword');
	Route::get('profile', 			'CustomerController@profile');
	Route::post('profile/update', 	'CustomerController@profileUpdate');
	Route::get('order-list',		'CustomerController@orderList');
	Route::post('ajax/submit-review',	'CustomerController@ajaxSubmitReview');
	Route::get('setting',			'CustomerController@setting');
	Route::post('setting/update-contact', 'CustomerController@settingUpdateContact');
	Route::post('auth/register', 	'CustomerController@authRegister')->name('authRegister');
	Route::post('auth/login', 		'CustomerController@authLogin')->name('authLogin');
	Route::post('auth/logout', 		'CustomerController@authLogout')->name('authLogout');
	Route::post('auth/forgot-password',	'CustomerController@authForgotPassword');
	Route::post('auth/recover-password', 'CustomerController@authRecoverPassword');
	Route::get('recover',			'CustomerController@recover');
	Route::post('send-verification','CustomerController@sendVerification');
	Route::get('verifikasi',		'CustomerController@verification');
});

// Partner
Route::post('partner/register', 'PartnerController@registerPartner')->name('registerPartner');

// Contact
Route::post('contactus', 'HomeController@contactUs')->name('contactUs');

// ajax submit post
Route::post('ajax/webstats', 'WebstatsController@index')->name('webstat');
Route::get('webstat-test', 'WebstatsController@webstattest');

Route::post('ajax/subscribe/submit', 'SubscribeController@submit')->name('subscribeSubmit');
Route::post('ajax/get-destination', 'DestinationController@ajaxGetDestination');
Route::post('ajax/get-recomendation', 'ProductController@ajaxGetRecomendation');
Route::post('ajax/get-latestpost', 'PostController@ajaxGetLatest');
Route::post('ajax/get-popularpost', 'PostController@ajaxGetPopuler');
Route::post('ajax/get-allpost', 'PostController@ajaxGetAllPost');
Route::post('ajax/get-session-order', 'TransactionController@ajaxSessionOrder');

Route::get('ajax/get-province', 'HomeController@ajaxGetProvince')->name('ajaxGetProvince');
Route::get('ajax/get-city', 'HomeController@ajaxGetCity')->name('ajaxGetCity');
Route::get('ajax/get-subdistrict', 'HomeController@ajaxGetSubdistrict')->name('ajaxGetSubdistrict');

// destination detail
Route::get('destination/{destinationId}-{destinationSlug}', 'DestinationController@detail')->name('destinationdetail');

// posting detail
Route::get('post/{postingSlug}', 'PostController@detail');
Route::get('post/c/{categoryId}-{categorySlug}', 'PostController@listByCategory');

// product detail
Route::get('tour/{productId}-{productSlug}', 'ProductTourController@detail')->name('producttour');
Route::get('souvenir/{productId}-{productSlug}', 'ProductSouvenirController@detail')->name('productsouvenir');
Route::get('kuliner/{productId}-{productSlug}', 'ProductKulinerController@detail')->name('productkuliner');
Route::get('akomodasi/{productId}-{productSlug}', 'ProductAkomodasiController@detail')->name('productakomodasi');
Route::get('transportasi/{productId}-{productSlug}', 'ProductTransportasiController@detail')->name('producttransportasi');
Route::post('transportasi/search', 'ProductTransportasiController@search')->name('transportasisearch');
Route::get('transportasi/detail/{productId}-{productSlug}', 'ProductTransportasiController@detailcar')->name('detailcar');

Route::post('akomodasi/{partnerId}/get-hotel-avail', 'ProductAkomodasiController@getHotelAvail')->name('getHotelAvail');
Route::post('akomodasi/{partnerId}/get-room-detail', 'ProductAkomodasiController@getRoomDetail')->name('getRoomDetail');

// 
Route::get('product/all/{productType}','ProductController@products');
// Route::post('product/ajax/{productType}','ProductController@ajaxGetProduct');
Route::post('product/ajax/tour','ProductController@ajaxGetTour');
Route::post('product/ajax/souvenir','ProductController@ajaxGetSouvenir');
Route::post('product/ajax/kuliner','ProductController@ajaxGetKuliner');
Route::post('product/ajax/akomodasi','ProductController@ajaxGetAkomodasi');
Route::post('product/ajax/transportasi','ProductController@ajaxGetTransportasi');
Route::post('product/ajax/all-tour','ProductController@ajaxGetTourAll');
Route::post('product/ajax/all-souvenir','ProductController@ajaxGetSouvenirAll');
Route::post('product/ajax/all-kuliner','ProductController@ajaxGetKulinerAll');
Route::post('product/ajax/all-akomodasi','ProductController@ajaxGetAkomodasiAll');
Route::post('product/ajax/all-transportasi','ProductController@ajaxGetTransportasiAll');
// all product by category
Route::get('product/{productId}-{productType}', 'ProductController@productType')->name('producttype');

// checkout
Route::get('checkout/{sessionId}', 'TransactionController@checkout');

// get review
Route::post('ajax/get-reviews','ReviewController@getReviews')->name('ajaxGetReview');
Route::get('review-add', 'ReviewController@review');

// 
Route::post('shipment/get-cost', 'CourierController@getCost')->name('getShipmentCost');
Route::post('shipment/get-tracking', 'CourierController@getTracking')->name('getTracking');

// general page contents
Route::get('{pageSlug}', 'PageController@index');


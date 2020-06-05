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

Route::get('/', 'Auth\LoginController@index')->name('login');
Route::post('/', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
// Route::middleware('auth')->group(function (){
    Route::view('index', 'index')->name('dashboard');

    Route::get('pawn_auction/pawn', 'PawnController@index')->name('pawn');
    Route::post('pawn_auction/pawn_store', 'PawnController@store')->name('pawn_store');
    Route::get('pawn_auction/pawn_print', 'PawnController@pawnPrint')->name('pawn_print');


    Route::prefix('reports')->group(function () {
        Route::get('notice_listing', 'Reports\NoticeListingController@index')->name('notice_listing');
        Route::get('notice_listing_print', 'Reports\NoticeListingController@print')->name('notice_listing_print');
        // Route::get('notice_listing_print/{jewelry_date}/{non_jewelry_date}', ['as' => 'notice_listing_print', 'uses' => 'Reports\NoticeListingController@print'])->name('notice_listing_print');
    });

    Route::prefix('settings')->group(function () {
        Route::resource('rates', 'RatesController')->except(['index']);
        Route::get('/rates/{branch_id?}/{item_category_id?}', 'RatesController@index')->name('rates.index');
        Route::get('rates.getBranchItem', 'RatesController@getBranchItem')->name('rates.getBranchItem');

        Route::resource('branch', 'BranchController')->parameters(['branch' => 'id']);
        Route::resource('user', 'UserController')->parameters(['user' => 'id']);
        Route::resource('customer', 'CustomerController')->parameters(['customer' => 'id']);
        Route::resource('attachment', 'AttachmentController')->parameters(['attachment' => 'id']);
        Route::get('attachment', 'AttachmentController@search')->name('attachment.search');
        Route::delete('customer/delete_attachment/{id}', 'CustomerController@remove_attachment')->name('customer.delete_attachment');
        /*
        Route::get('branch', 'BranchController@index')->name('branch');
        Route::post('branch', 'BranchController@store')->name('branch');
        Route::get('branch/add', 'BranchController@form')->name('branch.add');
        Route::get('branch/edit/{id}', 'BranchController@form')->name('branch.edit');
        Route::put('branch/update/{id}', 'BranchController@update')->name('branch.update');
        Route::delete('branch/delete/{id}', 'BranchController@delete')->name('branch.delete');
        */

    });
// });

// Route::get('/reports/notice_listing', 'Reports\NoticeListingController@index')->name('notice_listing');
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

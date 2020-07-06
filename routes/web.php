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
Route::middleware('auth')->group(function (){
    Route::view('index', 'index')->name('dashboard');

    Route::prefix('pawn_auction')->group(function(){
        Route::get('pawn', 'PawnController@index')->name('pawn');
        Route::post('pawn_store', 'PawnController@store')->name('pawn_store');
        Route::get('pawn_print/{id}', 'PawnController@pawnPrint')->name('pawn_print');
        Route::get('inventory/add/pawn/{id}', 'InventoryController@form_pawn')->name('inventory.add.pawn');
        Route::delete('inventory_item/{id}', 'InventoryController@removeItem')->name('inventory.removeItem');
        Route::delete('inventory_other_charges/{id}', 'InventoryController@removeOtherCharges')->name('inventory.removeOtherCharges');
        Route::get('inventory/renew/{id}', 'InventoryController@showRenew')->name('inventory.renew');
        Route::post('inventory/renew', 'InventoryController@submitRenew')->name('inventory.submitRenew');
        Route::post('inventory/auction', 'InventoryController@auction')->name('inventory.auction');
        Route::get('inventory/redeem/{id}', 'InventoryController@showRedeem')->name('inventory.redeem');
        Route::post('inventory/redeem', 'InventoryController@submitRedeem')->name('inventory.submitRedeem');
        Route::resource('inventory', 'InventoryController')->parameters(['inventory' => 'id']);

    });
    

    Route::prefix('reports')->group(function () {
        Route::get('notice_listing', 'Reports\NoticeListingController@index')->name('notice_listing');
        Route::get('notice_listing_print', 'Reports\NoticeListingController@print')->name('notice_listing_print');
        // Route::get('notice_listing_print/{jewelry_date}/{non_jewelry_date}', ['as' => 'notice_listing_print', 'uses' => 'Reports\NoticeListingController@print'])->name('notice_listing_print');
    });

    Route::prefix('settings')->group(function () {
        Route::resource('rates', 'RatesController')->except(['index']);
        Route::get('/rates/{branch_id?}/{item_category_id?}', 'RatesController@index')->name('rates.index');
        Route::get('rates.getBranchItem', 'RatesController@getBranchItem')->name('rates.getBranchItem');
        Route::get('rates.getItemType/{id}', 'RatesController@getItemType')->name('rates.getItemType');
        Route::get('rates.getKarat/{id}', 'RatesController@getKarat')->name('rates.getKarat');

        Route::resource('branch', 'BranchController')->parameters(['branch' => 'id']);
        Route::resource('user', 'UserController')->parameters(['user' => 'id']);
        Route::get('customer/search', 'CustomerController@search')->name('customer.search');
        Route::get('customer/search_attachment', 'CustomerController@search_attachment')->name('customer.search_attachment');
        Route::resource('customer', 'CustomerController')->parameters(['customer' => 'id']);
        Route::resource('attachment', 'AttachmentController')->parameters(['attachment' => 'id']);
        Route::get('attachment', 'AttachmentController@search')->name('attachment.search');
        Route::delete('customer/delete_attachment/{id}', 'CustomerController@remove_attachment')->name('customer.delete_attachment');
        Route::get('other_charges/search', 'OtherChargesController@search')->name('other_charges.search');
        Route::resource('other_charges', 'OtherChargesController')->parameters(['other_charges' => 'id']);

        /*
        Route::get('branch', 'BranchController@index')->name('branch');
        Route::post('branch', 'BranchController@store')->name('branch');
        Route::get('branch/add', 'BranchController@form')->name('branch.add');
        Route::get('branch/edit/{id}', 'BranchController@form')->name('branch.edit');
        Route::put('branch/update/{id}', 'BranchController@update')->name('branch.update');
        Route::delete('branch/delete/{id}', 'BranchController@delete')->name('branch.delete');
        */

    });
});

// Route::get('/reports/notice_listing', 'Reports\NoticeListingController@index')->name('notice_listing');
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

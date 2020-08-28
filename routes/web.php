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
Route::middleware(['auth'])->group(function (){
    Route::view('index', 'index')->name('dashboard');

    Route::prefix('pawn_auction')->group(function(){
        Route::get('pawn', 'PawnController@index')->name('pawn');
        Route::post('pawn_store', 'PawnController@store')->name('pawn_store');
        Route::get('pawn_print/{id}/ticket_id/{ticket_id}', 'PawnController@pawnPrint')->name('pawn_print');
        Route::resource('pawn', 'PawnController')->parameters(['pawn' => 'id']);
        Route::get('pawn/renew/{id}/{pawn_id?}', 'PawnController@showRenew')->name('pawn.renew');
        Route::get('pawn/renew/update/{ticket_id}/{id}', 'PawnController@showUpdateRenew')->name('renew_update');
        Route::put('pawn/renew/{id}', 'PawnController@updateRenew')->name('pawn.updateRenew');
        Route::post('pawn/renew', 'PawnController@submitRenew')->name('pawn.submitRenew');
        Route::get('pawn/redeem/update/{ticket_id}/{id}', 'PawnController@showUpdateRedeem')->name('redeem_update');
        Route::put('pawn/redeem/{id}', 'PawnController@updateRedeem')->name('pawn.updateRedeem');
        Route::get('pawn/redeem/{id}/{pawn_id?}', 'PawnController@showRedeem')->name('pawn.redeem');
        Route::post('pawn/redeem', 'PawnController@submitRedeem')->name('pawn.submitRedeem');
        Route::post('pawn/repawn', 'PawnController@submitRepawn')->name('pawn.submitRepawn');
        Route::get('pawn/repawn/update/{ticket_id}/{id}', 'PawnController@showUpdateRepawn')->name('repawn_update');
        Route::put('pawn/repawn/{id}', 'PawnController@updateRepawn')->name('pawn.updateRepawn');
        Route::get('pawn/repawn/{id?}', 'PawnController@repawn')->name('pawn.repawn');
        Route::post('pawn/auction', 'PawnController@auction')->name('pawn.auction');

        Route::get('inventory/{page?}', 'InventoryController@index')->name('inventory.index');
        Route::get('inventory/{id}', 'InventoryController@show')->name('inventory.show');

        // Route::get('inventory/add/pawn/{id}', 'InventoryController@form_pawn')->name('inventory.add.pawn');
        Route::delete('inventory_item/{id}', 'InventoryController@removeItem')->name('inventory.removeItem');
        Route::delete('inventory_other_charges/{id}', 'InventoryController@removeOtherCharges')->name('inventory.removeOtherCharges');
        Route::post('inventory/auction', 'InventoryController@auction')->name('inventory.auction');
        Route::put('foreclosed/{pawn_id}', 'ForeclosedController@updateForeclosed')->name('foreclosed.update');
        Route::get('foreclosed', 'ForeclosedController@index')->name('foreclosed.index');

        // Route::resource('inventory', 'InventoryController')->parameters(['inventory' => 'id']);

    });
    
     Route::middleware(['multiple:Administrator,Manager'])->group(function (){

        Route::prefix('reports')->group(function () {
            Route::post('notice_listing/store', 'Reports\NoticeListingController@store')->name('notice_listing.store');
            Route::get('notice_listing/search/{notice_yr?}/{notice_ctrl?}', 'Reports\NoticeListingController@search')->name('notice_listing.search');
            Route::get('notice_listing/{date?}/{branch?}', 'Reports\NoticeListingController@index')->name('notice_listing.index');
            Route::post('notice_listing/search/', 'Reports\NoticeListingController@submitSearch')->name('notice_listing.search.submit');
            Route::post('notice_listing/create/search', 'Reports\NoticeListingController@submitCreateSearch')->name('notice_listing.create.search.submit');
            Route::get('notice_listing_print/{id}', 'Reports\NoticeListingController@single_print')->name('single_print');
            Route::get('notice_listing_print/{notice_yr}/{notice_ctrl}', 'Reports\NoticeListingController@print')->name('notice_listing_print');

            // Route::get('notice_listing_print/{jewelry_date}/{non_jewelry_date}', ['as' => 'notice_listing_print', 'uses' => 'Reports\NoticeListingController@print'])->name('notice_listing_print');
        });
    });

    Route::prefix('settings')->group(function () {
        Route::get('customer/search', 'CustomerController@search')->name('customer.search');
        Route::get('customer/search_attachment', 'CustomerController@search_attachment')->name('customer.search_attachment');
        Route::get('attachment/search', 'AttachmentController@search')->name('attachment.search');
        Route::get('other_charges/search', 'OtherChargesController@search')->name('other_charges.search');
        Route::get('rates.getItemType/{id}', 'RatesController@getItemType')->name('rates.getItemType');
        Route::get('rates.getKarat/{id}/{branch_id?}', 'RatesController@getKarat')->name('rates.getKarat');

        Route::middleware(['multiple:Administrator,Manager'])->group(function(){
            Route::resource('rates', 'RatesController')->except(['index']);
            Route::get('/rates/{branch_id?}/{item_category_id?}', 'RatesController@index')->name('rates.index');
            Route::get('rates.getBranchItem', 'RatesController@getBranchItem')->name('rates.getBranchItem');
            Route::resource('other_charges', 'OtherChargesController')->parameters(['other_charges' => 'id']);
        });
        Route::middleware(['admin'])->group(function(){
            Route::put('branch/user_branch', 'BranchController@updateUserBranch')->name('branch.updateUserBranch');
            Route::resource('branch', 'BranchController')->parameters(['branch' => 'id']);
            Route::resource('customer', 'CustomerController')->parameters(['customer' => 'id']);
            Route::resource('attachment', 'AttachmentController')->parameters(['attachment' => 'id']);
            Route::delete('customer/delete_attachment/{id}', 'CustomerController@remove_attachment')->name('customer.delete_attachment');
            Route::resource('user', 'UserController')->parameters(['user' => 'id']);

        });


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

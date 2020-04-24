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

Route::get('/index', 'OrdinarioController@index')->name('dashboard');
Route::get('/pawn_auction/pawn', 'PawnController@index')->name('pawn');
Route::post('/pawn_auction/pawn_store', 'PawnController@store')->name('pawn_store');
Route::get('/pawn_auction/pawn_print', 'PawnController@pawnPrint')->name('pawn_print');
// Route::get('/pawn_auction/pawn_print', function(Codedge\Fpdf\Fpdf\Fpdf $fpdf){

// );
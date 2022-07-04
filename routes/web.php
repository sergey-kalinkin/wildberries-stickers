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

//any
Route::group(['namespace' => 'App\Http\Controllers', 'as' => 'any.'], function () {
    Route::get('/faq', 'CommonController@faq')->name('faq');
});

//guests only
Route::group(['namespace' => 'App\Http\Controllers', 'as' => 'guest.', 'middleware' => 'guest:web'], function () {
    Route::get('/', 'CommonController@index')->name('preview');
});

//clients
Route::group(['namespace' => 'App\Http\Controllers\Client', 'as' => 'client.', 'middleware' => 'auth:web'], function () {

    //
    Route::get('/home/{id?}', 'HomeController@index')->name('home');
    Route::get('/spreadsheets', 'HomeController@getListOfTables')->name('spreadsheets');

    //
    Route::group(['as' => 'spreadsheet.', 'prefix' => 'spreadsheet'], function () {
        Route::post('importFile', 'StickerController@importSpreadsheetFile');
        Route::post('generate/{id}', 'StickerController@generateBarcodes');
        Route::post('update_spreadsheet/{spreadsheet_id}', 'StickerController@updateSpreadsheet');
    });
});

require __DIR__ . '/auth.php';

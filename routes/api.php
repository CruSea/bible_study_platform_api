<?php

use Illuminate\Http\Request;
use LaravelQRCode\Facades\QRCode;
use QR_Code\QR_Code;
use QR_Code\Types\QR_Phone;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| User Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
|
 */

Route::get('/user', ['uses'=>'AuthController@index']);
Route::post('/user', ['uses'=>'AuthController@create']);
Route::get('/user/login', ['uses'=>'AuthController@login']);
Route::post('/user/authenticate', ['uses'=>'AuthController@authenticate']);
Route::post('/user/update/{id}', ['uses'=>'AuthController@edit']);
Route::delete('/user/{id}', ['uses'=>'AuthController@delete']);


/**
 *
 * Roles
 */
Route::get('/roles', ['uses'=>'RolesController@index']);



/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Bible Study Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| bible study
|
 */

Route::get('/bs', ['uses'=>'BibleStudyController@index']);
Route::post('/bs', ['uses'=>'BibleStudyController@create']);
Route::get('/bs/search', ['uses'=>'BibleStudyController@search']);
Route::get('/bs/{id}', ['uses'=>'BibleStudyController@show']);
Route::delete('/bs/{id}', ['uses'=>'BibleStudyController@delete']);
Route::post('/bs/browse', ['uses'=>'BibleStudyController@browseByYearAndTerm']);
Route::post('/bs/year/{id}', ['uses'=>'BibleStudyController@browseByYear']);
Route::get('/bs/category/{id}', ['uses'=>'BibleStudyController@browseByCategory']);




/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Bible Study Year Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| YEAR
|
 */

Route::get('/year', ['uses'=>'BSYearController@index']);
Route::post('/year', ['uses'=>'BSYearController@create']);
Route::get('/year/all', ['uses'=>'BSYearController@all']);
Route::get('/year/{id}', ['uses'=>'BSYearController@show']);
Route::delete('/year/{id}', ['uses'=>'BSYearController@delete']);




/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Categories Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| Categories
|
 */

Route::get('/category', ['uses'=>'CategoryController@index']);
Route::post('/category', ['uses'=>'CategoryController@create']);
Route::get('/category/all', ['uses'=>'CategoryController@all']);
Route::get('/category/{id}', ['uses'=>'CategoryController@show']);
Route::delete('/category/{id}', ['uses'=>'CategoryController@delete']);


/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Feed Back Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| Feed back
|
 */

Route::get('/feedback', ['uses'=>'FeedbackController@index']);
Route::post('/feedback', ['uses'=>'FeedbackController@create']);
Route::get('/feedback/{id}', ['uses'=>'FeedbackController@show']);
Route::delete('/feedback/{id}', ['uses'=>'FeedbackController@delete']);



/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Members Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| Feed back
|
 */

Route::get('/member', ['uses'=>'MembersController@index']);
Route::post('/member', ['uses'=>'MembersController@create']);
Route::get('/member/{id}', ['uses'=>'MembersController@show']);
Route::delete('/member/{id}', ['uses'=>'MembersController@delete']);




/**
|----------------------------------------------------------------------------------------------------------------------------------------------------
| Report Controller API Routes
|----------------------------------------------------------------------------------------------------------------------------------------------------
|
| report
|
 */

Route::get('/report', ['uses'=>'ReportController@index']);
Route::post('/report', ['uses'=>'ReportController@create']);
Route::get('/report/{id}', ['uses'=>'ReportController@show']);
Route::delete('/report/{id}', ['uses'=>'ReportController@delete']);
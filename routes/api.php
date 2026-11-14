<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('users/search', 'UserController@search')->name('users.search');
Route::get('users', 'UserController@getAll')->name('users.get');

Route::get('jobs/search', 'Order\JobController@search')->name('jobs.search');
Route::get('jobs', 'Order\JobController@getAll')->name('jobs.get');
Route::get('things/search', 'Board\ThingController@search')->name('things.search');
Route::get('companies', 'Address\CompanyController@getAll')->name('companies.get');
Route::get('companies/search', 'Address\CompanyController@search')->name('companies.search');
Route::get('offices/search', 'Address\OfficeController@getOfficesByCompany')->name('offices.get');
Route::get('print_circuit_boards/search', 'Board\PrintCircuitBoardController@search')->name('printCircuitBoards.search');
Route::get('detailed_boards', 'Board\DetailedBoardController@getAll')->name('detailedBoards.get');
Route::get('parts', 'Board\PartController@getAll')->name('parts.get');
Route::get('parts/search', 'Board\PartController@search')->name('parts.search');
Route::get('others/search', 'Board\OtherController@search')->name('others.search');
Route::get('text_infos', 'Other\TextInfoController@get')->name('textInfos.get');
Route::post('line/webhook', 'Line\LineController@webhook')->name('line.webhook');
Route::get('parts/kensaku/landisk_job_dir', 'Board\PrintCircuitBoardController@landisk_job_dir')->name('printCircuitBoards.landisk_job_dir');
Route::post('chatgpt/chat', 'Other\ChatGPTController@chat')->name('chatgpt.chat');

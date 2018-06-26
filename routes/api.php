<?php

use App\Ticket;
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

Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('files/{path?}', [
    'uses' => 'FileBrowserController@contents',
    'as'   => 'files.contents'
])->where('path', '.*');

Route::get('meta/{path?}', [
    'uses' => 'FileBrowserController@meta',
    'as'   => 'files.meta'
])->where('path', '.*');

Route::get('file/{path?}', [
    'uses' => 'FileBrowserController@file',
    'as'   => 'files.file'
])->where('path', '.*');

Route::get('tickets', function() {
    return Ticket::all();
});

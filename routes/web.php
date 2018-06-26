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

use App\Download;
use App\Template;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Authentication routes
 */
Auth::routes();

/**
 * Management routes behind authentication middleware
 */
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('dashboard', [
            'tickets'       => Ticket::all(),
            'downloadCount' => Download::count(),
            'downloads'     => Download::all(),
            'templateCount' => Template::count(),
            'templates'     => Template::all(),
            'userCount'     => User::count(),
            'users'         => User::all()
        ]);
    })->name('dashboard');

    // Templates CRUD
    Route::resource('templates', 'TemplateController');

    // Template previews
    Route::get('templates/preview/{template}', 'DownloadController@preview')->name('templates.preview');

    // Users CRUD
    Route::resource('users', 'UserController');

    // Downloads CRUD
    Route::resource('downloads', 'DownloadController');

    // Settings
    Route::get('settings', 'SettingsController@index')->name('settings.index');
    Route::post('settings', 'SettingsController@update')->name('settings.update');

    // Help pages
    Route::get('help', function () {
        return view('help');
    })->name('help');
});

/**
 * Public download routes
 */
Route::get('/download/{uuid}', 'DownloadController@landing')->name('downloads.landing');
Route::get('/download/{uuid}/save', 'DownloadController@download')->name('downloads.download');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Plc\OEEReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'unisharp']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/login', ['as' => 'auth.get_login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
Route::get('/forget-password', ['as' => 'auth.forget_password', 'uses' => 'Auth\AuthController@forgetPassword']);

//Ckfinder
Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');
Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');
//Route::post('/forget-password', ['as' => 'auth.forget_password', 'uses' => 'Auth\AuthController@ForgetPassword']);

Route::prefix('plc')->name('plc.')->group(function () {
    Route::get('oee', [OEEReportController::class, 'index'])->name('oee.index');
    Route::post('oee', [OEEReportController::class, 'show'])->name('oee.show');
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', ['as' => 'personnel.index', 'uses' => 'Personnel\DashboardController@index']);

// Quản lý người dùng
Route::get('/user', ['as' => 'personnel.user.index', 'uses' => 'Personnel\UserController@index']);
Route::get('/user/sync', ['as' => 'personnel.user.sync', 'uses' => 'Personnel\UserController@sync']);
Route::get('/user/sync-position', ['as' => 'personnel.user.sync_position', 'uses' => 'Personnel\UserController@syncPosition']);
Route::get('/user/sync-department', ['as' => 'personnel.user.sync_department', 'uses' => 'Personnel\UserController@syncDepartment']);
Route::get('/user/create', ['as' => 'personnel.user.create', 'uses' => 'Personnel\UserController@create']);
Route::get('/user/edit/{id}', ['as' => 'personnel.user.edit', 'uses' => 'Personnel\UserController@edit']);
Route::post('/user/store', ['as' => 'personnel.user.store', 'uses' => 'Personnel\UserController@store']);
Route::post('/user/update/{id}', ['as' => 'personnel.user.update', 'uses' => 'Personnel\UserController@update']);
Route::delete('/user/destroy/{id}', ['as' => 'personnel.user.destroy', 'uses' =>
'Personnel\UserController@destroy']);
Route::get('/profile', ['as' => 'personnel.user.profile', 'uses' => 'Personnel\UserController@profile']);

// Quản lý chức vụ
Route::get('/position', ['as' => 'personnel.position.index', 'uses' => 'Personnel\PositionController@index']);
Route::get('/position/create', ['as' => 'personnel.position.create', 'uses' => 'Personnel\PositionController@create']);
Route::get('/position/edit/{id}', ['as' => 'personnel.position.edit', 'uses' => 'Personnel\PositionController@edit']);
Route::post('/position/store', ['as' => 'personnel.position.store', 'uses' => 'Personnel\PositionController@store']);
Route::post('/position/update/{id}', ['as' => 'personnel.position.update', 'uses' => 'Personnel\PositionController@update']);
Route::delete('/position/destroy/{id}', ['as' => 'personnel.position.destroy', 'uses' =>
    'Personnel\PositionController@destroy']);

// Quản lý phòng ban
Route::get('/department', ['as' => 'personnel.department.index', 'uses' => 'Personnel\DepartmentController@index']);
Route::get('/department/convertUidToCode', ['as' => 'personnel.department.convertUidToCode', 'uses' => 'Personnel\DepartmentController@convertUidToCode']);
Route::get('/department/sync', ['as' => 'personnel.department.sync', 'uses' => 'Personnel\DepartmentController@sync']);
Route::get('/department/create', ['as' => 'personnel.department.create', 'uses' => 'Personnel\DepartmentController@create']);
Route::get('/department/edit/{id}', ['as' => 'personnel.department.edit', 'uses' => 'Personnel\DepartmentController@edit']);
Route::post('/department/store', ['as' => 'personnel.department.store', 'uses' => 'Personnel\DepartmentController@store']);
Route::post('/department/update/{id}', ['as' => 'personnel.department.update', 'uses' => 'Personnel\DepartmentController@update']);
Route::delete('/department/destroy/{id}', ['as' => 'personnel.department.destroy', 'uses' =>
    'Personnel\DepartmentController@destroy']);

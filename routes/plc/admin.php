<?php

use App\Http\Controllers\Plc\PlcAlertController;
use App\Http\Controllers\Plc\PlcDataController;
use Illuminate\Support\Facades\Route;



Route::get('/index', ['as' => 'plc.index', 'uses' => 'Plc\PlcController@index']);

// Routes cho Machine
Route::get('/machine', ['as' => 'plc.machine.index', 'uses' => 'Plc\MachineController@index']);
Route::get('/machine/create', ['as' => 'plc.machine.create', 'uses' => 'Plc\MachineController@create']);
Route::post('/machine/store', ['as' => 'plc.machine.store', 'uses' => 'Plc\MachineController@store']);
Route::get('/machine/edit/{id}', ['as' => 'plc.machine.edit', 'uses' => 'Plc\MachineController@edit']);
Route::post('/machine/update/{id}', ['as' => 'plc.machine.update', 'uses' => 'Plc\MachineController@update']);
Route::delete('/machine/destroy/{id}', ['as' => 'plc.machine.destroy', 'uses' => 'Plc\MachineController@destroy']);
Route::post('/machine/toggle-status/{id}', ['as' => 'plc.machine.toggle-status', 'uses' => 'Plc\MachineController@toggleStatus']);

// Hiển thị và tạo mới theo máy
Route::get('/machine/{machine_id}/thresholds', ['as' => 'plc.machine.thresholds.show','uses' => 'Plc\MachineThresholdController@show']);
Route::get('/machine/{machine_id}/thresholds/create', ['as' => 'plc.machine.thresholds.create', 'uses' => 'Plc\MachineThresholdController@create']);

// CRUD thresholds
Route::post('/machine/thresholds/store', ['as' => 'plc.machine.thresholds.store', 'uses' => 'Plc\MachineThresholdController@store']);
Route::get('/machine/thresholds/edit/{id}', ['as' => 'plc.machine.thresholds.edit','uses' => 'Plc\MachineThresholdController@edit']);
Route::post('/machine/thresholds/update/{id}', ['as' => 'plc.machine.thresholds.update','uses' => 'Plc\MachineThresholdController@update']);
Route::delete('/machine/thresholds/destroy/{id}', ['as' => 'plc.machine.thresholds.destroy','uses' => 'Plc\MachineThresholdController@destroy']);

// Toggle routes
Route::post('/machine/thresholds/{id}/toggle-chart', [ 'as' => 'plc.machine.thresholds.toggle-chart','uses' => 'Plc\MachineThresholdController@toggleChart']);
Route::post('/machine/thresholds/{id}/toggle-status', ['as' => 'plc.machine.thresholds.toggle-status','uses' => 'Plc\MachineThresholdController@toggleStatus']);
Route::get('/chart/{machineId}', ['as' => 'plc.chart.show','uses' => 'Plc\ChartController@show']);
Route::get('/chart/{machineId}/data', ['as' => 'plc.chart.data','uses' => 'Plc\ChartController@getData']);
Route::get('/chart/{machineId}/products', ['as' => 'plc.chart.products', 'uses' => 'Plc\ChartController@getProducts']);

Route::get('/data/{machine_id}', ['as' => 'plc.data.getData','uses' => 'Plc\PlcDataController@getData']);

Route::get('/monitor/{machine_id}', ['as' => 'plc.data.monitor','uses' => 'Plc\PlcDataController@monitor']);

Route::get('/alert', [PlcAlertController::class, 'index'])->name('plc.alert.index');
Route::post('/alert/{alert}/status', [PlcAlertController::class, 'updateStatus'])->name('plc.alert.updateStatus');
Route::get('/alert/history/{id}', [PlcAlertController::class, 'history'])->name('plc.alert.history');

//Data sản xuất
// Products
Route::get('/products', ['as' => 'plc.products.index', 'uses' => 'Plc\ProductController@index']);
Route::get('/products/create', ['as' => 'plc.products.create', 'uses' => 'Plc\ProductController@create']);
Route::post('/products/store', ['as' => 'plc.products.store', 'uses' => 'Plc\ProductController@store']);
Route::get('/products/edit/{id}', ['as' => 'plc.products.edit', 'uses' => 'Plc\ProductController@edit']);
Route::post('/products/update/{id}', ['as' => 'plc.products.update', 'uses' => 'Plc\ProductController@update']);
Route::delete('/products/destroy/{id}', ['as' => 'plc.products.destroy', 'uses' => 'Plc\ProductController@destroy']);

// Materials
Route::get('/materials', ['as' => 'plc.materials.index', 'uses' => 'Plc\MaterialController@index']);
Route::get('/materials/create', ['as' => 'plc.materials.create', 'uses' => 'Plc\MaterialController@create']);
Route::post('/materials/store', ['as' => 'plc.materials.store', 'uses' => 'Plc\MaterialController@store']);
Route::get('/materials/edit/{id}', ['as' => 'plc.materials.edit', 'uses' => 'Plc\MaterialController@edit']);
Route::post('/materials/update/{id}', ['as' => 'plc.materials.update', 'uses' => 'Plc\MaterialController@update']);
Route::delete('/materials/destroy/{id}', ['as' => 'plc.materials.destroy', 'uses' => 'Plc\MaterialController@destroy']);

// PE Standards
Route::get('/pe-standards', ['as' => 'plc.pe_standards.index', 'uses' => 'Plc\PeStandardController@index']);
Route::get('/pe-standards/create', ['as' => 'plc.pe_standards.create', 'uses' => 'Plc\PeStandardController@create']);
Route::post('/pe-standards/store', ['as' => 'plc.pe_standards.store', 'uses' => 'Plc\PeStandardController@store']);
Route::get('/pe-standards/edit/{id}', ['as' => 'plc.pe_standards.edit', 'uses' => 'Plc\PeStandardController@edit']);
Route::post('/pe-standards/update/{id}', ['as' => 'plc.pe_standards.update', 'uses' => 'Plc\PeStandardController@update']);
Route::delete('/pe-standards/destroy/{id}', ['as' => 'plc.pe_standards.destroy', 'uses' => 'Plc\PeStandardController@destroy']);

// Production Entries
Route::get('/production/entries', ['as' => 'plc.production.entries.index', 'uses' => 'Plc\ProductionEntryController@index']);
Route::get('/production/entries/create', ['as' => 'plc.production.entries.create', 'uses' => 'Plc\ProductionEntryController@create']);
Route::post('/production/entries/store', ['as' => 'plc.production.entries.store', 'uses' => 'Plc\ProductionEntryController@store']);
Route::get('/production/entries/edit/{id}', ['as' => 'plc.production.entries.edit', 'uses' => 'Plc\ProductionEntryController@edit']);
Route::post('/production/entries/update/{id}', ['as' => 'plc.production.entries.update', 'uses' => 'Plc\ProductionEntryController@update']);
Route::delete('/production/entries/destroy/{id}', ['as' => 'plc.production.entries.destroy', 'uses' => 'Plc\ProductionEntryController@destroy']);

// OEE Reports
Route::get('/reports/oee', ['as' => 'plc.reports.oee.index', 'uses' => 'Plc\OEEReportController@index']);
Route::get('/reports/oee/daily', ['as' => 'plc.reports.oee.daily', 'uses' => 'Plc\OEEReportController@daily']);
Route::get('/reports/oee/monthly', ['as' => 'plc.reports.oee.monthly', 'uses' => 'Plc\OEEReportController@monthly']);
Route::get('/reports/oee/export', ['as' => 'plc.reports.oee.export', 'uses' => 'Plc\OEEReportController@export']);

// Dashboard
Route::get('/dashboard', ['as' => 'plc.dashboard', 'uses' => 'Plc\DashboardController@index']);
Route::get('/dashboard/machine-status', ['as' => 'plc.dashboard.machine-status', 'uses' => 'Plc\DashboardController@getMachineStatus']);
Route::get('/dashboard/top-products', ['as' => 'plc.dashboard.top-products', 'uses' => 'Plc\DashboardController@getTopProducts']);
Route::get('/dashboard/production-trend', ['as' => 'plc.dashboard.production-trend', 'uses' => 'Plc\DashboardController@getProductionTrend']);

// Import/Export Data
Route::post('/standards/import', ['as' => 'plc.standards.import', 'uses' => 'Plc\StandardDataController@import']);
Route::get('/standards/template', ['as' => 'plc.standards.template', 'uses' => 'Plc\StandardDataController@downloadTemplate']);

// API routes cho DevExtreme
Route::get('/api/dashboard/stats', ['as' => 'plc.api.dashboard.stats', 'uses' => 'Plc\Api\DashboardController@getStats']);
Route::get('/api/dashboard/oee', ['as' => 'plc.api.dashboard.oee', 'uses' => 'Plc\Api\DashboardController@getOEE']);



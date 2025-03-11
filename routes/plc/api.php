<?php

Route::prefix('api')->group(function () {
    // Dashboard APIs
    Route::get('/dashboard/stats', ['as' => 'plc.api.dashboard.stats', 'uses' => 'Api\DashboardController@getStats']);
    Route::get('/dashboard/machine-status', ['as' => 'plc.api.dashboard.machine-status', 'uses' => 'Api\DashboardController@getMachineStatus']);
    Route::get('/dashboard/top-products', ['as' => 'plc.api.dashboard.top-products', 'uses' => 'Api\DashboardController@getTopProducts']);
    Route::get('/dashboard/production-trend', ['as' => 'plc.api.dashboard.production-trend', 'uses' => 'Api\DashboardController@getProductionTrend']);
    Route::get('/dashboard/oee', ['as' => 'plc.api.dashboard.oee', 'uses' => 'Api\DashboardController@getOEE']);

    // Production Data APIs
    Route::get('/production', ['as' => 'plc.api.production.data', 'uses' => 'Api\ProductionController@getProductionData']);

    // Machine Data APIs
    Route::get('/machines', ['as' => 'plc.api.machines.data', 'uses' => 'Api\MachineController@getMachineData']);
});

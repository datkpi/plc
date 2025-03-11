<?php

use Illuminate\Support\Facades\Route;

Route::get('/thong-tin-ung-vien/{token}', ['as' => 'frontend.candidate.getForm', 'uses' => 'Frontend\CandidateController@getForm']);
Route::post('/thong-tin-ung-vien/submit/{token}', ['as' => 'frontend.candidate.submitForm', 'uses' => 'Frontend\CandidateController@submitForm']);
Route::post('/thong-tin-ung-vien/update/{token}', ['as' => 'frontend.candidate.update', 'uses' => 'Frontend\CandidateController@update']);

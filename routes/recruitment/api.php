<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Dashboard
Route::get('/dashboard/get-data', ['as' => 'recruitment.dashboard.getData', 'uses' => 'Recruitment\DashboardController@getData']);

//Lấy thông báo của người dùng
Route::get('/notification', ['as' => 'api.notification.index', 'uses' => 'Recruitment\NotificationController@getNotification']);

Route::get('/request-form/get-annual/{id}', ['as' => 'recruitment.request_form.get_annual', 'uses' => 'Recruitment\RequestFormController@getAnnual']);

//Lấy user id
Route::get('/user/get-data', ['as' => 'api.auth.getAuthData', 'uses' => 'Auth\AuthController@getAuthData']);

//User
Route::get('/user/get-by-department', ['as' => 'api.user.getByDepartment', 'uses' => 'Recruitment\UserController@getByDepartment']);

//form
Route::post('/candidate/change-can-edit/{id}', ['as' => 'recruitment.candidate.change_can_edit', 'uses' => 'Recruitment\CandidateController@changeCanEdit']);
Route::post('/candidate/change-to-candidate/{id}', ['as' => 'recruitment.candidate.change_to_candidate', 'uses' => 'Recruitment\CandidateController@changeToCandidate']);

//Lấy dữ liệu
Route::get('/position/get-data', ['as' => 'recruitment.position.getData', 'uses' => 'Recruitment\PositionController@getData']);
Route::get('/candidate/get-data', ['as' => 'recruitment.candidate.get_data', 'uses' => 'Recruitment\CandidateController@getData']);
Route::post('/candidate/create-form', ['as' => 'recruitment.candidate.create_form', 'uses' => 'Recruitment\CandidateController@createForm']);
Route::get('/request-form/get-data', ['as' => 'recruitment.request_form.get_data', 'uses' => 'Recruitment\RequestFormController@getData']);
Route::get('/department/get-data', ['as' => 'recruitment.department.getData', 'uses' => 'Recruitment\DepartmentController@getData']);
Route::get('/recruitment-plan/get-data', ['as' => 'recruitment.recruitment_plan.get_data', 'uses' => 'Recruitment\RecruitmentPlanController@getData']);
Route::get('/interview-address/get-data', ['as' => 'recruitment.interview_address.getData', 'uses' => 'Recruitment\InterviewAddressController@getData']);
Route::get('/interview-schedule/get-data', ['as' => 'recruitment.interview_schedule.getData', 'uses' => 'Recruitment\InterviewScheduleController@getData']);

//api thống kê báo cáo
// Thống kê báo cáo bảng
Route::get('/report/recruitment', ['as' => 'api.report.recruitment.index', 'uses' => 'Recruitment\ReportController@getDataIndex']);
Route::get('/report/recruitment/get-candidate', ['as' => 'api.report.recruitment.getCandidate', 'uses' => 'Recruitment\ReportController@getCandidate']);
Route::get('/report/recruitment/convert-rate', ['as' => 'api.report.recruitment.convertRate', 'uses' => 'Recruitment\ReportController@getConvertRate']);
Route::get('/report/recruitment/interview-rate', ['as' => 'api.report.recruitment.interviewRate', 'uses' => 'Recruitment\ReportController@getInterviewRate']);
Route::get('/report/recruitment/candidate-source', ['as' => 'recruitment.report.recruitment.getCandidateSource', 'uses' => 'Recruitment\ReportController@getCandidateSource']);
Route::get('/report/recruitment/recruitment-kpi', ['as' => 'api.report.recruitment.recruitmentKpi', 'uses' => 'Recruitment\ReportController@getRecruitmentKpi']);

// Thống kê báo cáo chart
Route::get('/report/recruitment/chart', ['as' => 'api.report.recruitment.indexChart', 'uses' => 'Recruitment\ReportController@getDataIndexChart']);
Route::get('/report/recruitment/get-candidate-chart', ['as' => 'api.report.recruitment.getCandidateChart', 'uses' => 'Recruitment\ReportController@getCandidateChart']);
Route::get('/report/recruitment/convert-rate-chart', ['as' => 'api.report.recruitment.convertRateChart', 'uses' => 'Recruitment\ReportController@getConvertRateChart']);
Route::get('/report/recruitment/interview-rate-chart', ['as' => 'api.report.recruitment.interviewRateChart', 'uses' => 'Recruitment\ReportController@getInterviewRateChart']);
Route::get('/report/recruitment/candidate-source-chart', ['as' => 'recruitment.report.recruitmentChart.getCandidateSource', 'uses' => 'Recruitment\ReportController@getCandidateSourceChart']);
Route::get('/report/recruitment/recruitment-kpi-chart', ['as' => 'api.report.recruitment.recruitmentKpiChart', 'uses' => 'Recruitment\ReportController@getRecruitmentKpiChart']);

//api dashboard
Route::get('/dashboard/get-data-funnel', ['as' => 'api.dashboard.getDataFunnel', 'uses' => 'Recruitment\DashboardController@getDataFunnel']);
Route::get('/dashboard/get-data-pie', ['as' => 'api.dashboard.getDataPie', 'uses' => 'Recruitment\DashboardController@getDataPie']);

// interview schedule
Route::get('/interview-schedule/get-interviewer-data/{id}', ['as' => 'api.interview_schedule.getInterviewerData', 'uses' => 'Recruitment\InterviewScheduleController@getInterviewerData']);
Route::post('/interview-schedule/evaluate/{id}', ['as' => 'api.interview_schedule.evaluate', 'uses' => 'Recruitment\InterviewScheduleController@evaluate']);

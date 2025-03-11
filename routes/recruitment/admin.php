<?php

// Index
Route::get('/dashboard', ['as' => 'recruitment.index', 'uses' => 'Recruitment\DashboardController@index']);
// trả về lỗi 403
// Route::get('/', ['as' => 'recruitment.403', 'uses' => 'Recruitment\DashboardController@403']);

// Route::post('/excel/import', ['as' => 'recruitment.excel.import', 'uses' => 'Recruitment\ExcelController@import']);
// Route::post('/excel/export', ['as' => 'recruitment.excel.export', 'uses' => 'Recruitment\ExcelController@export']);
// Route::post('/excel/download/{id}', ['as' => 'recruitment.excel.download', 'uses' => 'Recruitment\ExcelController@download']);

// Quản lý tài khoản
Route::get('/ckeditor/upload', ['as' => 'ckeditor.upload', 'uses' => 'Recruitment\AccountController@upload']);
Route::get('/users/export/', ['as' => 'recruitment.user.index', 'uses' => 'Recruitment\AccountController@export']);
Route::get('/account', ['as' => 'recruitment.account.index', 'uses' => 'Recruitment\AccountController@index']);
Route::get('/account/create', ['as' => 'recruitment.account.create', 'uses' => 'Recruitment\AccountController@create']);
Route::get('/account/edit/{id}', ['as' => 'recruitment.account.edit', 'uses' => 'Recruitment\AccountController@edit']);
Route::post('/account/store', ['as' => 'recruitment.account.store', 'uses' => 'Recruitment\AccountController@store']);
Route::post('/account/update/{id}', ['as' => 'recruitment.account.update', 'uses' => 'Recruitment\AccountController@update']);
Route::delete('/account/destroy/{id}', ['as' => 'recruitment.account.destroy', 'uses' => 'Recruitment\AccountController@destroy']);

// Quản lý người dùng
Route::get('/user', ['as' => 'recruitment.user.index', 'uses' => 'Recruitment\UserController@index']);
Route::get('/user/create', ['as' => 'recruitment.user.create', 'uses' => 'Recruitment\UserController@create']);
Route::get('/user/edit/{id}', ['as' => 'recruitment.user.edit', 'uses' => 'Recruitment\UserController@edit']);
Route::post('/user/store', ['as' => 'recruitment.user.store', 'uses' => 'Recruitment\UserController@store']);
Route::post('/user/update/{id}', ['as' => 'recruitment.user.update', 'uses' => 'Recruitment\UserController@update']);
Route::get('/user/get-change-password/{id}', ['as' => 'recruitment.user.get_change_password', 'uses' => 'Recruitment\UserController@getChangePassword']);
Route::post('/user/change-password/{id}', ['as' => 'recruitment.user.change_password', 'uses' => 'Recruitment\UserController@changePassword']);
Route::delete('/user/destroy/{id}', ['as' => 'recruitment.user.destroy', 'uses' => 'Recruitment\UserController@destroy']);
Route::get('/user/logout', ['as' => 'recruitment.user.logout', 'uses' => 'Auth\AuthController@logout']);

// Quản lý mẫu email
Route::get('/mail-template', ['as' => 'recruitment.mail_template.index', 'uses' => 'Recruitment\MailTemplateController@index']);
Route::get('/mail-template/test/{id}', ['as' => 'recruitment.mail_template.test', 'uses' => 'Recruitment\MailTemplateController@test']);
Route::get('/mail-template/create', ['as' => 'recruitment.mail_template.create', 'uses' => 'Recruitment\MailTemplateController@create']);
Route::get('/mail-template/edit/{id}', ['as' => 'recruitment.mail_template.edit', 'uses' => 'Recruitment\MailTemplateController@edit']);
Route::post('/mail-template/store', ['as' => 'recruitment.mail_template.store', 'uses' => 'Recruitment\MailTemplateController@store']);
Route::post('/mail-template/update/{id}', ['as' => 'recruitment.mail_template.update', 'uses' => 'Recruitment\MailTemplateController@update']);
Route::delete('/mail-template/destroy/{id}', ['as' => 'recruitment.mail_template.destroy', 'uses' => 'Recruitment\MailTemplateController@destroy']);

// Quản lý gửi mail
// Route::get('/mail', ['as' => 'recruitment.mail.index', 'uses' => 'Recruitment\MailController@index']);
// Route::get('/mail/create', ['as' => 'recruitment.mail.create', 'uses' => 'Recruitment\MailController@create']);
// Route::get('/mail/edit/{id}', ['as' => 'recruitment.mail.edit', 'uses' => 'Recruitment\MailController@edit']);
// Route::post('/mail/store', ['as' => 'recruitment.mail.store', 'uses' => 'Recruitment\MailController@store']);
// Route::post('/mail/update/{id}', ['as' => 'recruitment.mail.update', 'uses' => 'Recruitment\MailController@update']);
// Route::delete('/mail/destroy/{id}', ['as' => 'recruitment.mail.destroy', 'uses' => 'Recruitment\MailController@destroy']);

// Quản lý nhóm mail
// Route::get('/mail-group', ['as' => 'recruitment.mail_group.index', 'uses' => 'Recruitment\MailGroupController@index']);
// Route::get('//mail-group/create', ['as' => 'recruitment.mail_group.create', 'uses' => 'Recruitment\MailGroupController@create']);
// Route::get('//mail-group/edit/{id}', ['as' => 'recruitment.mail_group.edit', 'uses' => 'Recruitment\MailGroupController@edit']);
// Route::post('//mail-group/store', ['as' => 'recruitment.mail_group.store', 'uses' => 'Recruitment\MailGroupController@store']);
// Route::post('//mail-group/update/{id}', ['as' => 'recruitment.mail_group.update', 'uses' => 'Recruitment\MailGroupController@update']);
// Route::delete('//mail-group/destroy/{id}', ['as' => 'recruitment.mail_group.destroy', 'uses' => 'Recruitment\MailGroupController@destroy']);

// Quản lý phòng ban
Route::get('/department', ['as' => 'recruitment.department.index', 'uses' => 'Recruitment\DepartmentController@index']);
// Route::get('/department/get-data', ['as' => 'recruitment.department.getData', 'uses' => 'Recruitment\DepartmentController@getData']);
Route::get('/department/create', ['as' => 'recruitment.department.create', 'uses' => 'Recruitment\DepartmentController@create']);
Route::get('/department/edit/{id}', ['as' => 'recruitment.department.edit', 'uses' => 'Recruitment\DepartmentController@edit']);
Route::post('/department/store', ['as' => 'recruitment.department.store', 'uses' => 'Recruitment\DepartmentController@store']);
Route::post('/department/update/{id}', ['as' => 'recruitment.department.update', 'uses' => 'Recruitment\DepartmentController@update']);
Route::delete('/department/destroy/{id}', ['as' => 'recruitment.department.destroy', 'uses' => 'Recruitment\DepartmentController@destroy']);

// Quản lý chức vụ
Route::get('/position', ['as' => 'recruitment.position.index', 'uses' => 'Recruitment\PositionController@index']);
// Route::get('/position/get-data', ['as' => 'recruitment.position.getData', 'uses' => 'Recruitment\PositionController@getData']);
Route::get('/position/create', ['as' => 'recruitment.position.create', 'uses' => 'Recruitment\PositionController@create']);
Route::get('/position/edit/{id}', ['as' => 'recruitment.position.edit', 'uses' => 'Recruitment\PositionController@edit']);
Route::post('/position/store', ['as' => 'recruitment.position.store', 'uses' => 'Recruitment\PositionController@store']);
Route::post('/position/update/{id}', ['as' => 'recruitment.position.update', 'uses' => 'Recruitment\PositionController@update']);
Route::delete('/position/destroy/{id}', ['as' => 'recruitment.position.destroy', 'uses' => 'Recruitment\PositionController@destroy']);

// Quản lý nguồn ứng viên
Route::get('/source', ['as' => 'recruitment.source.index', 'uses' => 'Recruitment\SourceController@index']);
Route::get('/source/create', ['as' => 'recruitment.source.create', 'uses' => 'Recruitment\SourceController@create']);
Route::get('/source/edit/{id}', ['as' => 'recruitment.source.edit', 'uses' => 'Recruitment\SourceController@edit']);
Route::post('/source/store', ['as' => 'recruitment.source.store', 'uses' => 'Recruitment\SourceController@store']);
Route::post('/source/update/{id}', ['as' => 'recruitment.source.update', 'uses' => 'Recruitment\SourceController@update']);
Route::delete('/source/destroy/{id}', ['as' => 'recruitment.source.destroy', 'uses' => 'Recruitment\SourceController@destroy']);

// Quản lý ứng viên
Route::get('/candidate', ['as' => 'recruitment.candidate.index', 'uses' => 'Recruitment\CandidateController@index']);
Route::get('/candidate/import-history', ['as' => 'recruitment.candidate.import_history', 'uses' => 'Recruitment\CandidateController@importHistory']);
Route::get('/candidate/export-candidate', ['as' => 'recruitment.candidate.export_candidate', 'uses' => 'Recruitment\CandidateController@exportCandidate']);
Route::get('/candidate/create', ['as' => 'recruitment.candidate.create', 'uses' => 'Recruitment\CandidateController@create']);
Route::get('/candidate/syncCurrentStage', ['as' => 'recruitment.candidate.syncCurrentStage', 'uses' => 'Recruitment\CandidateController@syncCurrentStage']);
// Route::get('/candidate/get-data', ['as' => 'recruitment.candidate.get_data', 'uses' => 'Recruitment\CandidateController@getData']);
Route::post('/candidate/import-excel', ['as' => 'recruitment.candidate.import_excel', 'uses' => 'Recruitment\CandidateController@importExcel']);
Route::post('/candidate/import-excel-update-data', ['as' => 'recruitment.candidate.import_excel_update_data', 'uses' => 'Recruitment\CandidateController@importExcelUpdateData']);
Route::get('/candidate/edit/{id}', ['as' => 'recruitment.candidate.edit', 'uses' => 'Recruitment\CandidateController@edit']);
Route::post('/candidate/store', ['as' => 'recruitment.candidate.store', 'uses' => 'Recruitment\CandidateController@store']);
Route::post('/candidate/update/{id}', ['as' => 'recruitment.candidate.update', 'uses' => 'Recruitment\CandidateController@update']);
Route::post('/candidate/comment/{id}', ['as' => 'recruitment.candidate.comment', 'uses' => 'Recruitment\CandidateController@comment']);
Route::delete('/candidate/destroy/{id}', ['as' => 'recruitment.candidate.destroy', 'uses' => 'Recruitment\CandidateController@destroy']);

// Quản lý phiếu đề nghị tuyển dụng
Route::get('/request-form', ['as' => 'recruitment.request_form.index', 'uses' => 'Recruitment\RequestFormController@index']);
// Route::get('/candidate/update-gender-value', ['as' => 'recruitment.candidate.updateGenderValue', 'uses' => 'Recruitment\CandidateController@updateGenderValue']);
// Route::get('/request-form/get-data', ['as' => 'recruitment.request_form.get_data', 'uses' => 'Recruitment\RequestFormController@getData']);
Route::get('/request-form/create', ['as' => 'recruitment.request_form.create', 'uses' => 'Recruitment\RequestFormController@create']);
Route::get('/request-form/edit/{id}', ['as' => 'recruitment.request_form.edit', 'uses' => 'Recruitment\RequestFormController@edit']);
Route::post('/request-form/store', ['as' => 'recruitment.request_form.store', 'uses' => 'Recruitment\RequestFormController@store']);
Route::post('/request-form/update/{id}', ['as' => 'recruitment.request_form.update', 'uses' => 'Recruitment\RequestFormController@update']);
Route::delete('/request-form/destroy/{id}', ['as' => 'recruitment.request_form.destroy', 'uses' => 'Recruitment\RequestFormController@destroy']);
Route::post('/request-form/approve/{type}/{id}', ['as' => 'recruitment.request_form.approve', 'uses' => 'Recruitment\RequestFormController@approve']);
Route::post('/request-form/add-plan', ['as' => 'recruitment.request_form.add_plan', 'uses' => 'Recruitment\RequestFormController@addPlan']);
Route::post('/request-form/approve-all/{id}', ['as' => 'recruitment.request_form.approve_all', 'uses' => 'Recruitment\RequestFormController@approveAll']);
Route::post('/request-form/comment/{id}', ['as' => 'recruitment.request_form.comment', 'uses' => 'Recruitment\RequestFormController@comment']);

// Quản lý chi tiết phiếu tuyển dụng
Route::post('/request-form-detail/comment/{request_form_id}', ['as' => 'recruitment.request_form_detail.comment', 'uses' => 'Recruitment\RequestFormDetailController@comment']);

// Quản lý kế hoạch tuyển dụng
Route::get('/recruitment-plan', ['as' => 'recruitment.recruitment_plan.index', 'uses' => 'Recruitment\RecruitmentPlanController@index']);
// Route::get('/recruitment-plan/get-data', ['as' => 'recruitment.recruitment_plan.get_data', 'uses' => 'Recruitment\RecruitmentPlanController@getData']);
Route::get('/recruitment-plan/create', ['as' => 'recruitment.recruitment_plan.create', 'uses' => 'Recruitment\RecruitmentPlanController@create']);
Route::get('/recruitment-plan/edit/{id}', ['as' => 'recruitment.recruitment_plan.edit', 'uses' => 'Recruitment\RecruitmentPlanController@edit']);
Route::post('/recruitment-plan/store', ['as' => 'recruitment.recruitment_plan.store', 'uses' => 'Recruitment\RecruitmentPlanController@store']);
Route::post('/recruitment-plan/add-form/{id}', ['as' => 'recruitment.recruitment_plan.add_form', 'uses' => 'Recruitment\RecruitmentPlanController@addForm']);
Route::post('/recruitment-plan/update/{id}', ['as' => 'recruitment.recruitment_plan.update', 'uses' => 'Recruitment\RecruitmentPlanController@update']);
Route::delete('/recruitment-plan/destroy/{id}', ['as' => 'recruitment.recruitment_plan.destroy', 'uses' => 'Recruitment\RecruitmentPlanController@destroy']);

// Quản lý định biên nhân sự
Route::get('/annual-employee', ['as' => 'recruitment.annual_employee.index', 'uses' => 'Recruitment\AnnualEmployeeController@index']);
Route::get('/annual-employee/create', ['as' => 'recruitment.annual_employee.create', 'uses' => 'Recruitment\AnnualEmployeeController@create']);
Route::get('/annual-employee/edit/{id}', ['as' => 'recruitment.annual_employee.edit', 'uses' => 'Recruitment\AnnualEmployeeController@edit']);
Route::post('/annual-employee/approve/{id}', ['as' => 'recruitment.annual_employee.approve', 'uses' => 'Recruitment\AnnualEmployeeController@approve']);
Route::post('/annual-employee/store', ['as' => 'recruitment.annual_employee.store', 'uses' => 'Recruitment\AnnualEmployeeController@store']);
Route::post('/annual-employee/update/{id}', ['as' => 'recruitment.annual_employee.update', 'uses' => 'Recruitment\AnnualEmployeeController@update']);
Route::delete('/annual-employee/destroy/{id}', ['as' => 'recruitment.annual_employee.destroy', 'uses' => 'Recruitment\AnnualEmployeeController@destroy']);

// Nhu cầu tuyển dụng
Route::get('/recruitment-need', ['as' => 'recruitment.recruitment_need.index', 'uses' => 'Recruitment\RecruitmentNeedController@index']);
Route::get('/recruitment-need/create', ['as' => 'recruitment.recruitment_need.create', 'uses' => 'Recruitment\RecruitmentNeedController@create']);
Route::get('/recruitment-need/syncCurrentMonth', ['as' => 'recruitment.recruitment_need.syncCurrentMonth', 'uses' => 'Recruitment\RecruitmentNeedController@syncCurrentMonth']);
Route::get('/recruitment-need/edit/{id}', ['as' => 'recruitment.recruitment_need.edit', 'uses' => 'Recruitment\RecruitmentNeedController@edit']);
Route::post('/recruitment-need/approve/{id}', ['as' => 'recruitment.recruitment_need.approve', 'uses' => 'Recruitment\RecruitmentNeedController@approve']);
Route::post('/recruitment-need/store', ['as' => 'recruitment.recruitment_need.store', 'uses' => 'Recruitment\RecruitmentNeedController@store']);
Route::post('/recruitment-need/update/{id}', ['as' => 'recruitment.recruitment_need.update', 'uses' => 'Recruitment\RecruitmentNeedController@update']);
Route::delete('/recruitment-need/destroy/{id}', ['as' => 'recruitment.recruitment_need.destroy', 'uses' => 'Recruitment\RecruitmentNeedController@destroy']);

// Cài đặt duyệt
Route::get('/approve', ['as' => 'recruitment.approve.index', 'uses' => 'Recruitment\ApproveController@index']);
Route::get('/approve/create', ['as' => 'recruitment.approve.create', 'uses' => 'Recruitment\ApproveController@create']);
Route::get('/approve/edit/{id}', ['as' => 'recruitment.approve.edit', 'uses' => 'Recruitment\ApproveController@edit']);
Route::post('/approve/store', ['as' => 'recruitment.approve.store', 'uses' => 'Recruitment\ApproveController@store']);
Route::post('/approve/update/{id}', ['as' => 'recruitment.approve.update', 'uses' => 'Recruitment\ApproveController@update']);
Route::delete('/approve/destroy/{id}', ['as' => 'recruitment.approve.destroy', 'uses' => 'Recruitment\ApproveController@destroy']);

// Địa điểm phỏng vấn
// Route::get('/interview-address', ['as' => 'recruitment.interview_address.index', 'uses' => 'Recruitment\InterviewAddressController@index']);
// Route::get('/interview-address/create', ['as' => 'recruitment.interview_address.create', 'uses' => 'Recruitment\InterviewAddressController@create']);
// Route::get('/interview-address/edit/{id}', ['as' => 'recruitment.interview_address.edit', 'uses' => 'Recruitment\InterviewAddressController@edit']);
// Route::post('/interview-address/store', ['as' => 'recruitment.interview_address.store', 'uses' => 'Recruitment\InterviewAddressController@store']);
// Route::post('/interview-address/update/{id}', ['as' => 'recruitment.interview_address.update', 'uses' => 'Recruitment\InterviewAddressController@update']);
// Route::delete('/interview-address/destroy/{id}', ['as' => 'recruitment.interview_address.destroy', 'uses' => 'Recruitment\InterviewAddressController@destroy']);

// Quản lý lịch tuyển dụng
Route::get('/interview-schedule', ['as' => 'recruitment.interview_schedule.index', 'uses' => 'Recruitment\InterviewScheduleController@index']);
// Route::get('/interview-schedule/get-data', ['as' => 'recruitment.interview_schedule.getData', 'uses' => 'Recruitment\InterviewScheduleController@getData']);
//Route::get('/interview-schedule/create', ['as' => 'recruitment.interview_schedule.create', 'uses' => 'Recruitment\InterviewScheduleController@create']);
Route::get('/interview-schedule/edit/{candidate_id}', ['as' => 'recruitment.interview_schedule.edit', 'uses' => 'Recruitment\InterviewScheduleController@edit']);
Route::get('/interview-schedule/sync', ['as' => 'recruitment.interview_schedule.sync', 'uses' => 'Recruitment\InterviewScheduleController@sync']);
Route::get('/interview-schedule/syncStage', ['as' => 'recruitment.interview_schedule.syncStage', 'uses' => 'Recruitment\InterviewScheduleController@syncStage']);
Route::get('/interview-schedule/sync-pvsb', ['as' => 'recruitment.interview_schedule.sync_pvsb', 'uses' => 'Recruitment\InterviewScheduleController@syncPvsb']);
Route::post('/interview-schedule/store', ['as' => 'recruitment.interview_schedule.store', 'uses' => 'Recruitment\InterviewScheduleController@store']);
Route::post('/interview-schedule/update/{id}', ['as' => 'recruitment.interview_schedule.update', 'uses' => 'Recruitment\InterviewScheduleController@update']);
Route::post('/interview-schedule/delete-candidate/{id}', ['as' => 'recruitment.interview_schedule.deleteCandidate', 'uses' => 'Recruitment\InterviewScheduleController@deleteCandidate']);
Route::delete('/interview-schedule/destroy/{id}', ['as' => 'recruitment.interview_schedule.destroy', 'uses' => 'Recruitment\InterviewScheduleController@destroy']);
Route::delete('/interview-schedule/delete-interviewer/{id}', ['as' => 'recruitment.interview_schedule.deleteInterviewer', 'uses' => 'Recruitment\InterviewScheduleController@deleteInterviewer']);
Route::post('/interview-schedule/add-interviewer', ['as' => 'recruitment.interview_schedule.addInterviewer', 'uses' => 'Recruitment\InterviewScheduleController@addInterviewer']);
Route::post('/interview-schedule/comment', ['as' => 'recruitment.interview_schedule.comment', 'uses' => 'Recruitment\InterviewScheduleController@comment']);

// Cài đặt vai trò
Route::get('/role', ['as' => 'recruitment.role.index', 'uses' => 'Recruitment\RoleController@index']);
Route::get('/role/create', ['as' => 'recruitment.role.create', 'uses' => 'Recruitment\RoleController@create']);
Route::get('/role/edit/{id}', ['as' => 'recruitment.role.edit', 'uses' => 'Recruitment\RoleController@edit']);
Route::post('/role/store', ['as' => 'recruitment.role.store', 'uses' => 'Recruitment\RoleController@store']);
Route::post('/role/update/{id}', ['as' => 'recruitment.role.update', 'uses' => 'Recruitment\RoleController@update']);
Route::delete('/role/destroy/{id}', ['as' => 'recruitment.role.destroy', 'uses' => 'Recruitment\RoleController@destroy']);

// Lịch sử log
Route::get('/activity', ['as' => 'recruitment.activity.index', 'uses' => 'Recruitment\ActivityController@index']);
Route::get('/activity/{id}', ['as' => 'recruitment.activity.show', 'uses' => 'Recruitment\ActivityController@show']);

// Cấu hình phiếu đề xuất
Route::get('/request-form-config', ['as' => 'recruitment.request_form_config.index', 'uses' => 'Recruitment\RequestFormConfigController@edit']);
Route::post('/request-form-config/update/{id}', ['as' => 'recruitment.request_form_config.update', 'uses' => 'Recruitment\RequestFormConfigController@update']);

//Đồng bộ kế hoạch tuyển dụng
Route::get('/recruitment-need/sync', ['as' => 'recruitment.recruitment_need.sync', 'uses' => 'Recruitment\RecruitmentNeedController@sync']);

//Thông báo
Route::get('/notification', ['as' => 'recruitment.notification.index', 'uses' => 'Recruitment\NotificationController@index']);
Route::post('/notification/read/{id}', ['as' => 'recruitment.notification.read', 'uses' => 'Recruitment\NotificationController@markNotificationAsRead']);
Route::get('/notification/read-all', ['as' => 'recruitment.notification.readAll', 'uses' => 'Recruitment\NotificationController@markNotificationAsReadAll']);

// Thống kê báo cáo
Route::get('/report/recruitment', ['as' => 'recruitment.report.recruitment.index', 'uses' => 'Recruitment\ReportController@index']);
Route::get('/report/recruitment/candidate', ['as' => 'recruitment.report.recruitment.candidate', 'uses' => 'Recruitment\ReportController@candidate']);
Route::get('/report/recruitment/convert-rate', ['as' => 'recruitment.report.recruitment.convertRate', 'uses' => 'Recruitment\ReportController@convertRate']);
Route::get('/report/recruitment/interview-rate', ['as' => 'recruitment.report.recruitment.interviewRate', 'uses' => 'Recruitment\ReportController@interviewRate']);
Route::get('/report/recruitment/candidate-source', ['as' => 'recruitment.report.recruitment.candidateSource', 'uses' => 'Recruitment\ReportController@candidateSource']);
Route::get('/report/recruitment/recruitment-kpi', ['as' => 'recruitment.report.recruitment.recruitmentKpi', 'uses' => 'Recruitment\ReportController@recruitmentKpi']);

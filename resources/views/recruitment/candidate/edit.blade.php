@extends('recruitment.layouts.master')
@section('content')
<!-- general form elements disabled -->

<div class="row">
    <div class="col-sm-6">
    <h5>Trạng thái ứng viên <b>{{$candidate->name}}</b> hiện tại: <span class="badge badge-pill badge-success">{{
            $candidate->status_value }}</span></h5>
    </div>
    @if(strpos($candidate->status, '_success') !== false)
    <div class="col-sm-6 d-flex justify-content-end">
        @if(Request::get('is_create') != true)
        <button id="pushIsCreate" value="create" type="button" class="btn btn-primary mb-1">Thêm lịch phỏng vấn</button>
        @else
        <button id="pushIsCreate" value="create" type="button" class="btn btn-danger mb-1">Huỷ tạo lịch phỏng vấn</button>
        @endif
    </div>
    @endif
</div>
{{-- <div class="row"> --}}
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="col-12 col-sm-12">
            <div class="card-header p-0 border-bottom-0 mb-2">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    {{-- @dd($stage) --}}
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                            href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                            aria-selected="false">Thông tin tuyển dụng</a>
                    </li> --}}

                    <li class="nav-item">
                        <a class="{{ Request::get('is_create') != true ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-home-tab"
                            data-toggle="pill" href="#custom-tabs-four-home" role="tab"
                            aria-controls="custom-tabs-four-home" aria-selected="true">Hồ sơ</a>
                    </li>
                    @for($i = 0; $i < $count; $i++)
                        @if($i == 0)
                            <li class="nav-item">
                                <a class="nav-link"
                                    id="custom-tabs-four-interview{{$i}}-tab" data-toggle="pill" href="#custom-tabs-four-interview{{$i}}"
                                    role="tab" aria-controls="custom-tabs-four-interview{{$i}}" aria-selected="true">{{$candidate->{"interview_result" . $i . "_value"} ? $candidate->{"interview_result" . $i . "_value"} : 'Đang'}} phỏng vấn sơ bộ
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link"
                                    id="custom-tabs-four-interview{{$i}}-tab" data-toggle="pill" href="#custom-tabs-four-interview{{$i}}"
                                    role="tab" aria-controls="custom-tabs-four-interview{{$i}}" aria-selected="true">{{$candidate->{"interview_result" . $i . "_value"} ?$candidate->{"interview_result" . $i . "_value"} : 'Đang' }} phỏng vấn vòng {{$i}}
                                </a>
                            </li>
                        @endif
                    @endfor
                    @if(Request::get('is_create') == true)
                        <li class="nav-item">
                            <a class="nav-link active"
                                id="custom-tabs-four-interview{{$i}}-tab" data-toggle="pill" href="#custom-tabs-four-interview{{$i}}"
                                role="tab" aria-controls="custom-tabs-four-interview{{$i}}" aria-selected="true">Tạo phỏng vấn vòng {{$i}}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link"
                            id="custom-tabs-four-interview4-tab" data-toggle="pill" href="#custom-tabs-four-interview4"
                            role="tab" aria-controls="custom-tabs-four-interview4" aria-selected="false">Thông tin nhận việc </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-chat-tab" data-toggle="pill"
                            href="#custom-tabs-four-chat" role="tab" aria-controls="custom-tabs-four-chat"
                            aria-selected="false">Trao đổi</a>
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-salary-tab" data-toggle="pill"
                            href="#custom-tabs-four-salary" role="tab" aria-controls="custom-tabs-four-salary"
                            aria-selected="false">Quá trình công tác</a>
                    </li> --}}
                </ul>
            </div>

            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade show {{Request::get('is_create') != true ? 'active' : ''}}"
                    id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                    <form action="{{ route('recruitment.candidate.update', $candidate->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Sàng lọc hồ sơ</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row" id="change">
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ngày SLHS </label>
                                            <input name="interview_date" type="date" class="form-control"
                                                value="{{ $candidate->interview_date != null ? date('Y-m-d', strtotime($candidate->interview_date)) : '' }}"
                                                placeholder="Nhập ...">
                                            {{-- {!! $errors->first('changer_name', '<span
                                                class="text-danger">:message</span>') !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Người SLHS </label>
                                            <select name="interviewer" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $interviewer !!}
                                            </select>
                                            {!! $errors->first('interviewer', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Nhận xét SLHS </label>
                                            <input name="interview_comment" type="text" class="form-control"
                                                value="{{ $candidate->interview_comment }}" placeholder="Nhập ...">
                                            {{-- {!! $errors->first('changer_name', '<span
                                                class="text-danger">:message</span>') !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Kết quả SLHS </label>
                                            <select name="interview_result" class="form-control"
                                                data-placeholder="Select">
                                                {!! $listInterviewResults !!}
                                            </select>
                                            {{-- {!! $errors->first('changer_name', '<span
                                                class="text-danger">:message</span>') !!} --}}
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ghi chú ứng viên </label>
                                            <textarea rows="2" name="info2" type="text" class="form-control"
                                                placeholder="Nhập ..." value="{{ $candidate->info2 }}">{{ $candidate->info2 }}</textarea>
                                            {!! $errors->first('info2', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                                <input name="is_sendmail_slhs" type="checkbox"
                                                    class="">
                                                <label class="">Thư kết quả SLHS</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Thông tin chung --}}
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin ứng viên</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Vị trí/ chức danh <span class="text-danger">*</span></label>
                                            <select name="position_id" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $positions !!}
                                            </select>
                                            {!! $errors->first('position_id', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Đơn vị tuyển dụng </label>
                                            <select name="department_id" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $departments !!}
                                            </select>
                                            {!! $errors->first('department_id', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Tên ứng viên <span class="text-danger">*</span></label>
                                            <input name="name" type="text" class="form-control"
                                                value="{{ $candidate->name }}" placeholder="Nhập ...">
                                            {!! $errors->first('name', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ngày sinh <span class="text-danger">*</span></label>
                                            <input name="birthday" type="date" class="form-control"
                                                value="{{ date('Y-m-d', strtotime($candidate->birthday)) }}"
                                                placeholder="Nhập ...">
                                            {!! $errors->first('birthday', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ngày nhận hồ sơ <span class="text-danger">*</span></label>
                                            <input name="received_time" type="date" class="form-control"
                                                value="{{ $candidate->displayDate($candidate->received_time) }}"
                                                placeholder="Nhập ...">
                                            {!! $errors->first('received_time', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Người liên quan </label>
                                            <select name="relationer[]" class="form-control select2" multiple
                                                data-placeholder="Select">
                                                {!! $relationerCandidates !!}
                                            </select>
                                            {!! $errors->first('relationer', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Người nhận hồ sơ <span class="text-danger">*</span></label>
                                            <select name="receiver_id" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $receivers !!}
                                            </select>
                                            {!! $errors->first('receiver_id', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Nguồn hồ sơ <span class="text-danger">*</span></label>
                                            <select name="source_id" class="form-control" data-placeholder="Select">
                                                {!! $sources !!}
                                            </select>
                                            {!! $errors->first('source_id', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ghi chú mối quan hệ </label>
                                            <input name="relationship_note" type="text" class="form-control"
                                                placeholder="Nhập ..." value="{{ $candidate->relationship_note }}">
                                            {!! $errors->first('relationship_note', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Giới tính <span class="text-danger">*</span></label>
                                            <select name="gender" class="form-control" data-placeholder="Select">
                                                {!! $genders !!}
                                                {!! $errors->first('gender', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Số điện thoại <span class="text-danger">*</span></label>
                                            <input name="phone_number" type="text" class="form-control"
                                                placeholder="Nhập ..." value="{{ $candidate->phone_number }}">
                                            {!! $errors->first('phone_number', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Chiều cao </label>
                                            <input name="height" type="text" class="form-control" placeholder="Nhập ..."
                                                value="{{ $candidate->height }}">
                                            {!! $errors->first('height', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Cân nặng </label>
                                            <input name="weight" type="text" class="form-control" placeholder="Nhập ..."
                                                value="{{ $candidate->weight }}">
                                            {!! $errors->first('weight', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Số căn cước </label>
                                            <input name="cccd" type="text" class="form-control" placeholder="Nhập ..."
                                                value="{{ $candidate->cccd }}">
                                            {!! $errors->first('cccd', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Ngày cấp căn cước </label>
                                            <input name="cccd_date" type="date" class="form-control"
                                                placeholder="Nhập ..."
                                                value="{{ $candidate->cccd_date ? $candidate->displayDate($candidate->cccd_date) : '' }}">
                                            {!! $errors->first('cccd_date', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Email </label>
                                            <input name="email" type="email" class="form-control" placeholder="Nhập ..."
                                                value="{{ $candidate->email }}">
                                            {!! $errors->first('email', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Hộ khẩu <span class="text-danger">*</span></label>
                                            <select name="household" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $household !!}
                                                {!! $errors->first('household', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Nơi ở hiện tại </label>
                                            <select name="address" class="form-control select2"
                                                data-placeholder="Select">
                                                {!! $address !!}
                                            </select>
                                            {!! $errors->first('address', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Nơi ở (chi tiết) </label>
                                            <input name="address_detail" type="text" class="form-control"
                                                placeholder="Nhập ..." value="{{ $candidate->address_detail }}">
                                            {!! $errors->first('address_detail', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Quá trình đào tạo</label>
                                            <textarea name="training_process" class="form-control"
                                                id="ckeditor_training_process" rows="3"
                                                placeholder="Nhập ...">{!! $candidate->training_process !!}</textarea>
                                            {!! $errors->first('training_process', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Ngoại ngữ</label>
                                            <textarea name="languague" class="form-control" id="ckeditor_languague"
                                                rows="3" placeholder="Nhập ...">{!! $candidate->languague !!}</textarea>
                                            {!! $errors->first('languague', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phần mềm đặc thù</label>
                                            <textarea name="skill" class="form-control" id="ckeditor_skill" rows="3"
                                                placeholder="Nhập ...">{!! $candidate->skill !!}</textarea>
                                            {!! $errors->first('skill', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Kinh nghiệm làm việc</label>
                                            <textarea name="experience" class="form-control"
                                                id="ckeditor_working_process" rows="3"
                                                placeholder="Nhập ...">{!! $candidate->experience !!}</textarea>
                                            {!! $errors->first('experience', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Tính cách </label>
                                            <textarea rows="2" name="character" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->character }}</textarea>
                                            {!! $errors->first('character', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Sở thích </label>
                                            <textarea rows="2" name="interest" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->interest }}</textarea>
                                            {!! $errors->first('interest', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Điểm mạnh </label>
                                            <textarea rows="2" name="strength" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->strength }}</textarea>
                                            {!! $errors->first('strength', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Điểm yếu </label>
                                            <textarea rows="2" name="weakness" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->weakness }}</textarea>
                                            {!! $errors->first('weakness', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Người liên hệ khẩn </label>
                                            <textarea rows="2" name="contact" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->contact }}</textarea>
                                            {!! $errors->first('contact', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Bài viết về vị trí ứng tuyển của ứng viên </label>
                                            <textarea rows="2" name="infomation" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->infomation }}</textarea>
                                            {!! $errors->first('infomation', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Thông tin tham chiếu </label>
                                            <textarea rows="2" name="info1" type="text" class="form-control"
                                                placeholder="Nhập ...">{{ $candidate->info1 }}</textarea>
                                            {!! $errors->first('info1', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-12">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Kinh nghiệm làm việc </label>
                                            <textarea rows="2" name="experience" type="text" class="form-control"
                                                placeholder="Nhập ..." value="{{ $data->experience }}"></textarea>
                                            {!! $errors->first('experience', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="float-right m-3">
                            <button id="" type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
                @if($count > 0)
                @foreach($datas as $key => $data)
                @if(optional($data)->candidate_id != null)
                <div class="tab-pane fade show"
                    id="custom-tabs-four-interview{{$key}}" role="tabpanel"
                    aria-labelledby="custom-tabs-four-interview{{$key}}-tab">
                    <!-- general form elements disabled -->
                        <form action="{{ route('recruitment.interview_schedule.update', optional($data)->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="candidate_id" value={{$candidate_id}}>
                            <div class="card mt-2 card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Thông tin chung</h3>
                                    {{-- <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div> --}}
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Tên lịch phỏng vấn <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ optional($data)->name }}">
                                                {!! $errors->first('name', '<span class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Người phụ trách <span class="text-danger">*</span></label>
                                                <select style="width:100%;" name="relationer"
                                                    class="form-control select2" data-placeholder="Select">
                                                    <option value="">---Chọn---</option>
                                                    @foreach($relationers as $relationer)
                                                    @if($relationer->id == $data->relationer)
                                                    <option selected value="{{$relationer->id}}">
                                                        {{$relationer->name}}</option>
                                                    @else
                                                    <option value="{{$relationer->id}}">{{$relationer->name}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('relationer', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Ứng viên tham gia <span class="text-danger">*</span></label>
                                                <select name="candidate_id" class="form-control select2"
                                                    data-placeholder="Select">
                                                    @foreach ($candidates as $candidate)
                                                    @if ($candidate->id == $data->candidate_id)
                                                    <option selected value="{{ $candidate->id }}">
                                                        {{ $candidate->name }}
                                                        -
                                                        {{ optional($candidate->position)->name }}
                                                        ({{ $candidate->phone_number }})
                                                    </option>
                                                    @else
                                                    <option value="{{ $candidate->id }}">{{ $candidate->name }} -
                                                        {{ optional($candidate->position)->name }}
                                                        ({{ $candidate->phone_number }})
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                {!! $errors->first('candidate_id', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div> --}}
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Bắt đầu <span class="text-danger">*</span></label>
                                                <input id="interview_from" value="{{ $data->interview_from }}"
                                                    type="datetime-local" name="interview_from" class="form-control"
                                                    step="any">
                                                {!! $errors->first('interview_from', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Kết thúc <span class="text-danger">*</span></label>
                                                <input id="interview_to" value="{{ $data->interview_to }}"
                                                    type="datetime-local" name="interview_to" class="form-control"
                                                    step="any">
                                                {!! $errors->first('interview_to', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Nơi phỏng vấn <span class="text-danger">*</span></label>
                                                <input value="{{ $data->address }}" type="text" name="address"
                                                    class="form-control">
                                                {!! $errors->first('address', '<span
                                                    class="text-danger">:message</span>') !!}
                                                {{-- <select name="address" class="form-control select2"
                                                    data-placeholder="Select">
                                                    @foreach($interviewAddress as $address)
                                                    @if($address->id == $data->address)
                                                    <option selected value="{{$address->id}}">{{$address->name}}
                                                    </option>
                                                    @else
                                                    <option value="{{$address->id}}">{{$address->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select> --}}
                                                {!! $errors->first('address', '<span
                                                    class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Hạn ứng viên phản hồi mail</label>
                                                <input class="form-control" type="date" name="react_at"
                                                    value="{{ $data->react_at != null ? date('Y-m-d', strtotime($data->react_at)) : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-sm-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input name="active" type="checkbox" class="custom-control-input"
                                                        checked id="customSwitch1">
                                                    <label class="custom-control-label" for="customSwitch1">Hoạt
                                                        động</label>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                    <input name="is_sendmail" type="checkbox"
                                                        class="">
                                                    <label class="">Gửi thư mời phỏng vấn</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                    <input name="is_interviewed" type="checkbox"
                                                        class="">
                                                    <label class="" for="">Gửi thư cám ơn sau phỏng vấn</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                    <input name="is_interview_result" type="checkbox"
                                                        class="">
                                                    <label class="" for="">Gửi thư kết quả phỏng vấn</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Thông tin hội đồng phỏng vấn</h3>
                                    {{-- <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div> --}}
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <button type="button" class="btn btn-primary mb-3 ml-3 addRow"
                                            data-toggle="modal" data-target="#modal-interview">
                                            Thêm người phỏng vấn
                                        </button>
                                        <div class="col-sm-12">
                                            <table class="table table-striped projects">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Người phỏng vấn
                                                        </th>
                                                        <th>
                                                            <input type="checkbox" id="check-all">
                                                            Người nhận xét/ đánh giá
                                                        </th>
                                                        <th>
                                                            Người nhận xét/ đánh giá cuối
                                                        </th>
                                                        <th>
                                                            Lịch phỏng vấn đang tham gia
                                                        </th>
                                                        <th>
                                                            Thao tác
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="formContainer">
                                                    @php
                                                    $interviewerDatas = is_string($data->interviewer) ?
                                                    json_decode($data->interviewer, true) : $data->interviewer;
                                                    @endphp
                                                    @if ($interviewers != null && $interviewerDatas != null )
                                                    @foreach ($interviewerDatas as $key1 => $interviewerData)
                                                    <tr class="onRemove">
                                                        <td>
                                                            <select name="interviewer[]"
                                                                class="form-control select-candidate select2" required>
                                                                @foreach ($interviewers as $interviewer)
                                                                <option value=""></option>
                                                                @if ($interviewer->id ==
                                                                $interviewerData['user_id'])
                                                                <option selected="selected"
                                                                    value="{{ $interviewer->id }}">
                                                                    {{ $interviewer->name }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $interviewer->id }}">
                                                                    {{ $interviewer->name }}
                                                                </option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="check-each" {{
                                                                $interviewerData['can_evaluate']==true ? 'checked' : ''
                                                                }} name="can_evaluate[]" type="checkbox"
                                                                value="{{ $interviewerData['user_id'] }}">
                                                        </td>
                                                        <td>
                                                            <input {{ $interviewerData['final_evaluate']==true
                                                                ? 'checked' : '' }} name="final_evaluate" type="radio"
                                                                value="{{$interviewerData['user_id']}}">
                                                        </td>
                                                        <td>
                                                            <a href="#">Xem</a>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-danger remove">Xoá</a>
                                                        </td>
                                                    </tr>
                                                    @if ($interviewerData['can_evaluate'] == true)
                                                    <tr>
                                                        <input type="hidden" name="userId"
                                                            value="{{ $interviewerData['user_id'] }}">
                                                        <input type="hidden" name="dataId" value="{{ $data->id }}">
                                                        <td colspan="3">
                                                            <textarea class="form-control" name="comment[]"
                                                                placeholder="Nhập nhận xét tại đây...">{{ $interviewerData['comment'] }}</textarea>
                                                        </td>
                                                        <td style="width:20%;">
                                                            <select name="interviewResult[]"
                                                                class="form-control select2" {{$data['stage'] < $candidate->stage ? 'disabled' : ''}}
                                                                data-placeholder="Chọn kết quả">
                                                                <option value="" selected>Chọn kết
                                                                    quả
                                                                    đánh
                                                                    giá</option>
                                                                {{-- {!! $interviewResults !!} --}}
                                                                @foreach ($interviewResults as $interviewResult)
                                                                @if ($interviewResult->name ==
                                                                $interviewerData['result'])
                                                                <option selected value="{{ $interviewResult->name }}">
                                                                    {{ $interviewResult->value }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $interviewResult->name }}">
                                                                    {{ $interviewResult->value }}
                                                                </option>
                                                                @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            {{-- @if (Auth::user()->id == $interviewerData['user_id'])
                                                            --}}
                                                            <button type="button" class="btn btn-primary evaluate">Gửi
                                                                đánh
                                                                giá
                                                            </button>
                                                            {{-- @else
                                                            <button type="button" disabled class="btn btn-primary">Gửi
                                                                đánh
                                                                giá</button>
                                                            @endif --}}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        {{-- <div class="col-sm-12">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                        <input name="is_take_job" type="checkbox"
                                                            class="">
                                                        <label class="">Chuyển sang vòng nhận việc nếu đạt</label>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            {{-- @if($stage -1 == $key && $data->status == 'active') --}}
                            {{-- @if($data->status == 'active')
                            <div class="float-right">
                                <button name="schedule" value="evaluate_submit" type="submit"
                                    class="btn btn-success m-2">Xác nhận kết quả
                                    phỏng vấn</button>
                            </div>
                            @endif --}}
                            <div class="float-right">
                                <button name="schedule" value="save" type="submit"
                                    class="btn btn-primary m-2">Lưu</button>
                            </div>

                            {{-- <div class="float-right">
                                <button name="schedule" value="cancel" type="submit"
                                    class="btn btn-danger m-2">Huỷ</button>
                            </div> --}}
                            {{-- @elseif($stage == $key && $canCreateSchedule == true)
                            <div class="float-right">
                                <button type="submit" class="btn btn-primary m-2">Tạo lịch phỏng
                                    vấn</button>
                            </div> --}}
                            {{-- @endif --}}

                            {{-- @if($data->stage == null)
                            <div class="float-right">
                                <button name="schedule" value="create_schedule" type="submit"
                                    class="btn btn-primary m-2">Tạo phỏng
                                    vấn</button>
                            </div>
                            @endif --}}
                        </form>
                </div>
                @endif
                @endforeach
                @endif

                @if(Request::get('is_create') == true)
                <div class="tab-pane fade show active"
                    id="custom-tabs-four-interview{{$i}}" role="tabpanel"
                    aria-labelledby="custom-tabs-four-interview{{$i}}-tab">
                    <!-- general form elements disabled -->
                    <form action="{{ route('recruitment.interview_schedule.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="candidate_id" value={{$candidate_id}}>
                        <div class="card mt-2 card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin chung</h3>
                                {{-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Tên lịch phỏng vấn <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{old('name')}}">
                                            {!! $errors->first('name', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Người phụ trách <span class="text-danger">*</span></label>
                                            <select style="width:100%;" name="relationer" class="form-control select2"
                                                data-placeholder="Chọn">
                                                <option selected value="">---Chọn---</option>
                                                @foreach($relationers as $relationer)
                                                <option value="{{$relationer->id}}">{{$relationer->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                            {!! $errors->first('relationer', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Bắt đầu <span class="text-danger">*</span></label>
                                            <input id="interview_from" value="" type="datetime-local"
                                                name="interview_from" class="form-control" step="any">
                                            {!! $errors->first('interview_from', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kết thúc <span class="text-danger">*</span></label>
                                            <input id="interview_to" value="" type="datetime-local" name="interview_to"
                                                class="form-control" step="any">
                                            {!! $errors->first('interview_to', '<span
                                                class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Nơi phỏng vấn <span class="text-danger">*</span></label>
                                            <input value="{{ old('address') }}" type="text" name="address"
                                                class="form-control">
                                            {!! $errors->first('address', '<span class="text-danger">:message</span>')
                                            !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Hạn ứng viên phản hồi mail</label>
                                            <input class="form-control" type="date" name="react_at" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                            <input name="is_sendmail" type="checkbox"
                                                class="">
                                            <label class="">Gửi thư mời phỏng vấn</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                            <input name="is_interviewed" type="checkbox"
                                                class="">
                                            <label class="" for="">Gửi thư cám ơn sau phỏng vấn</label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin hội đồng phỏng vấn</h3>
                                {{-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <button type="button" class="btn btn-primary mb-3 ml-3 addRow" data-toggle="modal"
                                        data-target="#modal-interview">
                                        Thêm người phỏng vấn
                                    </button>
                                    <div class="col-sm-12">
                                        <table class="table table-striped projects">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Người phỏng vấn
                                                    </th>
                                                    <th>
                                                        <input type="checkbox" id="check-all">
                                                        Người nhận xét/ đánh giá
                                                    </th>
                                                    <th>
                                                        Người nhận xét/ đánh giá cuối
                                                    </th>
                                                    <th>
                                                        Lịch phỏng vấn đang tham gia
                                                    </th>
                                                    <th>
                                                        Thao tác
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="formContainer">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="float-right">
                            <button name="schedule" value="create_schedule" type="submit" class="btn btn-primary m-2">Tạo lịch phỏng vấn</button>
                        </div>

                    </form>
                </div>
                @endif


                    <div class="tab-pane fade show" id="custom-tabs-four-interview4" role="tabpanel"
                    aria-labelledby="custom-tabs-four-interview4-tab">
                    <form method="post" action="{{route('recruitment.candidate.update', $candidate->id)}}">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin deal lương</h3>
                                {{-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            {{-- <div class="col-sm-3">
                                                <!-- text input -->
                                                <div class="form-group">
                                                    <label>Loại lương </label>
                                                    <input name="salary_to" type="text" class="form-control"
                                                        value="{{ $candidate->salary_to }}" placeholder="Nhập ...">
                                                    {!! $errors->first('salary_to', '<span class="text-danger">:message</span>')
                                                    !!}
                                                </div>
                                            </div> --}}
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Lương từ </label>
                                                <input name="salary_from" type="text" class="form-control"
                                                    value="{{ $candidate->salary_from }}" placeholder="Nhập ...">
                                                {!! $errors->first('salary_from', '<span class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Lương đến </label>
                                                <input name="salary_to" type="text" class="form-control"
                                                    value="{{ $candidate->salary_to }}" placeholder="Nhập ...">
                                                {!! $errors->first('salary_to', '<span class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Ghi chú lương</label>
                                                <textarea name="salary_note" class="form-control" id="" rows="3"
                                                    placeholder="Nhập ...">{{ $candidate->salary_note }}</textarea>
                                                {{-- {!! $errors->first('changer_name', '<span
                                                    class="text-danger">:message</span>') !!} --}}
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin nhận việc</h3>
                                {{-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Kết quả tuyển dụng </label>
                                                <select name="recruitment_result" class="form-control"
                                                    data-placeholder="Select">
                                                    {!! $recruitmentResults !!}
                                                </select>
                                                {!! $errors->first('recruitment_result', '<spanh
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Mã NTP </label>
                                                <input name="user_uid" type="text" class="form-control"
                                                    value="{{ $candidate->user_uid }}" placeholder="Nhập ...">
                                                {!! $errors->first('user_uid', '<span class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Ngày bắt đầu thử việc </label>
                                                <input name="probation_from" type="date" class="form-control"
                                                    value="{{ $candidate->probation_from != null ? date('Y-m-d', strtotime($candidate->probation_from)) : ''}}"
                                                    placeholder="Nhập ...">
                                                {!! $errors->first('probation_from', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Ngày kết thúc thử việc </label>
                                                <input name="probation_to" type="date" class="form-control"
                                                    value="{{ $candidate->probation_to != null ? date('Y-m-d', strtotime($candidate->probation_to)) : ''}}"
                                                    placeholder="Nhập ...">
                                                {!! $errors->first('probation_to', '<span
                                                    class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Ngày từ chối nhận việc (nếu có) </label>
                                                <input name="reject_job_date" type="date" class="form-control"
                                                    value="{{ $candidate->reject_job_date != null ? date('Y-m-d', strtotime($candidate->reject_job_date)) : ''}}"
                                                    placeholder="Nhập ...">
                                                {!! $errors->first('reject_job_date', '<span
                                                    class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-9">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Lý do từ chối nhân việc (nếu có) </label>
                                                <input name="un_recruitment_reason" type="text" class="form-control"
                                                    value="{{ old('un_recruitment_reason') }}" placeholder="Nhập ...">
                                                {!! $errors->first('changer_name', '<span
                                                    class="text-danger">:message</span>')
                                                !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                        </div> --}}

                                        <div class="col-sm-3">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Kết quả thử việc </label>
                                                <select name="probation_result" class="form-control"
                                                    data-placeholder="Select">
                                                    {!! $probationResults !!}
                                                </select>
                                                {!! $errors->first('probation_result', '<span
                                                    class="text-danger">:message</span>') !!}
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <!-- text input -->
                                            <div class="form-group">
                                                <label>Ghi chú nhận việc</label>
                                                <textarea name="un_probation_reason" class="form-control" id="" rows="3"
                                                    placeholder="Nhập ...">{{ $candidate->un_probation_reason }}</textarea>
                                                {{-- {!! $errors->first('changer_name', '<span
                                                    class="text-danger">:message</span>') !!} --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="float-right">
                                        <button name="schedule" value="update" type="submit"
                                            class="btn btn-primary m-2">Lưu</button>
                                    </div>
                            </div>
                        </div>
                    </form>
                    </div>

            </div>
            <!-- /.card -->
        </div>
    </div>

    {{--
</div> --}}


<!-- /.modal -->
{{-- <form action="{{ route('recruitment.candidate.comment', $data->id) }}" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm comment</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="new">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Điền comment</label>
                                <textarea name="comment" class="form-control" id="ckeditor-comment" rows="3"
                                    placeholder="Nhập ..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form> --}}
<!-- /.modal -->

<script src="{{ asset('js/recruitment.js') }}"></script>
<script>
    var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        // CKEDITOR.replace('ckeditor_skill', options);
        // CKEDITOR.replace('ckeditor_working_process', options);
        // CKEDITOR.replace('ckeditor_languague', options);
        // CKEDITOR.replace('ckeditor_training_process', options);
</script>
<script>
    $(document).ready(function() {
                   $('.addRow').click(function() {
                    const container = $(this).closest('.formContainer');
                const newRow = `
                   <tr class="onRemove">
                        <td style="width: 25%;">
                            <select name="interviewer[]" class="form-control select-candidate" required>
                                {!! $listInterviewers !!}
                            </select>
                        </td>
                        <td>
                            <input class="check-each" name="can_evaluate[]" type="checkbox">
                        </td>
                        <td>
                            <input name="final_evaluate" type="radio">
                        </td>
                        <td>
                            <a href="#">Xem</a>
                        </td>
                        <td>
                            <a href="#" class="text-danger remove">Xoá</a>
                        </td>
                    </tr>
        `;
                $(this).closest('.card-body').find('.formContainer').append(newRow);
                $('.select-candidate').last().select2({
                placeholder: "Chọn",
                // ... any other select2 options you might have ...
                });
            });

            // Xoá 1 người phỏng vấn
            $('.formContainer').on('click', '.remove', function(event) {
                event.preventDefault();
                $(this).closest('.onRemove').remove();
            });

            // Hàm check all
           $('.card-body').on('click', '#check-all', function(e) {
        $(this).closest('table').find('.check-each').not(this).prop('checked', this.checked);
        });

            // Sự kiện check trùng khi chọn người phỏng vấn
            // $(document).on('change', '.select-candidate', function() {
            //     var selectedValues = [];
            //     $('.select-candidate').not(this).each(function() {
            //         selectedValues.push($(this).val());
            //     });

            //     var selectedValue = $(this).val();
            //     if ($.inArray(selectedValue, selectedValues) !== -1) {
            //         alert('Người này đã được chọn');
            //         $(this).val("").trigger(
            //             'change.select2'); //Thêm trigger để nội dung trở về giá trị ban đầu
            //     } else {
            //         // Khi value select ở 1 row chọn giá trị
            //         let thisSelectedValue = $(this).val();
            //         // Tìm checkbox và radio button tương ứng trong cùng một dòng và cập nhật giá trị cho chúng
            //         var parentRow = $(this).closest('tr');
            //         parentRow.find('[name="can_evaluate[]"]').val(thisSelectedValue);
            //         parentRow.find('[name="final_evaluate"]').val(thisSelectedValue);
            //     }
            // });

            $(document).on('change', '.select-candidate', function() {
                let thisSelectedValue = $(this).val();
                // Tìm checkbox và radio button tương ứng trong cùng một dòng và cập nhật giá trị cho chúng
                var parentRow = $(this).closest('tr');
                parentRow.find('[name="can_evaluate[]"]').val(thisSelectedValue);
                parentRow.find('[name="final_evaluate"]').val(thisSelectedValue);
            });

            // start_time < end_time
            $('#interview_from, #interview_to').change(function() {
                const interviewFrom = new Date($('#interview_from').val());
                const interviewTo = new Date($('#interview_to').val());

                if (interviewFrom && interviewTo && interviewTo <= interviewFrom) {
                    alert('Thời gian kết thúc phải sau thời gian bắt đầu');
                    $('#interview_to').val(''); // reset giá trị của interview_to
                }

                updateInterviewTimeRange();
            });

            $('.evaluate').on('click', function() {
                var $row = $(this).closest('tr');

                var selectedValue = $row.find('select[name="interviewResult[]"]').val();
                var comment = $row.find('textarea[name="comment[]"]').val();
                var userId = $row.find('input[name="userId"]').val();
                var dataId = $row.find('input[name="dataId"]').val();
                evaluate(selectedValue, comment, userId, dataId)
            })

            $('#pushIsCreate').on('click', function() {
                let currentUrl = window.location.href;

                // Check if the URL contains the parameter is_create=true
                if (currentUrl.includes('is_create=true')) {
                    // Remove the parameter from the URL
                    currentUrl = currentUrl.replace(/[\?&]is_create=true/, '');
                    // If the parameter was the only one in the URL, remove the "?" or "&" left after its removal
                    currentUrl = currentUrl.replace(/[\?&]$/, '');
                } else {
                    // Add the parameter is_create=true to the URL
                    if (currentUrl.includes('?')) {
                        currentUrl += '&is_create=true';
                    } else {
                        currentUrl += '?is_create=true';
                    }
                }

                // Redirect to the updated URL
                window.location.href = currentUrl;
            });

            function evaluate(selectedValue, comment, userId, dataId) {
                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/recruitment/interview-schedule/evaluate/' + dataId,
                    dataType: 'json',
                    data: {
                        selectedValue,
                        comment,
                        userId,
                        dataId,
                    },
                    // async: false,
                    success: function(resp) {
                        alert('Thành công');
                    }
                });
            }
        });

        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        CKEDITOR.replace('ckeditor-comment', options);
</script>
@include('ckfinder::setup')
@stop

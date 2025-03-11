@extends('recruitment.layouts.master_candidate')
@section('content')
<!-- general form elements disabled -->

<header class="header">
    <p id="description" class="text-center">
        <img id="ntpLogo" src="https://nhuatienphong.vn/main/imgs/ntp/logo2.png">
    </p>
    <h1 id="title" class="text-center">Biểu Mẫu Thông Tin</h1>

</header>
<div class="form-wrap">
    @if(optional($checkFormToken)->can_edit == true)
    <form id="survey-form" action="{{ route('frontend.candidate.update', optional($checkFormToken)->form_token) }}"
        method="POST" enctype="multipart/form-data">
        @else
        <form id="survey-form"
            action="{{ route('frontend.candidate.submitForm', optional($checkFormToken)->form_token) }}" method="POST"
            enctype="multipart/form-data">
            @endif
            @csrf
            <p id="description" class="text-center">
                Lưu ý những trường thông tin có dấu (<span class="text-danger">*</span>) là bắt buộc nhập
            </p>
            {{-- Thông tin chung --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Thông tin cơ bản</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Họ và tên <span class="text-danger">*</span></label>
                                <input required name="name" type="text" class="form-control" value="{{ $checkFormToken->name ? $checkFormToken->name : old('name') }}"
                                    placeholder="Nhập ...">
                                {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Vị trí ứng tuyển <span class="text-danger">*</span></label>
                                <select required name="position_id" class="form-control select2"
                                    data-placeholder="Select">
                                    {!! $positions !!}
                                </select>
                                {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Ngày sinh <span class="text-danger">*</span></label>
                                <input required name="birthday" type="date" class="form-control"
                                    value="{{ $checkFormToken->birthday ? date('Y-m-d', strtotime($checkFormToken->birthday)) : old('birthday') }}" placeholder="Nhập ...">
                                {!! $errors->first('birthday', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Nguồn hồ sơ <span class="text-danger">*</span></label>
                                <select required name="source_id" class="form-control" data-placeholder="Select">
                                    {!! $sources !!}
                                </select>
                                {!! $errors->first('source_id', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Giới tính <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" data-placeholder="Select">
                                    {!! $genders !!}
                                    {!! $errors->first('gender', '<span class="text-danger">:message</span>') !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Số điện thoại <span class="text-danger">*</span></label>
                                <input name="phone_number" type="text" class="form-control" placeholder="Nhập ..."
                                    value="{{ $checkFormToken->phone_number ? $checkFormToken->phone_number :  old('phone_number') }}">
                                {!! $errors->first('phone_number', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input required name="email" type="email" class="form-control" placeholder="Nhập ..."
                                    value="{{ $checkFormToken->email ? $checkFormToken->email : old('email') }}">
                                {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Hộ khẩu <span class="text-danger">*</span></label>
                                <select required name="household" class="form-control select2"
                                    data-placeholder="Select">
                                    {!! $selectHousehold !!}
                                    {!! $errors->first('household', '<span class="text-danger">:message</span>') !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Nơi ở hiện tại <span class="text-danger">*</span></label>
                                <select required name="address" class="form-control select2" data-placeholder="Select">
                                    {!! $selectAddress !!}
                                </select>
                                {!! $errors->first('address', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Lý do tuyển --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Thông tin công việc</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row" id="change">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Quá trình đào tạo <span class="text-danger">*</span></label>
                                <textarea required name="training_process" class="form-control"
                                    id="ckeditor_training_process" rows="5"
                                    placeholder="Nhập ...">{{ $checkFormToken->training_process ? $checkFormToken->training_process : old('training_process') }}</textarea>
                                {!! $errors->first('training_process', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Kinh nghiệm làm việc <span class="text-danger">*</span></label>
                                <textarea required name="experience" class="form-control" id="ckeditor_working_process"
                                    rows="3" placeholder="Nhập ...">{{$checkFormToken->experience ? $checkFormToken->experience :  old('experience') }}</textarea>
                                {!! $errors->first('experience', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Ngoại ngữ </label>
                                <textarea name="languague" class="form-control" id="ckeditor_languague"
                                    rows="3" placeholder="Nhập ...">{{ $checkFormToken->languague ? $checkFormToken->languague : old('languague') }}</textarea>
                                {!! $errors->first('languague', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Phần mềm đặc thù </label>
                                <textarea name="skill" class="form-control" id="ckeditor_skill" rows="3"
                                    placeholder="Nhập ...">{{  $checkFormToken->skill ? $checkFormToken->skill : old('skill') }}</textarea>
                                {!! $errors->first('skill', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="float-right m-3">
                    <button id="" type="submit" class="btn btn-primary">Gửi thông tin</button>
                </div>
            </div>

        </form>
</div>

<script src="{{ asset('js/candidate.js') }}"></script>
<!-- /.card -->
{{-- <script>
    --}}
    {{--        CKEDITOR.replace( 'ckeditor', { --}}

    {{--            filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}", --}}
    {{--            filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123", --}}
    {{--            filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123", --}}
    {{--            filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files", --}}
    {{--            filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images", --}}
    {{--            filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash", --}}
    {{--        } ); --}}
    {{--
</script> --}}
@include('ckfinder::setup')
<style>
    .container {
        max-width: 1530px;
        width: 100%;
    }

    h1 {
        font-weight: 700;
        font-size: 45px;
    }

    .header {
        margin-top: 30px;
        margin-bottom: 50px;
    }

    #description {
        font-size: 24px;
    }

    #ntpLogo {
        max-height: 100px;
    }

    .form-wrap {
        background: rgba(255, 255, 255, 1);
        width: 100%;
        max-width: 1050px;
        padding: 50px;
        margin: 0 auto;
        position: relative;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
    }

    .form-wrap:before {
        content: "";
        width: 90%;
        height: calc(100% + 60px);
        left: 0;
        right: 0;
        margin: 0 auto;
        position: absolute;
        top: -30px;
        background: #00bcd9;
        z-index: -1;
        opacity: 0.8;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
    }

    .form-group {
        margin-bottom: 25px;
    }


    textarea.form-control {
        height: 160px;
        padding-top: 15px;
        resize: none;
    }

    .btn {
        padding: .657rem .75rem;
        font-size: 18px;
        letter-spacing: 0.050em;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }


    .btn-primary:hover {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }

    .btn-primary:focus,
    .btn-primary.focus {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }

    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
    }

    .btn-primary:not(:disabled):not(.disabled):active:focus,
    .btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-primary.dropdown-toggle:focus {
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }
</style>

@stop

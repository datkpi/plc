@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('recruitment.source.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin nguồn tuyển</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <!-- /.card-header -->
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên nguồn <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Link nguồn </label>
                            <input name="link" type="text" class="form-control" value="{{ old('link') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('link', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Số điện thoại liên hệ </label>
                            <input name="phone_contact" type="text" class="form-control"
                                value="{{ old('phone_contact') }}" placeholder="Nhập ...">
                            {!! $errors->first('phone_contact', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Email đăng ký </label>
                            <input name="email_register" type="email" class="form-control"
                                value="{{ old('email_register') }}" placeholder="Nhập ...">
                            {!! $errors->first('email_register', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Thông tin liên hệ</label>
                            <textarea name="contact" class="form-control" id="ckeditor" rows="3" placeholder="Nhập mô tả ..."></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" id="ckeditor" rows="3" placeholder="Nhập mô tả ..."></textarea>
                        </div>
                    </div>

                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input name="active" type="checkbox" class="custom-control-input" checked id="customSwitch3">
                            <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary m-3">Lưu</button>
        </div>
    </form>

    @include('ckfinder::setup')
@stop

@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('recruitment.approve.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cài đặt xét duyệt phiếu tuyển dụng</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tiêu đề <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Phòng ban <span class="text-danger">*</span></label>
                            <select name="department_id" class="form-control select2" data-placeholder="Select">
                                {!! $departments !!}
                            </select>
                            {!! $errors->first('department_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ban NSCL <span class="text-danger">*</span></label>
                            <select name="approve_1" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('approve_1', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Phó tổng giám đốc phụ trách <span class="text-danger">*</span></label>
                            <select name="approve_2" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('approve_2', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Phó tổng giám đốc nội chính <span class="text-danger">*</span></label>
                            <select name="approve_3" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('approve_3', '<sgáy thì hơi khó chiệupan class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Tổng giám đốc <span class="text-danger">*</span></label>
                            <select name="approve_4" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('approve_4', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input name="active" type="checkbox" class="custom-control-input" checked
                                    id="customSwitch3">
                                <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" id="ckeditor" rows="3" placeholder="Nhập mô tả ..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>

    @include('ckfinder::setup')
@stop

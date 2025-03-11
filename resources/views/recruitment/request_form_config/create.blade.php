@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('recruitment.request_form_config.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cấu hình phiếu tuyển dụng</h3>
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
                            <label>Hạn chót để duyệt sau bao nhiêu (ngày) <span class="text-danger">*</span></label>
                            <input name="deadline_after" type="text" class="form-control" placeholder="Nhập ...">
                            {!! $errors->first('deadline_after', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input name="pass_approve" type="checkbox" class="custom-control-input" checked
                                    id="customSwitch3">
                                <label class="custom-control-label" for="customSwitch3">Cho phép bỏ qua bước duyệt</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cấu hình lịch phỏng vấn</h3>
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
                            <label>Hạn chót để duyệt sau bao nhiêu (ngày) <span class="text-danger">*</span></label>
                            <input name="deadline_after" type="text" class="form-control" placeholder="Nhập ...">
                            {!! $errors->first('deadline_after', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input name="pass_approve" type="checkbox" class="custom-control-input" checked
                                    id="customSwitch3">
                                <label class="custom-control-label" for="customSwitch3">Cho phép bỏ qua bước duyệt</label>
                            </div>
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

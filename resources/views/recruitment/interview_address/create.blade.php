@extends('recruitment.layouts.master')
@section('content')

    <form action="{{ route('recruitment.interview_address.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin vị trí</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nơi phỏng vấn <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" class="form-control" id="ckeditor" rows="3" placeholder="Nhập mô tả ..."></textarea>
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
            </div>
            <!-- /.card-body -->
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
    @include('ckfinder::setup')
@stop

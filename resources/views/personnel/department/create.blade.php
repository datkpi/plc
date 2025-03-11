@extends('admin.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('admin.department.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin phòng ban</h3>
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
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên phòng ban <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Mã phòng ban <span class="text-danger">*</span></label>
                            <input name="uid" type="text" class="form-control" value="{{ old('uid') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('uid', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Phòng ban cấp trên <span class="text-danger">*</span></label>
                            <select name="parent_id" class="form-control select2" data-placeholder="Select">
                                {!! $departments !!}
                            </select>
                            {!! $errors->first('parent_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Người quản lý <span class="text-danger">*</span></label>
                            <select name="manager_by" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('manager_by', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Liên hệ</label>
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
                {{-- <div class="col-sm-3">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input name="active" type="checkbox" class="custom-control-input" checked id="customSwitch3">
                        <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                    </div>
                </div>
            </div> --}}
                {{-- <div class="col-sm-3">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input name="active" type="checkbox" class="custom-control-input" checked id="customSwitch3">
                        <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                    </div>
                </div>
            </div> --}}
            </div>
            <!-- /.card-body -->
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
    <!-- /.card -->
    {{--      <script> --}}
    {{--        CKEDITOR.replace( 'ckeditor', { --}}

    {{--            filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}", --}}
    {{--            filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123", --}}
    {{--            filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123", --}}
    {{--            filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files", --}}
    {{--            filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images", --}}
    {{--            filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash", --}}
    {{--        } ); --}}
    {{--        </script> --}}
    @include('ckfinder::setup')
@stop

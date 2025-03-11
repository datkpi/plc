@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->


    {{-- <div class="container">
        <div class="mt-5 mb-5 text-center">
            <h2>Additional element : Steps Using Bootstrap 4</h2>
        </div>
        <ul class="step d-flex flex-nowrap">
            <li class="step-item">
                <a href="#!" class="">Step 1</a>
            </li>
            <li class="step-item">
                <a href="#!" class="">Step 2</a>
            </li>
            <li class="step-item active">
                <a href="#!" class="">Step 3</a>
            </li>
            <li class="step-item">
                <a href="#!" class="">Step 4</a>
            </li>
        </ul>
        <div class="text-muted mt-5 text-center small">by : <a class="text-muted" target="_blank"
                href="http://totoprayogo.com">totoprayogo.com</a></div>
    </div> --}}
    <form action="{{ route('recruitment.position.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên vị trí <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <div class="form-group">
                            <label>Loại chức vụ (để xác định người duyệt) <span class="text-danger">*</span></label>
                            <select name="positionRanks" class="form-control" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('manager_by', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Vị trí cấp trên </label>
                            <select name="parent_id" class="form-control select2" data-placeholder="Select">
                                {!! $positions !!}
                            </select>
                            {!! $errors->first('parent_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Người quản lý </label>
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

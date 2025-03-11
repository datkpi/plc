@extends('recruitment.layouts.master')
@section('content')
<!-- general form elements disabled -->
<form action="{{ route('recruitment.user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin cơ bản</h3>
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
                        <label>Họ tên <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control" value="{{ $data->name }}">
                        {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Mã nhân sự<span class="text-danger">*</span></label>
                        <input name="user_uid" type="text" class="form-control" value="{{ $data->user_uid }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('user_uid', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Vai trò <span class="text-danger">*</span></label>
                        <select name="role_id[]" multiple class="form-control custom-select select2">
                            {!! $roles !!}
                        </select>
                        {!! $errors->first('role_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Ngày sinh <span class="text-danger">*</span></label>
                        <input name="birthday" type="date" class="form-control" value="{{ $data->birthday }}">
                        {!! $errors->first('birthday', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Số điện thoại <span class="text-danger">*</span></label>
                        <input name="phone_number" type="text" class="form-control" value="{{ $data->phone_number }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('phone_number', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Chọn phòng ban <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-control custom-select select2">
                            {!! $departments !!}
                        </select>
                        {!! $errors->first('department_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>


                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input name="email" type="email" class="form-control" value="{{ $data->email }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Giới tính <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control custom-select">
                            {!! $genders !!}
                        </select>
                        {!! $errors->first('gender', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Căn cước công dân <span class="text-danger">*</span></label>
                        <input type="text" name="cccd" value="{{ $data->cccd }}" class="form-control"
                            placeholder="Nhập ...">
                        {!! $errors->first('cccd', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Chọn vị trí/ chức danh <span class="text-danger">*</span></label>
                        <select name="position_id" class="form-control custom-select select2">
                            {!! $positions !!}
                        </select>
                        {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-sm-12">
                    <!-- textarea -->
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" class="form-control" id="ckeditor" rows="3"
                            placeholder="Nhập mô tả ...">{!! $data->description !!}</textarea>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Chữ ký mail</label>
                        <textarea name="mail_footer" class="form-control" id="mail-footer" rows="3"
                            placeholder="Nhập ...">{!!$data->mail_footer!!}</textarea>
                        {!! $errors->first('mail_footer', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="formFile" class="form-label">Ảnh đại diện</label>
                        <input name="avatar" accept="image/" onchange="previewImage()" class="form-control" type="file"
                            id="formFile">
                    </div>
                </div>
                <img id="preview_image"
                    src="{{ $data->avatar ? asset($data->avatar) : asset('storage/photos/shares/thumbs/default-thumbnail.jpg') }}"
                    style="" class="img-thumbnail col-sm-3">

            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input name="active" type="checkbox" class="custom-control-input" {{ $data->active ? 'checked' :
                        '' }} id="customSwitch3">
                        <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Thông tin tài khoản</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Tài khoản <span class="text-danger">*</span></label>
                        <input name="username" type="text" class="form-control" value="{{ $data->username }}"
                            placeholder="Nhập username ...">
                        {!! $errors->first('username', '<span class="text-danger">:message</span>') !!}
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
<script>
    // var options = {
    // filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    // filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
    // filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    // filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
    // };
    CKEDITOR.replace('mail-footer');
</script>
@stop

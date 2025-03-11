@extends('recruitment.layouts.master')
@section('content')
<!-- general form elements disabled -->
<form action="{{route('recruitment.user.change_password', $data->id)}}" method="POST" enctype="multipart/form-data">
    @csrf

        <!-- /.card-header -->

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Đổi mật khẩu</h3>
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
                        <label>Mật khẩu mới <span class="text-danger">*</span></label>
                        <input name="password" type="password" class="form-control" value="{{ old('username') }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('password', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Nhập lại mật khẩu mới <span class="text-danger">*</span></label>
                        <input name="re_password" type="password" class="form-control" placeholder="Nhập ...">
                        {!! $errors->first('re_password', '<span class="text-danger">:message</span>') !!}
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
{{--      <script>--}}
{{--        CKEDITOR.replace( 'ckeditor', {--}}

{{--            filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}",--}}
{{--            filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123",--}}
{{--            filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123",--}}
{{--            filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files",--}}
{{--            filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images",--}}
{{--            filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash",--}}
{{--        } );--}}
{{--        </script>--}}
@include('ckfinder::setup')
@stop

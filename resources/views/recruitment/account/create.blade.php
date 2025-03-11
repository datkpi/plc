@extends('recruitment.layouts.master')
@section('content')
      <!-- general form elements disabled -->
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Thêm tài khoản</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form>
            <div class="row">
              <div class="col-sm-6">
                <!-- text input -->
                <div class="form-group">
                  <label>Tài khoản</label>
                  <input type="text" class="form-control" placeholder="Nhập username ...">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Mật khẩu</label>
                  <input type="text" class="form-control" placeholder="Nhập mật khẩu ...">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <!-- textarea -->
                <div class="form-group">
                  <label>Mô tả</label>
                  <textarea class="form-control" id="ckeditor" rows="3" placeholder="Nhập mô tả ..."></textarea>
                </div>
              </div>
            </div>

            <div class="float-right">
                <button type="submit" class="btn btn-primary">Lưu</button>
              </div>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <script>
          var options = {
              filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
              filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
              filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
              filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
          };

          CKEDITOR.replace('ckeditor', options);
        {{--CKEDITOR.replace( 'ckeditor', {--}}

        {{--    filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}",--}}
        {{--    filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123",--}}
        {{--    filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123",--}}
        {{--    filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files",--}}
        {{--    filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images",--}}
        {{--    filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash",--}}
        {{--} );--}}
        </script>
        @include('ckfinder::setup')
@stop


@extends('recruitment.layouts.master')
@section('content')
<!-- general form elements disabled -->

<a class="btn btn-info btn-sm mb-2" href="{{ route('recruitment.mail_template.test', $data->id) }}">Test mail</a>

<form action="{{ route('recruitment.mail_template.update', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ứng viên</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" style="height: 400px; overflow-y: scroll;">
                    <ul class="nav nav-pills flex-column">
                        @foreach($candidateColunms as $key => $candidateColunm)
                        @if($key == 0)
                        @continue;
                        @endif
                        <li class="nav-item active placeholder" data-field="candidate.{{ $candidateColunm }}">
                            <a href="#" class="nav-link">
                                {{-- <i class="fas fa-inbox"></i> --}}
                                candidate.{{$candidateColunm}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thông tin</h3>
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
                                <label>Tên template <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control" value="{{ $data->name }}"
                                    placeholder="Nhập ...">
                                {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Code </label>
                                <input name="code" class="form-control" placeholder="Code:" value="{{$data->code}}">
                                {!! $errors->first('code', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <!-- textarea -->
                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea name="description" class="form-control" id="ckeditor" rows="1"
                                    placeholder="Nhập ...">{{ $data->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nội dung mail</label>
                                <textarea name="body" class="form-control" id="ckeditor_body" rows="3"
                                    placeholder="Nhập ...">{!! $data->body !!}</textarea>
                                {!! $errors->first('body', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Chữ ký</label>
                                <textarea name="footer" class="form-control" id="ckeditor_footer" rows="3"
                                    placeholder="Nhập ...">{!! $data->footer !!}</textarea>
                                {!! $errors->first('footer', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <input class="form-control" id="thumbnail" name="file" type="text"
                                value="{{ $data->file }}">
                        </div>
                        <div class="col-sm-3">
                            <button class="form-control" id="lfm" data-input="thumbnail" multiple
                                data-preview="holder">Chọn file đính kèm</button>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input name="active" type="checkbox" class="custom-control-input" checked
                                    id="customSwitch3" {{ $data->active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                            </div>
                        </div>
                    </div>
                    {{-- <iframe src="/laravel-filemanager"
                        style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe> --}}
                </div>
                <!-- /.card-body -->
            </div>
            <div class="float-right">
                <button type="submit" class="btn btn-primary m-3">Lưu</button>
            </div>
        </div>
    </div>
</form>

<!-- /.card -->
<script>
    var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        CKEDITOR.replace('ckeditor_body', options);
        CKEDITOR.replace('ckeditor_footer', options);
</script>

<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script>
    $('#lfm').filemanager('file');
</script>
@stop

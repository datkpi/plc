@extends('recruitment.layouts.master')
@section('content')

    <form action="{{ route('recruitment.mail.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-3">
                {{-- <a href="mailbox.html" class="btn btn-primary btn-block mb-3">Back to Inbox</a> --}}

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách gửi</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">

                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th>
                                        Email
                                    </th>
                                    <th>
                                        Người nhận
                                    </th>
                                    <th class="">
                                        Trạng thái
                                    </th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        Name
                                    </td>

                                    <td class="project-actions">
                                        Chưa gửi
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        Name
                                    </td>

                                    <td class="project-actions">
                                        Chưa gửi
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        Name
                                    </td>

                                    <td class="project-actions">
                                        Chưa gửi
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        4
                                    </td>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        Name
                                    </td>

                                    <td class="project-actions">
                                        Chưa gửi
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        {{-- <ul class="nav nav-pills flex-column">
                  <li class="nav-item active">
                    <a href="#" class="nav-link">
                      <i class="fas fa-inbox"></i> Inbox
                      <span class="badge bg-primary float-right">12</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-envelope"></i> Sent
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-file-alt"></i> Drafts
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="fas fa-filter"></i> Junk
                      <span class="badge bg-warning float-right">65</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="far fa-trash-alt"></i> Trash
                    </a>
                  </li>
                </ul> --}}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                {{-- <div class="card">
              <div class="card-header">
                <h3 class="card-title">Labels</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-danger"></i> Important</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-warning"></i> Promotions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#"><i class="far fa-circle text-primary"></i> Social</a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div> --}}
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Gửi mail</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{-- <div class="form-group">
                  <label>To ()</label>
                  <input class="form-control" placeholder="To:">
                </div> --}}
                        <div class="form-group">
                            <label>Subject <span class="text-danger">*</span></label>
                            <input name="subject" class="form-control" placeholder="Subject:" value="{{ $data->subject }}">
                            {!! $errors->first('subject', '<span class="text-danger">:message</span>') !!}
                        </div>
                        <div class="form-group">
                            <label>Bcc (Nhập cách nhau dấu phẩy)</label>
                            <input name="bcc" class="form-control" placeholder="Bcc:" value="{{ $data->bcc }}">
                        </div>
                        <div class="form-group">
                            <label>Cc (Nhập cách nhau dấu phẩy)</label>
                            <input name="cc" class="form-control" placeholder="Cc:" value="{{ $data->cc }}">
                        </div>
                        <div class="form-group">
                            <label>Nội dung mail <span class="text-danger">*</span></label>
                            {!! $errors->first('body', '<span class="text-danger">:message</span>') !!}
                            <textarea name="body" class="form-control" id="ckeditor_body" rows="3" placeholder="Nhập ...">{!! $data->body !!}</textarea>

                        </div>
                        <div class="form-group">
                            <label>Chữ ký</label>
                            {!! $errors->first('footer', '<span class="text-danger">:message</span>') !!}
                            <textarea name="footer" class="form-control" id="ckeditor_footer" rows="3" placeholder="Nhập ...">{!! $data->footer !!}</textarea>

                        </div>
                        <div class="form-group">
                            <div class="btn btn-default btn-file">
                                <i class="fas fa-paperclip"></i> File đính kèm
                                <input type="file" name="attachment">
                            </div>
                            <p class="help-block">Dung lượng tối đa 50MB</p>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input name="active" type="checkbox" class="custom-control-input" id="customSwitch3"
                                    {{ $data->active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                            <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Lưu
                                lại</button>
                            <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Lưu và gửi
                                ngay</button>
                        </div>
                        <a type="button" href="{{ route('recruitment.mail.index') }}" class="btn btn-default"><i
                                class="fas fa-times"></i> Quay lại</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </form>
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

@stop

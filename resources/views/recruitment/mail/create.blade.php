@extends('recruitment.layouts.master')
@section('content')

<form action="{{ route('recruitment.mail.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-3">
            <div class="row col-12">
                <button class="form-control col-6" onclick="document.getElementById('getFileImport').click()"><i
                        class="fa-solid fa-file-import"></i> Nhập file excel</button>
                <input type='file' id="getFileImport" style="display:none">
                <a type="button" class="form-control col-6 text-center"
                    onclick="document.getElementById('getFileTemplate').click()"><i class='fas fa-file-download'></i>
                    Tải file mẫu</a>
                <input type='file' id="getFileTemplate" style="display:none">
            </div>

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
                        <label>Nhập excel</label>
                        <input type="file" class="form-control" placeholder="">
                    </div> --}}
                    <div class="form-group">
                        <label>Tiêu đề <span class="text-danger">*</span></label>
                        <input name="subject" class="form-control" placeholder="Subject:">
                        {!! $errors->first('subject', '<span class="text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Code </label>
                        <input name="code" class="form-control" placeholder="Code:">
                        {!! $errors->first('code', '<span class="text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Mẫu template </label>
                        <select class="select2 form-control" name="template_id">
                            {!! $templates !!}
                        </select>
                        {!! $errors->first('teamplate_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                    <div class="form-group">
                        <label>Bcc (Nhập cách nhau dấu phẩy)</label>
                        <input name="bcc" class="form-control" placeholder="Bcc:">
                    </div>
                    <div class="form-group">
                        <label>Cc (Nhập cách nhau dấu phẩy)</label>
                        <input name="cc" class="form-control" placeholder="Cc:">
                    </div>
                    <div class="form-group">
                        <label>Nội dung mail <span class="text-danger">*</span></label>
                        {!! $errors->first('body', '<span class="text-danger">:message</span>') !!}
                        <textarea name="body" class="form-control" id="ckeditor_body" rows="3"
                            placeholder="Nhập ..."></textarea>

                    </div>
                    <div class="form-group">
                        <label>Chữ ký</label>
                        {!! $errors->first('footer', '<span class="text-danger">:message</span>') !!}
                        <textarea name="footer" class="form-control" id="ckeditor_footer" rows="3"
                            placeholder="Nhập ..."></textarea>

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
                            <input name="active" type="checkbox" class="custom-control-input" checked
                                id="customSwitch3">
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
                            class="fas fa-times"></i> Huỷ</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
</form>
<script src="
                            https://cdn.jsdelivr.net/npm/tinymce@6.6.2/tinymce.min.js
                            "></script>
<link href="
https://cdn.jsdelivr.net/npm/tinymce@6.6.2/skins/ui/oxide/content.min.css
" rel="stylesheet">
<script>
    var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        CKEDITOR.replace('ckeditor_body', options);
        CKEDITOR.replace('ckeditor_footer', options);
        CKEDITOR.on("instanceReady", function(event) {
            event.editor.on("beforeCommandExec", function(event) {
                // Show the paste dialog for the paste buttons and right-click paste
                if (event.data.name == "paste") {
                    event.editor._.forcePasteDialog = true;
                }
                // Don't show the paste dialog for Ctrl+Shift+V
                if (event.data.name == "pastetext" && event.data.commandData.from == "keystrokeHandler") {
                    event.cancel();
                }
            })
        });
</script>

@stop

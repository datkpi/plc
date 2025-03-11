@extends('recruitment.layouts.master')
@section('content')

<ul class="nav nav-tabs" id="statusList">
    <li class="nav-item">
        <a data-status="all" class="nav-link active" aria-current="page" href="#">Tất cả<span class="count">
                ({{ $totalCandidate }})</span></a>
    </li>
    @foreach ($status as $st)
    <li data-status="{{ $st->name }}" class="nav-item">
        <a data-status="{{ $st->name }}" class="nav-link" href="#">{{ $st->value }}<span class="count">
                ({{ isset($countByStatus[$st->name]) ? $countByStatus[$st->name] : 0 }})
            </span></a>
    </li>
    @endforeach
</ul>
<div id="gridData"></div>
<div>
    <input type="file" id="fileInput" style="display:none" onchange="ImportExcel(event)">
</div>

<!-- /.modal tạo link ứng tuyển -->
<form action="" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tạo form ứng tuyển</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="new">
                        <b class="col-sm-12 mb-2" id="formUrl"></b>
                        <b class="col-sm-12 mb-2 text-danger" style="color:red;">Nếu gửi kèm mail hãy nhập đầy đủ các thông tin bên dưới: </b>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Tên ứng viên <span class="text-danger">*</span></label>
                                <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                    placeholder="Nhập ...">
                                {!! $errors->first('name', '<span class="text-danger">:message</span>')
                                !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Giới tính <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control" data-placeholder="Select">
                                    {!! $genders !!}
                                    {!! $errors->first('gender', '<span class="text-danger">:message</span>') !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Vị trí/ chức danh <span class="text-danger">*</span></label>
                                <select name="position_id" class="form-control select2" data-placeholder="Select">
                                    {!! $positions !!}
                                </select>
                                {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <!-- text input -->
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input name="email" type="email" class="form-control" placeholder="Nhập ..."
                                    value="{{ old('email')}}">
                                {!! $errors->first('email', '<span class="text-danger">:message</span>')
                                !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input name="is_send_mail" type="checkbox" class="custom-control-input"
                                        id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3">Gửi mail</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                    <button type="button" id="createForm" class="btn btn-primary">Tạo form</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<!-- /.modal -->

{{-- modal list form ứng tuyển --}}
<form action="" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modal-form">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Danh sách form ứng tuyển</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>

                                <th>
                                    Tên
                                </th>
                                <th>
                                    Vị trí
                                </th>
                                <th>
                                    Email
                                </th>
                                <th>
                                    Link truy cập
                                </th>
                                <th class="">
                                    Trạng thái
                                </th>
                                <th>
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $key => $data)

                            <tr>
                                {{-- <td>
                                    {{ ++$key }}
                                </td> --}}
                                <td>
                                    <a>
                                        {{ $data->name }}
                                    </a>
                                    <br />
                                    <small>
                                        {{ optional($data->position)->name }}
                                    </small>
                                </td>
                                <td></td>
                                <td>
                                    {{ $data->email }}
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm copyLink"
                                        href="{{ route('frontend.candidate.getForm', $data->form_token) }}">
                                        <i class="fas fa-copy"></i>
                                        Sao chép
                                    </a>
                                </td>
                                <td class="">
                                    {!! $data->is_submit
                                    ? '<span class="badge badge-success">Đã điền thông tin</span>'
                                    : '<span class="badge badge-danger">Chưa điền thông tin</span>' !!}
                                </td>
                                <td class="project-actions">
                                    <a class="btn btn-info btn-sm canEdit" data-id={{$data->id}}>
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Cấp quyền sửa
                                    </a>
                                    <a class="btn btn-primary btn-sm changeCandidate" data-id={{$data->id}}>
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                        Chuyển ứng viên
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                    {{-- <button type="button" id="createForm" class="btn btn-primary">Tạo form</button> --}}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</form>
<style>

</style>

<script src="{{ asset('js/devxtreme/candidate.js') }}"></script>
@stop

@extends('recruitment.layouts.master')
@section('content')

    <form action="{{ route('recruitment.recruitment_plan.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
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
                    <div class="col-sm-4">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên kế hoạch tuyển dụng <span class="text-danger">*</span></label>
                            <input name="name" type="text" class="form-control" value="{{ $data->name }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input name="start_date" class="form-control"
                                value="{{ $data->displayDate($data->start_date) }}" type="date">
                            {!! $errors->first('start_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ngày kết thúc <span class="text-danger">*</span></label>
                            <input name="end_date" class="form-control" value="{{ $data->displayDate($data->end_date) }}"
                                type="date">
                            {!! $errors->first('end_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách phiếu yêu cầu</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-lg">
                        Thêm phiếu mới
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            {{-- <th style="width: 2%" class="">
                                <input class="form-control" type="checkbox" name="" id="check-all">
                            </th> --}}
                            <th>
                                Tên phiếu yêu cầu
                            </th>
                            <th>
                                Phòng ban tuyển dụng
                            </th>
                            <th>
                                Vị trí tuyển dụng
                            </th>
                            <th>
                                Số lượng tuyển
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requestForms ?? [] as $key => $requestForm)
                            <tr>
                                {{-- <td>
                                    <input class="form-control checkbox" type="checkbox" name="requestForm[]"
                                        value="{{ $requestForm->id }}">
                                </td> --}}
                                <td>
                                    {{ $requestForm->name }}
                                </td>
                                <td>
                                    {{ $requestForm->department->name }}
                                </td>
                                <td>
                                    {{ $requestForm->position->name }}
                                </td>
                                <td>
                                    {{ $requestForm->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="float-right">
            {{-- <button type="submit" class="btn btn-danger">Xoá phiếu đã chọn</button> --}}
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>

    <!-- /.modal -->
    <form action="{{ route('recruitment.recruitment_plan.add_form', $data->id) }}" method="POST" enctype="multipart/form-data">
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Danh sách phiếu tuyển dụng đã duyệt</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 2%" class="">
                                        <input class="form-control" type="checkbox" name="" id="check-all">
                                    </th>
                                    <th>
                                        Tên phiếu yêu cầu
                                    </th>
                                    <th>
                                        Phòng ban tuyển dụng
                                    </th>
                                    <th>
                                        Vị trí tuyển dụng
                                    </th>
                                    <th>
                                        Số lượng tuyển
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($requestFormApproveds ?? [] as $key => $requestFormApproved)
                                    <tr>
                                        <td>
                                            <input class="form-control checkbox" type="checkbox" name="requestForm[]"
                                                value="{{ $requestFormApproved->id }}">
                                        </td>
                                        <td>
                                            {{ $requestFormApproved->name }}
                                        </td>
                                        <td>
                                            {{ $requestFormApproved->department->name }}
                                        </td>
                                        <td>
                                            {{ $requestFormApproved->position->name }}
                                        </td>
                                        <td>
                                            {{ $requestFormApproved->quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <script>
        $('#check-all').click(function() {
            if ($(this).prop('checked')) {
                $('.checkbox').prop('checked', true);
            } else {
                $('.checkbox').prop('checked', false);
            }
        });
    </script>
@stop

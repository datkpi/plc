@extends('recruitment.layouts.master')
@section('content')

    <form action="{{ route('recruitment.recruitment_plan.store') }}" method="POST" enctype="multipart/form-data">
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
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input name="start_date" class="form-control" value="{{ old('start_date') }}" type="date">
                            {!! $errors->first('start_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Ngày kết thúc <span class="text-danger">*</span></label>
                            <input name="end_date" class="form-control" value="{{ old('end_date') }}" type="date">
                            {!! $errors->first('end_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách phiếu yêu cầu đã được duyệt</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
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
                        @foreach ($selectRequestForms as $key => $selectRequestForm)
                            <tr>
                                <td>
                                    <input class="form-control checkbox" type="checkbox" name="requestForm[]"
                                        value="{{ $selectRequestForm->id }}">
                                </td>
                                <td>
                                    {{ $selectRequestForm->name }}
                                </td>
                                <td>
                                    {{ $selectRequestForm->department->name }}
                                </td>
                                <td>
                                    {{ $selectRequestForm->position->name }}
                                </td>
                                <td>
                                    {{ $selectRequestForm->quantity }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>

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

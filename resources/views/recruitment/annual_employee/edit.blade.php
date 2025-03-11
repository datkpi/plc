@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('recruitment.annual_employee.update', $data->id) }}" method="POST" enctype="multipart/form-data"
        lang="vi">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin định biên</h3>
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
                            <label>Chọn vị trí chức danh <span class="text-danger">*</span></label>
                            <select name="position_id" class="form-control select2" data-placeholder="Select">
                                {!! $positions !!}
                            </select>
                            {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tháng <span class="text-danger">*</span></label>
                            <select name="month" class="form-control" data-placeholder="Select">
                                {!! \App\Common\StringHelpers::getSelectMonth($data->month) !!}
                            </select>
                            {!! $errors->first('month', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Năm <span class="text-danger">*</span></label>
                            <select name="year" class="form-control select2" data-placeholder="Select">
                                {!! \App\Common\StringHelpers::getSelectYear($data->year) !!}
                            </select>
                            {!! $errors->first('year', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Số lượng <span class="text-danger">*</span></label>
                            <input name="employee_number" type="text" value="{{ $data->employee_number }}"
                                class="form-control" placeholder="Nhập ...">
                            {!! $errors->first('employee_number', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-4">
                        <div class="form-group">
                            <label>Năm </label>
                            <select name="manager_by" class="form-control" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('manager_by', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="float-right">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>

    @include('ckfinder::setup')
@stop

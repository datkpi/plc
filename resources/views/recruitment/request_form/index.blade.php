@extends('recruitment.layouts.master')
@section('content')

    <ul class="nav nav-tabs" id="statusList">
        <li class="nav-item">
            <a data-status="all" class="{{ $totalApprove > 0 ? 'nav-link' : 'nav-link active' }}" aria-current="page"
                href="#">Tất cả<span class="count">
                    ({{ $totalRequestForm }})</span></a>

        </li>
        <li class="nav-item">
            <a data-status="current_approve"
                class="{{ $totalApprove > 0 ? 'nav-link active text-danger' : 'nav-link text-danger' }}" aria-current="page"
                href="#">Đang đợi tôi
                duyệt<span class="count">
                    ({{ $totalApprove }})</span></a>
        </li>
        @foreach ($status as $st)
            <li data-status="{{ $st->name }}" class="nav-item">
                <a data-status="{{ $st->name }}" class="nav-link" href="#">{{ $st->value }}<span
                        class="count">
                        ({{ isset($countByStatus[$st->name]) ? $countByStatus[$st->name] : 0 }})
                    </span></a>
            </li>
        @endforeach
    </ul>

    <div id="gridData"></div>
    <div id="addRecruitmentPlanPopup"></div>
    <style>
        .disable-checkbox .dx-select-checkbox {
            pointer-events: none;
            opacity: 0.3;
        }
    </style>


    <!-- Default box -->
    {{-- <div class="container-fluid">
        <form action="enhanced-results.html">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select class="select2" style="width: 100%;">
                                    <option selected>ASC</option>
                                    <option>DESC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Phòng ban</label>
                                <select class="select2" style="width: 100%;">
                                    <option selected>ASC</option>
                                    <option>DESC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Vị trí</label>
                                <select class="select2" style="width: 100%;">
                                    <option selected>Title</option>
                                    <option>Date</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Từ khoá</label>
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="Nhập từ khoá ..."
                                        value="">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="card">

        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            <input class="form-controll" type="checkbox">
                        </th>
                        <th>
                            Tên phiếu
                        </th>
                        <th>
                            Ngày tạo
                        </th>
                        <th>
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                {{ $data->name }}
                            </td>
                            <td>
                                {{ $data->createdAt() }}
                            </td>
                            <td class="project-actions">
                                <a class="btn btn-info btn-sm" href="{{ route('recruitment.request_form.edit', $data->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Sửa
                                </a>
                                <form action="{!! route('recruitment.request_form.destroy', $data->id) !!}" method="POST" style="display: inline-block">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}

                                    <button type="submit" class="btn btn-danger btn-sm delete_confirm"
                                        data-action="delete">
                                        <i class="fas fa-trash-alt">
                                        </i>
                                        Xoá
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div> --}}
    <!-- /.card -->

    <script src="{{ asset('js/devxtreme/request-form.js') }}"></script>
@stop

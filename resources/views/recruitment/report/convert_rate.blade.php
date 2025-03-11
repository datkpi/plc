@extends('recruitment.layouts.master')
@section('content')

    <!-- Info boxes -->
    <div class="row align-items-center">
        <div class="col-sm-2">
            <div class="form-group">
                <label>Từ ngày</label>
                <input lang="vi-VN" class="form-control" type="date" name="date_from" value="{{ old('date_from') }}">
                {{-- value="{{ old('date_filter', now()->format('Y-m')) --}}
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Tới ngày</label>
                <input class="form-control" type="date" name="date_to" value="{{ old('date_to') }}" lang="vi-VN">
            </div>
        </div>

        {{-- <div class="col-sm-2">
            <div class="form-group">
                <label>Hiển thị theo</label>
                <select name="source_name" class="form-control">
                    <option value="month">Tháng</option>
                    <option value="quarter">Quý</option>
                    <option value="year">Năm</option>
                </select>
            </div>
        </div> --}}

        <div class="col-sm-3">
            <div class="form-group">
                <label>Chọn vị trí:</label>
                <div class="input-group">
                    <select id="positionSelect" name="position_id[]" class="form-control select2" multiple>
                        {{-- <option selected value="">Tất cả</option> --}}
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" id="clearPositionFilter">x</button>
                    </span>
                </div>
            </div>
        </div>
        {{-- <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn phòng ban:</label>
                <select name="department_id" class="form-control select2">
                    <option value="">Tất cả</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->name }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
        </div> --}}

        <button id="filterButton" class="form-control col-sm-1 btn btn-primary mt-3" type="submit">Lọc</button>

        <div class="col-12 col-sm-12 col-md-12">
            <div class="demo-container">
                <div id="pivotgrid-demo">
                    <div id="pivotgrid-chart"></div>
                    <div id="pivotgrid"></div>
                </div>
            </div>
        </div>
        <style>
            #pivotgrid-chart {
                margin-bottom: 30px;
            }

            .centered-cell {
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
        <script src="{{ asset('js/devxtreme/report/convert-rate.js') }}"></script>

    @stop

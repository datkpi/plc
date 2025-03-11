@extends('recruitment.layouts.master')
@section('content')

    <!-- Info boxes -->
    <div class="row align-items-center">
        <div class="col-sm-2">
            <div class="form-group">
                <label>Từ ngày</label>
                <input lang="vi-VN" id="date_from" class="form-control" type="date" name="date_from"
                    value="{{ old('date_from') }}">
                {{-- value="{{ old('date_filter', now()->format('Y-m')) --}}
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
                <label>Tới ngày</label>
                <input class="form-control" id="date_to" type="date" name="date_to" value="{{ old('date_to') }}"
                    lang="vi-VN">
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn phòng ban:</label>
                <select name="department_id" id="department" class="form-control select2">
                    <option value="">Tất cả</option>
                    @foreach ($departments as $department)
                        @if ($department->name == 'Ban Nhân sự chiến lược')
                            <option selected value="{{ $department->id }}">{{ $department->name }}</option>
                        @else
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn vị trí:</label>
                <div class="input-group">
                    <select id="positionSelect" name="position_id[]" class="form-control select2" multiple>
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

        <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn nhân sự:</label>
                <div class="input-group">
                    <select id="userSelect" name="user_id[]" class="form-control select2" multiple>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" id="clearUserFilter">x</button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-1 mt-3">
            <button id="filter" type="button" class="btn btn-primary">Lọc</button>
        </div>

    </div>
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

        .dx-pivotgrid .dx-pivotgrid-area-row .dx-pivotgrid-grand-total {
            order: -1;
            border-bottom: 1px solid #d5d5d5;
        }
    </style>
    <script src="{{ asset('js/devxtreme/report/recruitment-kpi.js') }}"></script>

@stop

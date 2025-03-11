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
        <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn nguồn:</label>
                <div class="input-group">
                    <select id="sourceSelect" name="source_id[]" class="form-control select2" multiple>
                        {{-- <option selected value="">Tất cả</option> --}}
                        @foreach ($sources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" id="clearSourceFilter">x</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
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
        <div class="col-sm-2">
            <div class="form-group">
                <label>Chọn trạng thái:</label>
                <div class="input-group">
                    <select id="statusSelect" name="status" class="form-control select2">
                        <option selected value="">Tất cả</option>
                        <option value="recruitment_success ">Trúng tuyển (nhận việc)</option>
                    </select>
                    {{-- <span class="input-group-btn">
                        <button class="btn btn-danger" type="button" id="clearStatusFilter">x</button>
                    </span> --}}
                </div>
            </div>
        </div>

        <button id="filterButton" class="form-control col-sm-1 btn btn-primary mt-3" type="submit">Lọc</button>

        {{-- <div class="col-sm-3">
            <div class="form-group">
                <label>Lọc theo thời gian</label>
                <button id="reportrange" class="btn btn-default float-right mb-2">
                    <i class="far fa-calendar-alt"></i> Chọn
                    <i class="fas fa-caret-down"></i>
                </button>
            </div>
        </div> --}}

        <div class="col-12 col-sm-12 col-md-12">
            <div class="demo-container">
                {{-- <div id="pivotgrid-chart"></div> --}}
                <div id="pieChart"></div>
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

    <script type="text/javascript">
        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                GetData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày gần đây': [moment().subtract(6, 'days'), moment()],
                    '30 ngày gần đây': [moment().subtract(29, 'days'), moment()],
                    'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            // cb(start, end);

        });
    </script>

    <script src="{{ asset('js/devxtreme/report/candidate-source.js') }}"></script>

@stop

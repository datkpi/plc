@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Báo cáo OEE theo tháng</h3>
        <div class="card-tools">
            <a href="{{ route('plc.reports.oee.export') }}?type=monthly&{{ request()->getQueryString() }}"
               class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('plc.reports.oee.monthly') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Tháng</label>
                        <select name="month" class="form-control">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month', now()->month) == $i ? 'selected' : '' }}>
                                    Tháng {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Năm</label>
                        <select name="year" class="form-control">
                            @for($i = now()->year - 2; $i <= now()->year; $i++)
                                <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Máy</label>
                        <select name="machine_id" class="form-control">
                            <option value="">-- Chọn máy --</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}"
                                    {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
                                    {{ $machine->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Phương pháp tính</label>
                        <select name="method" id="method" class="form-control" disabled>
                            <option value="product" selected>
                                Trung bình từ OEE ngày
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Xem báo cáo
                    </button>
                </div>
            </div>

            <!-- Tham số bổ sung cho phương pháp tính theo công thức tháng -->
            <div id="designParams" class="row mt-3" style="{{ request('method') == 'design' ? '' : 'display: none;' }}">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Thời gian tháng (phút)</label>
                        <input type="number" name="total_month_time" class="form-control"
                               value="{{ request('total_month_time', 30 * 24 * 60) }}" min="0">
                        <small class="text-muted">Mặc định: 30 ngày x 24h x 60p = 43200 phút</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Thời gian không kế hoạch (phút)</label>
                        <input type="number" name="unplanned_time" class="form-control"
                               value="{{ request('unplanned_time', 0) }}" min="0">
                        <small class="text-muted">Thời gian bảo trì, ngày nghỉ, v.v.</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Năng suất thiết kế (kg/h)</label>
                        <input type="number" name="design_capacity" class="form-control"
                               value="{{ request('design_capacity', 0) }}" min="0" step="0.1">
                        <small class="text-muted">Năng suất thiết kế của máy</small>
                    </div>
                </div>
            </div>
        </form>

        @if(($monthlyOEE || $monthlyOEEByDesign) && $selectedMachine)
        <h4>Báo cáo OEE tháng {{ $month }}/{{ $year }} - {{ $selectedMachine->name }}</h4>

        @if($method == 'product' && $monthlyOEE)
        <!-- Phương pháp 1: Trung bình từ OEE ngày -->
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Availability (%)</th>
                                <th>Performance (%)</th>
                                <th>Quality (%)</th>
                                <th>OEE (%)</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyOEE['daily'] as $date => $oee)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                <td class="text-right">{{ number_format($oee['availability'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($oee['performance'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($oee['quality'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($oee['oee'] * 100, 1) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('plc.reports.oee.daily', ['date' => $date, 'machine_id' => $machineId]) }}"
                                        class="btn btn-info btn-xs">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                            <tr class="font-weight-bold bg-light">
                                <td>Trung bình tháng</td>
                                <td class="text-right">{{ number_format($monthlyOEE['monthly']['availability'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($monthlyOEE['monthly']['performance'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($monthlyOEE['monthly']['quality'] * 100, 1) }}</td>
                                <td class="text-right">{{ number_format($monthlyOEE['monthly']['oee'] * 100, 1) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">OEE tháng {{ $month }}/{{ $year }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="oeeMonthlyPieChart" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ xu hướng -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Biểu đồ xu hướng OEE trong tháng</h5>
                    </div>
                    <div class="card-body">
                        <div id="oeeTrendChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ thành phần -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Phân tích thành phần OEE</h5>
                    </div>
                    <div class="card-body">
                        <div id="oeeComponentChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>

        @elseif($method == 'design' && $monthlyOEEByDesign)
        <!-- Phương pháp 2: Theo công thức tháng -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">OEE tháng {{ $month }}/{{ $year }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Availability</th>
                                <td>{{ number_format($monthlyOEEByDesign['availability'] * 100, 1) }}%</td>
                            </tr>
                            <tr>
                                <th>Performance</th>
                                <td>{{ number_format($monthlyOEEByDesign['performance'] * 100, 1) }}%</td>
                            </tr>
                            <tr>
                                <th>Quality</th>
                                <td>{{ number_format($monthlyOEEByDesign['quality'] * 100, 1) }}%</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <th>OEE</th>
                                <td>{{ number_format($monthlyOEEByDesign['oee'] * 100, 1) }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Chi tiết tính toán</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Thời gian tháng</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['total_month_time'] / 60, 1) }} giờ</td>
                            </tr>
                            <tr>
                                <th>Thời gian không kế hoạch</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['unplanned_time'] / 60, 1) }} giờ</td>
                            </tr>
                            <tr>
                                <th>Thời gian kế hoạch</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['planned_time'] / 60, 1) }} giờ</td>
                            </tr>
                            <tr>
                                <th>Thời gian chạy máy</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['total_run_time_hours'], 1) }} giờ</td>
                            </tr>
                            <tr>
                                <th>Chính phẩm</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['total_good_products'], 1) }} kg</td>
                            </tr>
                            <tr>
                                <th>Phế phẩm</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['total_defect_products_kg'], 1) }} kg</td>
                            </tr>
                            <tr>
                                <th>Tổng sản phẩm</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['total_products_kg'], 1) }} kg</td>
                            </tr>
                            <tr>
                                <th>Năng suất thực tế</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['actual_hourly_rate'], 1) }} kg/h</td>
                            </tr>
                            <tr>
                                <th>Năng suất thiết kế</th>
                                <td>{{ number_format($monthlyOEEByDesign['details']['design_capacity'], 1) }} kg/h</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ OEE -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Biểu đồ OEE</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="oeeSummaryChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Phân tích tổn thất OEE</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="oeeLossChart" style="height: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích lý thuyết -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Phân tích sản lượng lý thuyết vs thực tế</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Hạng mục</th>
                                <th>Sản lượng (kg)</th>
                                <th>Tỷ lệ (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $theoreticalMax = $monthlyOEEByDesign['details']['planned_time'] / 60 * $monthlyOEEByDesign['details']['design_capacity'];
                                $availabilityLoss = $theoreticalMax * (1 - $monthlyOEEByDesign['availability']);
                                $performanceLoss = $theoreticalMax * $monthlyOEEByDesign['availability'] * (1 - $monthlyOEEByDesign['performance']);
                                $qualityLoss = $theoreticalMax * $monthlyOEEByDesign['availability'] * $monthlyOEEByDesign['performance'] * (1 - $monthlyOEEByDesign['quality']);
                                $actualGood = $monthlyOEEByDesign['details']['total_good_products_kg'];
                            @endphp
                            <tr>
                                <td>Sản lượng lý thuyết tối đa</td>
                                <td>{{ number_format($theoreticalMax, 1) }}</td>
                                <td>100%</td>
                            </tr>
                            <tr class="table-danger">
                                <td>Tổn thất do Availability</td>
                                <td>{{ number_format($availabilityLoss, 1) }}</td>
                                <td>{{ number_format($availabilityLoss / $theoreticalMax * 100, 1) }}%</td>
                            </tr>
                            <tr class="table-warning">
                                <td>Tổn thất do Performance</td>
                                <td>{{ number_format($performanceLoss, 1) }}</td>
                                <td>{{ number_format($performanceLoss / $theoreticalMax * 100, 1) }}%</td>
                            </tr>
                            <tr class="table-info">
                                <td>Tổn thất do Quality</td>
                                <td>{{ number_format($qualityLoss, 1) }}</td>
                                <td>{{ number_format($qualityLoss / $theoreticalMax * 100, 1) }}%</td>
                            </tr>
                            <tr class="table-success font-weight-bold">
                                <td>Sản lượng chính phẩm thực tế</td>
                                <td>{{ number_format($actualGood, 1) }}</td>
                                <td>{{ number_format($actualGood / $theoreticalMax * 100, 1) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @else
        <div class="alert alert-info">
            Vui lòng chọn máy để xem báo cáo
        </div>
        @endif
    </div>
</div>

@if(($monthlyOEE || $monthlyOEEByDesign) && $selectedMachine)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(function() {
    // Hiển thị/ẩn tham số bổ sung cho phương pháp tính theo công thức tháng
    $('#method').change(function() {
        if ($(this).val() == 'design') {
            $('#designParams').show();
        } else {
            $('#designParams').hide();
        }
    });

    @if($method == 'product' && $monthlyOEE)
    // Biểu đồ tròn OEE tháng
    $("#oeeMonthlyPieChart").dxPieChart({
        dataSource: [{
            name: 'OEE',
            value: {{ $monthlyOEE['monthly']['oee'] * 100 }}
        }, {
            name: 'Tổn thất',
            value: {{ 100 - ($monthlyOEE['monthly']['oee'] * 100) }}
        }],
        series: [{
            argumentField: 'name',
            valueField: 'value',
            label: {
                visible: true,
                connector: {
                    visible: true
                },
                format: {
                    type: 'fixedPoint',
                    precision: 1
                },
                customizeText: function(e) {
                    return e.argumentText + ': ' + e.valueText + '%';
                }
            }
        }],
        palette: ['#36a2eb', '#ff6384'],
        title: 'OEE Tháng'
    });

    // Biểu đồ xu hướng OEE
    $("#oeeTrendChart").dxChart({
        dataSource: [
            @foreach($monthlyOEE['daily'] as $date => $oee)
            {
                date: '{{ Carbon\Carbon::parse($date)->format('d/m') }}',
                availability: {{ $oee['availability'] * 100 }},
                performance: {{ $oee['performance'] * 100 }},
                quality: {{ $oee['quality'] * 100 }},
                oee: {{ $oee['oee'] * 100 }}
            },
            @endforeach
        ],
        commonSeriesSettings: {
            argumentField: 'date',
            type: 'line'
        },
        series: [
            { valueField: 'oee', name: 'OEE', color: '#9966ff' },
            { valueField: 'availability', name: 'Availability', color: '#36a2eb' },
            { valueField: 'performance', name: 'Performance', color: '#ffce56' },
            { valueField: 'quality', name: 'Quality', color: '#4bc0c0' }
        ],
        legend: {
            verticalAlignment: 'bottom',
            horizontalAlignment: 'center'
        },
        valueAxis: {
            title: {
                text: 'Phần trăm (%)'
            },
            visualRange: {
                startValue: 0,
                endValue: 100
            }
        },
        argumentAxis: {
            title: {
                text: 'Ngày trong tháng'
            }
        },
        tooltip: {
            enabled: true,
            customizeTooltip: function(arg) {
                return {
                    text: arg.seriesName + ': ' + arg.valueText + '%'
                };
            }
        }
    });

    // Biểu đồ thành phần OEE
    $("#oeeComponentChart").dxChart({
        dataSource: [{
            component: 'Availability',
            value: {{ $monthlyOEE['monthly']['availability'] * 100 }}
        }, {
            component: 'Performance',
            value: {{ $monthlyOEE['monthly']['performance'] * 100 }}
        }, {
            component: 'Quality',
            value: {{ $monthlyOEE['monthly']['quality'] * 100 }}
        }, {
            component: 'OEE',
            value: {{ $monthlyOEE['monthly']['oee'] * 100 }}
        }],
        series: {
            argumentField: 'component',
            valueField: 'value',
            type: 'bar',
            color: '#36a2eb'
        },
        valueAxis: {
            title: {
                text: 'Phần trăm (%)'
            },
            visualRange: {
                startValue: 0,
                endValue: 100
            }
        },
        argumentAxis: {
            title: {
                text: 'Thành phần'
            }
        },
        tooltip: {
            enabled: true,
            customizeTooltip: function(arg) {
                return {
                    text: arg.argumentText + ': ' + arg.valueText + '%'
                };
            }
        }
    });

    @elseif($method == 'design' && $monthlyOEEByDesign)
    // Biểu đồ OEE (phương pháp 2)
    new Chart(document.getElementById('oeeSummaryChart'), {
        type: 'bar',
        data: {
            labels: ['OEE và các thành phần'],
            datasets: [
                {
                    label: 'Availability',
                    data: [{{ $monthlyOEEByDesign['availability'] * 100 }}],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                    label: 'Performance',
                    data: [{{ $monthlyOEEByDesign['performance'] * 100 }}],
                    backgroundColor: 'rgba(255, 206, 86, 0.7)'
                },
                {
                    label: 'Quality',
                    data: [{{ $monthlyOEEByDesign['quality'] * 100 }}],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)'
                },
                {
                    label: 'OEE',
                    data: [{{ $monthlyOEEByDesign['oee'] * 100 }}],
                    backgroundColor: 'rgba(153, 102, 255, 0.7)'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Phần trăm (%)'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%';
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ phân tích tổn thất
    @php
        $theoreticalMax = $monthlyOEEByDesign['details']['planned_time'] / 60 * $monthlyOEEByDesign['details']['design_capacity'];
        $availabilityLoss = $theoreticalMax * (1 - $monthlyOEEByDesign['availability']);
        $performanceLoss = $theoreticalMax * $monthlyOEEByDesign['availability'] * (1 - $monthlyOEEByDesign['performance']);
        $qualityLoss = $theoreticalMax * $monthlyOEEByDesign['availability'] * $monthlyOEEByDesign['performance'] * (1 - $monthlyOEEByDesign['quality']);
        $actualGood = $monthlyOEEByDesign['details']['total_good_products_kg'];
    @endphp

    new Chart(document.getElementById('oeeLossChart'), {
        type: 'pie',
        data: {
            labels: ['Chính phẩm', 'Tổn thất Availability', 'Tổn thất Performance', 'Tổn thất Quality'],
            datasets: [{
                data: [
                    {{ $actualGood }},
                    {{ $availabilityLoss }},
                    {{ $performanceLoss }},
                    {{ $qualityLoss }}
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(54, 162, 235, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value.toFixed(1) + ' kg (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>

@endif
@stop

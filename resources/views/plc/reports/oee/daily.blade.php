@extends('plc.layouts.master')
@section('content')
<div class="card">
   <div class="card-header">
       <h3 class="card-title">Báo cáo OEE theo ngày</h3>
       <div class="card-tools">
           <a href="{{ route('plc.reports.oee.export') }}?type=daily&{{ request()->getQueryString() }}"
              class="btn btn-success btn-sm">
               <i class="fas fa-download"></i> Xuất Excel
           </a>
       </div>
   </div>

   <div class="card-body">
       <!-- Filter Form -->
       <form action="{{ route('plc.reports.oee.daily') }}" method="GET" class="mb-4">
           <div class="row">
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Từ ngày</label>
                       <input type="date" name="from_date" class="form-control"
                              value="{{ request('from_date', now()->subDays(7)->toDateString()) }}">
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Đến ngày</label>
                       <input type="date" name="to_date" class="form-control"
                              value="{{ request('to_date', now()->toDateString()) }}">
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
               <div class="col-md-3 d-flex align-items-end">
                   <button type="submit" class="btn btn-primary">
                       <i class="fas fa-search"></i> Xem báo cáo
                   </button>
               </div>
           </div>
       </form>

       @if($selectedMachine)
       <h4>Báo cáo OEE {{ $selectedMachine->name }}</h4>

       @if($dailyOEE)
       <!-- Line Chart -->
       <div class="row mb-4">
           <div class="col-12">
               <div class="card">
                   <div class="card-header">
                       <h5 class="card-title">Biểu đồ OEE theo ngày</h5>
                   </div>
                   <div class="card-body">
                       <div id="oeeLineChart" style="height: 400px;"></div>
                   </div>
               </div>
           </div>
       </div>

       <!-- Hiển thị kết quả OEE theo từng ngày -->
       @foreach($dailyOEE as $date => $data)
       <div class="card mb-4">
           <div class="card-header">
               <h5 class="card-title">
                   Báo cáo OEE ngày {{ Carbon\Carbon::parse($date)->format('d/m/Y') }} - {{ $selectedMachine->name }}
               </h5>
           </div>
           <div class="card-body">
               <div class="table-responsive">
                   <table class="table table-bordered">
                       <thead>
                           <tr>
                               <th>Ca</th>
                               <th>Availability (%)</th>
                               <th>Performance (%)</th>
                               <th>Quality (%)</th>
                               <th>OEE (%)</th>
                           </tr>
                       </thead>
                       <tbody>
                           @foreach(['CA1', 'CA2', 'CA3'] as $shift)
                           <tr>
                               <td>{{ $shift }}</td>
                               <td class="text-right">{{ isset($data['shifts'][$shift]) ? number_format($data['shifts'][$shift]['availability'] * 100, 1) : '0.0' }}</td>
                               <td class="text-right">{{ isset($data['shifts'][$shift]) ? number_format($data['shifts'][$shift]['performance'] * 100, 1) : '0.0' }}</td>
                               <td class="text-right">{{ isset($data['shifts'][$shift]) ? number_format($data['shifts'][$shift]['quality'] * 100, 1) : '0.0' }}</td>
                               <td class="text-right">{{ isset($data['shifts'][$shift]) ? number_format($data['shifts'][$shift]['oee'] * 100, 1) : '0.0' }}</td>
                           </tr>
                           @endforeach
                           <tr class="font-weight-bold bg-light">
                               <td>Trung bình ngày</td>
                               <td class="text-right">{{ number_format($data['daily']['availability'] * 100, 1) }}</td>
                               <td class="text-right">{{ number_format($data['daily']['performance'] * 100, 1) }}</td>
                               <td class="text-right">{{ number_format($data['daily']['quality'] * 100, 1) }}</td>
                               <td class="text-right">{{ number_format($data['daily']['oee'] * 100, 1) }}</td>
                           </tr>
                       </tbody>
                   </table>
               </div>

               <!-- Chi tiết ngày -->
               <div class="row mt-3">
                   <div class="col-md-6">
                       <table class="table table-bordered">
                           <tbody>
                               <tr>
                                   <th>Số ca có dữ liệu</th>
                                   <td>{{ $data['details']['valid_shifts'] }}</td>
                               </tr>
                               <tr>
                                   <th>Tổng thời gian chạy</th>
                                   <td>{{ number_format($data['details']['total_run_time_minutes'] / 60, 1) }} Giờ</td>
                               </tr>
                               <tr>
                                   <th>Chính phẩm</th>
                                   <td>{{ number_format($data['details']['total_good_products_kg'], 1) }} kg</td>
                               </tr>
                               <tr>
                                   <th>Phế phẩm</th>
                                   <td>{{ number_format($data['details']['total_defect_products_kg'], 1) }} kg</td>
                               </tr>
                               <tr>
                                   <th>Tổng sản phẩm</th>
                                   <td>{{ number_format($data['details']['total_products_kg'], 1) }} kg</td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
               </div>
           </div>
       </div>
       @endforeach

       @else
       <div class="alert alert-info">
           Không có dữ liệu OEE cho khoảng thời gian đã chọn
       </div>
       @endif

       @else
       <div class="alert alert-info">
           Vui lòng chọn máy để xem báo cáo
       </div>
       @endif
   </div>
</div>

@if($selectedMachine && $dailyOEE)
<script>
$(function() {
    // Chuẩn bị dữ liệu cho biểu đồ
    var chartData = [];
    @foreach($dailyOEE as $date => $data)
    chartData.push({
        date: "{{ Carbon\Carbon::parse($date)->format('d/m/Y') }}",
        availability: {{ $data['daily']['availability'] * 100 }},
        performance: {{ $data['daily']['performance'] * 100 }},
        quality: {{ $data['daily']['quality'] * 100 }},
        oee: {{ $data['daily']['oee'] * 100 }}
    });
    @endforeach

    // Khởi tạo biểu đồ
    $("#oeeLineChart").dxChart({
        dataSource: chartData,
        commonSeriesSettings: {
            type: "line",
            argumentField: "date"
        },
        series: [
            { valueField: "availability", name: "Availability", color: "#36a2eb" },
            { valueField: "performance", name: "Performance", color: "#ffce56" },
            { valueField: "quality", name: "Quality", color: "#4bc0c0" },
            { valueField: "oee", name: "OEE", color: "#9966ff" }
        ],
        argumentAxis: {
            label: {
                wordWrap: "none",
                rotationAngle: -45
            }
        },
        valueAxis: {
            visualRange: {
                startValue: 0,
                endValue: 100
            },
            title: {
                text: "Phần trăm (%)"
            }
        },
        legend: {
            verticalAlignment: "bottom",
            horizontalAlignment: "center"
        },
        tooltip: {
            enabled: true,
            customizeTooltip: function(arg) {
                return {
                    text: arg.seriesName + ": " + arg.valueText.toFixed(1) + "%"
                };
            }
        }
    });
});
</script>
@endif
@stop
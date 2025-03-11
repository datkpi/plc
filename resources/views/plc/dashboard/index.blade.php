{{-- views/plc/dashboard/index.blade.php --}}
@extends('plc.layouts.master')

@section('content')
<div class="row">
    <!-- Stats boxes -->
    <div class="col-lg-3 col-6">
        <div id="machineCountWidget"></div>
    </div>
    <!-- Other stat boxes... -->
</div>

<div class="row">
    <!-- Machine Status -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tình trạng máy</h3>
            </div>
            <div class="card-body">
                <div id="machineStatusGrid"></div>
            </div>
        </div>
    </div>

    <!-- Top Products Chart -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Top 5 sản phẩm (tuần này)</h3>
            </div>
            <div class="card-body">
                <div id="topProductsChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Production Trend -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sản lượng 7 ngày gần nhất</h3>
            </div>
            <div class="card-body">
                <div id="productionTrendChart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn3.devexpress.com/jslib/23.1.3/js/dx.all.js"></script>
<script>
$(function() {
    // Stats Widget
    $('#machineCountWidget').dxCircularGauge({
        value: {{ $stats['total_machines'] }},
        valueIndicator: {
            type: 'rectangleNeedle',
            color: '#9B870C'
        },
        scale: {
            startValue: 0,
            endValue: 20,
            tickInterval: 2
        },
        title: {
            text: 'Máy đang hoạt động',
            font: { size: 16 }
        }
    });

    // Machine Status Grid
    $('#machineStatusGrid').dxDataGrid({
        dataSource: DevExpress.data.createDataSource({
            loadUrl: '{{ route('plc.api.dashboard.machine-status') }}',
            key: 'id'
        }),
        columns: [{
            dataField: 'name',
            caption: 'Máy'
        }, {
            dataField: 'status',
            caption: 'Trạng thái',
            cellTemplate: function(container, options) {
                $('<div>')
                    .addClass(options.value ? 'badge badge-success' : 'badge badge-secondary')
                    .text(options.value ? 'Đang chạy' : 'Dừng')
                    .appendTo(container);
            }
        }, {
            dataField: 'product_code',
            caption: 'Sản phẩm'
        }, {
            dataField: 'speed',
            caption: 'Tốc độ',
            format: {
                type: 'fixedPoint',
                precision: 1
            }
        }],
        showBorders: true,
        filterRow: { visible: true },
        headerFilter: { visible: true },
        searchPanel: { visible: true },
        scrolling: { mode: 'virtual' }
    });

    // Top Products Chart
    $('#topProductsChart').dxChart({
        dataSource: DevExpress.data.createDataSource({
            loadUrl: '{{ route('plc.api.dashboard.top-products') }}'
        }),
        series: {
            argumentField: 'name',
            valueField: 'quantity',
            name: 'Sản lượng',
            type: 'bar',
            color: '#6b71c3'
        },
        title: 'Top 5 sản phẩm',
        legend: {
            visible: false
        }
    });

    // Production Trend Chart
    $('#productionTrendChart').dxChart({
        dataSource: DevExpress.data.createDataSource({
            loadUrl: '{{ route('plc.api.dashboard.production-trend') }}'
        }),
        series: {
            argumentField: 'date',
            valueField: 'quantity',
            name: 'Sản lượng',
            type: 'line'
        },
        argumentAxis: {
            argumentType: 'datetime',
            tickInterval: { days: 1 },
            label: {
                format: 'dd/MM'
            }
        },
        legend: {
            visible: false
        },
        tooltip: {
            enabled: true,
            format: {
                type: 'fixedPoint',
                precision: 0
            }
        }
    });
});
</script>

@stop

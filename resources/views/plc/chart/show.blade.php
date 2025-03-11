@extends('plc.layouts.master')
@section('content')

<style>
.custom-tooltip {
    padding: 8px;
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    font-size: 12px;
}
.form-control:disabled {
    background-color: #e9ecef;
}
.chart-error {
    padding: 20px;
    background-color: #f8d7da;
    color: #721c24;
    border-radius: 4px;
    margin-bottom: 20px;
}
.color-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 8px;
}
.series-list {
    max-height: 400px;
    overflow-y: auto;
}
</style>

<div class="card">
    <div class="card-header">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h5 class="card-title mb-0">Biểu đồ máy #{{ $machine->name }}</h5>
                <small class="text-muted">Cập nhật lần cuối: <span id="lastUpdate">-</span></small>
            </div>
        </div>

        <div class="row align-items-center g-3">
            <!-- Chế độ Realtime -->
            <div class="col-auto">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="realtimeSwitch" checked>
                    <label class="form-check-label">Realtime</label>
                </div>
            </div>

            <!-- Thời gian bắt đầu -->
            <div class="col-md-2">
                <input type="datetime-local" class="form-control form-control-sm" id="startTime"
                       value="{{ now()->subMinutes(30)->format('Y-m-d\TH:i') }}"
                       max="{{ now()->format('Y-m-d\TH:i') }}"
                       disabled>
            </div>

            <!-- Đến -->
            <div class="col-auto pt-1">
                <span>đến</span>
            </div>

            <!-- Thời gian kết thúc -->
            <div class="col-md-2">
                <input type="datetime-local" class="form-control form-control-sm" id="endTime"
                       value="{{ now()->format('Y-m-d\TH:i') }}"
                       max="{{ now()->format('Y-m-d\TH:i') }}"
                       disabled>
            </div>

            <!-- Nút lọc -->
            <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm" id="filterBtn" disabled>
                    <i class="fas fa-filter"></i> Lọc
                </button>
            </div>

            <!-- Sản phẩm -->
            <div class="col-md-2">
                <select id="productFilter" class="form-select form-select-sm">
                    <option value="">Tất cả sản phẩm</option>
                </select>
            </div>

            <!-- Auto scroll -->
            <div class="col-auto ms-auto">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" id="autoScrollSwitch" checked>
                    <label class="form-check-label">Tự động cuộn</label>
                </div>
            </div>

            <!-- Thêm vào sau phần filter sản phẩm -->
            <div class="col-md-auto">
                <button type="button" class="btn btn-outline-secondary btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#seriesFilterModal">
                    <i class="fas fa-eye"></i> Hiển thị đường
                </button>
            </div>

            <!-- Thêm vào sau nút "Hiển thị đường" -->
            <div class="col-md-3">
                <select id="tagSelector" class="form-select form-select-sm">
                    <option value="">-- Chọn tag để thêm --</option>
                    @foreach($availableTags['parameter'] as $tag)
                        <option value="{{ $tag['key'] }}"
                                data-type="parameter"
                                data-label="{{ $tag['label'] }}">
                            {{ $tag['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card-body p-0" style="position: relative;">
        <!-- Thông báo lỗi -->
        <div id="chartError" class="chart-error d-none">
            <strong>Lỗi: </strong><span id="errorMessage"></span>
        </div>

        <!-- Loading overlay -->
        <div id="chartLoading" class="position-absolute w-100 h-100 d-none" style="z-index: 10;">
            <div class="w-100 h-100 bg-white opacity-75 d-flex align-items-center justify-content-center">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>

        <!-- Chart container -->
        <div id="plcChart" style="height: 600px;"></div>
    </div>
</div>

<!-- Thêm modal filter vào cuối file, trước phần script -->
<div class="modal fade" id="seriesFilterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-dialog-scrollable modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lựa chọn đường hiển thị</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllSeries">
                            Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllSeries">
                            Bỏ chọn tất cả
                        </button>
                    </div>
                </div>
                <div class="series-list">
                    @foreach($series as $s)
                    <div class="form-check mb-2">
                        <input type="checkbox" 
                               class="form-check-input series-checkbox" 
                               id="series_{{ $s['valueField'] }}"
                               value="{{ $s['valueField'] }}" 
                               checked>
                        <label class="form-check-label" for="series_{{ $s['valueField'] }}">
                            <span class="color-dot" style="background-color: {{ $s['color'] }}"></span>
                            {{ $s['name'] }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="applySeriesFilter">
                    Áp dụng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // Debug mode - set to true during development
    const DEBUG = true;

    // Hàm ghi log debug
    function debugLog(...args) {
        if (DEBUG) {
            console.log(...args);
        }
    }

    // Hàm hiển thị lỗi
    function showError(message) {
        $('#errorMessage').text(message);
        $('#chartError').removeClass('d-none');
        $('#chartLoading').addClass('d-none');
    }

    // Hàm ẩn lỗi
    function hideError() {
        $('#chartError').addClass('d-none');
    }

    // Kiểm tra DevExtreme đã được load
    // if (typeof DevExpress === 'undefined' || !DevExpress.ui || !DevExpress.ui.dxChart) {
    //     showError("Thư viện biểu đồ DevExtreme không được tải. Vui lòng làm mới trang.");
    //     return;
    // }

    // Global variables
    let refreshInterval;
    let lastUpdate = null;
    let autoScroll = $('#autoScrollSwitch').is(':checked');
    const MAX_POINTS = 1000; // Giới hạn số điểm để tránh quá tải
    const REFRESH_RATE = 5000; // 5 giây làm mới một lần

    // Thêm vào phần đầu script, sau phần khai báo biến
    let dynamicSeriesColors = [
        '#FF9800', '#2196F3', '#4CAF50', '#E91E63', '#9C27B0',
        '#00BCD4', '#FFC107', '#795548', '#607D8B', '#3F51B5'
    ];
    let colorIndex = 0;

    // Hàm lấy màu cho series mới
    function getNextColor() {
        let color = dynamicSeriesColors[colorIndex];
        colorIndex = (colorIndex + 1) % dynamicSeriesColors.length;
        return color;
    }

    // Thêm biến để lưu trữ các trường đã chọn
    let selectedFields = [];

    try {
        // Lấy cấu hình series từ server
        const seriesConfig = {!! json_encode($series) !!} || [];
        debugLog("Series config:", seriesConfig);

        if (seriesConfig.length === 0) {
            showError("Không có dữ liệu cấu hình để hiển thị. Vui lòng kiểm tra cài đặt ngưỡng của máy.");
            return;
        }

        let visibleSeries = new Set(seriesConfig.map(s => s.valueField));

        // Khởi tạo chart với cấu hình cơ bản trước
        const chart = $("#plcChart").dxChart({
            dataSource: [],
            commonSeriesSettings: {
                argumentField: 'timestamp',
                type: 'spline',
                point: {
                    visible: true,
                    size: 6,
                    symbol: 'circle',
                    hoverMode: 'onlyPoint'
                }
            },
            valueAxis: {
                label: {
                    customizeText: function(e) {
                        return e.value.toFixed(2);
                    }
                }
            },
            argumentAxis: {
                argumentType: "datetime",
                label: {
                    format: function(value) {
                        const isRealtime = $('#realtimeSwitch').is(':checked');
                        if (!isRealtime) {
                            return moment(value).format('DD/MM HH:mm');
                        }
                        return moment(value).format('HH:mm:ss');
                    }
                }
            },
            tooltip: {
                enabled: true,
                location: 'point',
                customizeTooltip: function(pointInfo) {
                    if (!pointInfo || !pointInfo.point || !pointInfo.series) return null;

                    const timeStr = moment(pointInfo.argument).format('DD/MM/YYYY HH:mm:ss');
                    let warningHTML = '';

                    if (pointInfo.point.data.hasWarning) {
                        const warnings = pointInfo.point.data.warnings || [];
                        const warning = warnings.find(w => w.key === pointInfo.series.valueField);

                        if (warning) {
                            // Thêm thông tin min và max nếu có
                            let thresholdInfo = '';
                            if (warning.min_value !== undefined && warning.min_value !== null) {
                                thresholdInfo += `<strong>Ngưỡng tối thiểu:</strong> ${warning.min_value}<br/>`;
                            }
                            if (warning.max_value !== undefined && warning.max_value !== null) {
                                thresholdInfo += `<strong>Ngưỡng tối đa:</strong> ${warning.max_value}<br/>`;
                            }

                            warningHTML = `
                                <div style="margin-top: 5px; padding: 5px; background: rgba(255,0,0,0.1); border-left: 3px solid red;">
                                    <span style="color: red;">⚠️ Cảnh báo:</span><br/>
                                    <strong>Giá trị hiện tại:</strong> ${warning.value}<br/>
                                    ${thresholdInfo}
                                    <strong>Thông báo:</strong> ${warning.message || 'Vượt ngưỡng cho phép'}
                                </div>
                            `;
                        }
                    }

                    return {
                        html: `
                            <div class="custom-tooltip">
                                <div style="font-weight: bold">${timeStr}</div>
                                <div>${pointInfo.series.name}: ${pointInfo.value?.toFixed(2) || 'N/A'}</div>
                                ${warningHTML}
                            </div>
                        `
                    };
                }
            },
            customizePoint: function(point) {
                if (point.data.hasWarning && point.series) {
                    const warnings = point.data.warnings || [];
                    const warning = warnings.find(w =>
                        w.key === point.series.name ||
                        w.key === point.series.valueField
                    );

                    if (warning) {
                        return {
                            color: '#FF0000',
                            size: 8,
                            visible: true,
                            border: {
                                visible: true,
                                width: 1,
                                color: '#DC0000'
                            }
                        };
                    }
                }
                return null;
            },
            legend: {
                visible: true,
                horizontalAlignment: "center",
                verticalAlignment: "bottom"
            },
            onLegendClick: function (e) {
                var series = e.target;
                if (series.isVisible()) {
                    series.hide();
                } else {
                    series.show();
                }
            },
            zoomAndPan: {
                argumentAxis: "both",
                valueAxis: "both",
                dragToZoom: true,
                allowMouseWheel: true,
                panKey: 'shift'
            },
            scrollBar: {
                visible: true,
            },
            export: {
                enabled: true,
                printingEnabled: true,
                formats: ['PNG', 'PDF', 'JPEG', 'SVG', 'XLSX'],
                fileName: 'chart_export',
                margin: 10,
                backgroundColor: '#ffffff'
            },
            crosshair: {
                enabled: true,
                color: "#949494",
                width: 1,
                dashStyle: "dot",
                label: {
                    visible: true
                }
            },
            series: seriesConfig.map(s => ({
                ...s,
                visible: visibleSeries.has(s.valueField)
            })),
            onDrawn: function() {
                // Biểu đồ đã vẽ xong, ẩn loading nếu có
                $('#chartLoading').addClass('d-none');
            }
        }).dxChart("instance");

        // Load danh sách sản phẩm
        function loadProducts() {
            debugLog("Đang tải danh sách sản phẩm...");
            $.ajax({
                url: '{{ route("plc.chart.products", $machineId) }}',
                type: 'GET',
                success: function(res) {
                    if (res.success && res.products) {
                        const select = $('#productFilter');
                        select.empty().append('<option value="">Tất cả sản phẩm</option>');

                        res.products.forEach(product => {
                            select.append(new Option(product, product));
                        });
                        debugLog("Đã tải xong danh sách sản phẩm:", res.products.length);
                    } else {
                        debugLog("Không tìm thấy sản phẩm nào:", res);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải danh sách sản phẩm:", error);
                }
            });
        }

        // Load dữ liệu chart
        function loadChartData() {
            hideError();
            const isRealtime = $('#realtimeSwitch').is(':checked');

            if (!isRealtime && refreshInterval) {
                stopRealtimeUpdate();
            }

            const params = {
                realtime: isRealtime,
                product: $('#productFilter').val() || '',
                fields: selectedFields
            };

            // Xử lý thời gian
            if (isRealtime) {
                const currentData = chart.option("dataSource") || [];
                if (currentData.length > 0) {
                    // Nếu có dữ liệu, lấy từ điểm cuối cùng
                    const lastPoint = currentData[currentData.length - 1];
                    params.last_timestamp = moment(lastPoint.timestamp).format('YYYY-MM-DD HH:mm:ss');
                } else {
                    // Nếu chưa có dữ liệu, lấy 30 phút gần nhất
                    params.start_time = moment().subtract(30, 'minutes').format('YYYY-MM-DD HH:mm:ss');
                    params.end_time = moment().format('YYYY-MM-DD HH:mm:ss');
                }
            } else {
                // Chế độ lọc theo thời gian
                params.start_time = moment($('#startTime').val()).format('YYYY-MM-DD HH:mm:ss');
                params.end_time = moment($('#endTime').val()).format('YYYY-MM-DD HH:mm:ss');

                if (!params.start_time || !params.end_time) {
                    showError("Vui lòng chọn thời gian bắt đầu và kết thúc.");
                    return;
                }

                if (moment(params.start_time).isAfter(params.end_time)) {
                    showError("Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc.");
                    return;
                }
            }

            // Hiển thị loading
            if (!isRealtime || chart.option("dataSource")?.length === 0) {
                $('#chartLoading').removeClass('d-none');
            }

            debugLog("Đang tải dữ liệu chart với params:", params);

            $.ajax({
                url: '{{ route("plc.chart.data", $machineId) }}',
                data: params,
                type: 'GET',
                success: function(res) {
                    if (res.success && res.data) {
                        debugLog(`Nhận được ${res.data.length} điểm dữ liệu`);

                        // Chuyển đổi timestamp sang đối tượng Date
                        const newData = res.data.map(item => ({
                            ...item,
                            timestamp: new Date(item.timestamp.replace(' ', 'T'))
                        }));

                        if (isRealtime) {
                            let currentData = chart.option("dataSource") || [];

                            if (newData.length > 0) {
                                // Thêm dữ liệu mới vào cuối
                                currentData = [...currentData, ...newData];

                                // Giới hạn số điểm
                                if (currentData.length > MAX_POINTS) {
                                    currentData = currentData.slice(-MAX_POINTS);
                                }

                                chart.beginUpdate();
                                chart.option('dataSource', currentData);

                                // Tự động cuộn nếu được bật
                                if (autoScroll && currentData.length > 1) {
                                    chart.getArgumentAxis().visualRange({
                                        startValue: currentData[Math.max(0, currentData.length - 50)].timestamp,
                                        endValue: currentData[currentData.length - 1].timestamp
                                    });
                                }
                                chart.endUpdate();

                                debugLog(`Đã cập nhật chart với ${currentData.length} điểm dữ liệu`);
                            }
                        } else {
                            // Chế độ lọc: thay thế toàn bộ dữ liệu
                            chart.option('dataSource', newData);

                            if (newData.length > 0) {
                                chart.getArgumentAxis().visualRange({
                                    startValue: newData[0].timestamp,
                                    endValue: newData[newData.length - 1].timestamp
                                });
                                debugLog(`Đã cập nhật chart với ${newData.length} điểm dữ liệu`);
                            } else {
                                showError("Không có dữ liệu trong khoảng thời gian đã chọn");
                            }
                        }

                        lastUpdate = new Date();
                        updateLastUpdateTime();
                    } else {
                        showError("Không nhận được dữ liệu hợp lệ từ server");
                        debugLog("Response không hợp lệ:", res);
                    }
                },
                error: function(xhr, status, error) {
                    showError("Lỗi khi tải dữ liệu: " + error);
                    console.error("Chi tiết lỗi:", xhr);
                },
                complete: function() {
                    $('#chartLoading').addClass('d-none');
                }
            });
        }

        // Cập nhật thời gian
        function updateLastUpdateTime() {
            if (lastUpdate) {
                const timeAgo = moment(lastUpdate).fromNow();
                $("#lastUpdate").html(`<i class="fas fa-clock mr-1"></i> ${timeAgo}`);
            }
        }

        // Realtime functions
        function startRealtimeUpdate() {
            // Tải dữ liệu ngay lập tức
            loadChartData();

            // Sau đó thiết lập interval để cập nhật định kỳ
            if (refreshInterval) clearInterval(refreshInterval);
            refreshInterval = setInterval(loadChartData, REFRESH_RATE);

            debugLog("Đã bắt đầu chế độ realtime, cập nhật mỗi " + (REFRESH_RATE/1000) + " giây");
        }

        function stopRealtimeUpdate() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
                refreshInterval = null;
                debugLog("Đã dừng chế độ realtime");
            }
        }

        // Event handlers
        $('#realtimeSwitch').change(function() {
            const isRealtime = $(this).is(':checked');
            $('#startTime, #endTime, #filterBtn').prop('disabled', isRealtime);

            debugLog("Chế độ realtime: " + isRealtime);

            if (isRealtime) {
                // Khi bật realtime, xóa dữ liệu cũ và bắt đầu cập nhật mới
                chart.option('dataSource', []);
                startRealtimeUpdate();
            } else {
                // Khi tắt realtime, dừng cập nhật tự động
                stopRealtimeUpdate();
                // KHÔNG tự động load dữ liệu - chờ người dùng nhấn nút lọc
            }
        });

        $('#filterBtn').click(function() {
            if (!$('#realtimeSwitch').is(':checked')) {
                // Đảm bảo không bật lại realtime sau khi lọc
                stopRealtimeUpdate();
                // Tải dữ liệu theo bộ lọc
                loadChartData();
            }
        });

        $('#productFilter').change(function() {
            debugLog("Đã chọn sản phẩm: " + $(this).val());
            // Xóa dữ liệu cũ
            chart.option('dataSource', []);
            // Tải lại dữ liệu với sản phẩm mới
            loadChartData();
        });

        $('#autoScrollSwitch').change(function() {
            autoScroll = $(this).is(':checked');
            debugLog("Tự động cuộn: " + autoScroll);
        });

        // Thêm event handler cho các trường ngày tháng
        $('#startTime, #endTime').change(function() {
            debugLog("Ngày đã thay đổi, tắt chế độ realtime");

            // Tắt realtime switch nếu đang bật
            if ($('#realtimeSwitch').is(':checked')) {
                $('#realtimeSwitch').prop('checked', false).trigger('change');
            }
        });

        // Xử lý sự kiện cho các checkbox series
        $('.series-checkbox').change(function() {
            const seriesField = $(this).val();
            const isChecked = $(this).is(':checked');
            
            if (isChecked) {
                visibleSeries.add(seriesField);
            } else {
                visibleSeries.delete(seriesField);
            }

            // Cập nhật hiển thị series
            chart.beginUpdate();
            seriesConfig.forEach(series => {
                if (series.valueField === seriesField) {
                    const seriesInstance = chart.getSeriesByName(series.name);
                    if (seriesInstance) {
                        seriesInstance.option('visible', isChecked);
                    }
                }
            });
            chart.endUpdate();
        });

        // Nút chọn/bỏ chọn tất cả
        $('#selectAllSeries').click(function() {
            $('.series-checkbox').prop('checked', true).trigger('change');
        });

        $('#deselectAllSeries').click(function() {
            $('.series-checkbox').prop('checked', false).trigger('change');
        });

        // Áp dụng filter
        $('#applySeriesFilter').click(function() {
            chart.beginUpdate();
            seriesConfig.forEach(series => {
                const seriesInstance = chart.getSeriesByName(series.name);
                if (seriesInstance) {
                    seriesInstance.option('visible', visibleSeries.has(series.valueField));
                }
            });
            chart.endUpdate();
            
            $('#seriesFilterModal').modal('hide');
        });

        // Thêm vào sau phần khởi tạo chart
        // Xử lý khi chọn tag mới
        $('#tagSelector').change(function() {
            const value = $(this).val();
            if (!value) return;

            const option = $(this).find('option:selected');
            const label = option.data('label');
            
            debugLog("Đã chọn tag:", value, label);

            // Kiểm tra xem series đã tồn tại chưa
            const existingSeries = seriesConfig.find(s => s.valueField === value);
            
            if (!existingSeries) {
                // Thêm series mới
                const newSeries = {
                    name: label,
                    valueField: value,
                    color: getNextColor(),
                    isDefault: false
                };

                // Thêm vào danh sách series
                seriesConfig.push(newSeries);
                visibleSeries.add(value);

                // Thêm vào modal filter
                const checkboxHtml = `
                    <div class="form-check mb-2">
                        <input type="checkbox" 
                               class="form-check-input series-checkbox" 
                               id="series_${value}"
                               value="${value}" 
                               checked>
                        <label class="form-check-label" for="series_${value}">
                            <span class="color-dot" style="background-color: ${newSeries.color}"></span>
                            ${label}
                        </label>
                    </div>
                `;
                $('.series-list').append(checkboxHtml);

                // Cập nhật cấu hình series trong chart
                chart.beginUpdate();
                chart.option('series', seriesConfig.map(s => ({
                    ...s,
                    visible: visibleSeries.has(s.valueField)
                })));
                chart.endUpdate();

                debugLog("Đã thêm series mới:", newSeries);
            }

            // Reset select
            $(this).val('');
        });

        // Initialize
        debugLog("Khởi tạo chart");
        loadProducts();

        // Kiểm tra xem có đang ở chế độ realtime không
        if ($('#realtimeSwitch').is(':checked')) {
            startRealtimeUpdate();
        } else {
            // Chỉ load dữ liệu một lần nếu không phải realtime
            loadChartData();
        }

        setInterval(updateLastUpdateTime, 10000);

        // Cleanup
        $(window).on('unload', function() {
            if (refreshInterval) clearInterval(refreshInterval);
            debugLog("Dọn dẹp interval");
        });

    } catch (e) {
        // Xử lý lỗi
        showError("Lỗi khởi tạo biểu đồ: " + e.message);
        console.error("Chi tiết lỗi:", e);
    }
});
</script>

@stop

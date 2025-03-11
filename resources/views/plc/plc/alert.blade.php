@extends('plc.layouts.master')
@section('content')

<div class="card mb-4">
    <div class="card-header">
        <h5>Danh sách cảnh báo</h5>
    </div>
    <div class="card-body">
        <form id="filter-form" method="GET" class="row g-3">
            <!-- Máy -->
            <div class="col-md-3">
                <label class="form-label">Máy</label>
                <select name="machine_id" class="select2">
                    <option value="">Tất cả</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}">
                            {{ $machine->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Thông số -->
            <div class="col-md-3">
                <label class="form-label">Thông số</label>
                <select name="plc_data_key" class="select2">
                    <option value="">Tất cả</option>
                    <optgroup label="Thông số boolean">
                        @foreach($plcDataKeys['boolean'] as $key)
                            <option value="{{ $key['key'] }}" {{ request('plc_data_key') == $key['key'] ? 'selected' : '' }}>
                                {{ $key['label'] }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Thông số parameter">
                        @foreach($plcDataKeys['parameter'] as $key)
                            <option value="{{ $key['key'] }}" {{ request('plc_data_key') == $key['key'] ? 'selected' : '' }}>
                                {{ $key['label'] }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <!-- Ngày bắt đầu -->
            <div class="col-md-2">
                <label class="form-label">Từ ngày</label>
                <input type="date" name="date_from" class="form-control"
                       value="{{ request('date_from') }}">
            </div>

            <!-- Ngày kết thúc -->
            <div class="col-md-2">
                <label class="form-label">Đến ngày</label>
                <input type="date" name="date_to" class="form-control"
                       value="{{ request('date_to') }}">
            </div>

            <!-- Trạng thái -->
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="select2">
                    <option value="">Tất cả</option>
                    @foreach(\App\Models\PlcAlertSummary::STATUS_LIST as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Người xử lý -->
            <div class="col-md-3">
                <label class="form-label">Người xử lý</label>
                <select name="resolved_by" class="select2">
                    <option value="">Tất cả</option>
                    @foreach($resolvers as $resolver)
                        <option value="{{ $resolver }}" {{ request('resolved_by') == $resolver ? 'selected' : '' }}>
                            {{ $resolver }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Lọc
                    </button>
                    <a href="{{ route('plc.alert.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Đặt lại
                    </a>
                    {{-- @if($summaries->count() > 0)
                    <a href="{{ route('plc.alerts.export', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                    @endif --}}
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Danh sách cảnh báo</h4>
        <span class="text-muted">Tổng số: {{ $summaries->total() }}</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px">STT</th>
                        <th>Lần đầu xuất hiện</th>
                        <th>Lần cuối xuất hiện</th>
                        <th>Máy</th>
                        <th>Thông số</th>
                        <th>Giá trị ban đầu</th>
                        <th style="width: 80px" class="text-center">Số lần</th>
                        <th>Ngưỡng min</th>
                        <th>Ngưỡng max</th>
                        <th style="width: 100px">Mức độ</th>
                        <th>Thông báo</th>
                        <th style="width: 150px">Trạng thái</th>
                        <th>Người xử lý</th>
                        <th>Thời gian xử lý</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($summaries as $index => $summary)
                    <tr>
                        <td class="text-center">
                            {{ $summaries->firstItem() + $index }}
                        </td>
                        <td>{{ $summary->first_occurred_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $summary->last_occurred_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $summary->machine->name }}</td>
                        <td>{{ $summary->plc_data_key }}</td>
                        <td class="text-end">{{ number_format($summary->current_value, 4) }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $summary->occurrence_count }}</span>
                        </td>
                        <td class="text-end">{{ $summary->min_value ? number_format($summary->min_value, 4) : '-' }}</td>
                        <td class="text-end">{{ $summary->max_value ? number_format($summary->max_value, 4) : '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $summary->type === 'danger' ? 'danger' : 'warning' }}">
                                {{ $summary->type === 'danger' ? 'Nghiêm trọng' : 'Cảnh báo' }}
                            </span>
                        </td>
                        <td>{{ $summary->message }}</td>
                        <td>
                            @if($summary->status === 'new')
                            <select class="select2 select2-sm status-select"
                                    data-summary-id="{{ $summary->id }}">
                                <option value="new" selected>Chưa xử lý</option>
                                <option value="resolved">Đã xử lý</option>
                            </select>
                            @else
                            <span class="badge bg-success">Đã xử lý</span>
                            @endif
                        </td>
                        <td>{{ $summary->resolved_by ?? '-' }}</td>
                        <td>{{ $summary->resolved_at ? $summary->resolved_at->format('d/m/Y H:i:s') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('plc.alert.history', $summary->id) }}"
                               class="btn btn-sm btn-info"
                               data-bs-toggle="tooltip"
                               title="Xem lịch sử">
                                <i class="fas fa-history"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="15" class="text-center py-3">
                            <span class="text-muted">Không có dữ liệu</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($summaries->hasPages())
        <div class="mt-4">
            {{ $summaries->links() }}
        </div>
        @endif
    </div>
</div>

<script>
$(document).ready(function() {
    // Khởi tạo tooltips của Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Khởi tạo Select2 cho tất cả select boxes
    $('.select2').select2({
        width: '100%',
        placeholder: 'Chọn...',
        allowClear: true,
        language: {
            noResults: function() {
                return "Không tìm thấy kết quả";
            }
        }
    });

    // Select2 cho status select (select nhỏ hơn trong bảng)
    $('.select2-sm').select2({
        width: '100%',
        minimumResultsForSearch: -1, // Disable search
        dropdownCssClass: 'select2-dropdown-sm', // Custom class cho dropdown
        selectionCssClass: 'select2-selection-sm' // Custom class cho selection
    });

    // Xử lý khi thay đổi trạng thái cảnh báo
    $('.status-select').change(function() {
        const summaryId = $(this).data('summary-id');
        const newStatus = $(this).val();
        const select = $(this);
        const row = select.closest('tr');

        if (newStatus === 'resolved') {
            Swal.fire({
                title: 'Xác nhận xử lý',
                text: 'Bạn có chắc chắn muốn đánh dấu cảnh báo này đã được xử lý?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Đồng ý',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Đang xử lý...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Gọi API update status
                    $.ajax({
                        url: `/plc/alert/${summaryId}/status`,
                        type: 'post',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Thay thế select box bằng badge
                            select.parent().html('<span class="badge bg-success">Đã xử lý</span>');

                            // Cập nhật thông tin người xử lý và thời gian
                            row.find('td:nth-last-child(3)').text(response.resolved_by);
                            row.find('td:nth-last-child(2)').text(response.resolved_at);

                            // Hiển thị thông báo thành công
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: 'Đã cập nhật trạng thái cảnh báo',
                                timer: 1500
                            });
                        },
                        error: function(xhr) {
                            // Revert selection
                            select.val('new').trigger('change');

                            // Hiển thị lỗi
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có lỗi xảy ra khi cập nhật trạng thái',
                            });
                        }
                    });
                } else {
                    // Nếu cancel thì revert lại giá trị cũ
                    select.val('new').trigger('change');
                }
            });
        }
    });

    // Xử lý form filter
    $('#filter-form').submit(function() {
        // Disable nút submit để tránh duplicate
        $(this).find('button[type="submit"]').prop('disabled', true);

        // Show loading indicator nếu cần
        Swal.fire({
            title: 'Đang tải...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit form bình thường
        return true;
    });

    // Xử lý nút export
    $('.btn-export').click(function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Đang tạo file...',
            text: 'Vui lòng đợi trong giây lát',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Redirect to export URL
        window.location.href = $(this).attr('href');
    });
});
</script>


<style>
    /* .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
    } */
    .table th {
        white-space: nowrap;
    }
    .select2 {
        width: 100%;
    }
</style>

@stop

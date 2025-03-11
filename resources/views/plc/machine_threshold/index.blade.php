@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cài đặt cảnh báo - {{ $machine->name }}</h3>
        <a href="{{ route('plc.machine.thresholds.create', $machine->id) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm cảnh báo
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Tag PLC</th>
                        <th>Tên hiển thị</th>
                        <th>Loại</th>
                        <th>Cấu hình</th>
                        <th>Nội dung cảnh báo</th>
                        <th>Màu</th>
                        <th>Biểu đồ</th>
                        <th>Trạng thái</th>
                        <th width="100">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($thresholds as $threshold)
                    <tr>
                        <td>
                            @php
                                $label = collect($availableColumns['boolean'])->firstWhere('key', $threshold->plc_data_key)['label']
                                    ?? collect($availableColumns['parameter'])->firstWhere('key', $threshold->plc_data_key)['label']
                                    ?? $threshold->plc_data_key;
                            @endphp
                            {{ $label }}
                        </td>
                        <td>{{ $threshold->name }}</td>
                        <td>
                            @if($threshold->type == 'boolean')
                                <span class="badge bg-info">Boolean</span>
                            @elseif($threshold->type == 'range')
                                <span class="badge bg-primary">Min-Max</span>
                            @elseif($threshold->type == 'avg')
                                <span class="badge bg-success">Trung bình 10p</span>
                            @else
                                <span class="badge bg-warning">% Dao động</span>
                            @endif
                        </td>
                        <td>
                            @if($threshold->type == 'boolean')
                                Cảnh báo khi {{ $threshold->boolean_value ? 'TRUE (ON)' : 'FALSE (OFF)' }}
                            @elseif($threshold->type == 'range')
                                @if($threshold->min_value)
                                    <div>Min: {{ number_format($threshold->min_value, 2) }}</div>
                                @endif
                                @if($threshold->max_value)
                                    <div>Max: {{ number_format($threshold->max_value, 2) }}</div>
                                @endif
                            @elseif($threshold->type == 'avg')
                                <div>Trung bình 10 phút</div>
                                <div>Dao động: ±{{ $threshold->percent }}%</div>
                            @else
                                <div>Giá trị gốc: {{ number_format($threshold->base_value, 2) }}</div>
                                <div>Dao động: ±{{ $threshold->percent }}%</div>
                            @endif
                        </td>
                        <td>
                            @if($threshold->type == 'boolean')
                                {{ $threshold->warning_message }}
                            @elseif($threshold->type == 'range')
                                @if($threshold->min_value)
                                    <div>Cảnh báo khi dưới {{ number_format($threshold->min_value, 2) }}</div>
                                @endif
                                @if($threshold->max_value)
                                    <div>Cảnh báo khi trên {{ number_format($threshold->max_value, 2) }}</div>
                                @endif
                            @elseif($threshold->type == 'avg')
                                Cảnh báo khi dao động > {{ $threshold->percent }}% so với trung bình 10 phút
                            @else
                                Cảnh báo khi dao động > {{ $threshold->percent }}% so với {{ number_format($threshold->base_value, 2) }}
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="color-box mr-2" style="width:20px;height:20px;background:{{ $threshold->color }}"></div>
                                <span>{{ $threshold->color }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-chart"
                                       id="chart_{{ $threshold->id }}"
                                       data-id="{{ $threshold->id }}"
                                       {{ $threshold->show_on_chart ? 'checked' : '' }}>
                                <label class="custom-control-label" for="chart_{{ $threshold->id }}"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-status"
                                       id="status_{{ $threshold->id }}"
                                       data-id="{{ $threshold->id }}"
                                       {{ $threshold->status ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status_{{ $threshold->id }}"></label>
                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.machine.thresholds.edit', $threshold->id) }}"
                               class="btn btn-sm btn-info" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                    data-id="{{ $threshold->id }}" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Chưa có cảnh báo nào được cấu hình</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.color-box {
    border: 1px solid #dee2e6;
    border-radius: 4px;
}
</style>

<script>
$(function() {
    // Toggle chart
    $('.toggle-chart').change(function() {
        const id = $(this).data('id');
        const checkbox = $(this);

        $.ajax({
            url: `/plc/machine/thresholds/${id}/toggle-chart`,
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                if (!res.success) {
                    toastr.error('Có lỗi xảy ra!');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra!');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // Toggle status
    $('.toggle-status').change(function() {
        const id = $(this).data('id');
        const checkbox = $(this);

        $.ajax({
            url: `/plc/machine/thresholds/${id}/toggle-status`,
            type: 'POST',
            data: {_token: '{{ csrf_token() }}'},
            success: function(res) {
                if (!res.success) {
                    toastr.error('Có lỗi xảy ra!');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            },
            error: function() {
                toastr.error('Có lỗi xảy ra!');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // Delete confirm with SweetAlert2
    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        const form = $('#delete-form');

        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc muốn xóa cảnh báo này?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                form.attr('action', `/plc/machine/thresholds/${id}`);
                form.submit();
            }
        });
    });
});
</script>

@stop

{{-- views/plc/machine_threshold/index.blade.php --}}
@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thông số chi tiết - {{ $machine->name }}</h3>
        <div class="card-tools">
            <a href="{{ route('plc.machine.thresholds.create', $machine->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm cài đặt
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Thông số</th>
                        <th>Giới hạn dưới</th>
                        <th>Cảnh báo dưới</th>
                        <th>Cảnh báo trên</th>
                        <th>Giới hạn trên</th>
                        <th>Hiển thị biểu đồ</th>
                        <th>Trạng thái</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($thresholds as $threshold)
                    <tr>
                        <td>{{ $threshold->plc_data_key }}</td>
                        <td class="text-right">{{ number_format($threshold->min_value, 2) }}</td>
                        <td class="text-right">{{ number_format($threshold->warning_min_value, 2) }}</td>
                        <td class="text-right">{{ number_format($threshold->warning_max_value, 2) }}</td>
                        <td class="text-right">{{ number_format($threshold->max_value, 2) }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $threshold->show_on_chart ? 'success' : 'secondary' }}">
                                {{ $threshold->show_on_chart ? 'Có' : 'Không' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $threshold->is_active ? 'success' : 'secondary' }}">
                                {{ $threshold->is_active ? 'Đang kích hoạt' : 'Đã tắt' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.machine.thresholds.edit', $threshold->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plc.machine.thresholds.destroy', $threshold->id) }}"
                                  method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs delete-confirm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Chưa có cài đặt cảnh báo nào</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc muốn xóa cài đặt này?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@stop

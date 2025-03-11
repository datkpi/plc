@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách máy</h3>
        <div class="card-tools">
            <a href="{{ route('plc.machine.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm máy
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
                        <th>Tên máy</th>
                        <th>Mã máy</th>
                        <th>Địa chỉ IP</th>
                        <th>Mô tả</th>
                        <th>Trạng thái</th>
                        <th>Cài đặt cảnh báo</th>
                        <th>Tham số</th>
                        <th>Biểu đồ</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($machines as $machine)
                    <tr>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $machine->code }}</td>
                        <td>{{ $machine->ip_address }}</td>
                        <td>{{ $machine->description }}</td>
                        <td class="text-center">
                            <form action="{{ route('plc.machine.toggle-status', $machine->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-{{ $machine->status ? 'success' : 'secondary' }} btn-sm">
                                    {{ $machine->status ? 'Đang hoạt động' : 'Tạm dừng' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.machine.thresholds.show', $machine->id) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-cog"></i> Cài đặt
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.data.monitor', $machine->id) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-cog"></i> Chi tiết
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.chart.show', $machine->id) }}"
                               class="btn btn-info btn-sm">
                                <i class="fas fa-cog"></i> Chi tiết
                            </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('plc.machine.edit', $machine->id) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plc.machine.destroy', $machine->id) }}"
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
                        <td colspan="7" class="text-center">Chưa có máy nào được thêm</td>
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
        if (confirm('Bạn có chắc muốn xóa máy này?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@stop

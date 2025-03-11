{{-- views/plc/pe_standards/index.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách tiêu chuẩn cuộn PE</h3>
        <div class="card-tools">
            <a href="{{ route('plc.pe_standards.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Search Form -->
        <form action="{{ route('plc.pe_standards.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Tìm theo đường kính..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Đường kính</th>
                        <th>Chiều dài (m)</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($standards as $standard)
                    <tr>
                        <td>DN {{ $standard->diameter }}</td>
                        <td>{{ number_format($standard->length) }}</td>
                        <td class="text-center">
                            <a href="{{ route('plc.pe_standards.edit', $standard->id) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plc.pe_standards.destroy', $standard->id) }}"
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
                        <td colspan="3" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $standards->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc muốn xóa tiêu chuẩn này?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@stop

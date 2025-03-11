{{-- views/plc/materials/index.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Danh sách nguyên liệu</h3>
        <div class="card-tools">
            <a href="{{ route('plc.materials.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm nguyên liệu
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Search & Filter Form -->
        <form action="{{ route('plc.materials.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Tìm theo mã, tên..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select name="type" class="form-control">
                            <option value="">-- Tất cả loại --</option>
                            <option value="PE80" {{ request('type') == 'PE80' ? 'selected' : '' }}>PE80</option>
                            <option value="PE100" {{ request('type') == 'PE100' ? 'selected' : '' }}>PE100</option>
                            <option value="PPR" {{ request('type') == 'PPR' ? 'selected' : '' }}>PPR</option>
                        </select>
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
                        <th>Mã nguyên liệu</th>
                        <th>Tên nguyên liệu</th>
                        <th>Loại</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $material)
                    <tr>
                        <td>{{ $material->code }}</td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->type }}</td>
                        <td class="text-center">
                            <a href="{{ route('plc.materials.edit', $material->id) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plc.materials.destroy', $material->id) }}"
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
                        <td colspan="4" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $materials->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc muốn xóa nguyên liệu này?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@stop

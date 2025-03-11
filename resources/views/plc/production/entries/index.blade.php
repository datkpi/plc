{{-- views/plc/production/entries/index.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dữ liệu sản xuất (Form HD08.21)</h3>
        <div class="card-tools">
            <a href="{{ route('plc.production.entries.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm dữ liệu
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
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Máy</th>
                        <th>Sản phẩm</th>
                        <th>Ra máy (cây/cuộn)</th>
                        <th>Chính phẩm (cây/cuộn)</th>
                        <th>Phế phẩm (kg)</th>
                        <th>Phế liệu (kg)</th>
                        <th>CN chạy máy</th>
                        <th>CN kiểm</th>
                        <th>CN kho</th>
                        <th style="width: 100px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('d/m/Y') }}</td>
                        <td>{{ $entry->shift }}</td>
                        <td>{{ $entry->machine->name }}</td>
                        <td>{{ $entry->product_code }}</td>
                        <td class="text-right">{{ number_format($entry->output_quantity) }}</td>
                        <td class="text-right">{{ number_format($entry->good_quantity) }}</td>
                        <td class="text-right">{{ number_format($entry->defect_weight, 2) }}</td>
                        <td class="text-right">{{ number_format($entry->waste_weight, 2) }}</td>
                        <td>{{ $entry->machine_operator }}</td>
                        <td>{{ $entry->quality_checker }}</td>
                        <td>{{ $entry->warehouse_staff }}</td>
                        <td class="text-center">
                            <a href="{{ route('plc.production.entries.edit', $entry->id) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('plc.production.entries.destroy', $entry->id) }}"
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
                        <td colspan="12" class="text-center">Chưa có dữ liệu sản xuất</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $entries->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(function() {
    $('.delete-confirm').click(function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc muốn xóa dữ liệu này?')) {
            $(this).closest('form').submit();
        }
    });
});
</script>
@endpush
@stop

@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Báo cáo OEE</h3>
        <div class="card-tools">
            <a href="{{ route('plc.reports.oee.export') }}?{{ request()->getQueryString() }}"
               class="btn btn-success btn-sm">
                <i class="fas fa-download"></i> Xuất Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Form -->
        <form action="{{ route('plc.reports.oee.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Từ ngày</label>
                        <input type="date" name="from_date" class="form-control"
                               value="{{ request('from_date', Carbon\Carbon::now()->subDays(7)->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Đến ngày</label>
                        <input type="date" name="to_date" class="form-control"
                               value="{{ request('to_date', Carbon\Carbon::now()->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Máy</label>
                        <select name="machine_id" class="form-control">
                            <option value="">-- Tất cả máy --</option>
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
                        <i class="fas fa-search"></i> Lọc
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Máy</th>
                        <th>Sản phẩm</th>
                        <th>Availability (%)</th>
                        <th>Performance (%)</th>
                        <th>Quality (%)</th>
                        <th>OEE (%)</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->date->format('d/m/Y') }}</td>
                        <td>Ca {{ $entry->shift }}</td>
                        <td>{{ $entry->machine->name }}</td>
                        <td>{{ $entry->product_code }}</td>
                        <td class="text-right">{{ number_format($oeeData[$entry->id]['availability'] * 100, 1) }}</td>
                        <td class="text-right">{{ number_format($oeeData[$entry->id]['performance'] * 100, 1) }}</td>
                        <td class="text-right">{{ number_format($oeeData[$entry->id]['quality'] * 100, 1) }}</td>
                        <td class="text-right">{{ number_format($oeeData[$entry->id]['oee'] * 100, 1) }}</td>
                        <td class="text-center">
                            <a href="{{ route('plc.reports.oee.daily', ['date' => $entry->date->format('Y-m-d'), 'machine_id' => $entry->machine_id]) }}"
                               class="btn btn-info btn-xs">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
</div>
@stop

@extends('plc.layouts.master')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Lịch sử cảnh báo chi tiết</h4>
        <a href="{{ route('plc.alert.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="card-body">
        <!-- Thông tin summary -->
        <div class="alert alert-info">
            <h5>Thông tin cảnh báo</h5>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Máy:</strong> {{ $summary->machine->name }}</p>
                    <p><strong>Thông số:</strong> {{ $summary->plc_data_key }}</p>
                    <p><strong>Số lần xuất hiện:</strong> {{ $summary->occurrence_count }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Lần đầu xuất hiện:</strong> {{ $summary->first_occurred_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Lần cuối xuất hiện:</strong> {{ $summary->last_occurred_at->format('d/m/Y H:i:s') }}</p>
                    <p><strong>Trạng thái:</strong>
                        <span class="badge bg-{{ $summary->status === 'new' ? 'warning' : 'success' }}">
                            {{ $summary->status === 'new' ? 'Chưa xử lý' : 'Đã xử lý' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4">
                    <p><strong>Người xử lý:</strong> {{ $summary->resolved_by ?? '-' }}</p>
                    <p><strong>Thời gian xử lý:</strong> {{ $summary->resolved_at ? $summary->resolved_at->format('d/m/Y H:i:s') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Bảng lịch sử chi tiết -->
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Giá trị</th>
                        <th>Ngưỡng min</th>
                        <th>Ngưỡng max</th>
                        <th>Mức độ</th>
                        <th>Thông báo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alerts as $alert)
                    <tr>
                        <td>{{ $alert->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ number_format($alert->value, 4) }}</td>
                        <td>{{ $alert->min_value }}</td>
                        <td>{{ $alert->max_value }}</td>
                        <td>
                            <span class="badge bg-{{ $alert->type === 'danger' ? 'danger' : 'warning' }}">
                                {{ $alert->type === 'danger' ? 'Nghiêm trọng' : 'Cảnh báo' }}
                            </span>
                        </td>
                        <td>{{ $alert->message }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $alerts->links() }}
        </div>
    </div>
</div>
@stop

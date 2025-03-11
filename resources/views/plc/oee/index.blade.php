@extends('plc.layouts.master')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Báo cáo OEE</h5>
    </div>

    <div class="card-body">
        <form id="oeeForm">
            <!-- Chọn máy -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Máy</label>
                    <select class="form-select" name="machine_id" required>
                        <option value="">-- Chọn máy --</option>
                        @foreach($machines as $machine)
                            <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Loại báo cáo -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Loại báo cáo</label>
                    <select class="form-select" name="report_type" required>
                        <option value="daily">OEE theo ngày (chia theo sản phẩm)</option>
                        <option value="monthly">OEE theo tháng (không chia theo sản phẩm)</option>
                    </select>
                </div>
            </div>

            <!-- Thời gian (ngày) -->
            <div class="row mb-3" id="dailyFields">
                <div class="col-md-4">
                    <label class="form-label">Ngày</label>
                    <input type="date" class="form-control" name="date" 
                           value="{{ now()->format('Y-m-d') }}">
                </div>
            </div>

            <!-- Thời gian (tháng) -->
            <div class="row mb-3 d-none" id="monthlyFields">
                <div class="col-md-2">
                    <label class="form-label">Năm</label>
                    <select class="form-select" name="year">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tháng</label>
                    <select class="form-select" name="month">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>

            <!-- Thông số thiết kế cho OEE tháng -->
            <div class="row mb-3 d-none" id="monthlyParams">
                <div class="col-md-3">
                    <label class="form-label">Thời gian tháng (phút)</label>
                    <input type="number" class="form-control" name="total_month_time" 
                           min="0" step="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Thời gian không kế hoạch (phút)</label>
                    <input type="number" class="form-control" name="unplanned_time" 
                           min="0" step="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Năng suất thiết kế (kg/h)</label>
                    <input type="number" class="form-control" name="design_capacity" 
                           min="0" step="0.01">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-calculator"></i> Tính OEE
            </button>
        </form>

        <!-- Kết quả -->
        <div class="mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Availability</h5>
                            <h2 class="mb-0" id="availability">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Performance</h5>
                            <h2 class="mb-0" id="performance">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Quality</h5>
                            <h2 class="mb-0" id="quality">-</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">OEE</h5>
                            <h2 class="mb-0" id="oee">-</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('scripts')
<script>
$(function() {
    // Xử lý hiển thị/ẩn các trường theo loại báo cáo
    $('select[name="report_type"]').change(function() {
        const type = $(this).val();
        if (type === 'monthly') {
            $('#dailyFields').addClass('d-none');
            $('#monthlyFields').removeClass('d-none');
            $('#monthlyParams').removeClass('d-none');
        } else {
            $('#dailyFields').removeClass('d-none');
            $('#monthlyFields').addClass('d-none');
            $('#monthlyParams').addClass('d-none');
        }
    });

    // Xử lý submit form
    $('#oeeForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const reportType = formData.get('report_type');

        // Thêm method tương ứng
        formData.append('method', reportType === 'monthly' ? 'by_design' : 'by_product');
        
        $.ajax({
            url: '{{ route("plc.oee.show") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    // Hiển thị kết quả
                    $('#availability').text((data.availability * 100).toFixed(2) + '%');
                    $('#performance').text((data.performance * 100).toFixed(2) + '%');
                    $('#quality').text((data.quality * 100).toFixed(2) + '%');
                    $('#oee').text((data.oee * 100).toFixed(2) + '%');
                } else {
                    alert('Có lỗi xảy ra: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra: ' + xhr.responseJSON.message);
            }
        });
    });
});
</script>
@endpush 
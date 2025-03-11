@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa cảnh báo</h3>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('plc.machine.thresholds.update', $threshold->id) }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label class="form-label">Máy <span class="text-danger">*</span></label>
                <select name="machine_id" class="form-control select2" required>
                    <option value="">Chọn máy</option>
                    @foreach($machines as $machine)
                        <option value="{{ $machine->id }}"
                            {{ old('machine_id', $threshold->machine_id) == $machine->id ? 'selected' : '' }}>
                            {{ $machine->name }} ({{ $machine->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Tag PLC <span class="text-danger">*</span></label>
                <select name="plc_data_key" class="form-control select2" required>
                    <option value="">Chọn tag</option>
                    <optgroup label="Boolean - Trạng thái">
                        @foreach($availableColumns['boolean'] as $col)
                            <option value="{{ $col['key'] }}" data-type="boolean"
                                {{ old('plc_data_key', $threshold->plc_data_key) == $col['key'] ? 'selected' : '' }}>
                                {{ $col['label'] }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Parameter - Thông số">
                        @foreach($availableColumns['parameter'] as $col)
                            <option value="{{ $col['key'] }}" data-type="parameter"
                                {{ old('plc_data_key', $threshold->plc_data_key) == $col['key'] ? 'selected' : '' }}>
                                {{ $col['label'] }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $threshold->name) }}" required>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Màu sắc trên biểu đồ <span class="text-danger">*</span></label>
                <input type="color" name="color" class="form-control"
                       value="{{ old('color', $threshold->color) }}" required>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Loại cảnh báo <span class="text-danger">*</span></label>
                <select name="type" class="form-control" required onchange="toggleInputs(this.value)">
                    <option value="boolean" {{ old('type', $threshold->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    <option value="range" {{ old('type', $threshold->type) == 'range' ? 'selected' : '' }}>Khoảng Min-Max</option>
                    <option value="percent" {{ old('type', $threshold->type) == 'percent' ? 'selected' : '' }}>Theo % dao động</option>
                    <option value="avg" {{ old('type', $threshold->type) == 'avg' ? 'selected' : '' }}>Trung bình 10 phút</option>
                </select>
            </div>

            <!-- Boolean inputs -->
            <div id="boolean_inputs" class="type-inputs">
                <div class="form-group mb-3">
                    <label class="form-label">Giá trị cảnh báo <span class="text-danger">*</span></label>
                    <select name="boolean_value" class="form-control">
                        <option value="1" {{ old('boolean_value', $threshold->boolean_value) ? 'selected' : '' }}>
                            TRUE - Cảnh báo khi bật
                        </option>
                        <option value="0" {{ old('boolean_value', $threshold->boolean_value) ? '' : 'selected' }}>
                            FALSE - Cảnh báo khi tắt
                        </option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Nội dung cảnh báo <span class="text-danger">*</span></label>
                    <input type="text" name="warning_message" class="form-control"
                           value="{{ old('warning_message', $threshold->warning_message) }}"
                           placeholder="VD: Cảnh báo dừng khẩn cấp">
                </div>
            </div>

            <!-- Range inputs -->
            <div id="range_inputs" class="type-inputs">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Giới hạn dưới</label>
                            <input type="number" step="0.01" name="min_value" class="form-control"
                                   value="{{ old('min_value', $threshold->min_value) }}"
                                   placeholder="Để trống nếu không giới hạn">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Giới hạn trên</label>
                            <input type="number" step="0.01" name="max_value" class="form-control"
                                   value="{{ old('max_value', $threshold->max_value) }}"
                                   placeholder="Để trống nếu không giới hạn">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Percent inputs -->
            <div id="percent_inputs" class="type-inputs">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Giá trị cơ sở <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="base_value" class="form-control"
                                   value="{{ old('base_value', $threshold->base_value) }}"
                                   placeholder="VD: 100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">% dao động cho phép <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="percent" class="form-control"
                                   value="{{ old('percent', $threshold->percent) }}"
                                   placeholder="VD: 20">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average inputs -->
            <div id="avg_inputs" class="type-inputs">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Giá trị cơ sở <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="base_value" class="form-control"
                                   value="{{ old('base_value', $threshold->base_value) }}"
                                   placeholder="VD: 100">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">% dao động cho phép <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="percent" class="form-control"
                                   value="{{ old('percent', $threshold->percent) }}"
                                   placeholder="VD: 20">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="show_on_chart"
                           id="show_on_chart" value="1"
                           {{ old('show_on_chart', $threshold->show_on_chart) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="show_on_chart">Hiển thị trên biểu đồ</label>
                </div>
            </div>

            <div class="form-group mb-3">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="status"
                           id="status" value="1"
                           {{ old('status', $threshold->status) ? 'checked' : '' }}>
                    <label class="custom-control-label" for="status">Kích hoạt</label>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('plc.machine.thresholds.show', $threshold->machine_id) }}"
                   class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.type-inputs {
    display: none;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 1rem;
}
</style>

<script>
function toggleInputs(type) {
    // Hide all inputs
    $('.type-inputs').hide();

    // Show selected type inputs
    $(`#${type}_inputs`).show();
}

$(document).ready(function() {
    // Init select2
    $('.select2').select2({
        width: '100%',
        dropdownParent: $('body')
    });

    // Show current type inputs
    toggleInputs('{{ old('type', $threshold->type) }}');

    // When PLC tag changes
    $('select[name="plc_data_key"]').change(function() {
        const type = $(this).find(':selected').data('type');
        // Auto select appropriate type
        const newType = type === 'boolean' ? 'boolean' : 'range';
        $('select[name="type"]').val(newType).trigger('change');

        // Auto fill name if empty
        if (!$('input[name="name"]').val()) {
            $('input[name="name"]').val($(this).find(':selected').text().trim());
        }
    });
});
</script>

@stop

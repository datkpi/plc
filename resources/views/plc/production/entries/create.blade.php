{{-- views/plc/production/entries/create.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
   <div class="card-header">
       <h3 class="card-title">Thêm dữ liệu sản xuất (Form HD08.21)</h3>
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

       <form action="{{ route('plc.production.entries.store') }}" method="POST">
           @csrf
           <div class="row">
               <div class="col-md-4">
                   <div class="form-group">
                       <label>Ngày <span class="text-danger">*</span></label>
                       <input type="date" class="form-control" name="date" value="{{ old('date') }}" required>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-group">
                       <label>Ca <span class="text-danger">*</span></label>
                       <select class="form-control" name="shift" required>
                           <option value="">-- Chọn ca --</option>
                           <option value="CA1" {{ old('shift') == 'CA1' ? 'selected' : '' }}>Ca 1</option>
                           <option value="CA2" {{ old('shift') == 'CA2' ? 'selected' : '' }}>Ca 2</option>
                           <option value="CA3" {{ old('shift') == 'CA3' ? 'selected' : '' }}>Ca 3</option>
                       </select>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-group">
                       <label>Máy <span class="text-danger">*</span></label>
                       <select class="form-control" name="machine_id" required>
                           <option value="">-- Chọn máy --</option>
                           @foreach($machines as $machine)
                               <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                                   {{ $machine->name }}
                               </option>
                           @endforeach
                       </select>
                   </div>
               </div>
           </div>

           <div class="row">
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Sản phẩm <span class="text-danger">*</span></label>
                       <select class="form-control select2" name="product_code" required>
                           <option value="">-- Chọn sản phẩm --</option>
                           @foreach($products as $product)
                               <option value="{{ $product->code }}" {{ old('product_code') == $product->code ? 'selected' : '' }}>
                                   {{ $product->code }}
                               </option>
                           @endforeach
                       </select>
                   </div>
               </div>
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Tổ/bộ phận <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="operator_team" value="{{ old('operator_team') }}" required>
                   </div>
               </div>
               <!-- views/plc/production/entries/create.blade.php -->
               <div class="col-md-6">
                <div class="form-group">
                    <label>Công nhân vận hành <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="operator_name"
                        value="{{ old('operator_name') }}" required>
                </div>
               </div>
           </div>

           <div class="row">
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Ra máy (cây/cuộn) <span class="text-danger">*</span></label>
                       <input type="number" class="form-control" name="output_quantity" value="{{ old('output_quantity') }}" required>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Chính phẩm (cây/cuộn) <span class="text-danger">*</span></label>
                       <input type="number" class="form-control" name="good_quantity" value="{{ old('good_quantity') }}" required>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Phế phẩm (kg) <span class="text-danger">*</span></label>
                       <input type="number" step="0.01" class="form-control" name="defect_weight" value="{{ old('defect_weight') }}" required>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="form-group">
                       <label>Phế liệu (kg) <span class="text-danger">*</span></label>
                       <input type="number" step="0.01" class="form-control" name="waste_weight" value="{{ old('waste_weight') }}" required>
                   </div>
               </div>
           </div>

           <div class="row">
               <div class="col-md-4">
                   <div class="form-group">
                       <label>CN chạy máy <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="machine_operator" value="{{ old('machine_operator') }}" required>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-group">
                       <label>CN kiểm <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="quality_checker" value="{{ old('quality_checker') }}" required>
                   </div>
               </div>
               <div class="col-md-4">
                   <div class="form-group">
                       <label>CN kho <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="warehouse_staff" value="{{ old('warehouse_staff') }}" required>
                   </div>
               </div>
           </div>

           <div class="form-group">
               <label>Ghi chú</label>
               <textarea class="form-control" name="notes" rows="3">{{ old('notes') }}</textarea>
           </div>

           <div class="mt-4">
               <button type="submit" class="btn btn-primary">
                   <i class="fas fa-save"></i> Lưu
               </button>
               <a href="{{ route('plc.production.entries.index') }}" class="btn btn-secondary">
                   <i class="fas fa-times"></i> Hủy
               </a>
           </div>
       </form>
   </div>
</div>
@stop

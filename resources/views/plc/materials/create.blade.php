{{-- views/plc/materials/create.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
   <div class="card-header">
       <h3 class="card-title">Thêm nguyên liệu mới</h3>
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

       <form action="{{ route('plc.materials.store') }}" method="POST">
           @csrf

           <div class="row">
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Mã nguyên liệu <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="code"
                              value="{{ old('code') }}" required>
                   </div>
               </div>
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Tên nguyên liệu <span class="text-danger">*</span></label>
                       <input type="text" class="form-control" name="name"
                              value="{{ old('name') }}" required>
                   </div>
               </div>
           </div>

           <div class="form-group">
               <label>Loại nguyên liệu <span class="text-danger">*</span></label>
               <select name="type" class="form-control" required>
                   <option value="">-- Chọn loại --</option>
                   <option value="PE80" {{ old('type') == 'PE80' ? 'selected' : '' }}>PE80</option>
                   <option value="PE100" {{ old('type') == 'PE100' ? 'selected' : '' }}>PE100</option>
                   <option value="PPR" {{ old('type') == 'PPR' ? 'selected' : '' }}>PPR</option>
               </select>
           </div>

           <div class="mt-4">
               <button type="submit" class="btn btn-primary">
                   <i class="fas fa-save"></i> Lưu
               </button>
               <a href="{{ route('plc.materials.index') }}" class="btn btn-secondary">
                   <i class="fas fa-times"></i> Hủy
               </a>
           </div>
       </form>
   </div>
</div>
@stop

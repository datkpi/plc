{{-- views/plc/pe_standards/create.blade.php --}}
@extends('plc.layouts.master')
@section('content')
<div class="card">
   <div class="card-header">
       <h3 class="card-title">Thêm tiêu chuẩn cuộn PE</h3>
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

       <form action="{{ route('plc.pe_standards.store') }}" method="POST">
           @csrf

           <div class="row">
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Đường kính (DN) <span class="text-danger">*</span></label>
                       <input type="number" class="form-control" name="diameter"
                              value="{{ old('diameter') }}" required min="1" step="1"
                              placeholder="Nhập đường kính">
                       <small class="form-text text-muted">Ví dụ: 16, 20, 25...</small>
                   </div>
               </div>
               <div class="col-md-6">
                   <div class="form-group">
                       <label>Chiều dài chuẩn (m) <span class="text-danger">*</span></label>
                       <input type="number" class="form-control" name="length"
                              value="{{ old('length') }}" required min="1" step="1"
                              placeholder="Nhập chiều dài">
                       <small class="form-text text-muted">Ví dụ: 300, 200, 100...</small>
                   </div>
               </div>
           </div>

           <div class="mt-4">
               <button type="submit" class="btn btn-primary">
                   <i class="fas fa-save"></i> Lưu
               </button>
               <a href="{{ route('plc.pe_standards.index') }}" class="btn btn-secondary">
                   <i class="fas fa-times"></i> Hủy
               </a>
           </div>
       </form>
   </div>
</div>
@stop

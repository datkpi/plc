@extends('plc.layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Thêm sản phẩm mới</h3>
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

        <form action="{{ route('plc.products.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mã sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Định mức g/m <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="gm_spec"
                               value="{{ old('gm_spec') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Năng suất tối thiểu (kg/h) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="min_productivity"
                               value="{{ old('min_productivity') }}" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('plc.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@stop

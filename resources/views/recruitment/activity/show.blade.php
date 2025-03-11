@extends('recruitment.layouts.master')
@section('content')

    <!-- resources/views/activity/detail.blade.php -->

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Chi tiết chỉnh sửa</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                @foreach ($data->properties as $key => $value)
                    <div class="col-sm-6">
                        <strong>{{ $key }}:</strong>
                        @if (is_array($value))
                            <pre>{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                        @else
                            @php
                                $oldValue = $data->old_values[$key] ?? null;
                            @endphp
                            <span style="color: {{ $oldValue !== $value ? 'red' : 'black' }}">{{ $value }}</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <style>
                .changed {
                    color: red;
                }
            </style>
        </div>
    </div>
@stop

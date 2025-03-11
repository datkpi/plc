@extends('recruitment.layouts.master')
@section('content')

    <h3>Lịch sử thao tác</h3>
    <form action="{{ route('recruitment.activity.index') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="event">Thao tác:</label>
                <select name="event" id="event" class="form-control">
                    <option value="">Tất cả</option>
                    <option value="created">Tạo mới</option>
                    <option value="updated">Cập nhật</option>
                    <option value="deleted">Xóa</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="causer">Người thao tác:</label>
                <select class="form-control select2" name="causer" id="causer">
                    <option value="">Tất cả</option>
                    {!! $users !!}
                </select>
            </div>
            <div class="col-md-3">
                <label for="model">Chức năng:</label>
                <select name="model" id="model" class="form-control">
                    <option value="">Tất cả</option>
                    @foreach ($models as $model)
                        <option value="{{ $model }}" {{ old('model') == $model ? 'selected' : '' }}>
                            {{ $model }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Chức năng</th>
                <th>Người thao tác</th>
                <th>Hoạt động</th>
                <th>Chi tiết thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ $data->created_at }}</td>
                    <td>{{ $data->log_name }}</td>
                    <td>{{ optional($data->causer)->name }}</td>
                    <td>{{ $data->description }}</td>
                    {{-- <td>
                        <ul>
                            @foreach ($data->properties as $key => $value)
                                <li>
                                    <strong>{{ $key }}:</strong>
                                    @if (is_array($value))
                                        <pre>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</pre>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </td> --}}
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('recruitment.activity.show', $data->id) }}">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Xem
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $datas->links('pagination::bootstrap-4') }}

@stop

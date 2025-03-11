@extends('recruitment.layouts.master')
@section('content')

    <form action="{{ route('recruitment.user.index') }}" method="GET">
        <div class="row mb-3">
            <div class="col-md-3">
                <label for="event">Tên nhân viên:</label>
                <input value="{{ Request::get('name') }}" name="name" class="form-control" placeholder="Nhập tên người dùng">
            </div>
            <div class="col-md-3">
                <label for="causer">Phòng ban:</label>
                <select class="form-control select2" name="department_id" id="causer">
                    {!! $departments !!}
                </select>
            </div>
            <div class="col-md-3">
                <label for="model">Vị trí chức vụ:</label>
                <select name="position_id" id="model" class="form-control select2">
                    {!! $positions !!}
                </select>
            </div>
            <div class="col-md-1 align-self-end">
                <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">ImportExel</button>
            </div>
        </div>
    </form>
    <!-- Default box -->
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>

                        <th>
                            Ảnh
                        </th>
                        <th>
                            Họ tên
                        </th>
                        <th>
                            Mã nhân sự
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Số điện thoại
                        </th>
                        <th class="">
                            Trạng thái
                        </th>
                        <th>
                            Hành động
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            {{-- <td>
                                {{ ++$key }}
                            </td> --}}
                            <td>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <img alt="Avatar" class="table-avatar"
                                            src="{{ $data->avatar ? asset($data->avatar) : asset('storage/photos/shares/avatars/default-profile.jpg') }}">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <a>
                                    {{ $data->name }}
                                </a>
                                <br />
                                <small>
                                    {{ $data->birthday }}
                                </small>
                            </td>
                            <td>
                                {{ $data->user_uid }}
                            </td>
                            <td>
                                {{ $data->email }}
                            </td>
                            <td>
                                {{ $data->phone_number }}
                            </td>
                            <td class="">
                                {!! $data->active
                                    ? '<span class="badge badge-success">Hoạt động</span>'
                                    : '<span class="badge badge-danger">Khoá</span>' !!}
                            </td>
                            <td class="project-actions">
                                <a class="btn btn-info btn-sm"
                                    href="{{ route('recruitment.user.get_change_password', $data->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Đổi mật khẩu
                                </a>
                                <a class="btn btn-info btn-sm" href="{{ route('recruitment.user.edit', $data->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Sửa
                                </a>
                                <form action="{!! route('recruitment.user.destroy', $data->id) !!}" method="POST" style="display: inline-block">
                                    {!! method_field('DELETE') !!}
                                    {!! csrf_field() !!}

                                    <button type="submit" class="btn btn-danger btn-sm delete_confirm"
                                        data-action="delete">
                                        <i class="fas fa-trash-alt">
                                        </i>
                                        Xoá
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        <div class="m-2">
            {{ $datas->links('pagination::bootstrap-4') }}
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <script type="text/javascript">
        $('.delete_confirm').click(function(e) {
            if (!confirm('Bạn có muốn xoá bản ghi này?')) {
                e.preventDefault();
            }
        });
    </script>
@stop

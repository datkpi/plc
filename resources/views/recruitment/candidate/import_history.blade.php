@extends('recruitment.layouts.master')
@section('content')

    <!-- Default box -->
    <div class="card">

        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th>
                            Người import
                        </th>
                        <th>
                            Thời gian import
                        </th>
                        <th>
                            Tên file
                        </th>
                        <th>
                            Tổng số hàng
                        </th>
                        <th>
                            Import thành công
                        </th>
                        <th class="">
                            Import thất bại
                        </th>
                        <th>
                            Trạng thái
                        </th>
                        <th>
                            Chi tiết trạng thái
                        </th>
                        {{-- <th>
                            Hành động
                        </th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                {{ $data->createdBy->name }}
                            </td>
                            <td>
                                {{ $data->createdDatetime() }}
                            </td>
                            <td>
                                <a href="{{ asset($data->file) }}">{{ $data->filename }}</a>
                            </td>
                            <td>
                                {{ $data->total_row }}
                            </td>
                            <td>
                                {{ $data->record_imported }}
                            </td>
                            <td>
                                {{ $data->record_failed }}
                            </td>
                            <td class="">
                                {!! $data->status == 1
                                    ? '<span class="badge badge-success">Thành công</span>'
                                    : '<span class="badge badge-danger">Thất bại</span>' !!}

                            </td>
                            <td>
                                {{ $data->note }}
                            </td>
                            {{-- <td class="project-actions">
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
                            </td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

@stop

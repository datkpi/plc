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
                            Tiêu đề
                        </th>
                        <th>
                            Người duyệt
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
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                {{ $data->name }}
                            </td>
                            <td>
                                {{ optional($data->approve1)->name }} > {{ optional($data->approve2)->name }} >
                                {{ optional($data->approve3)->name }} > {{ optional($data->approve4)->name }}

                            </td>
                            <td class="">
                                {!! $data->active
                                    ? '<span class="badge badge-success">Hoạt động</span>'
                                    : '<span class="badge badge-danger">Khoá</span>' !!}
                            </td>
                            <td class="project-actions">
                                <a class="btn btn-info btn-sm" href="{{ route('recruitment.approve.edit', $data->id) }}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                    Sửa
                                </a>
                                <form action="{!! route('recruitment.approve.destroy', $data->id) !!}" method="POST" style="display: inline-block">
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

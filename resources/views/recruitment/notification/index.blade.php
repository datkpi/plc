@extends('recruitment.layouts.master')
@section('content')

    <!-- Default box -->
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>
                            Module
                        </th>
                        <th>
                            Nội dung
                        </th>
                        <th>
                            Link truy cập
                        </th>
                        <th>
                            Thời gian
                        </th>
                        <th>
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
                                {{ $data->id }}
                            </td>
                            @php
                                $dataDecoded = json_decode($data['data'], true);
                            @endphp
                            <td>
                                {{ $dataDecoded['title'] }}
                            </td>
                            <td>
                                {{ $dataDecoded['message'] }}
                            </td>
                            <td>
                                <a href="{{ $data->link }}">
                                    Truy cập
                                </a>
                            </td>
                            <td>
                                {{ $data->createdAt() }}
                            </td>
                            <td class="">
                                {!! $data->isRead == true
                                    ? '<span class="badge badge-success">Đã đọc</span>'
                                    : '<span class="badge badge-danger">Chưa đọc</span>' !!}
                            </td>
                            <td class="project-actions">
                                @if ($data->isRead != true)
                                    <form action="{!! route('recruitment.notification.read', $data->id) !!}" method="POST" style="display: inline-block">
                                        {{-- {!! method_field('post') !!} --}}
                                        {!! csrf_field() !!}

                                        <button type="submit" class="btn btn-primary btn-sm delete_confirm"
                                            data-action="delete">
                                            Đánh dấu đã đọc
                                        </button>
                                    </form>
                                @endif
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

    {{-- <script type="text/javascript">
        $('.delete_confirm').click(function(e) {
            if (!confirm('Bạn có muốn xoá bản ghi này?')) {
                e.preventDefault();
            }
        });
    </script> --}}
@stop

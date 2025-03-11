@extends('recruitment.layouts.master')
@section('content')

    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ route('recruitment.annual_employee.index') }}" method="GET">
            <div class="row">
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <select name="position_id" class="select2" style="width: 100%;">
                                    <option value="" selected>Tất cả phòng ban</option>
                                    {!! $positions !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="position_id" class="select2" style="width: 100%;">
                                    <option value="" selected>Năm</option>
                                    {!! $positions !!}
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary">
                                Tìm
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row float-right">
                        <button value="1" type="button" class="btn btn-primary ml-2 approve">
                            Duyệt
                        </button>
                        <button value="0" type="button" class="btn btn-danger ml-2 approve">
                            Không duyệt
                        </button>
                        <button type="button" class="btn btn-default ml-2 un-selected">
                            Huỷ bỏ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-bordered projects">
                <thead>
                    <tr>
                        <th>
                            Vị trí
                        </th>
                        <th>
                            Tháng 1
                        </th>
                        <th>
                            Tháng 2
                        </th>
                        <th>
                            Tháng 3
                        </th>
                        <th>
                            Tháng 4
                        </th>
                        <th>
                            Tháng 5
                        </th>
                        <th>
                            Tháng 6
                        </th>
                        <th>
                            Tháng 7
                        </th>sý
                        <th>
                            Tháng 8
                        </th>
                        <th>
                            Tháng 9
                        </th>
                        <th>
                            Tháng 10
                        </th>
                        <th>
                            Tháng 11
                        </th>
                        <th>
                            Tháng 12
                        </th>
                        {{-- <th>
                            Hành động --}}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $key => $data)
                        <tr>
                            <td>
                                {{ optional($data[0]->position)->name }}
                            </td>
                            @for ($i = 1; $i <= 12; $i++)
                                <td data-select="1">
                                    @foreach ($data as $dt)
                                        @if ($dt->month == $i)
                                            <span data-id="{{ $dt->id }}"
                                                {{ $dt->status == 'approving' ? 'class=text-danger' : '' }}>{{ $dt->employee_number }}</span>
                                            <a class="edit-annual" value="{{ $dt->id }}"
                                                href="{{ route('recruitment.annual_employee.edit', $dt->id) }}">
                                                <i class="far fa-edit fa-xs"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    {{-- <script>
        $('#positions').select2({
            placeholder: "Chọn phòng ban"
        });
    </script> --}}

    <style>
        .selected {
            background-color: rgb(213, 213, 201);
        }
    </style>
    <script>
        var listAnnualIds = [];

        $(document).click(function(event) {
            // Kiểm tra xem click có phát sinh từ <td> không
            if (!$(event.target).closest('td').length) {
                // Nếu không, bỏ class 'selected' khỏi tất cả các <td>
                $('td').removeClass('selected');
                listAnnualIds = []; // Xóa tất cả các phần tử khỏi listData
            }
        });
        $('.un-selected').on('click', function() {
            $('td').removeClass('selected');
            listAnnualIds = []; //

        });

        $('td').on('click', function(event) {
            var span = $(this).find('span'); // Tìm thẻ <span> trong <td> được click
            var dataId = span.data('id'); // Lấy giá trị data-id
            // Kiểm tra nếu data-id khác rỗng thì set trạng thái selected
            if (dataId != null) {

                event.stopPropagation(); // Ngăn chặn sự kiện lan truyền lên <document>

                // Kiểm tra xem thẻ td đã được chọn chưa
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                    var index = listAnnualIds.indexOf(dataId);
                    if (index !== -1) {
                        listAnnualIds.splice(index, 1);
                    }
                } else {
                    // Nếu chưa chọn, thì chọn
                    $(this).addClass('selected');
                    listAnnualIds.push(dataId);
                }
                console.log('list', listAnnualIds);
            } else {
                // Nếu data-id trống, không thực hiện hành động gì và trả về false để ngăn chặn sự kiện click
                return false;
            }
        });

        $('.approve').on('click', function() {
            approveAnnual(type = this.value)
        });

        function approveAnnual(type = '') {
            $.ajax({
                url: '/annual-employee/approve',
                type: 'POST',
                data: {
                    'listAnnualIds': listAnnualIds,
                    'type': type
                }, // Dữ liệu mà bạn muốn gửi đến máy chủ
                success: function(response) {
                    console.log(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                }
            });
        }


        // JavaScript code
        // document.addEventListener("DOMContentLoaded", function() {
        //     const cells = document.querySelectorAll("td");

        // cells.forEach(cell => {
        //     cell.addEventListener("click", function() {
        //         if (this.dataset.select == 1) {
        //             this.classList.toggle("selected");
        //         }
        //     });
        // });

        // cells.forEach(cell => {
        //     cell.addEventListener("click", function(event) {
        //         const selectedCell = event.target;
        //         const spanValue = selectedCell.querySelector("span").dataset.id;
        //         if (spanValue != null) {
        //             listAnnualIds.push(spanValue);
        //             console.log('lst', listAnnualIds);
        //             this.classList.toggle("selected");
        //         }
        //     });
        // });

        // document.body.addEventListener("click", function(event) {
        //     const target = event.target;
        //     if (!target.closest("td")) {
        //         cells.forEach(cell => {
        //             cell.classList.remove("selected");
        //         });
        //     }
        // });

        // });
    </script>

    <script src="{{ asset('js/devxtreme/request-form.js') }}"></script>
@stop

@extends('recruitment.layouts.master')
@section('content')
    {{-- <div class="row justify-content-end">
        <div class="">
            <nav class="step-arrows">
                @foreach ($status as $key => $st)
                    @if ($st->name == $data->status)
                        <a href='#' name="thea" value="state" class="current">
                            <span>
                                {{ $st->value }}<br>
                            </span>
                        </a>
                    @else
                        <a href='#' class="">
                            <span>
                                {{ $st->value }}<br>
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </div> --}}
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="col-12 col-sm-12">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="{{ $data->stage == null ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-info-tab" data-toggle="pill"
                            href="#custom-tabs-four-info" role="tab" aria-controls="custom-tabs-four-info"
                            aria-selected="true">Thông tin ứng viên </a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ $data->stage == 1 ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-interview0-tab" data-toggle="pill"
                            href="#custom-tabs-four-interview0" role="tab" aria-controls="custom-tabs-four-interview0"
                            aria-selected="true">Phỏng vấn phòng 1 {!! $data->stage >= 1 ? '<i class="fa fa-check" aria-hidden="true"></i>' : '' !!}</a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ $data->stage == 2 ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-interview1-tab" data-toggle="pill"
                            href="#custom-tabs-four-interview1" role="tab" aria-controls="custom-tabs-four-interview1"
                            aria-selected="false">Phỏng vấn phòng 2 {{$data->stage >=2 ? '<i class="fa fa-check" aria-hidden="true"></i>': '' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ $data->stage == 3 ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-interview2-tab" data-toggle="pill"
                            href="#custom-tabs-four-interview2" role="tab" aria-controls="custom-tabs-four-interview2"
                            aria-selected="false">Phỏng vấn phòng 3 {{$data->stage == 3 ? '<i class="fa fa-check" aria-hidden="true"></i>': '' }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="{{ $data->stage == 4 ? 'nav-link active' :  'nav-link'}}" id="custom-tabs-four-interview3-tab" data-toggle="pill"
                            href="#custom-tabs-four-interview3" role="tab" aria-controls="custom-tabs-four-interview3"
                            aria-selected="false">Nhận việc </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-chat-tab" data-toggle="pill" href="#custom-tabs-four-chat"
                            role="tab" aria-controls="custom-tabs-four-chat" aria-selected="false">Trao đổi</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-email-tab" data-toggle="pill"
                            href="#custom-tabs-four-email" role="tab" aria-controls="custom-tabs-four-email"
                            aria-selected="false">Gửi email</a>
                    </li> --}}
                </ul>
            </div>
            <div class="tab-content" id="custom-tabs-four-tabContent">

                @foreach($datas as $key => $data)

                <div class="tab-pane fade show {{$data->stage-1 == $key ? 'active' : ''}}" id="custom-tabs-four-interview{{$key}}" role="tabpanel"
                    aria-labelledby="custom-tabs-four-interview{{$key}}-tab">
                    <!-- general form elements disabled -->
                    <form action="{{ route('recruitment.interview_schedule.update', $data->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card mt-2 card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin chung</h3>
                                {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div> --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <!-- text input -->
                                        <div class="form-group">
                                            <label>Tên lịch phỏng vấn <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $data->name }}">
                                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Người phụ trách <span class="text-danger">*</span></label>
                                            <select style="width:100%;" name="relationer" class="form-control select2"
                                                data-placeholder="Select">
                                                  @foreach($relationers as $relationer)
                                                    @if($relationer->id == $data->relationer)
                                                        <option selected value="{{$relationer->id}}">{{$relationer->name}}</option>
                                                    @else
                                                        <option value="{{$relationer->id}}">{{$relationer->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            {!! $errors->first('relationer', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Ứng viên tham gia <span class="text-danger">*</span></label>
                                            <select name="candidate_id" class="form-control select2"
                                                data-placeholder="Select">
                                                @foreach ($candidates as $candidate)
                                                    @if ($candidate->id == $data->candidate_id)
                                                        <option selected value="{{ $candidate->id }}">
                                                            {{ $candidate->name }}
                                                            -
                                                            {{ optional($candidate->position)->name }}
                                                            ({{ $candidate->phone_number }})
                                                        </option>
                                                    @else
                                                        <option value="{{ $candidate->id }}">{{ $candidate->name }} -
                                                            {{ optional($candidate->position)->name }}
                                                            ({{ $candidate->phone_number }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            {!! $errors->first('candidate_id', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Bắt đầu <span class="text-danger">*</span></label>
                                            <input id="interview_from" value="{{ $data->interview_from }}"
                                                type="datetime-local" name="interview_from" class="form-control"
                                                step="any">
                                            {!! $errors->first('interview_from', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Kết thúc <span class="text-danger">*</span></label>
                                            <input id="interview_to" value="{{ $data->interview_to }}"
                                                type="datetime-local" name="interview_to" class="form-control"
                                                step="any">
                                            {!! $errors->first('interview_to', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Nơi phỏng vấn <span class="text-danger">*</span></label>
                                            <select name="address" class="form-control select2"
                                                data-placeholder="Select">
                                                @foreach($interviewAddress as $address)
                                                    @if($address->id == $data->address)
                                                        <option selected value="{{$address->id}}">{{$address->name}}</option>
                                                    @else
                                                        <option value="{{$address->id}}">{{$address->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            {!! $errors->first('address', '<span class="text-danger">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input name="active" type="checkbox" class="custom-control-input" checked
                                                id="customSwitch3">
                                            <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin hội đồng phỏng vấn</h3>
                                {{-- <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <button type="button" id="addRow" class="btn btn-primary mb-3 ml-3"
                                        data-toggle="modal" data-target="#modal-interview">
                                        Thêm người phỏng vấn
                                    </button>
                                    <div class="col-sm-12">
                                        <table class="table table-striped projects">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        Người phỏng vấn
                                                    </th>
                                                    <th>
                                                        <input type="checkbox" id="check-all">
                                                        Người nhận xét/ đánh giá
                                                    </th>
                                                    <th>
                                                        Người nhận xét/ đánh giá cuối
                                                    </th>
                                                    <th>
                                                        Lịch phỏng vấn đang tham gia
                                                    </th>
                                                    <th>
                                                        Thao tác
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="formContainer">
                                                @php
                                                    $interviewerDatas = is_string($data->interviewer) ? json_decode($data->interviewer, true) : $data->interviewer;
                                                @endphp
                                                @if ($interviewers != null && $interviewerDatas != null )
                                                    @foreach ($interviewerDatas as $key => $interviewerData)
                                                        <tr class="onRemove">
                                                            <td>
                                                                <select name="interviewer[]"
                                                                    class="form-control select-candidate" required>
                                                                    @foreach ($interviewers as $interviewer)
                                                                        @if ($interviewer->id == $interviewerData['user_id'])
                                                                            <option selected="selected"
                                                                                value="{{ $interviewer->id }}">
                                                                                {{ $interviewer->name }}
                                                                            </option>
                                                                        @else
                                                                            <option value="{{ $interviewer->id }}">
                                                                                {{ $interviewer->name }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input class="check-each"
                                                                    {{ $interviewerData['can_evaluate'] == true ? 'checked' : '' }}
                                                                    name="is_comment[]" type="checkbox" value="1">
                                                            </td>
                                                            <td>
                                                                <input
                                                                    {{ $interviewerData['final_evaluate'] == true ? 'checked' : '' }}
                                                                    name="is_evaluate" type="radio" value="1">
                                                            </td>
                                                            <td>
                                                                <a href="#">Xem</a>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-danger remove">Xoá</a>
                                                            </td>
                                                        </tr>
                                                        @if ($interviewerData['can_evaluate'] == true)
                                                            <tr>
                                                                <input type="hidden" name="userId"
                                                                    value="{{ $interviewerData['user_id'] }}">
                                                                <input type="hidden" name="dataId"
                                                                    value="{{ $data->id }}">
                                                                <td colspan="3">
                                                                    <textarea class="form-control" name="comment[]" placeholder="Nhập nhận xét tại đây...">{{ $interviewerData['comment'] }}</textarea>
                                                                </td>
                                                                <td style="width:20%;">
                                                                    <select name="interviewResult"
                                                                        class="form-control select2"
                                                                        data-placeholder="Chọn kết quả">
                                                                        <option value="" selected>Chọn kết
                                                                            quả
                                                                            đánh
                                                                            giá</option>
                                                                        {{-- {!! $interviewResults !!} --}}
                                                                        @foreach ($interviewResults as $interviewResult)
                                                                            @if ($interviewResult->name == $interviewerData['result'])
                                                                                <option selected
                                                                                    value="{{ $interviewResult->name }}">
                                                                                    {{ $interviewResult->value }}
                                                                                </option>
                                                                            @else
                                                                                <option
                                                                                    value="{{ $interviewResult->name }}">
                                                                                    {{ $interviewResult->value }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    {{-- @if (Auth::user()->id == $interviewerData['user_id'] --}}
                                                                    <button type="button"
                                                                        class="btn btn-primary evaluate">Gửi đánh
                                                                        giá</button>
                                                                    {{-- @else
                                                                        <button type="button" disabled
                                                                            class="btn btn-primary">Gửi
                                                                            đánh
                                                                            giá</button>
                                                                    @endif --}}
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button value="evaluate" type="submit" class="btn btn-primary m-2">Xác nhận kết quả phỏng vấn</button>
                        </div>
                        <div class="float-right">
                            <button value="save" type="submit" class="btn btn-primary m-2">Lưu</button>
                        </div>
                        <div class="float-right">
                            <button value="schedule" type="submit" class="btn btn-primary m-2">Tạo phỏng vấn</button>
                        </div>
                    </form>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Hàm thêm form
            $('#addRow').click(function() {
                const newRow = `
                    <tr class="onRemove">
                        <td name="" value="Nguyễn Thanh Ngân" style="width: 25%;">
                           <select name="interviewer[]" class="form-control select-candidate" required>
                                {!! $interviewers !!}
                            </select>
                        </td>
                        <td>
                            <input class="check-each" name="is_comment[]" type="checkbox">
                        </td>
                        <td>
                            <input name="is_evaluate" type="radio">
                        </td>
                        <td>
                            <a href="#">Xem</a>
                        </td>
                        <td>
                            <a href="#" class="text-danger remove">Xoá</a>
                        </td>
                    </tr>
        `;
                $('#formContainer').append(newRow);
                $('.select-candidate').last().select2({
                    placeholder: "Chọn",
                    // ... any other select2 options you might have ...
                });
            });

            // Xoá 1 người phỏng vấn
            $('#formContainer').on('click', '.remove', function(event) {
                event.preventDefault();
                $(this).closest('.onRemove').remove();
            });

            // Hàm check all
            $('#check-all').on('click', function(e) {
                $('.check-each').not(this).prop('checked', this.checked);
            });

            // Sự kiện check trùng khi chọn người phỏng vấn
            $(document).on('change', '.select-candidate', function() {
                var selectedValues = [];
                $('.select-candidate').not(this).each(function() {
                    selectedValues.push($(this).val());
                });

                var selectedValue = $(this).val();
                //console.log('selected', selectedValues)
                if ($.inArray(selectedValue, selectedValues) !== -1) {
                    alert('Ứng viên này đã được chọn');
                    $(this).val("").trigger(
                        'change.select2'); //Thêm trigger để nội dung trở về giá trị ban đầu
                }
            });

            // start_time < end_time
            $('#interview_from, #interview_to').change(function() {
                const interviewFrom = new Date($('#interview_from').val());
                const interviewTo = new Date($('#interview_to').val());

                if (interviewFrom && interviewTo && interviewTo <= interviewFrom) {
                    alert('Thời gian kết thúc phải sau thời gian bắt đầu');
                    $('#interview_to').val(''); // reset giá trị của interview_to
                }

                updateInterviewTimeRange();
            });

            $('.evaluate').on('click', function() {
                var $row = $(this).closest('tr');

                var selectedValue = $row.find('select[name="interviewResult"]').val();
                var comment = $row.find('textarea[name="comment[]"]').val();
                var userId = $row.find('input[name="userId"]').val();
                var dataId = $row.find('input[name="dataId"]').val();
                evaluate(selectedValue, comment, userId, dataId)
            })

            function evaluate(selectedValue, comment, userId, dataId) {
                $.ajax({
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/recruitment/interview-schedule/evaluate/' + dataId,
                    dataType: 'json',
                    data: {
                        selectedValue,
                        comment,
                        userId,
                        dataId,
                    },
                    // async: false,
                    success: function(resp) {
                        alert('Thành công');
                    }
                });
            }
        });
    </script>
    @include('ckfinder::setup')
@stop

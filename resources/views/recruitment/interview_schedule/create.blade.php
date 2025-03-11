@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <form action="{{ route('recruitment.interview_schedule.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin chung</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên lịch phỏng vấn <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kế hoạch tuyển dụng</label>
                            <select name="recruitment_plan_id" class="form-control" data-placeholder="Select">
                                {!! $recruitmentPlans !!}
                            </select>
                            {!! $errors->first('recruitment_plan_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Phiếu tuyển dụng <span class="text-danger">*</span></label>
                            <select name="request_form_id" class="form-control" data-placeholder="Select">
                                {!! $requestForms !!}
                            </select>
                            {!! $errors->first('request_form_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    {{-- <div class="col-sm-3">
                        <div class="form-group">
                            <label>Hội đồng phỏng vấn <span class="text-danger">*</span></label>
                            <select name="interviewer[]" class="form-control select2" multiple data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Người phụ trách <span class="text-danger">*</span></label>
                            <select name="relationer" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('relationer', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <div class="form-group">
                            <label>Vị trí </label>
                            <select name="relationer" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('relationer', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Ứng viên tham gia <span class="text-danger">*</span></label>
                            <select name="candidate_id" class="form-control select2" data-placeholder="Select">
                                @foreach ($candidates as $candidate)
                                    <option value="{{ $candidate->id }}">{{ $candidate->name }} -
                                        {{ optional($candidate->position)->name }}
                                        ({{ $candidate->phone_number }})
                                    </option>
                                @endforeach
                            </select>
                            {!! $errors->first('candidate_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <div class="form-group">
                            <label>Ngày phỏng vấn <span class="text-danger">*</span></label>
                            <input id="interview_date" value="{{ old('interview_date') }}" type="date"
                                name="interview_date" class="form-control" step="any">
                            {!! $errors->first('interview_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Bắt đầu <span class="text-danger">*</span></label>
                            <input id="interview_from" value="{{ $start_date }}" type="datetime-local"
                                name="interview_from" class="form-control" step="any">
                            {!! $errors->first('interview_from', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Kết thúc <span class="text-danger">*</span></label>
                            <input id="interview_to" value="{{ $end_date }}" type="datetime-local" name="interview_to"
                                class="form-control" step="any">
                            {!! $errors->first('interview_to', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Nơi phỏng vấn <span class="text-danger">*</span></label>
                            <select name="address" class="form-control select2" data-placeholder="Select">
                                {!! $interviewAddress !!}
                            </select>
                            {!! $errors->first('address', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input name="active" type="checkbox" class="custom-control-input" checked id="customSwitch3">
                            <label class="custom-control-label" for="customSwitch3">Hoạt động</label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thông tin hội đồng phỏng vấn</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <button type="button" id="addRow" class="btn btn-primary mb-3 ml-3" data-toggle="modal"
                        data-target="#modal-interview">
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
                                {{-- <tr class="onRemove">
                                    <td name="" value="Nguyễn Thanh Ngân">Nguyễn
                                        Thanh Ngân
                                    </td>
                                    <td>
                                        <input class="check-each" name="is_comment[]" type="checkbox" value="1">
                                    </td>
                                    <td>
                                        <input name="is_evaluate" type="radio" value="1">
                                    </td>
                                    <td>
                                        <a href="#">Xem</a>
                                    </td>
                                    <td>
                                        <a href="#" class="text-danger remove">Xoá</a>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách ứng viên tham gia</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="formContainer">
                <button id="addRow" type="button" class="btn btn-primary mb-3">Thêm ứng viên</button>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Ứng viên <span class="text-danger">*</span></label>
                            <select name="candidate_id[]" class="form-control select2 select-candidate" required
                                data-placeholder="Select">
                                {!! $candidates !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Giờ phỏng vấn <span class="text-danger">*</span></label>
                            <input id="interview_at" type="datetime-local" name="interview_at[]" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Email </label>
                            <input name="email[]" class="form-control" required data-placeholder="Select">
                            {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Số điện thoại </label>
                            <input name="phone_number[]" required class="form-control" required data-placeholder="Select">
                            {!! $errors->first('phone_number', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove">Xoá</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- /.card-body -->
        </div> --}}
        <div class="float-right">
            <button type="submit" class="btn btn-primary mb-2">Lưu</button>
        </div>
    </form>



    <script>
        $(document).ready(function() {
            // Hàm thêm form
            $('#addRow').click(function() {
                const newRow = `
                    <tr class="onRemove">
                        <td style="width: 25%;">
                           <select name="interviewer[]" class="form-control select-candidate" required>
                                {!! $interviewers !!}
                            </select>
                        </td>
                        <td>
                            <input class="check-each"  name="can_evaluate[]" type="checkbox">
                        </td>
                        <td>
                            <input name="final_evaluate" type="radio">
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
                    alert('Người này đã được chọn');
                    $(this).val("").trigger(
                        'change.select2'); //Thêm trigger để nội dung trở về giá trị ban đầu
                } else {
                    // Khi value select ở 1 row chọn giá trị
                    let thisSelectedValue = $(this).val();
                    // Tìm checkbox và radio button tương ứng trong cùng một dòng và cập nhật giá trị cho chúng
                    var parentRow = $(this).closest('tr');
                    parentRow.find('[name="can_evaluate[]"]').val(thisSelectedValue);
                    parentRow.find('[name="final_evaluate"]').val(thisSelectedValue);
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

        });
    </script>
    @include('ckfinder::setup')
@stop

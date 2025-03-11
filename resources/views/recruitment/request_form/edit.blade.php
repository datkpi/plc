@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->

    <div class="row justify-content-end">
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
                {{-- <a href='#' class="done">
                        <span>
                            Bản thảo<br>
                        </span>
                    </a>
                    <a href='#' class="done">
                        <span>
                            Duyệt<br>
                        </span>
                    </a>
                    <a href='#' class="current">
                        <span>
                            Đã duyệt<br>
                        </span>
                    </a>
                    <a href='#'>
                        <span>
                            Hoàn thành<br>
                        </span>
                    </a>
                    <a href='#'>
                        <span>
                            Huỷ<br>
                        </span>
                    </a> --}}
            </nav>
        </div>
    </div>
    {{-- <div class="row">
            <div class="col-sm-6">
                <label>Danh sách duyệt</label>
                <ul class="step d-flex flex-nowrap">
                    <li class="step-item">
                        <a href="#!" class="">Thanh Ngân</a>
                    </li>
                    <li class="step-item">
                        <a href="#!" class="">Hùng Cường</a>
                    </li>
                    <li class="step-item active">
                        <a href="#!" class="">Step 3</a>
                    </li>
                    <li class="step-item">
                        <a href="#!" class="">Step 4</a>
                    </li>
                </ul>
            </div>
        </div> --}}
    <div class="row">
        <div class="row  col-sm-8">
            <div class="col-12 col-sm-12">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                    href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                    aria-selected="true">Thông tin</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                    href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                                    aria-selected="false">Ứng viên</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill"
                                    href="#custom-tabs-four-messages" role="tab"
                                    aria-controls="custom-tabs-four-messages" aria-selected="false">Người duyệt</a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill"
                                    href="#custom-tabs-four-settings" role="tab"
                                    aria-controls="custom-tabs-four-settings" aria-selected="false">Duyệt và trao đổi
                                    ({{ count($requestFormDetails) }})</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel"
                                aria-labelledby="custom-tabs-four-home-tab">

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Duyệt phiếu tuyển dụng</h3>
                                        {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}
                                    </div>
                                    {{-- Thông tin chung --}}
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-7 ">

                                                <ul class="step d-flex flex-nowrap justify-content-start">
                                                    {{-- <li class="step-item">
                                                        <a href="#!" class="">Thanh Ngân</a>
                                                    </li>
                                                    <li class="step-item">
                                                        <a href="#!" class="">Hùng Cường</a>
                                                    </li>
                                                    <li class="step-item active">
                                                        <a href="#!" class="">Step 3sssssssss</a>
                                                    </li>
                                                    <li class="step-item">
                                                        <a href="#!" class="">Step 4</a>
                                                    </li> --}}
                                                    @foreach ($approveList as $keyApprove => $approve)
                                                        @if ($data->stage == 0 && $keyApprove == 0)
                                                            <li class="step-item active">
                                                                <a href="#!" class="">{{ $approve->name }}</a>
                                                            </li>
                                                        @elseif($keyApprove == $data->stage)
                                                            <li class="step-item active">
                                                                <a href="#!" class="">{{ $approve->name }}</a>
                                                            </li>
                                                        @else
                                                            <li class="step-item">
                                                                <a href="#!" class="">{{ $approve->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>

                                            </div>
                                            @if ($data->status == 'approving')
                                                <div class="col-sm-5 row justify-content-end">
                                                    <form action="{{ route('recruitment.request_form.approve_all', $data->id) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success  m-1">Duyệt toàn
                                                            bộ</button>
                                                    </form>
                                                    {{-- @if (\Auth::user()->id == $data->current_approve && $data->status == \App\Common\Enums\RequestFormEnum::approving) --}}
                                                    <form
                                                        action="{{ route('recruitment.request_form.approve', ['type' => \App\Models\RequestForm::APPROVE, 'id' => $data->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary  m-1">Duyệt</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('recruitment.request_form.approve', ['type' => \App\Models\RequestForm::REJECT, 'id' => $data->id]) }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary m-1">Từ
                                                            chối</button>
                                                    </form>
                                                    {{-- @endif --}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <form action="{{ route('recruitment.request_form.update', $data->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Thông tin tuyển dụng</h3>
                                            {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Tên phiếu đề xuất </label>
                                                        <input name="name" type="text" class="form-control"
                                                            value="{{ $data->name }}" placeholder="Nhập ...">
                                                        {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Ngày đề nghị <span class="text-danger">*</span></label>
                                                        <input name="request_date" type="date" class="form-control"
                                                            value="{{ date('Y-m-d', strtotime($data->request_date)) }}"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('request_date', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Hạn chót để duyệt <span class="text-danger">*</span></label>
                                                        <input disabled name="approve_deadline" type="date"
                                                            class="form-control"
                                                            value="{{ date('Y-m-d', strtotime($data->approve_deadline)) }}"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('approve_deadline', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Đơn vị tuyển dụng </label>
                                                        <select name="department_id" class="form-control select2"
                                                            data-placeholder="Select">
                                                            {!! $departments !!}
                                                        </select>
                                                        {!! $errors->first('department_id', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Vị trí cần tuyển <span
                                                                class="text-danger">*</span></label>
                                                        <select name="position_id" class="form-control select2"
                                                            data-placeholder="Select">
                                                            {!! $positions !!}
                                                        </select>
                                                        {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Nhân viên hiện có <span class="text-danger">*</span></label>
                                                        <input class="form-control" disabled value="{{ $staffing }}"
                                                            id="staffing">
                                                        {!! $errors->first('staffing', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Định biên nhân sự đã duyệt </label>
                                                        <select name="annual_employee_id" required class="form-control"
                                                            id="annual-employee" data-placeholder="Select">
                                                            @foreach ($annualEmployees as $annual)
                                                                @if ($annual->id == $data->annual_employee_id)
                                                                    <option checked
                                                                        value="{{ $annual->employee_number }}">Tháng
                                                                        {{ $annual->month }}/
                                                                        {{ $annual->year }}
                                                                        ({{ $annual->employee_number }})
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $annual->employee_number }}">Tháng
                                                                        {{ $annual->month }}/
                                                                        {{ $annual->year }}
                                                                        ({{ $annual->employee_number }})
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        {!! $errors->first('annual_employee_id', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div> --}}
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Số lượng yêu cầu </label>
                                                        <input name="quantity" type="text" id="staff-quantity"
                                                            class="form-control" placeholder="Nhập ..."
                                                            value="{{ $data->quantity }}">
                                                        <span id="staff-quantity-check" style="display:none"
                                                            class="text-warning">Số lượng yêu cầu đang lớn hơn định
                                                            biên</span>
                                                        {!! $errors->first('quantity', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Giảm trừ (nếu có) </label>
                                                        <input name="sub" type="text" id="" class="form-control" value="{{ $data->sub }}"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('sub', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Người phụ trách trực tiếp </label>
                                                        <input name="manager_id" type="text" id="manager_id"
                                                            class="form-control" placeholder="Nhập ..."
                                                            value="{{ $data->manager_id }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Thời gian dự kiến đi làm </label>
                                                        <input name="estimate_start" type="date" class="form-control"
                                                            value="{{ date('Y-m-d', strtotime($data->estimate_start)) }}"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('estimate_start', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Mức lương từ </label>
                                                        <input name="salary_from" type="text" class="form-control" value="0"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('salary_from', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <!-- text input -->
                                                    <div class="form-group">
                                                        <label>Mức lương đến </label>
                                                        <input name="salary_to" type="text" class="form-control" value="0"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('salary_to', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label>Lý do giảm trừ</label>
                                                        <textarea name="sub_reason" class="form-control"
                                                            id="sub_reason" rows="3"
                                                            placeholder="Nhập ...">{!! $data->sub_reason !!}</textarea>
                                                        {!! $errors->first('sub_reason', '<span
                                                            class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Lý do tuyển --}}
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Lý do tuyển dụng</h3>
                                            {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 pb-2">
                                                    <div class="form-check form-check-inline">
                                                        <input checked class="form-check-input" value="change"
                                                            type="checkbox" name="recruitment_type[]" id="inlineRadio1">
                                                        <label class="form-check-label" for="inlineRadio1"><b>Tuyển thay
                                                                thế</b></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" value="new" type="checkbox"
                                                            name="recruitment_type[]" id="inlineRadio2">
                                                        <label class="form-check-label" for="inlineRadio2"><b>Tuyển
                                                                mới</b></label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" value="parttime" type="checkbox"
                                                            name="recruitment_type[]" id="inlineRadio3">
                                                        <label class="form-check-label" for="inlineRadio3"><b>Thời
                                                                vụ</b></label>
                                                    </div>
                                                </div>
                                                {!! $errors->first('recruitment_type', '<span class="text-danger">:message</span>') !!}
                                            </div>
                                            <div class="row" id="new">
                                                <div class="col-sm-12">
                                                    <!-- textarea -->
                                                    <div class="form-group">
                                                        <label>Lý do tuyển thay thế</label>
                                                        <textarea name="recruitment_change" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ...">{{ $data->recruitment_change }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <!-- textarea -->
                                                    <div class="form-group">
                                                        <label>Lý do tuyển mới</label>
                                                        <textarea name="new_recruitment_reason" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ...">{{ $data->new_recruitment_reason }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Thông tin chi tiết --}}
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h3 class="card-title">Thông tin chi tiết</h3>
                                            {{-- <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                title="Collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div> --}}
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <!-- textarea -->
                                                    <div class="form-group">
                                                        <label>Mô tả công việc chính</label>
                                                        <textarea name="job_description" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ...">{!! $data->job_description !!}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Trình độ học vấn yêu cầu </label>
                                                        <select name="level" class="form-control"
                                                            data-placeholder="Select">
                                                            {!! $levels !!}
                                                        </select>
                                                        {!! $errors->first('level', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Yêu cầu học vấn khác </label>
                                                        <input name="other_level" type="text" class="form-control"
                                                            value="{{ $data->other_level }}" placeholder="Nhập ...">
                                                        {!! $errors->first('other_level', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Lĩnh vực đào tạo yêu cầu </label>
                                                        <select name="field" class="form-control"
                                                            data-placeholder="Select">
                                                            {!! $fields !!}
                                                        </select>
                                                        {!! $errors->first('field', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Lĩnh vực khác </label>
                                                        <input name="other_field" type="text" class="form-control"
                                                            value="{{ $data->other_field }}" placeholder="Nhập ...">
                                                        {!! $errors->first('other_field', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Thời gian kinh nghiệm yêu cầu </label>
                                                        <select name="experience" class="form-control"
                                                            data-placeholder="Select">
                                                            {!! $experiences !!}
                                                        </select>
                                                        {!! $errors->first('experience', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Kinh nghiệm khác </label>
                                                        <input name="other_experience" type="text"
                                                            class="form-control" value="{{ $data->other_experience }}"
                                                            placeholder="Nhập ...">
                                                        {!! $errors->first('other_experience', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Tên ngoại ngữ yêu cầu </label>
                                                        <input name="language_name" type="text" class="form-control"
                                                            value="{{ $data->language_name }}" placeholder="Nhập ...">
                                                        {!! $errors->first('language_name', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        <label>Trình độ ngoại ngữ yêu cầu </label>
                                                        <select name="language_level" class="form-control"
                                                            data-placeholder="Select">
                                                            {!! $language_levels !!}
                                                        </select>
                                                        {!! $errors->first('language_level', '<span class="text-danger">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                aria-labelledby="custom-tabs-four-profile-tab">
                                Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut
                                ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere
                                cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis
                                posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere
                                nec nunc. Nunc euismod pellentesque diam.
                            </div>
                            {{-- <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                            aria-labelledby="custom-tabs-four-messages-tab">
                            Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat
                            augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit
                            sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut
                            velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus
                            tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet
                            sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum
                            gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt
                            eleifend ac ornare magna.
                        </div> --}}
                            {{-- <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel"
                                aria-labelledby="custom-tabs-four-settings-tab">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="timeline">


                                            @foreach ($requestFormDetails as $requestFormDetail)
                                                <div>

                                                    @if ($requestFormDetail->action == 1)
                                                        <i class="fas fa-comments bg-yellow"></i>
                                                    @else
                                                        <i class="fas fa-user bg-green"></i>
                                                    @endif
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i>
                                                            {{ $requestFormDetail->createdDateTime() }}</span>
                                                        <h3 class="timeline-header">
                                                            @if ($requestFormDetail->created_by != null)
                                                                <a
                                                                    href="{{ route('recruitment.user.edit', $requestFormDetail->created_by) }}">{{ $requestFormDetail->createdBy->name }}
                                                                </a>{!! $requestFormDetail->content !!}
                                                            @else
                                                        </h3>

                                                        @if ($requestFormDetail->comment != null)
                                                            <div class="timeline-body">
                                                                {!! $requestFormDetail->comment !!}
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endforeach <a href="#">Hệ thống
                                            </a>{!! $requestFormDetail->content !!}
                                            @endif


                                            <div>
                                                <i class="fas fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div> --}}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <div class="row col-sm-4">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary mb-3 ml-3" data-toggle="modal" data-target="#modal-lg">
                    Thêm comment
                </button>
                <!-- The time line -->
                <div class="timeline">
                    <!-- timeline time label -->
                    {{-- <div class="time-label">
                                            <span class="bg-red">10 Feb. 2014</span>
                                        </div> --}}
                    <!-- /.timeline-label -->

                    @foreach ($requestFormDetails as $requestFormDetail)
                        <div>

                            @if ($requestFormDetail->action == 1)
                                <i class="fas fa-comments bg-yellow"></i>
                            @else
                                <i class="fas fa-user bg-green"></i>
                            @endif
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i>
                                    {{ $requestFormDetail->createdDateTime() }}</span>
                                <h3 class="timeline-header">
                                    @if ($requestFormDetail->created_by != null)
                                        <a href="{{ route('recruitment.user.edit', $requestFormDetail->created_by) }}">{{ optional($requestFormDetail->createdBy)->name }}
                                        </a>
                                    @else
                                        {{-- <a href="#">Hệ thống
                                        </a> --}}
                                    @endif{!! $requestFormDetail->content !!}
                                </h3>

                                @if ($requestFormDetail->comment != null)
                                    <div class="timeline-body">
                                        {!! $requestFormDetail->comment !!}
                                    </div>
                                @endif
                                {{-- <div class="timeline-footer">
                                                        <a class="btn btn-primary btn-sm">Read more</a>
                                                    <a class="btn btn-danger btn-sm">Delete</a>
                                                    </div> --}}
                            </div>
                        </div>
                    @endforeach

                    <div>
                        <i class="fas fa-clock bg-gray"></i>

                    </div>


                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
    {{-- @if ($data->status == 'plan' || $data->status == 'approving') --}}
        <div class="float-right m-3">
            <button name="type" value="update" type="submit" class="btn btn-primary">Lưu</button>
        </div>
    {{-- @endif --}}
    @if ($data->status == 'plan')
        <button name="type" value="update_status" type="submit" class="btn btn-primary float-right m-3">Chuyển
            duyệt</button>
    @endif
    </form>



    <!-- /.modal -->
    <form action="{{ route('recruitment.request_form_detail.comment', $data->id) }}" method="POST"
        enctype="multipart/form-data">
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm comment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row" id="new">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Điền comment</label>
                                    <textarea name="comment" class="form-control" id="ckeditor-comment" rows="3" placeholder="Nhập ..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </form>
    <!-- /.modal -->

    <script src="{{ asset('js/recruitment.js') }}"></script>
    <script src="{{ asset('js/myjs/request-form.js') }}"></script>
    <!-- /.card -->
    <script>
        var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        CKEDITOR.replace('ckeditor-comment', options);
    </script>
    @include('ckfinder::setup')
@stop

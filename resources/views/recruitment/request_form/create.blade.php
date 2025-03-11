@extends('recruitment.layouts.master')
@section('content')
    <!-- general form elements disabled -->
    <input type="hidden" value="" id="check-annual">
    <form action="{{ route('recruitment.request_form.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Thông tin chung --}}
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Thông tin tuyển dụng</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Tên phiếu đề xuất </label>
                            <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày đề nghị <span class="text-danger">*</span></label>
                            <input name="request_date" type="date" class="form-control" value="{{ old('request_date') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('request_date', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Hạn chót để duyệt (không chọn hệ thống tự add theo cấu hình) </label>
                            <input name="approve_deadline" type="date" class="form-control"
                                value="{{ old('approve_deadline') }}" placeholder="Nhập ...">
                            {!! $errors->first('approve_deadline', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Đơn vị tuyển dụng </label>
                            <select name="department_id" class="form-control select2" data-placeholder="Select">
                                {!! $departments !!}
                            </select>
                            {!! $errors->first('department_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Vị trí cần tuyển <span class="text-danger">*</span></label>
                            <select name="position_id" class="form-control select2" id="position"
                                data-placeholder="Select">
                                {!! $positions !!}
                            </select>
                            {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Định biên nhân sự đã duyệt </label>
                            <select name="annual_employee_id" class="form-control" id="annual-employee"
                                data-placeholder="Select">
                                @foreach ($annualEmployees as $annual)
                                    <option value="{{ $annual->employee_number }}">Tháng {{ $annual->month }}/
                                        {{ $annual->year }} ({{ $annual->employee_number }})</option>
                                @endforeach
                            </select>
                            {!! $errors->first('annual_employee_id', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Số lượng yêu cầu </label>
                            <input name="quantity" type="text" id="staff-quantity" class="form-control" value="0"
                                placeholder="Nhập ...">
                            <span id="staff-quantity-check" style="display:none" class="text-warning">Nhân sự cần tuyển đang
                                lớn
                                hơn
                                định
                                biên</span>
                            {!! $errors->first('quantity', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Giảm trừ (nếu có) </label>
                            <input name="sub" type="text" id="sub" class="form-control" value="{{old('sub')}}"
                                placeholder="Nhập ...">
                            {!! $errors->first('sub', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người phụ trách trực tiếp </label>
                            {{-- <select name="manager_id" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select> --}}
                            <input name="manager_id" type="text" id="manager_id" class="form-control"
                                placeholder="Nhập ...">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Thời gian dự kiến đi làm </label>
                            <input name="estimate_start" type="date" class="form-control"
                                value="{{ old('estimate_start') }}" placeholder="Nhập ...">
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
                                placeholder="Nhập ...">{!! old('sub_reason') !!}</textarea>
                            {!! $errors->first('sub_reason', '<span
                                class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lý do tuyển --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Lý do tuyển dụng</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 pb-2">
                        <div class="form-check form-check-inline">
                            <input checked class="form-check-input" value="change" type="checkbox" name="recruitment_type[]"
                                id="inlineRadio1">
                            <label class="form-check-label" for="inlineRadio1"><b>Tuyển thay thế</b></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" value="new" type="checkbox" name="recruitment_type[]"
                                id="inlineRadio2">
                            <label class="form-check-label" for="inlineRadio2"><b>Tuyển mới</b></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" value="parttime" type="checkbox" name="recruitment_type[]"
                                id="inlineRadio3">
                            <label class="form-check-label" for="inlineRadio3"><b>Thời vụ</b></label>
                        </div>
                    </div>
                    {!! $errors->first('recruitment_type', '<span class="text-danger">:message</span>') !!}
                </div>
                {{-- <div class="row" id="change">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Họ tên nhân sự thay thế </label>
                            <input name="changer_name" type="text" class="form-control"
                                value="{{ old('changer_name') }}" placeholder="Nhập ...">
                            {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Chức vụ người thay thế </label>
                            <input name="changer_position" type="text" class="form-control"
                                value="{{ old('changer_position') }}" placeholder="Nhập ...">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Ngày làm việc cuối cùng </label>
                            <input name="work_last_time" type="text" class="form-control"
                                value="{{ old('work_last_time') }}" placeholder="Nhập ...">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Lý do tuyển thay thế</label>
                            <input name="recruitment_change" type="text" class="form-control"
                                value="{{ old('recruitment_change') }}" placeholder="Nhập ...">
                        </div>
                    </div>
                </div> --}}
                <div class="row" id="new">
                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Lý do tuyển thay thế</label>
                            <textarea name="recruitment_change" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ...">{{ old('recruitment_change') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Lý do tuyển mới</label>
                            <textarea name="new_recruitment_reason" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ...">{{ old('new_recruitment_reason') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thông tin chi tiết --}}
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Thông tin chi tiết</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Mô tả công việc chính</label>
                            <textarea name="job_description" class="form-control" id="ckeditor" rows="3" placeholder="Nhập ..."></textarea>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trình độ học vấn yêu cầu <span class="text-danger">*</span></label>
                            <select name="level" class="form-control" data-placeholder="Select">
                                {!! $levels !!}
                            </select>
                            {!! $errors->first('level', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Yêu cầu học vấn khác </label>
                            <input name="other_level" type="text" class="form-control"
                                value="{{ old('other_level') }}" placeholder="Nhập ...">
                            {!! $errors->first('other_level', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Lĩnh vực đào tạo yêu cầu <span class="text-danger">*</span></label>
                            <select name="field" class="form-control" data-placeholder="Select">
                                {!! $fields !!}
                            </select>
                            {!! $errors->first('field', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Lĩnh vực khác </label>
                            <input name="other_field" type="text" class="form-control"
                                value="{{ old('other_field') }}" placeholder="Nhập ...">
                            {!! $errors->first('other_field', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Thời gian kinh nghiệm yêu cầu <span class="text-danger">*</span></label>
                            <select name="experience" class="form-control" data-placeholder="Select">
                                {!! $experiences !!}
                            </select>
                            {!! $errors->first('experience', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Kinh nghiệm khác </label>
                            <input name="other_experience" type="text" class="form-control"
                                value="{{ old('other_experience') }}" placeholder="Nhập ...">
                            {!! $errors->first('other_experience', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Tên ngoại ngữ yêu cầu <span class="text-danger">*</span></label>
                            <input name="language_name" type="text" class="form-control"
                                value="{{ old('language_name') }}" placeholder="Nhập ...">
                            {!! $errors->first('language_name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trình độ ngoại ngữ yêu cầu <span class="text-danger">*</span></label>
                            <select name="language_level" class="form-control" data-placeholder="Select">
                                {!! $language_levels !!}
                            </select>
                            {!! $errors->first('language_level', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="float-right m-3">
            <button value="1" name="button" type="submit" class="btn btn-primary">Lưu </button>
            <button value="2" name="button" type="submit" class="btn btn-primary">Lưu và gửi phê duyệt</button>
        </div>
    </form>

    <script src="{{ asset('js/recruitment.js') }}"></script>
    <script src="{{ asset('js/myjs/request-form.js') }}"></script>

    <!-- /.card -->
    {{--      <script> --}}
    {{--        CKEDITOR.replace( 'ckeditor', { --}}

    {{--            filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}", --}}
    {{--            filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123", --}}
    {{--            filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123", --}}
    {{--            filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files", --}}
    {{--            filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images", --}}
    {{--            filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash", --}}
    {{--        } ); --}}
    {{--        </script> --}}
    @include('ckfinder::setup')
@stop

@extends('recruitment.layouts.master')
@section('content')
<!-- general form elements disabled -->
<form action="{{ route('recruitment.candidate.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        {{-- Lý do tuyển --}}
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Thông tin tuyển dụng</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row" id="change">
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày SLHS </label>
                            <input name="interview_date" type="date" class="form-control"
                                value="{{ old('interview_date') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người SLHS </label>
                            <select name="interviewer" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nhận xét SLHS </label>
                            <input name="interview_comment" type="text" class="form-control"
                                value="{{ old('interview_comment') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả SLHS </label>
                            <select name="interview_result" class="form-control" data-placeholder="Select">
                                {!! $interviewResults !!}
                            </select>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>



                    <div class="col-sm-12">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ghi chú ứng viên </label>
                            <textarea name="info2" class="form-control" id="" rows="3"
                                placeholder="Nhập ...">{{ old('info2') }}</textarea>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>

    {{-- Thông tin chung --}}
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Thông tin ứng viên</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                {{-- <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Trạng thái ứng viên <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" data-placeholder="Select">
                            {!! $candidateStatus !!}
                        </select>
                        {!! $errors->first('status', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Vị trí/ chức danh <span class="text-danger">*</span></label>
                        <select name="position_id" class="form-control select2" data-placeholder="Select">
                            {!! $positions !!}
                        </select>
                        {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
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
                    <!-- text input -->
                    <div class="form-group">
                        <label>Tên ứng viên <span class="text-danger">*</span></label>
                        <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('name', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Ngày sinh <span class="text-danger">*</span></label>
                        <input name="birthday" type="date" class="form-control" value="{{ old('birthday') }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('birthday', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                {{-- <div class="col-sm-3">
                    <div class="form-group">
                        <label>Đơn vị tuyển dụng <span class="text-danger">*</span></label>
                        <select name="department_id" class="form-control" data-placeholder="Select">
                            {!! $departments !!}
                        </select>
                        {!! $errors->first('department_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Vị trí ứng tuyển <span class="text-danger">*</span></label>
                        <select name="position_id" class="form-control" data-placeholder="Select">
                            {!! $positions !!}
                        </select>
                        {!! $errors->first('position_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Ngày nhận hồ sơ <span class="text-danger">*</span></label>
                        <input name="received_time" type="date" class="form-control" value="{{ old('received_time') }}"
                            placeholder="Nhập ...">
                        {!! $errors->first('received_time', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Người liên quan </label>
                        <select name="relationer[]" class="form-control select2" multiple>
                            {!! $users !!}
                        </select>
                        {!! $errors->first('relationer', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Người nhận hồ sơ <span class="text-danger">*</span></label>
                        <select name="receiver_id" class="form-control select2" data-placeholder="Select">
                            {!! $users !!}
                        </select>
                        {!! $errors->first('receiver_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                {{-- <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Người đảm nhận <span class="text-danger">*</span></label>
                        <select name="recruiter" class="form-control select2" data-placeholder="Select">
                            {!! $users !!}
                        </select>
                        {!! $errors->first('recruiter', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
                <div class="col-sm-3">
                    <div class="form-group">
                        <label>Nguồn hồ sơ <span class="text-danger">*</span></label>
                        <select name="source_id" class="form-control" data-placeholder="Select">
                            {!! $sources !!}
                        </select>
                        {!! $errors->first('source_id', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Ghi chú mối quan hệ </label>
                        <input name="relationship_note" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('relationship_note') }}">
                        {!! $errors->first('relationship_note', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Giới tính <span class="text-danger">*</span></label>
                        <select name="gender" class="form-control" data-placeholder="Select">
                            {!! $genders !!}
                            {!! $errors->first('gender', '<span class="text-danger">:message</span>') !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Số điện thoại <span class="text-danger">*</span></label>
                        <input name="phone_number" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('phone_number') }}">
                        {!! $errors->first('phone_number', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Chiều cao </label>
                        <input name="height" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('height') }}">
                        {!! $errors->first('height', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Cân nặng </label>
                        <input name="weight" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('weight') }}">
                        {!! $errors->first('cccd', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Số căn cước </label>
                        <input name="cccd" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('cccd') }}">
                        {!! $errors->first('cccd', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Ngày cấp căn cước </label>
                        <input name="cccd_date" type="date" class="form-control" placeholder="Nhập ..."
                            value="{{ old('cccd_date') }}">
                        {!! $errors->first('cccd_date', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Email </label>
                        <input name="email" type="email" class="form-control" placeholder="Nhập ..."
                            value="{{ old('email') }}">
                        {!! $errors->first('email', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>

                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Hộ khẩu <span class="text-danger">*</span></label>
                        <select name="household" class="form-control select2" data-placeholder="Select">
                            {!! $selectProvinces !!}
                            {!! $errors->first('household', '<span class="text-danger">:message</span>') !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Nơi ở hiện tại </label>
                        <select name="address" class="form-control select2" data-placeholder="Select">
                            {!! $selectProvinces !!}
                        </select>
                        {!! $errors->first('address', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Nơi ở (chi tiết) </label>
                        <input name="address_detail" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('address_detail') }}">
                        {!! $errors->first('address_detail', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Quá trình đào tạo</label>
                        <textarea name="training_process" class="form-control" id="ckeditor_training_process" rows="3"
                            placeholder="Nhập ...">{{ old('training_process') }}</textarea>
                        {!! $errors->first('training_process', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Ngoại ngữ</label>
                        <textarea name="languague" class="form-control" id="ckeditor_languague" rows="3"
                            placeholder="Nhập ...">{{ old('languague') }}</textarea>
                        {!! $errors->first('languague', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Phần mềm đặc thù</label>
                        <textarea name="skill" class="form-control" id="ckeditor_skill" rows="3"
                            placeholder="Nhập ...">{{ old('skill') }}</textarea>
                        {!! $errors->first('skill', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Kinh nghiệm làm việc</label>
                        <textarea name="experience" class="form-control" id="ckeditor_working_process" rows="3"
                            placeholder="Nhập ...">{{ old('experience') }}</textarea>
                        {!! $errors->first('experience', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>

                {{-- <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Trình độ <span class="text-danger">*</span></label>
                        <select name="level" class="form-control" data-placeholder="Select">
                            {!! $levels !!}
                        </select>
                        {!! $errors->first('level', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Hệ <span class="text-danger">*</span></label>
                        <select name="branch" class="form-control" data-placeholder="Select">
                            {!! $branchs !!}
                        </select>
                        {!! $errors->first('branch', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
                {{-- <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Chuyên ngành học <span class="text-danger">*</span></label>
                        <input name="major" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('major') }}">
                        {!! $errors->first('major', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Trường đào tạo <span class="text-danger">*</span></label>
                        <input name="training_place" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('training_place') }}">
                        {!! $errors->first('training_place', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Xếp loại </label>
                        <select name="rank" class="form-control" data-placeholder="Select">
                            {!! $ranks !!}
                        </select>
                        {!! $errors->first('rank', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Tiếng Anh </label>
                        <input name="english" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('english') }}">
                        {!! $errors->first('english', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Ngoại ngữ khác </label>
                        <input name="other_language" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('other_language') }}">
                        {!! $errors->first('other_language', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Phần mềm khác </label>
                        <input name="other_software" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('other_software') }}">
                        {!! $errors->first('other_software', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Tính cách </label>
                        <textarea rows="2" name="character" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('character') }}</textarea>
                        {!! $errors->first('character', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Sở thích </label>
                        <textarea rows="2" name="interest" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('interest') }}</textarea>
                        {!! $errors->first('interest', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Điểm mạnh </label>
                        <textarea rows="2" name="strength" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('weakness') }}</textarea>
                        {!! $errors->first('strength', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Điểm yếu </label>
                        <textarea rows="2" name="weakness" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('weakness') }}</textarea>
                        {!! $errors->first('weakness', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Người liên hệ khẩn </label>
                        <textarea rows="2" name="contact" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('contact') }}</textarea>
                        {!! $errors->first('contact', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Bài viết về vị trí ứng tuyển của ứng viên </label>
                        <textarea rows="2" name="infomation" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('infomation') }}</textarea>
                        {!! $errors->first('infomation', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Thông tin tham chiếu </label>
                        <textarea rows="2" name="info1" type="text" class="form-control"
                            placeholder="Nhập ...">{{ old('info1') }}</textarea>
                        {!! $errors->first('info1', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div>
                {{-- <div class="col-sm-6">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Thông tin tham chiếu 2 </label>
                        <textarea rows="2" name="info2" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('info2') }}"></textarea>
                        {!! $errors->first('info2', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
                {{-- <div class="col-sm-12">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Kinh nghiệm làm việc </label>
                        <textarea rows="3" name="experience" type="text" class="form-control" placeholder="Nhập ..."
                            value="{{ old('experience') }}"></textarea>
                        {!! $errors->first('experience', '<span class="text-danger">:message</span>') !!}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="float-right m-3">
        <button type="submit" class="btn btn-primary">Lưu</button>
    </div>
</form>

<script src="{{ asset('js/candidate.js') }}"></script>
<script>
    var options = {
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()'
        };

        // CKEDITOR.replace('ckeditor_skill', options);
        // CKEDITOR.replace('ckeditor_working_process', options);
        // CKEDITOR.replace('ckeditor_languague', options);
        // CKEDITOR.replace('ckeditor_training_process', options);
</script>
@include('ckfinder::setup')
@stop

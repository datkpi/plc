                 {{-- <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày PVSB </label>
                            <input name="interview_date0" type="date" class="form-control"
                                value="{{ old('interview_date0') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người PVSB </label>
                            <select name="interviewer0" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer0', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nhận xét PVSB </label>
                            <input name="interview_comment0" type="text" class="form-control"
                                value="{{ old('interview_comment0') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả PVSB </label>
                            <select name="interview_result0" class="form-control" data-placeholder="Select">
                                {!! $interviewResults !!}
                            </select>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày phỏng vấn lần 1 </label>
                            <input name="interview_date1" type="date" class="form-control"
                                value="{{ old('interview_date1') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người phỏng vấn lần 1 </label>
                            <select name="interviewer1" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer1', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nhận xét phỏng vấn lần 1 </label>
                            <input name="interview_comment1" type="text" class="form-control"
                                value="{{ old('interview_comment1') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả phỏng vấn lần 1 </label>
                            <select name="interview_result1" class="form-control" data-placeholder="Select">
                                {!! $interviewResults !!}
                            </select>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày phỏng vấn lần 2 </label>
                            <input name="interview_date1" type="date" class="form-control"
                                value="{{ old('interview_date1') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người phỏng vấn lần 2 </label>
                            <select name="interviewer2" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer1', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nhận xét phỏng vấn lần 2 </label>
                            <input name="interview_comment2" type="text" class="form-control"
                                value="{{ old('interview_comment2') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả phỏng vấn lần 2 </label>
                            <select name="interview_result2" class="form-control" data-placeholder="Select">
                                {!! $interviewResults !!}
                            </select>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày phỏng vấn lần 3 </label>
                            <input name="interview_date3" type="date" class="form-control"
                                value="{{ old('interview_date3') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Người phỏng vấn lần 3 </label>
                            <select name="interviewer3" class="form-control select2" data-placeholder="Select">
                                {!! $users !!}
                            </select>
                            {!! $errors->first('interviewer3', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Nhận xét phỏng vấn lần 3 </label>
                            <input name="interview_comment3" type="text" class="form-control"
                                value="{{ old('interview_comment3') }}" placeholder="Nhập ...">
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả phỏng vấn lần 3 </label>
                            <select name="interview_result3" class="form-control" data-placeholder="Select">
                                {!! $interviewResults !!}
                            </select>
                            {{-- {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!} --}}
                        </div>
                    </div> --}}

                    <div class="col-sm-12">
                        <!-- textarea -->
                        <div class="form-group">
                            <label>Điểm thi tuyển</label>
                            <textarea name="new_recruitment_reason" class="form-control" id="ckeditor" rows="3"
                                placeholder="Nhập ..."></textarea>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả thi tuyển </label>
                            <select name="exam_result" class="form-control" data-placeholder="Select">
                                {!! $examResults !!}
                            </select>
                            {!! $errors->first('exam_result', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-9">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Lý do từ chối thi tuyển (nếu có) </label>
                            <input name="un_exam_reason" type="text" class="form-control"
                                value="{{ old('un_exam_reason') }}" placeholder="Nhập ...">
                            {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div> --}}

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả tuyển dụng </label>
                            <select name="recruitment_result" class="form-control" data-placeholder="Select">
                                {!! $recruitmentResults !!}
                            </select>
                            {!! $errors->first('recruitment_result', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Mã NTP </label>
                            <input name="user_uid" type="text" class="form-control" value="{{ old('user_uid') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('user_uid', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày bắt đầu thử việc </label>
                            <input name="probation_from" type="date" class="form-control"
                                value="{{ old('probation_from') }}" placeholder="Nhập ...">
                            {!! $errors->first('probation_from', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Ngày kết thúc thử việc </label>
                            <input name="probation_to" type="date" class="form-control" value="{{ old('probation_to') }}"
                                placeholder="Nhập ...">
                            {!! $errors->first('probation_to', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-sm-9">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Lý do từ chối nhân việc (nếu có) </label>
                            <input name="un_recruitment_reason" type="text" class="form-control"
                                value="{{ old('un_recruitment_reason') }}" placeholder="Nhập ...">
                            {!! $errors->first('changer_name', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                    </div> --}}

                    <div class="col-sm-3">
                        <!-- text input -->
                        <div class="form-group">
                            <label>Kết quả thử việc </label>
                            <select name="probation_result" class="form-control" data-placeholder="Select">
                                {!! $probationResults !!}
                            </select>
                            {!! $errors->first('probation_result', '<span class="text-danger">:message</span>') !!}
                        </div>
                    </div>

@extends('recruitment.layouts.master')
@section('content')

<div id="scheduler"></div>
<div id="editpopup"></div>

<!-- /.modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tạo lịch phỏng vấn</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="new">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Chọn ứng viên</label>
                            <select name="candidate_id" class="form-control select2" data-placeholder="Select">
                                @foreach ($candidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ $candidate->name }} -
                                    {{ optional($candidate->position)->name }}
                                    ({{ $candidate->phone_number }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Huỷ</button>
                <a type="button" id="create_schedule" class="btn btn-primary">Tạo
                    lịch</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script src="{{ asset('js/devxtreme/interview-schedule.js') }}"></script>
@stop

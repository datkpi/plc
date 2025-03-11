@extends('recruitment.layouts.master')
@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-info" ></span>
        <div class="info-box-content">
          <span class="info-box-text">Hạn 10 ngày kết thúc thử việc</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-success"></span>

        <div class="info-box-content">
          <span class="info-box-text">Hạn 7 ngày kết thúc thử việc</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-warning"></span>
        <div class="info-box-content">
          <span class="info-box-text">Hạn 3 ngày kết thúc thử việc</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon bg-danger"></span>
        <div class="info-box-content">
          <span class="info-box-text">Hết hạn thử việc</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
    <div id="gridData"></div>
    <div id="addRecruitmentPlanPopup"></div>
    <div>
        <input type="file" id="fileInput" style="display:none" onchange="ImportExcel(event)">
    </div>
    <script src="{{ asset('js/devxtreme/report/candidate.js') }}"></script>

@stop

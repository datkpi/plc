@extends('recruitment.layouts.master')
@section('content')

    <!-- Info boxes -->
    <div class="row">


        {{-- <div class="col-12 col-sm-12 col-md-12">
            <div class="demo-container">
                <div id="pivotgrid-demo">
                    <div id="pivotgrid-chart"></div>
                    <div id="pivotgrid"></div>
                </div>
            </div>
        </div> --}}
        <style>
            #pivotgrid-chart {
                margin-bottom: 30px;
            }

            .centered-cell {
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
        <script src="{{ asset('js/devxtreme/dashboard.js') }}"></script>
        {{-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">CPU Traffic</span>
                    <span class="info-box-number">
                        10
                        <small>%</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">41,410</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">New Members</span>
                    <span class="info-box-number">2,000</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col --> --}}
    </div>
    <!-- /.row -->


    <!-- Main row -->
    <div class="row">
        <!-- Left col -->
        <div class="col-md-9">
            <!-- MAP & BOX PANE -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tổng quan tuyển dụng</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">

                    <div class="demo-container">
                        <div id="pivotgrid-demo">
                            <div id="pivotgrid-chart"></div>
                            <div id="pivotgrid"></div>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="row">
                <div class="col-md-6">
                    <!-- DIRECT CHAT -->
                    <div class="card direct-chat direct-chat-warning">
                        <div class="card-header">Tỷ lệ chuyển đổi</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                {{-- <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                                    <i class="fas fa-comments"></i>
                                </button> --}}
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="funnelChart"></div>

                            <!-- Contacts are loaded here -->
                        </div>
                        <!-- /.card-body -->

                    </div>
                    <!--/.direct-chat -->
                </div>
                <!-- /.col -->

                <div class="col-md-6">
                    <!-- USERS LIST -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ứng viên theo vị trí</h3>

                            <div class="card-tools">
                                {{-- <span class="badge badge-danger">8</span> --}}
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div id="pieChart"></div>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.card-body -->
                        {{-- <div class="card-footer text-center">
                            <a href="javascript:">Danh sách </a>
                        </div> --}}
                        <!-- /.card-footer -->
                    </div>
                    <!--/.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.col -->

        <div class="col-md-3">

            <a class="info-box mb-3 bg-warning" href="{{ route('recruitment.request_form.index') }}">
                <span class="info-box-icon"><i class="fa fa-check" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Phiếu đề xuất đợi duyệt</span>
                    <span class="info-box-number">{{ $approveCount }}</span>
                </div>
            </a>
            <a class="info-box mb-3 bg-success" href="{{ route('recruitment.candidate.index') }}">
                <span class="info-box-icon"><i class="fa fa-user nav-icon" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Đang ứng tuyển</span>
                    <span class="info-box-number">{{ $candidateCount }}</span>
                </div>
            </a>
            <a class="info-box mb-3 bg-danger" href="{{ route('recruitment.interview_schedule.index') }}">
                <span class="info-box-icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lịch phỏng vấn</span>
                    <span class="info-box-number">{{ $interviewScheduleCount }}
                    </span>
                </div>
            </a>
            <a class="info-box mb-3 bg-info" href="{{ route('recruitment.user.index') }}">
                <span class="info-box-icon"><i class="fa fa-users nav-icon"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tổng số nhân viên</span>
                    <span class="info-box-number">{{ $userCount }}</span>
                </div>
            </a>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nhân viên mới</h3>

                    <div class="card-tools">
                        <span class="badge badge-danger">8</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <ul class="users-list clearfix">
                        @foreach ($users as $user)
                            <li>
                                <img width="128" height="128" alt="Avatar" class="table-avatar"
                                    src="{{ $user->avatar ? asset($user->avatar) : asset('storage/photos/shares/avatars/default-profile.jpg') }}">
                                <a class="users-list-name" href="#">{{ $user->name }}</a>
                                {{-- <span class="users-list-date">15 Jan</span> --}}
                            </li>
                        @endforeach
                    </ul>
                    <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                    <a href="javascript:">Danh sách nhân viên</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->



@stop

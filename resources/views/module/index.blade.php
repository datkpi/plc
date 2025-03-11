@extends('module.layouts.master')
@section('content')

<h5 class="section-title h1">Hệ thống tiền phong hrm</h5>
<div class="row">
    <div class="col-sm-3">
        <div class="card" >
            <img class="card-img-top" style="max-height: 140px;" src="https://www.ismartrecruit.com/upload/blog/main_image/Banner_Designs_(4).webp" alt="Tuyển dụng">
            <div class="card-body">
            {{-- <h5 class="card-title">Module tuyển dụng</h5> --}}
            <p class="card-text">Module tuyển dụng</p>
            <a href="{{route('recruitment.index')}}" class="btn btn-primary">Truy cập</a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card" style="">
            <img class="card-img-top" style="max-height: 140px;" src="https://www.thomas.co/sites/default/files/thomas-files/styles/resource_banner_image/public/2022-05/A%20Complete%20Guide%20to%20Internal%20Recruitment.jpg?itok=b_SwjSg8" alt="Nhân sự">
            <div class="card-body">
            {{-- <h5 class="card-title">Module nhân sự</h5> --}}
            <p class="card-text">Module nhân sự</p>
            <a href="{{route('personnel.index')}}" class="btn btn-primary">Truy cập</a>
            </div>
        </div>
    </div>

    <div class="col-sm-3">
        <div class="card" style="">
            <img class="card-img-top" style="max-height: 140px;" src="https://www.thomas.co/sites/default/files/thomas-files/styles/resource_banner_image/public/2022-05/A%20Complete%20Guide%20to%20Internal%20Recruitment.jpg?itok=b_SwjSg8" alt="Nhân sự">
            <div class="card-body">
            {{-- <h5 class="card-title">Module nhân sự</h5> --}}
            <p class="card-text">Quản lý máy PLC</p>
            <a href="{{route('plc.index')}}" class="btn btn-primary">Truy cập</a>
            </div>
        </div>
    </div>
</div>

<style>
    section .section-title {
        text-align: center;
        font-weight: bold;
        color: #007b5e;
        margin-bottom: 30px;
        margin-top: 30px;
        text-transform: uppercase;
    }
</style>
@stop

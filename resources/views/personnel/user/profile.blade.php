@extends('personnel.layouts.master')
@section('content')
    <x-personnel.edit_modal :model="$model" :selectOptions="$selectOptions" :fieldMetadata="$fieldMetadata" />
    <div class="container emp-profile">
        <form method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-img">
                        <img src="{{ asset('assets/view/adminlte/img/AdminLTELogo.png') }}" alt="" />
                        <div class="file btn btn-lg btn-primary">
                            Change Photo
                            <input type="file" name="file" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="profile-head">
                        <h5>
                            {{ $data_user->name }}
                        </h5>
                        <h6>
                            User name: {{ $data_user->username }}
                        </h6>
                        <p class="proile-rating">Gender: <span>{{ $data_user->gender }}</span></p>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                                    aria-controls="profile" aria-selected="false">Timeline</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2">
                    @if (Route::has('personnel.' . strtolower($model->getTable()) . '.edit'))
                        <a class="btn btn-info btn-sm editBtn"
                            data-edit="{{ route('personnel.' . strtolower($model->getTable()) . '.edit', $data_user->id) }}"
                            data-id="{{ $data_user->id }}"
                            data-route="{{ route('personnel.' . strtolower($model->getTable()) . '.update', $data_user->id) }}">
                            <i class="fas fa-pencil-alt"></i> Sửa
                        </a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="profile-work">
                        <p>WORK LINK</p>
                        <a href="">Zalo</a><br />
                        <a href="">FaceBook</a><br />
                        <a href="">Intagram</a>
                        {{-- <p>SKILLS</p>
                        <a href="">Web Designer</a><br />
                        <a href="">Web Developer</a><br />
                        <a href="">WordPress</a><br />
                        <a href="">WooCommerce</a><br />
                        <a href="">PHP, .Net</a><br /> --}}
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="tab-content profile-tab" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Mã Nhân Sự</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->user_uid ?? '' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Birth Day</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->birthday }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Address</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->address }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Phone</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->phone }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Căn cước công dân </label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->cccd }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Phòng ban</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->department->name }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Vị trí công việc</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->position->name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Contacts</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->contact }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Role</label>
                                </div>
                                <div class="col-md-6">
                                    <p>{{ $data_user->role->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/personnel/personnel.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }} ">
@stop

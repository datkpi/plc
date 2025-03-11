@extends('recruitment.layouts.master_content')
@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('auth.get_login')}}"><b>Đăng nhập</b> hệ thống</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Vui lòng nhập tài khoản và mật khẩu</p>

                <form action="{{ route('auth.login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="code" required class="form-control" placeholder="Tài khoản">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" required class="form-control" placeholder="Mật khẩu">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{--                    <div class="col-8"> --}}
                        {{--                        <div class="icheck-primary"> --}}
                        {{--                            <input type="checkbox" id="remember"> --}}
                        {{--                            <label for="remember"> --}}
                        {{--                                Remember Me --}}
                        {{--                            </label> --}}
                        {{--                        </div> --}}
                        {{--                    </div> --}}
                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                {{--            <div class="social-auth-links text-center mb-3"> --}}
                {{--                <p>- OR -</p> --}}
                {{--                <a href="#" class="btn btn-block btn-primary"> --}}
                {{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook --}}
                {{--                </a> --}}
                {{--                <a href="#" class="btn btn-block btn-danger"> --}}
                {{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+ --}}
                {{--                </a> --}}
                {{--            </div> --}}
                <!-- /.social-auth-links -->

                {{-- <p class="mb-1">
                <a href="{{route('auth.forget_password')}}">Quên mật khẩu</a>
            </p> --}}
                {{--            <p class="mb-0"> --}}
                {{--                <a href="register.html" class="text-center">Register a new membership</a> --}}
                {{--            </p> --}}
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
@stop

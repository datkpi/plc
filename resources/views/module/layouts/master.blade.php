<!DOCTYPE html>
<html lang="vi">

<head>
    @include('module/layouts/_head')
</head>

<body class="old-transition">
    <div class="wrapper">

        <!-- navbar -->
        @include('module/layouts/_navbar')
        <!-- sidebar -->
        {{-- @include('module/layouts/_sidebar') --}}
        <!-- Main content -->
        <div class="container">
            <!-- header -->
            {{-- @include('module/layouts/_header') --}}
            <!-- Page content -->
            <section class="content">
                <div class="container-fluid">

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible session-notify">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>{{ session('error') }}</strong>
                        </div>
                    @elseif (session('success'))
                        <div class="alert alert-success alert-dismissible session-notify">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>{{ session('success') }}</strong>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- Footer -->
        @include('module/layouts/_footer')
    </div>
</body>
@yield('script')

</html>

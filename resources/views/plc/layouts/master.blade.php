<!DOCTYPE html>
<html lang="vi">

<head>
    @include('plc/layouts/_head')
</head>

<body class="old-transition sidebar-mini sidebar-collapse layout-fixed">
    <div class="wrapper">

        <!-- navbar -->
        @include('plc/layouts/_navbar')
        <!-- sidebar -->
        @include('plc/layouts/_sidebar')
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- header -->
            @include('plc/layouts/_header')
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
        @include('plc/layouts/_footer')
    </div>
</body>
@yield('script')

</html>

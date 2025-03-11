    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tiền Phong HRM</title>

    <script src="{{ asset('assets/view/adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/view/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/fontawesome-free/css/all.min.css') }} ">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/dropzone/min/dropzone.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('assets/view/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/summernote/summernote-bs4.min.css') }}">
    <link href="smart_wizard.min.css" rel="stylesheet">
    {{-- devxtreme --}}
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.1.4/css/dx.common.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.1.4/css/dx.light.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.1.4/css/dx-diagram.css" />
    <!--Step to step -->
    {{-- <link href="{{ asset('lib/step-to-step/css/smart_wizard_round.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/step-to-step/css/smart_wizard_arrows.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/step-to-step/css/smart_wizard_dots.min.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('lib/my-step-to-step/css/my-step.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/my-step-to-step/css/my-step-arrows.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/my-step-to-step/css/responsive-multiple-step.css') }}" rel="stylesheet">
    <!-- Cài đặt thư viện Globalize -->
    <!-- Cài đặt ngôn ngữ tiếng Việt -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/globalize/1.7.0/globalize/cultures/globalize.culture.vi.min.js">
    </script>

    {{-- devxtreme --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"
        integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn3.devexpress.com/jslib/23.1.4/js/dx-diagram.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/devextreme/22.2.6/js/dx.all.js"></script>
    {{-- <script src="https://cdn3.devexpress.com/jslib/23.1.4/js/dx.all.js"></script> --}}
    {{-- <script src="{{ asset('ckeditor/ckeditor.js') }}"></script> --}}
    <script src="https://cdn.ckeditor.com/4.16.0/full-all/ckeditor.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-database.js"></script>
    {{-- <script>
        //Tiếng việt cho date
        DevExpress.localization.locale("vi");

        //Validate vietsub
        DevExpress.localization.addCultureInfo("vi", {
            messages: {
                "dxValidator-required": "Vui lòng nhập giá trị vào trường này.",
                "dxValidator-pattern": "Trường này không khớp với định dạng yêu cầu.",
                "dxValidator-email": "Vui lòng nhập địa chỉ email hợp lệ.",
                "dxValidator-url": "Vui lòng nhập URL hợp lệ.",
                "dxValidator-length": "Vui lòng nhập giá trị có độ dài phù hợp.",
                "dxValidator-range": "Vui lòng nhập giá trị trong phạm vi hợp lệ.",
                "dxValidator-compare": "Giá trị không khớp.",
                "dxValidator-custom": "Giá trị không hợp lệ.",
                "dxValidator-numeric": "Vui lòng nhập giá trị số.",
                "dxValidator-numericMin": "Giá trị phải lớn hơn hoặc bằng {0}.",
                "dxValidator-numericMax": "Giá trị phải nhỏ hơn hoặc bằng {0}.",
                "dxValidator-numericMinMax": "Giá trị phải nằm trong khoảng từ {0} đến {1}.",
                "dxValidator-rangeMin": "Giá trị phải lớn hơn hoặc bằng {0}.",
                "dxValidator-rangeMax": "Giá trị phải nhỏ hơn hoặc bằng {0}.",
                "dxValidator-rangeMinMax": "Giá trị phải nằm trong khoảng từ {0} đến {1}.",
                "dxValidator-stringLengthMin": "Độ dài của chuỗi phải lớn hơn hoặc bằng {0} ký tự.",
                "dxValidator-stringLengthMax": "Độ dài của chuỗi phải nhỏ hơn hoặc bằng {0} ký tự.",
                "dxValidator-stringLengthMinMax": "Độ dài của chuỗi phải nằm trong khoảng từ {0} đến {1} ký tự.",
                "dxValidator-customFunc": "Giá trị không hợp lệ.",
                "dxDateBox-validation-datetime": "Vui lòng nhập ngày tháng hợp lệ.",
                "dxDateBox-validation-nullable": "Vui lòng nhập giá trị vào trường này.",
                "dxTextArea-validation-length": "Vui lòng nhập giá trị có độ dài phù hợp.",
                "dxTextArea-validation-range": "Vui lòng nhập giá trị trong phạm vi hợp lệ.",
                "dxTextArea-validation-numeric": "Vui lòng nhập giá trị số.",
                "dxTextArea-validation-numericMin": "Giá trị phải lớn hơn hoặc bằng {0}.",
                "dxTextArea-validation-numericMax": "Giá trị phải nhỏ hơn hoặc bằng {0}.",
                "dxTextArea-validation-numericMinMax": "Giá trị phải nằm trong khoảng từ {0} đến {1}.",
                "dxTextArea-validation-rangeMin": "Giá trị phải lớn hơn hoặc bằng {0}.",
                "dxTextArea-validation-rangeMax": "Giá trị phải nhỏ hơn hoặc bằng {0}.",
                "dxTextArea-validation-rangeMinMax": "Giá trị phải nằm trong khoảng từ {0} đến {1}.",
                "dxTextArea-validation-stringLengthMin": "Độ dài của chuỗi phải lớn hơn hoặc bằng {0} ký tự.",
                "dxTextArea-validation-stringLengthMax": "Độ dài của chuỗi phải nhỏ hơn hoặc bằng {0} ký tự.",
                "dxTextArea-validation-stringLengthMinMax": "Độ dài của chuỗi phải nằm trong khoảng từ {0} đến {1} ký tự.",
                "dxTextArea-validation-customFunc": "Giá trị không hợp lệ.",
                "dxFileUploader-validation-datatype": "Loại tệp không hợp lệ.",
                "dxFileUploader-validation-size": "Kích thước tệp quá lớn.",
                "dxFileUploader-validation-minSize": "Kích thước tệp quá nhỏ.",
                "dxFileUploader-validation-maxSize": "Kích thước tệp quá lớn.",
                "dxFileUploader-validation-invalidMaxFileSizeMessage": "Kích thước tệp vượt quá giới hạn được chỉ định.",
                "dxFileUploader-validation-invalidMinFileSizeMessage": "Kích thước tệp nhỏ hơn giới hạn được chỉ định.",
                "dxFileUploader-validation-invalidFileExtensionMessage": "Loại tệp không hợp lệ.",
                "dxRangeSlider-unsupported-type": "Kiểu dữ liệu không hỗ trợ.",
                "dxRangeSlider-unsupported-minValue": "MinValue không hỗ trợ.",
                "dxRangeSlider-unsupported-maxValue": "MaxValue không hỗ trợ.",
                "dxSwitch-onText": "Bật",
                "dxSwitch-offText": "Tắt",
                "dxForm-optionalMark": "tùy chọn"

            }
        });
    </script> --}}

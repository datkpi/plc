@extends('recruitment.layouts.master_candidate')
@section('content')
<!-- general form elements disabled -->

<header class="header">
    <p id="description" class="text-center">
        <img id="ntpLogo" src="https://nhuatienphong.vn/main/imgs/ntp/logo2.png">
    </p>
    <h1 id="title" class="text-center">Biểu Mẫu Thông Tin</h1>
    <p id="description" class="text-center">
        Chúng tôi đã nhận được hồ sơ của bạn và đang trong quá trình xem xét.
    </p>
</header>

<script src="{{ asset('js/candidate.js') }}"></script>
<!-- /.card -->
{{-- <script>
    --}}
    {{--        CKEDITOR.replace( 'ckeditor', { --}}

    {{--            filebrowserBrowseUrl     : "{{ route('ckfinder_browser') }}", --}}
    {{--            filebrowserImageBrowseUrl: "{{ route('ckfinder_browser') }}?type=Images&token=123", --}}
    {{--            filebrowserFlashBrowseUrl: "{{ route('ckfinder_browser') }}?type=Flash&token=123", --}}
    {{--            filebrowserUploadUrl     : "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files", --}}
    {{--            filebrowserImageUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Images", --}}
    {{--            filebrowserFlashUploadUrl: "{{ route('ckfinder_connector') }}?command=QuickUpload&type=Flash", --}}
    {{--        } ); --}}
    {{--
</script> --}}
@include('ckfinder::setup')
<style>
    .container {
        max-width: 1530px;
        width: 100%;
    }

    h1 {
        font-weight: 700;
        font-size: 45px;
    }

    .header {
        margin-top: 30px;
        margin-bottom: 50px;
    }

    #ntpLogo {
        max-height: 100px;
    }

    #description {
        font-size: 24px;
    }

    .form-wrap {
        background: rgba(255, 255, 255, 1);
        width: 100%;
        max-width: 1050px;
        padding: 50px;
        margin: 0 auto;
        position: relative;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
    }

    .form-wrap:before {
        content: "";
        width: 90%;
        height: calc(100% + 60px);
        left: 0;
        right: 0;
        margin: 0 auto;
        position: absolute;
        top: -30px;
        background: #00bcd9;
        z-index: -1;
        opacity: 0.8;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        border-radius: 10px;
        -webkit-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        -moz-box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
        box-shadow: 0px 0px 40px rgba(0, 0, 0, 0.15);
    }

    .form-group {
        margin-bottom: 25px;
    }


    textarea.form-control {
        height: 160px;
        padding-top: 15px;
        resize: none;
    }

    .btn {
        padding: .657rem .75rem;
        font-size: 18px;
        letter-spacing: 0.050em;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }


    .btn-primary:hover {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }

    .btn-primary:focus,
    .btn-primary.focus {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }

    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle {
        color: #00bcd9;
        background-color: #ffffff;
        border-color: #00bcd9;
    }

    .btn-primary:not(:disabled):not(.disabled):active:focus,
    .btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-primary.dropdown-toggle:focus {
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
    }
</style>

@stop

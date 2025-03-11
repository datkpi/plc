<script src="{{ asset('assets/view/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('assets/view/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
<link rel="stylesheet" href="{{ asset('assets/view/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
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
{{--
<link rel="stylesheet" type="text/css" href="https://cdn3.devexpress.com/jslib/23.1.4/css/dx.material.teal.light.css" />
--}}
<!--Step to step -->
{{--
<link href="{{ asset('lib/step-to-step/css/smart_wizard_round.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/step-to-step/css/smart_wizard_arrows.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/step-to-step/css/smart_wizard_dots.min.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('lib/my-step-to-step/css/my-step.css') }}" rel="stylesheet">
<link href="{{ asset('lib/my-step-to-step/css/my-step-arrows.css') }}" rel="stylesheet">
<link href="{{ asset('lib/my-step-to-step/css/responsive-multiple-step.css') }}" rel="stylesheet">
<div class="container">
    <header class="header">
        <h1 id="title" class="text-center">Survey Form</h1>
        <p id="description" class="text-center">
            Thank you for taking the time to help us improve the platform
        </p>
    </header>
    <div class="form-wrap">
        <form id="survey-form">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="name-label" for="name">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter your name" class="form-control"
                            required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="email-label" for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control"
                            required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="number-label" for="number">Age <small>(optional)</small></label>
                        <input type="number" name="age" id="number" min="10" max="99" class="form-control"
                            placeholder="Age">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>current role</label>
                        <select id="dropdown" name="role" class="form-control" required>
                            <option disabled selected value>Select</option>
                            <option value="student">Student</option>
                            <option value="job">Full Time Job</option>
                            <option value="learner">Full Time Learner</option>
                            <option value="preferNo">Prefer not to say</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Would you recommend survey to a friend?</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline1" value="Definitely" name="customRadioInline1"
                                class="custom-control-input" checked="">
                            <label class="custom-control-label" for="customRadioInline1">Definitely</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline2" value="Maybe" name="customRadioInline1"
                                class="custom-control-input">
                            <label class="custom-control-label" for="customRadioInline2">Maybe</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="customRadioInline3" value="Not sure" name="customRadioInline1"
                                class="custom-control-input">
                            <label class="custom-control-label" for="customRadioInline3">Not sure</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>This survey useful yes or no?</label>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="yes" value="yes" id="yes"
                                checked="">
                            <label class="custom-control-label" for="yes">Yes</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="custom-control-input" name="no" value="no" id="no">
                            <label class="custom-control-label" for="no">No</label>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Leave Message</label>
                        <textarea id="comments" class="form-control" name="comment"
                            placeholder="Enter your comment here..."></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <button type="submit" id="submit" class="btn btn-primary btn-block">Gửi thông tin</button>
                </div>
            </div>

        </form>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css?family=Roboto:400,500,700,900&display=swap');

    body {
        padding: 100px 0;
        background: #ecf0f4;
        width: 100%;
        height: 100%;
        font-size: 18px;
        line-height: 1.5;
        font-family: 'Roboto', sans-serif;
        color: #222;
    }

    .container {
        max-width: 1230px;
        width: 100%;
    }

    h1 {
        font-weight: 700;
        font-size: 45px;
        font-family: 'Roboto', sans-serif;
    }

    .header {
        margin-bottom: 80px;
    }

    #description {
        font-size: 24px;
    }

    .form-wrap {
        background: rgba(255, 255, 255, 1);
        width: 100%;
        max-width: 850px;
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

    .form-group>label {
        display: block;
        font-size: 18px;
        color: #000;
    }

    .custom-control-label {
        color: #000;
        font-size: 16px;
    }

    .form-control {
        height: 50px;
        background: #ecf0f4;
        border-color: transparent;
        padding: 0 15px;
        font-size: 16px;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #00bcd9;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        -moz-box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, .1);
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

    .btn-primary {
        color: #fff;
        background-color: #00bcd9;
        border-color: #00bcd9;
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
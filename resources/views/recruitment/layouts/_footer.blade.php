  <!-- /.content-wrapper -->
  {{--  <footer class="main-footer"> --}}
  {{--    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> --}}
  {{--    All rights reserved. --}}
  {{--    <div class="float-right d-none d-sm-inline-block"> --}}
  {{--      <b>Version</b> 3.2.0 --}}
  {{--    </div> --}}
  {{--  </footer> --}}


  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
      $.widget.bridge('uibutton', $.ui.button)
  </script>

  <!-- Bootstrap 4 -->
  <script src="{{ asset('assets/view/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('assets/view/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
  <!-- Bootstrap4 Duallistbox -->
  <script src="{{ asset('assets/view/adminlte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}">
  </script>
  <!-- ChartJS -->
  <script src="{{ asset('assets/view/adminlte/plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('assets/view/adminlte/plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  {{-- <script src="{{ asset('assets/view/adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/view/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> --}}
  <!-- InputMask -->
  <script src="{{ asset('assets/view/adminlte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <!-- bootstrap color picker -->
  <script src="{{ asset('assets/view/adminlte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}">
  </script>
  <!-- Bootstrap Switch -->
  <script src="{{ asset('assets/view/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
  <!-- BS-Stepper -->
  <script src="{{ asset('assets/view/adminlte/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
  <!-- dropzonejs -->
  <script src="{{ asset('assets/view/adminlte/plugins/dropzone/min/dropzone.min.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('assets/view/adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('assets/view/adminlte/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/view/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script
      src="{{ asset('assets/view/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
  </script>
  <!-- Summernote -->
  <script src="{{ asset('assets/view/adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('assets/view/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}">
  </script>
  <!-- AdminLTE App -->
  <script src="{{ asset('assets/view/adminlte/dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('assets/view/adminlte/dist/js/demo.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('js/common.js') }}"></script>
  <!-- Add ckeditor-->
  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script> --}}
  {{-- <script>
    ClassicEditor
    .create( document.querySelector( '#ckeditor' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
</script> --}}



  {{-- <script src="https://cdn.ckeditor.com/ckeditor5/36.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckbox.io/ckbox/1.5.1/ckbox.js"></script>
<script src="https://cdn.ckbox.io/ckbox/1.5.1/translations/es.js"></script>
<link rel="stylesheet" href="https://cdn.ckbox.io/ckbox/1.5.1/styles/themes/dark.css">

<script>
    ClassicEditor
        .create( document.querySelector( '#ckeditor' ), {
            ckbox: {
                tokenUrl: 'https://your.token.url',
            },
            toolbar: [
                'ckbox', 'imageUpload', '|', 'heading', '|', 'undo', 'redo', '|', 'bold', 'italic', '|',
                'blockQuote', 'indent', 'link', '|', 'bulletedList', 'numberedList'
            ],
        } )
        .catch( error => {
            console.error( error );
        } );
</script> --}}

  <!-- Add js cho thư viện select2, datetime, color-->
  <script src="{{ asset('js/lib.js') }}"></script>

 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="margin-left: 0px !important;">
     <!-- Left navbar links -->

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <!-- Notifications Dropdown Menu -->
         <li class="nav-item dropdown user-menu">
             <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                 <img src="{{ isset(\Auth::user()->avatar) ? asset(\Auth::user()->avatar) : asset('storage/photos/shares/avatars/default-profile.jpg') }}"
                     class="user-image img-circle elevation-2" alt="User Image">
                 <span class="d-none d-md-inline">{{ isset(\Auth::user()->name) ? \Auth::user()->name : '' }}</span>
             </a>
             <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <!-- User image -->
                 <li class="user-header bg-primary">
                     <img src="{{ isset(\Auth::user()->avatar) ? asset(\Auth::user()->avatar) : asset('storage/photos/shares/avatars/default-profile.jpg') }}"
                         class="img-circle elevation-2" alt="User Image">
                     <p>
                        {{ isset(\Auth::user()->name) ? \Auth::user()->name : '' }}
                         {{-- <small>Member since Nov. 2012</small> --}}
                     </p>
                 </li>

                 <!-- Menu Footer-->
                 <li class="user-footer">
                     <a href="{{route('personnel.user.profile')}}" class="btn btn-default btn-flat">Hồ sơ</a>
                     <a href="{{ route('recruitment.user.logout') }}" class="btn btn-default btn-flat float-right">Đăng
                         xuất</a>
                 </li>
             </ul>
         </li>
         <li class="nav-item">
             <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                 <i class="fas fa-expand-arrows-alt"></i>
             </a>
         </li>
         {{-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> --}}
     </ul>
 </nav>


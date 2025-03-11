 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
     <!-- Left navbar links -->
     <ul class="navbar-nav">
         <li class="nav-item">
             <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
         </li>
         {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li> --}}
     </ul>

     <!-- Right navbar links -->
     <ul class="navbar-nav ml-auto">
         <!-- Navbar Search -->
         {{-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> --}}

         <!-- Messages Dropdown Menu -->
         {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('assets/view/adminlte/dist/img/user1-128x128.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('assets/view/adminlte/dist/img/user8-128x128.png') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ asset('assets/view/adminlte/dist/img/user3-128x128.png') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li> --}}

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
                 <!-- Menu Body -->
                 {{-- <li class="user-body">
                    <div class="row">
                    <div class="col-4 text-center">
                        <a href="#">Followers</a>
                    </div>
                    <div class="col-4 text-center">
                        <a href="#">Sales</a>
                    </div>
                    <div class="col-4 text-center">
                        <a href="#">Friends</a>
                    </div>
                    </div>
                    <!-- /.row -->
                </li> --}}
                 <!-- Menu Footer-->
                 <li class="user-footer">
                     <a href="{{route('personnel.user.profile')}}" class="btn btn-default btn-flat">Hồ sơ</a>
                     <a href="{{ route('recruitment.user.logout') }}" class="btn btn-default btn-flat float-right">Đăng xuất</a>
                 </li>
             </ul>
         </li>
         <li class="nav-item dropdown">
             <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="far fa-bell"></i>
                 <span class="badge badge-danger navbar-badge">0</span>
             </a>
             <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notification"
                 style="overflow: scroll; max-height:500px;">
                 <span class="dropdown-item dropdown-header">0 Thông báo</span>
                 <div class="dropdown-divider"></div>
                 {{-- <a href="#" class="dropdown-item">
                     <i class="fas fa-envelope mr-2"></i> 4 new messages
                     <span class="float-right text-muted text-sm">3 mins</span>
                 </a>
                 <div class="dropdown-divider"></div> --}}
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item dropdown-footer">Tất cả thông báo</a>
             </div>
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



 <script>
     $(document).ready(function() {
         fetchNotifications()

         function fetchNotifications() {
             $.ajax({
                 url: '/api/recruitment/notification',
                 method: 'GET',
                 success: function(resp) {
                     let data = resp.data;
                     let notificationList = $(
                         ".dropdown-menu.dropdown-menu-lg.dropdown-menu-right.notification");
                     notificationList.empty(); // Xóa danh sách thông báo cũ

                     // Cập nhật số thông báo
                     $(".badge-danger.navbar-badge").text(data.length);

                     // Thêm tiêu đề thông báo
                     notificationList.append(
                         '<div class = "container"' +
                         '<div class = "row">' +
                         '<a href="/notification" class="dropdown-item dropdown-header col-sm-6 text-primary">Đánh dấu đã đọc</a>' +
                         '<a href="/notification" class="dropdown-item dropdown-header col-sm-6 text-primary">Xem tất cả</a>' +
                         '</div>' +
                         '</div>'
                     );
                     notificationList.append('<div class="dropdown-divider"></div>');

                     // Lặp qua các thông báo và thêm vào danh sách
                     data.forEach(function(notification) {

                         let parsedData = JSON.parse(notification.data);
                         //  console.log(parsedData.message);

                         let notificationItem = '<b class="pl-3 mt-2">' + parsedData.title +
                             ' </b>' + '<a href="' + notification.link +
                             '" class="dropdown-item">' +
                             parsedData.message +
                             '</a>' +
                             '<span class="pl-3 text-muted text-sm">' + notification
                             .created_at + '</span>' +
                             '<div class="dropdown-divider"></div>';

                         notificationList.append(notificationItem);
                     });

                     // Thêm liên kết xem tất cả thông báo
                     notificationList.append(
                         '<a href="#" class="dropdown-item dropdown-footer">Tất cả thông báo</a>'
                     );
                 }

                 //  success: function(data) {
                 //      let unreadCount = 0;
                 //      data.forEach(function(notification) {
                 //          let isRead = notification.read_ats

                 //          if (!isRead) unreadCount++;

                 //          // Tạo một mục thông báo
                 //          let notificationItem = `
                //     <a href="${notification.link}" class="dropdown-item ${isRead ? 'text-muted' : ''}">
                //         <i class="fas fa-envelope mr-2"></i> ${notification.message}
                //         <span class="float-right text-muted text-sm">${moment(notification.created_at).fromNow()}</span>
                //     </a>
                //     <div class="dropdown-divider"></div>
                // `;

                 //          // Thêm vào menu
                 //          $('.dropdown-menu').prepend(notificationItem);
                 //      });

                 //      // Cập nhật số thông báo chưa đọc
                 //      $('.navbar-badge').text(unreadCount);
                 //      $('.dropdown-header').text(unreadCount + " Notifications");
                 //  },
             });
         }

         // Gọi hàm mỗi 30 giây
         //setInterval(fetchNotifications, 30000);
     });
 </script>
 <style>
     .dropdown-item {
         white-space: collapse !important;
     }

     .dropdown-menu-lg {
         max-width: 330px !important;
         min-width: 350px !important;
         padding: 0;
     }

     .dropdown-menu-lg .dropdown-item {
         padding: 0rem 1rem !important;
     }
 </style>
 <!-- /.navbar -->

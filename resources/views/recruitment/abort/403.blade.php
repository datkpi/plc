  @extends('recruitment.layouts.master')
  @section('content')
      <div class="error-page">
          <h2 class="headline text-warning"> 404</h2>

          <div class="error-content">
              <h3><i class="fas fa-exclamation-triangle text-warning"></i> Cố lỗi xảy ra</h3>

              <p>
                  Bạn không có quyền truy câp chức năng này
                  <a href="{{ route('recruitment.index') }}">Về trang chủ</a>
              </p>

              <form class="search-form">
                  <div class="input-group">
                      <input type="text" name="search" class="form-control" placeholder="Search">

                      <div class="input-group-append">
                          <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
                          </button>
                      </div>
                  </div>
                  <!-- /.input-group -->
              </form>
          </div>
          <!-- /.error-content -->
      </div>
  @stop

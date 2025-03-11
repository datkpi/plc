 <!-- Content Header (Page header) -->
 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-left">
                     <li class="breadcrumb-item"><a href="{!! route('recruitment.index') !!}">Trang chủ</a></li>
                     {{-- @if ($method == 'index')
                         <li class="breadcrumb-item active">{{ trans('route.' . $parent_route) }}</li>
                     @else
                         @if (isset($type))
                             <li class="breadcrumb-item active"><a class="text-default"
                                     href="{!! route($parent_route, ['type' => $type]) !!}">{{ trans('route.' . $parent_route) }}</a></li>
                         @else
                             <li class="breadcrumb-item active"><a class="text-default"
                                     href="{!! route($parent_route) !!}">{{ trans('route.' . $parent_route) }}</a></li>
                         @endif
                         <li class="breadcrumb-item active">{{ trans('route.' . $method) }}</li>
                     @endif --}}
                 </ol>

             </div>
             <div class="col-sm-6">
                 {{-- <div class="float-sm-right">
                     @if ($parent_route !== 'recruitment.index')
                         @if ($method == 'index')
                             @if (\Route::has(str_replace('index', 'create', $current_route)) && strpos($current_route, 'index') != false)
                                 @if (isset($type))
                                     <a href="{!! route(str_replace('index', 'create', $current_route), ['type' => $type]) !!}" class="btn btn-link btn-float text-default"><i
                                             class="icon-googleplus5 text-primary"></i><span>Thêm mới</span></a>
                                 @else
                                     <a href="{!! route(str_replace('index', 'create', $current_route)) !!}" class="btn btn-link btn-float text-default"><i
                                             class="icon-googleplus5 text-primary"></i><span>Thêm mới</span></a>
                                 @endif
                             @endif
                         @else
                             @if (isset($type))
                                 <a href="{!! route($parent_route, ['type' => $type]) !!}" class="btn btn-link btn-float text-default"><i
                                         class="icon-square-left text-primary"></i><span>Quay lại</span></a>
                             @else
                                 <a href="{!! route($parent_route) !!}" class="btn btn-link btn-float text-default"><i
                                         class="icon-square-left text-primary"></i><span>Quay lại</span></a>
                             @endif
                         @endif
                     @endif
                     <div>
                     </div>
                 </div> --}}
             </div><!-- /.container-fluid -->
 </section>

 <!-- Main content -->

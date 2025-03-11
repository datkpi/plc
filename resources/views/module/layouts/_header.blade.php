 <!-- Content Header (Page header) -->
 <section class="content-header">
     <div class="container-fluid">
         <div class="row mb-2">
             <div class="col-sm-6">
                 <ol class="breadcrumb float-sm-left">
                     <li class="breadcrumb-item"><a href="{!! route('recruitment.index') !!}">Trang chủ</a></li>
                     @if ($method == 'index')
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
                     @endif
                 </ol>
             </div>
             @if($method == 'create')
                    <div class="col-sm-6">
                        <div class="float-sm-right">
                           <a href="#" data-toggle="modal" data-target="#baseModal"><i
                               class="icon-googleplus5 text-primary"></i><span>Thêm mới</span></a>
                            <div>
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->
                    @endif
         </div>
     </div>

 </section>

 <!-- Main content -->

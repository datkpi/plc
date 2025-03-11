@extends('personnel.layouts.master')
@section('content')

   {{-- Modal thêm mới --}}
  <x-personnel.modal :model="$model" :selectOptions="$selectOptions" :fieldMetadata="$fieldMetadata" />
  {{-- Modal xem chi tiết --}}
  <x-personnel.edit_modal :model="$model" :selectOptions="$selectOptions" :fieldMetadata="$fieldMetadata" />

  {{-- Search form --}}
  <x-personnel.search :selectOptions="$selectOptions" :model="$model" :fieldMetadata="$fieldMetadata" />

    <!-- Default box -->
    <div class="card">
        <div class="card-body p-0">
            <x-personnel.table :rows="$datas" :model="$model" :fieldMetadata="$fieldMetadata"/>
        </div>
        <x-personnel.paginate :paginator="$datas" />

        {{-- <div class="m-2">
            {{ $datas->links('pagination::bootstrap-4') }}
        </div> --}}
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <script type="text/javascript">
        $('.delete_confirm').click(function(e) {
            if (!confirm('Bạn có muốn xoá bản ghi này?')) {
                e.preventDefault();
            }
        });

</script>

@stop

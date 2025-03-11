<div class="table-responsive-lg">
<table class="table table-striped table-hover table-sm">
    <thead class="">
        <tr>
            @foreach ($fieldMetadata as $field)
                @if (isset($field['show']) && $field['show'])
                    <th>{{ $field['label'] }}</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($fieldMetadata as $field)
                    @if (isset($field['show']) && $field['show'])
                        <td>
                            @switch($field['type'])
                                @case('checkbox')
                                    {{-- Xử lý cho checkbox --}}
                                    @if ($row->{$field['field']})
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-danger">Khoá</span>
                                    @endif
                                @break

                                @case('date')
                                    {{ \Carbon\Carbon::parse($row->{$field['field']})->format('d/m/Y') }}
                                @break

                                @default
                                    @if (isset($field['relation']))
                                        {{ $row->{$field['relation']}->{$field['relationField']} ?? '' }}
                                    @else
                                        {{ $row->{$field['field']} }}
                                    @endif
                            @endswitch
                        </td>
                    @endif
                @endforeach

                {{-- Cột hành động --}}
                <td class="project-actions">
                    {{-- <a class="btn btn-info btn-sm" href="{{ route('recruitment.user.get_change_password', $row->id) }}">
                        <i class="fas fa-pencil-alt"></i> Đổi mật khẩu
                    </a> --}}
                    @if (Route::has('personnel.' . strtolower($model->getTable())  . '.edit') )
                    <a class="btn btn-info btn-sm editBtn" data-edit="{{ route('personnel.' . strtolower($model->getTable())  . '.edit', $row->id) }}" data-id="{{ $row->id }}"
                        data-route="{{ route('personnel.' . strtolower($model->getTable())  . '.update', $row->id) }}">
                        <i class="fas fa-pencil-alt"></i> Sửa
                    </a>
                    @endif
                    @if (Route::has('personnel.' . strtolower($model->getTable())  . '.destroy') )
                    <form action="{!! route('personnel.' . strtolower($model->getTable())  . '.destroy', $row->id) !!}" method="POST" style="display: inline-block">
                        {!! method_field('DELETE') !!}
                        {!! csrf_field() !!}

                        <button type="submit" class="btn btn-danger btn-sm delete_confirm" data-action="delete">
                            <i class="fas fa-trash-alt"></i> Xoá</button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>

<input type="hidden" id="editId" value="{{ old('editId') }}">


@if ($errors->any())
    <script>
        var errors = @json($errors->all());
    </script>
@endif

<script>
        if (typeof errors !== 'undefined' && errors.length > 0) {
            var action = "{{ session('action') }}"; // Get the action value from the session
            console.log('action', action)
            if (action == 'store') {
                setTimeout(function() {
                    $('#baseModal').modal('show');
            }, 200);

            } else if (action == 'update') {
                var editId = "{{ session('editId') }}";
                setTimeout(function() {
                var editButton = $('.editBtn[data-id="' + editId + '"]');
                if (editButton.length) {
                    editButton.trigger('click');
                }
            }, 200);
                // loadData()
                //$('#editModal').modal('show');
            }
        }
</script>

<script src="{{ asset('js/recruitment/table-component.js') }}"></script>
<script src="{{ asset('js/personnel/personnel.js') }}"></script>

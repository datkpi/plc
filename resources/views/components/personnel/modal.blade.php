<div class="modal fade" id="baseModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="baseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="baseModalLabel">Thêm mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('personnel.' . strtolower($model->getTable()) . '.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @foreach ($fieldMetadata as $field)
                            {{-- @if (isset($field['show']) && $field['show']) --}}
                            @if (isset($selectOptions[$field['field']]))
                                @if (isset($field['multiple']) && $field['multiple'])
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="{{ $field['field'] }}">{{ $field['label'] }} {!!isset($field['required']) &&  $field['required'] ? '<span class="text-danger">(*)</span>' : '' !!}</label>
                                        <select name="{{ $field['field'] }}[]" class="form-control custom-select select2" multiple>
                                            {!! $selectOptions[$field['field']] !!}
                                        </select>
                                    </div>
                                </div>
                                @else
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="{{ $field['field'] }}">{{ $field['label'] }} {!!isset($field['required']) &&  $field['required'] ? '<span class="text-danger">(*)</span>' : '' !!}</label>
                                            <select name="{{ $field['field'] }}" class="form-control custom-select select2">
                                                {!! $selectOptions[$field['field']] !!}
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="{{ $field['field'] }}">{{ $field['label'] }} {!!isset($field['required']) &&  $field['required'] ? '<span class="text-danger">(*)</span>' : '' !!}</label>
                                        @switch($field['type'])
                                            @case('text')
                                                {{-- <textarea class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}"></textarea> --}}
                                                <input type="text" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}">
                                                @break

                                            @case('string')
                                                <input type="text" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}">
                                                @break

                                            @case('image')
                                                <input name="{{ $field['field'] }}" accept="image/*" onchange="previewImage(this)" class="form-control" type="file" id="input_{{ $field['field'] }}">
                                                <img id="preview_image_{{ $field['field'] }}" src="{{ asset('storage/photos/shares/thumbs/default-thumbnail.jpg') }}" style="display:none;" class="img-thumbnail">
                                                @break


                                            @case('checkbox')
                                                <input type="checkbox" class="" id="{{ $field['field'] }}" name="{{ $field['field'] }}">
                                                @break

                                            @default
                                                <input type="{{ $field['type'] }}" class="form-control" id="{{ $field['field'] }}" name="{{ $field['field'] }}">
                                        @endswitch
                                        {!! $errors->first($field['field'], '<span class="text-danger">:message</span>') !!}
                                    </div>
                                </div>
                            @endif
                            {{-- @endif --}}
                        @endforeach
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </form>
    </div>
</div>

<style>
    .modal-body {
        max-height: 550px;
        overflow-y: auto;
    }
</style>


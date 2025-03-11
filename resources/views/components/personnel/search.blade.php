<form action="{{ route('personnel.' . strtolower($model->getTable()) . '.index') }}" method="GET">
    <div class="row mb-3">

        @foreach ($searchFields as $field)
        @if(isset($field['search']) && $field['search'])
        @if ($field['type'] == 'date-range')
            {{-- From --}}
            <div class="col-sm-3">
            <div class="form-group">
                <label for="{{ $field['start_field']['name'] }}">{{ $field['start_field']['label'] }}</label>
                <input type="date" class="form-control form-control-sm"
                       name="{{ $field['start_field']['name'] }}"
                       value="{{ request()->get($field['start_field']['name']) }}">
            </div>
            </div>

            {{-- To --}}
            <div class="col-sm-3">
            <div class="form-group">
                <label for="{{ $field['end_field']['name'] }}">{{ $field['end_field']['label'] }}</label>
                <input type="date" class="form-control form-control-sm"
                       name="{{ $field['end_field']['name'] }}"
                       value="{{ request()->get($field['end_field']['name']) }}">
            </div>
            </div>
        @elseif (isset($selectOptions[$field['field']]))
            @if (isset($field['multiple']) && $field['multiple'])
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="{{ $field['field'] }}">{{ $field['label'] }}</label>
                        <select name="{{ $field['field'] }}[]" class="form-control form-control-sm custom-select select2" multiple>
                            {!! $selectOptions[$field['field']] !!}
                        </select>
                    </div>
                </div>
            @else
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="{{ $field['field'] }}">{{ $field['label'] }}</label>
                        <select name="{{ $field['field'] }}" class="form-control form-control-sm custom-select select2">
                            {!! $selectOptions[$field['field']] !!}
                        </select>
                    </div>
                </div>
            @endif
        @else
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="search_{{ $field['field'] }}">{{ $field['label'] }}</label>
                    @switch($field['type'])
                    @case('text')
                    @case('string')
                        <input type="text" class="form-control form-control-sm" name="{{ $field['field'] }}"
                            id="search_{{ $field['field'] }}"
                            value="{{ request()->get($field['field'], '') }}">
                        @break

                    @case('number')
                        <input type="number" class="form-control form-control-sm" name="{{ $field['field'] }}"
                            id="search_{{ $field['field'] }}"
                            value="{{ request()->get($field['field'], '') }}">
                        @break

                    @case('date')
                        <input type="date" class="form-control form-control-sm" name="{{ $field['field'] }}"
                            id="search_{{ $field['field'] }}"
                            value="{{ request()->get($field['field'], '') }}">
                        @break

                    @case('select')
                        <select name="{{ $field['field'] }}" id="search_{{ $field['field'] }}" class="form-control form-control-sm">
                            <option value="">Chọn {{ $field['label'] }}</option>
                            @if(isset($selectOptions[$field['field']]))
                                @foreach ($selectOptions[$field['field']] as $optionId => $optionName)
                                    <option value="{{ $optionId }}" {{ request()->get($field['field']) == $optionId ? 'selected' : '' }}>
                                        {{ $optionName }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @break
                @endswitch
                </div>
            </div>
        @endif
        @endif
        @endforeach
        @if(count($searchFields) > 0)
        <div class="col-sm-3 d-flex align-items-center">
                <button type="submit" class="btn btn-sm btn-primary mt-3">Tìm kiếm</button>
        </div>
        @endif
    </div>
</form>

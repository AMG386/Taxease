

@if (isset($class))
    <div class="{{ $class }}">
    @else
        <div class="col-lg-6">
@endif
<label class="form-label">{!! $label !!}</label>

<select id="{{ $name }}" name="{{ $name }}" class="form-select form-select-solid"
    @if (isset($disabled) && $disabled) disabled @endif>
    @if (isset($all) && $all)
        <option value="All">All</option>
    @else
        <option value="">Select</option>
    @endif
    @foreach ($elements as $val => $key)
        <option value="{{ $val }}" {{ isset($value) && $value == $val ? 'selected' : '' }}>
            {{ $key }}
        </option>
    @endforeach

</select>
<label class="form-label font-normal text-danger error {{ $name }}_error d-none"
    id="{{ $name }}_error"></label>
</div>


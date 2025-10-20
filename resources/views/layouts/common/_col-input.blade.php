

@if (isset($class))
    <div class="{{ $class }}">
    @else
        <div class="col-lg-6">
@endif

<label class="form-label">{!! $label !!}</label>
<input @if (isset($type)) type="{{$type}}" @else type="text"  @endif name="{{ $name }}" class="form-control form-control-solid" value="{{ $value ?? '' }}"
    @if (isset($disabled) && $disabled) disabled @endif @if (isset($readonly) && $readonly) readonly @endif>
<label class="form-label font-normal text-danger error {{ $name }}_error d-none"
    id="{{ $name }}_error"></label>
</div>

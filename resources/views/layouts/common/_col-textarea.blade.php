{{-- <div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            value="{{ $value ?? '' }}" @if (isset($readonly) && $readonly) readonly @endif  @if (isset($disabled) && $disabled) disabled @endif>
        <div class="text-danger d-none error {{ $name }}_error" id="{{ $name }}_error"></div>
    </div>
</div> --}}

{{-- <div class="{{ $class }} mt-2">
    <label for="{{ $name }}" class="mb-0">{{ $label }}</label>

    @if (isset($value))
        <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            value="{{  old($name) ?? $value }}" @if (isset($readonly) && $readonly) readonly @endif
            @if (isset($disabled) && $disabled) disabled @endif>

    @else

    <input type="{{ $type }}" class="form-control" id="{{ $name }}" name="{{ $name }}"
            value="{{  old($name) }}" @if (isset($readonly) && $readonly) readonly @endif
            @if (isset($disabled) && $disabled) disabled @endif>

        
    @endif



    @error($name)
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

</div> --}}


<div>
    <label for="{{ $name }}"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>


    <textarea id="{{ $name }}" rows="4" name="{{ $name }}"
        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        placeholder="@if (isset($placeholder)) {{ $placeholder }} @endif">{{ $value ?? '' }}</textarea>

    <label class="block text-sm font-small text-red-600 dark:text-white error {{ $name }}_error hidden"
        id="{{ $name }}_error"></label>
</div>

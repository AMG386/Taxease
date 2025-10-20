<div class="{{ $class ?? 'col-md-6' }}">
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-800 mb-2">
        {{ $label }}
    </label>

    <div class="relative">
        <select
            name="{{ $name }}[]"
            id="{{ $name }}"
            multiple
            class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            @if(isset($elements) && is_array($elements))
                @foreach ($elements as $key => $value)
                    <option value="{{ $key }}"
                        @if(isset($selected) && is_array($selected) && in_array($key, $selected)) selected @endif
                    >
                        {{ $value }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>

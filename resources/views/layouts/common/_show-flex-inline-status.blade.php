<div class="p-1 flex flex-row justify-between items-center text-gray-400 border-b">
    <div class="flex items-center">
        {{-- <i class="fab fa-chrome mr-4"></i>     --}}
        <h1>{{ $label }}</h1>
    </div>
    <div>
        <span
            class="bg-{{ config('constants.status-badge.' . $value) }}-100 text-{{ config('constants.status-badge.' . $value) }}-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-{{ config('constants.status-badge.' . $value) }}-400 border border-{{ config('constants.status-badge.' . $value) }}-400">{{ config('constants.status.' . $value) }}</span>
    </div>
</div>

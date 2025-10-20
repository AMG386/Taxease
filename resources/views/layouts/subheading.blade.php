<div class="container-fixed">
    @if (isset($page_summary) && !empty($page_summary['breadcrumb']))
        <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
            <div class="flex flex-col justify-center gap-2">
                <h1 class="text-xl font-medium leading-none text-gray-900">
                    {{ isset($page_summary) ? $page_summary['title'] : 'No Title' }}
                </h1>


                <div class="flex items-center gap-2 text-sm font-normal text-gray-700">
                    <div class="flex items-center gap-1 text-sm font-normal">
                        <a class="text-gray-700 hover:text-primary" href="{{ url('/') }}">
                            Home
                        </a>

                        @foreach ($page_summary['breadcrumb'] as $k => $item)
                            <span class="text-gray-400 text-sm">
                                /
                            </span>
                            @if ($loop->last)
                                <span class="text-gray-700">
                                    {{ $item['title'] }}
                                </span>
                            @else
                                <span class="text-gray-700">
                                    <a class="text-gray-700 hover:text-primary" href="{{ url($item['page']) }}">
                                        {{ $item['title'] }}
                                    </a>


                                </span>
                            @endif
                        @endforeach

                    </div>
                </div>

            </div>
            <div class="flex items-center gap-2.5">
                @if (isset($page_summary) && !empty($page_summary['back-button']))
                    <a class="btn btn-sm btn-light" href="{{ url($page_summary['back-button']['url']) }}">
                        Back </a>
                @endif

                @if (isset($page_summary) && !empty($page_summary['buttons']))
                    @foreach ($page_summary['buttons'] as $k => $item)
                        <a href="@if (!empty($item['url'])) {{ url($item['url']) }} @else # @endif"
                            @if (!empty($item['id'])) id="{{ $item['id'] }}" @endif>
                            <button type="button"
                                class="btn btn-sm btn-primary mr-1">{{ $item['title'] }}</button>
                        </a>
                    @endforeach
                @endif

                @if (isset($page_summary) && !empty($page_summary['delete-button']))
                    <button type="button"
                        class="btn btn-sm btn-danger mr-1"
                        data-url="{{ $page_summary['delete-button']['url'] }}" id="deleteButton"
                        {{-- data-modal-toggle="deleteModal" --}}
                        data-redirect="{{ $page_summary['delete-button']['redirect'] }}">Delete</button>
                @endif


            </div>
        </div>
    @endif
</div>

 <!--begin::Toolbar-->
 <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
     @if (isset($page_summary) && !empty($page_summary['breadcrumb']))
         <!--begin::Toolbar container-->
         <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
             <!--begin::Page title-->
             <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                 <!--begin::Title-->
                 <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                     {{ isset($page_summary) ? $page_summary['title'] : 'No Title' }}</h1>
                 <!--end::Title-->
                 <!--begin::Breadcrumb-->
                 <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                     <!--begin::Item-->
                     <li class="breadcrumb-item text-muted">
                         <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                     </li>
                     <!--end::Item-->

                     @foreach ($page_summary['breadcrumb'] as $k => $item)
                         <!--begin::Item-->
                         <li class="breadcrumb-item">
                             <span class="bullet bg-gray-500 w-5px h-2px"></span>
                         </li>
                         <!--end::Item-->

                         @if ($loop->last)
                             <li class="breadcrumb-item text-muted">
                                 {{ $item['title'] }}
                             </li>
                         @else
                             <!--begin::Item-->
                             <li class="breadcrumb-item text-muted">
                                 <a class="text-gray-700 hover:text-primary" href="{{ url($item['page']) }}">
                                     {{ $item['title'] }}
                                 </a>
                             </li>
                             <!--end::Item-->
                         @endif
                     @endforeach

                 </ul>
                 <!--end::Breadcrumb-->
             </div>
             <!--end::Page title-->
             <!--begin::Actions-->
             <div class="d-flex align-items-center gap-2 gap-lg-3">

                 @if (isset($page_summary) && !empty($page_summary['back-button']))
                     <a class="btn btn-sm btn-secondary" href="{{ url($page_summary['back-button']['url']) }}">
                         Back </a>
                 @endif

                 @if (isset($page_summary) && !empty($page_summary['buttons']))
                     @foreach ($page_summary['buttons'] as $k => $item)
                         @if (isset($item['modal']) && $item['modal'])
                             <a href="#"
                                 @if (!empty($item['id'])) id="{{ $item['id'] }}" @endif>
                                 <button type="button"
                                     class="btn btn-sm btn-primary mr-1 btnAction" data-url="{{ url($item['url']) }}"  @if (!empty($item['callback'])) data-callback="{{ url($item['callback']) }}" @endif
                                     @if (isset($item['redirect'])) data-redirect="{{ url($item['redirect']) }}" @endif>{{ $item['title'] }}</button>
                             </a>
                         @else
                             <a href="@if (!empty($item['url'])) {{ url($item['url']) }} @else # @endif"
                                 @if (!empty($item['id'])) id="{{ $item['id'] }}" @endif>
                                 <button type="button"
                                     class="btn btn-sm btn-primary mr-1">{{ $item['title'] }}</button>
                             </a>
                         @endif
                     @endforeach
                 @endif

                 @if (isset($page_summary) && !empty($page_summary['delete-button']))
                     <button type="button" class="btn btn-sm btn-danger mr-1 btndelete"
                         data-url="{{ $page_summary['delete-button']['url'] }}" id="deleteButton" {{-- data-modal-toggle="deleteModal" --}}
                         data-actiontype="redirect">Delete</button>
                 @endif


                 {{-- <!--begin::Secondary button-->
                 <a href="#" class="btn btn-sm fw-bold btn-secondary" data-bs-toggle="modal"
                     data-bs-target="#kt_modal_create_app">Rollover</a>
                 <!--end::Secondary button-->
                 <!--begin::Primary button-->
                 <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                     data-bs-target="#kt_modal_new_target">Add Target</a>
                 <!--end::Primary button--> --}}
             </div>
             <!--end::Actions-->
         </div>
         <!--end::Toolbar container-->
     @endif
 </div>
 <!--end::Toolbar-->

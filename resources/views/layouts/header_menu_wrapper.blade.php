    <!--begin::Menu wrapper-->
    <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
            id="kt_app_header_menu" data-kt-menu="true">
            
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('dashboard') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-home fs-5 me-2"></i>
                        Dashboard
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('itr.*') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('itr.summary', ['fy' => date('Y') . '-' . substr(date('Y') + 1, 2, 2)]) }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-calculator fs-5 me-2"></i>
                        ITR Summary
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('reminders.*') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('reminders.index') }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-alarm fs-5 me-2"></i>
                        Reminders
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('audits.*') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('audits.index') }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-activity fs-5 me-2"></i>
                        Audit Logs
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            @php
                $gp = \App\Models\GstProfile::where('user_id', auth()->id() ?? 1)->first();
            @endphp
            @if ($gp && $gp->gst_type === 'composition')
            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('gst.composition.*') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('gst.composition.dashboard') }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-calculator fs-5 me-2"></i>
                        GSTR-4 (Composition)
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->
            @endif

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item {{ request()->routeIs('gst.settings*') ? 'here show menu-here-bg' : '' }} menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <a href="{{ route('gst.settings') }}" class="menu-link">
                    <span class="menu-title">
                        <i class="ki-duotone ki-setting-3 fs-5 me-2"></i>
                        GST Settings
                    </span>
                </a>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-title">Reports</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-title">Settings</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div
                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-500px w-xxl-600px">
                    <!--begin:Dashboards menu-->
                    <div class="menu-active-bg pt-1 pb-3 px-3 py-lg-6 px-lg-6"
                        data-kt-menu-dismiss="true">
                        <!--begin:Row-->
                        <div class="row">
                            <!--begin:Col-->
                            <div class="col-lg-12">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Heading-->
                                        <h4
                                            class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">
                                            General Settings</h4>
                                        <!--end:Heading-->
                                        <!--begin:Menu item-->
                                        
                                        <div class="menu-item p-0 m-0">
                                        <!--begin:Menu link-->
                                        {{-- <a href="{{ route('units.index') }}" class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                            </span>
                                            <span class="menu-title">Units</span>
                                        </a> --}}
                                        <!--end:Menu link-->
                                    {{-- </div>
                                         <div class="menu-item p-0 m-0">
                                        <!--begin:Menu link-->
                                        <a href="{{ route('gsts.index') }}" class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                            </span>
                                            <span class="menu-title">Gst</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div> --}}
                                      {{-- <div class="menu-item p-0 m-0">
                                        <!--begin:Menu link-->
                                        <a href="{{ route('taxgroups.index') }}" class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                            </span>
                                            <span class="menu-title">Tax Groups</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div> --}}
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Heading-->
                                        <h4
                                            class="fs-6 fs-lg-4 text-gray-800 fw-bold mt-3 mb-3 ms-4">
                                            User Settings</h4>
                                        <!--end:Heading-->
                                        <!--begin:Menu item-->
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a  href="{{route('users.index')}}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span
                                                        class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Users</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a  href="{{route('usergroups.index')}}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span
                                                        class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">User Groups</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="toolbars/classic.html" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span
                                                        class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Calendar</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Col-->
                        </div>
                        <!--end:Row-->
                    </div>
                    <!--end:Dashboards menu-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

            <!--begin:Menu item-->
            <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="bottom-start"
                class="menu-item menu-lg-down-accordion me-0 me-lg-2">
                <!--begin:Menu link-->
                <span class="menu-link">
                    <span class="menu-title">Masters</span>
                    <span class="menu-arrow d-lg-none"></span>
                </span>
                <!--end:Menu link-->
                <!--begin:Menu sub-->
                <div
                    class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown p-0 w-100 w-lg-500px w-xxl-600px">
                    <!--begin:Dashboards menu-->
                    <div class="menu-active-bg pt-1 pb-3 px-3 py-lg-6 px-lg-6"
                        data-kt-menu-dismiss="true">
                        <!--begin:Row-->
                        <div class="row">
                            <!--begin:Col-->
                            <div class="col-lg-12">
                                <!--begin:Row-->
                                <div class="row">
                                    <!--begin:Col-->
                                    <div class="col-lg-6 mb-3">
                                        <!--begin:Menu item-->
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="{{ route('services.index') }}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Services</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="{{ route('packages.index') }}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Packages</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        {{-- <div class="menu-item p-0 m-0">
                                            <!--begin:Menu link-->
                                            <a href="{{ route('offers.index') }}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Offers</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        <div class="menu-item p-0 m-0">
                                            {{-- <!--begin:Menu link-->
                                            <a href="{{ route('memberships.index') }}" class="menu-link">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot bg-gray-300i h-6px w-6px"></span>
                                                </span>
                                                <span class="menu-title">Memberships</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div> --}}
                                        <!--end:Menu item-->
                                    </div>
                                    <!--end:Col-->
                                </div>
                                <!--end:Row-->
                            </div>
                            <!--end:Col-->
                        </div>
                        <!--end:Row-->
                    </div>
                    <!--end:Dashboards menu-->
                </div>
                <!--end:Menu sub-->
            </div>
            <!--end:Menu item-->

        </div>
        <!--end::Menu-->
    </div>
    <!--end::Menu wrapper-->

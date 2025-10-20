@php
    $gp = \App\Models\GstProfile::where('user_id', auth()->id() ?? 1)->first();
@endphp

<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <!--begin::Sidebar mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>
        <!--end::Sidebar mobile toggle-->
        
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ route('dashboard') }}" class="d-lg-none">
                <span class="text-primary fw-bold fs-3">TaxEase</span>
            </a>
        </div>
        <!--end::Mobile logo-->
        
        <!--begin::Header wrapper-->
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <!--begin::Menu wrapper-->
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('itr.*') ? 'active' : '' }}" href="{{ route('itr.summary', ['fy' => date('Y') . '-' . substr(date('Y') + 1, 2, 2)]) }}">
                            <span class="menu-title">ITR Summary</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('reminders.*') ? 'active' : '' }}" href="{{ route('reminders.index') }}">
                            <span class="menu-title">Reminders</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('audits.*') ? 'active' : '' }}" href="{{ route('audits.index') }}">
                            <span class="menu-title">Audit Logs</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    
                    @if ($gp && $gp->gst_type === 'composition')
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('gst.composition.*') ? 'active' : '' }}" href="{{ route('gst.composition.dashboard') }}">
                            <span class="menu-title">GSTR-4 (Composition)</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                    @endif
                    
                    <!--begin:Menu item-->
                    <div class="menu-item me-0 me-lg-2">
                        <a class="menu-link py-3 {{ request()->routeIs('gst.settings*') ? 'active' : '' }}" href="{{ route('gst.settings') }}">
                            <span class="menu-title">GST Settings</span>
                        </a>
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
            
            <!--begin::Navbar-->
            <div class="app-navbar flex-shrink-0">
                @auth
                <!--begin::User menu-->
                <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                    <!--begin::Menu wrapper-->
                    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        <div class="symbol-label fs-3 fw-semibold text-success">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    </div>
                    <!--begin::User account menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label fs-3 fw-semibold text-success">{{ substr(Auth::user()->name, 0, 1) }}</div>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}</div>
                                    <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                                </div>
                            </div>
                        </div>
                        <!--end::Menu item-->
                        
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        
                        @if (Route::has('profile.edit'))
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <a href="{{ route('profile.edit') }}" class="menu-link px-5">My Profile</a>
                        </div>
                        <!--end::Menu item-->
                        @endif
                        
                        <!--begin::Menu separator-->
                        <div class="separator my-2"></div>
                        <!--end::Menu separator-->
                        
                        <!--begin::Menu item-->
                        <div class="menu-item px-5">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="menu-link px-5">Sign Out</a>
                            </form>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::User account menu-->
                    <!--end::Menu wrapper-->
                </div>
                <!--end::User menu-->
                @else
                <!--begin::Login link-->
                <div class="app-navbar-item">
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                </div>
                <!--end::Login link-->
                @endauth
            </div>
            <!--end::Navbar-->
        </div>
        <!--end::Header wrapper-->
    </div>
    <!--end::Header container-->
</div>
<!--end::Header-->

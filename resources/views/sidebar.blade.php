<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
  data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <!--begin::Logo-->
  <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="{{ url('/') }}">
      <span style="font-weight: 900; font-size: 24px; color: white;">TaxEase</span>
    </a>
    <!--end::Logo image-->
    <!--begin::Sidebar toggle-->
    <div id="kt_app_sidebar_toggle"
      class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
      data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
      data-kt-toggle-name="app-sidebar-minimize">
      <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
        <span class="path1"></span>
        <span class="path2"></span>
      </i>
    </div>
    <!--end::Sidebar toggle-->
  </div>
  <!--end::Logo-->
  <!--begin::sidebar menu-->
  <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
      <!--begin::Scroll wrapper-->
      <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
        data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
          data-kt-menu="true" data-kt-menu-expand="false">

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-home fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Dashboard</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.*') ? 'active' : '' }}" href="{{ route('gst.stepper') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-document fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GSTR-3B</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          @php
            $gp = \App\Models\GstProfile::where('user_id', auth()->id() ?? 1)->first();
          @endphp

          @if ($gp && $gp->gst_type === 'composition')
          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.composition.*') ? 'active' : '' }}" href="{{ route('gst.composition.dashboard') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-calculator fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GSTR-4 (Composition)</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          @endif

          <!--begin:Menu item-->
          <div class="menu-item pt-5">
            <!--begin:Menu content-->
            <div class="menu-content">
              <span class="menu-heading fw-bold text-uppercase fs-7">Management</span>
            </div>
            <!--end:Menu content-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('itr.*') ? 'active' : '' }}" href="{{ route('itr.summary', ['fy' => date('Y').'-'.substr(date('Y')+1,2,2)]) }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-calculator fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">ITR Summary</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}" href="{{ route('reminders.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-alarm fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Reminders</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.returns.*') ? 'active' : '' }}" href="{{ route('gst.returns.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-file-text fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GST Returns</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('audits.*') ? 'active' : '' }}" href="{{ route('audits.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-activity fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Audit Logs</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item pt-5">
            <!--begin:Menu content-->
            <div class="menu-content">
              <span class="menu-heading fw-bold text-uppercase fs-7">Finance</span>
            </div>
            <!--end:Menu content-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('incomes.*') ? 'active' : '' }}" href="{{ route('incomes.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-plus fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Income Logs</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-minus fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Expense Logs</span>
            </a>
            <!--end:Menu link-->
          </div>

          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('sales.invoices.*') ? 'active' : '' }}" href="{{ route('sales.invoices.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-bill fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Sales Invoices</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('purchases.invoices.*') ? 'active' : '' }}" href="{{ route('purchases.invoices.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-receipt fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Purchase Invoices</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item pt-5">
            <!--begin:Menu content-->
            <div class="menu-content">
              <span class="menu-heading fw-bold text-uppercase fs-7">Settings</span>
            </div>
            <!--end:Menu content-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.settings*') ? 'active' : '' }}" href="{{ route('gst.settings') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-setting-3 fs-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GST Settings</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

        </div>
        <!--end::Menu-->
      </div>
      <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
  </div>
  <!--end::sidebar menu-->
</div>
<!--end::Sidebar-->

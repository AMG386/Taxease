<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="280px"
  data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <!--begin::Logo-->
  <div class="app-sidebar-logo px-6 py-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="{{ url('/') }}" class="d-flex align-items-center text-decoration-none">
      <div class="symbol symbol-50px me-3">
        <div class="symbol-label bg-gradient-primary">
          <i class="ki-duotone ki-chart-pie-4 fs-2x text-white">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
          </i>
        </div>
      </div>
      <div class="d-flex flex-column">
        <span class="fw-bolder text-white fs-3">TaxEase</span>
        <span class="text-white opacity-75 fs-7">Tax Management</span>
      </div>
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

          <!--begin:Menu item - Dashboard-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-element-11 fs-2 text-primary">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                </i>
              </span>
              <span class="menu-title">Dashboard</span>
              <span class="menu-badge">
                <span class="badge badge-light-primary badge-sm">Main</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - Quick GST-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.*') && !request()->routeIs('gst.returns.*') ? 'active' : '' }}" href="{{ route('gst.stepper') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-rocket fs-2 text-success">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GSTR-3B Filing</span>
              <span class="menu-badge">
                <span class="badge badge-light-success badge-sm">Quick</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu separator-->
          <div class="separator separator-dashed my-4"></div>
          <!--end:Menu separator-->

          <!--begin:Menu item - Tax Management Section Header-->
          <div class="menu-item pt-3">
            <!--begin:Menu content-->
            <div class="menu-content">
              <div class="d-flex align-items-center">
                <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Tax Management</span>
                <i class="ki-duotone ki-receipt fs-6 text-muted ms-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </div>
            </div>
            <!--end:Menu content-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - ITR Summary-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('itr.*') ? 'active' : '' }}" href="{{ route('itr.summary', ['fy' => date('Y').'-'.substr(date('Y')+1,2,2)]) }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-calculator fs-2 text-primary">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">ITR Summary</span>
              <span class="menu-badge">
                <span class="badge badge-light-primary badge-sm">{{ date('Y') }}-{{ substr(date('Y')+1,2,2) }}</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - GST Returns-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('gst.returns.*') ? 'active' : '' }}" href="{{ route('gst.returns.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-file-down fs-2 text-info">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">GST Returns</span>
              <span class="menu-badge">
                <span class="badge badge-light-info badge-sm">Track</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - Reminders-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}" href="{{ route('reminders.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-notification-bing fs-2 text-danger">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
              </span>
              <span class="menu-title">Reminders</span>
              <span class="menu-badge">
                <span class="badge badge-light-danger badge-sm">Due</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - Audit Logs-->
          <div class="menu-item">
            <!--begin:Menu link-->
            <a class="menu-link {{ request()->routeIs('audits.*') ? 'active' : '' }}" href="{{ route('audits.index') }}">
              <span class="menu-icon">
                <i class="ki-duotone ki-shield-search fs-2 text-warning">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
              </span>
              <span class="menu-title">Audit Logs</span>
              <span class="menu-badge">
                <span class="badge badge-light-warning badge-sm">Monitor</span>
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu separator-->
          <div class="separator separator-dashed my-4"></div>
          <!--end:Menu separator-->

          <!--begin:Menu item - Finance Section Header-->
          <div class="menu-item pt-3">
            <!--begin:Menu content-->
            <div class="menu-content">
              <div class="d-flex align-items-center">
                <span class="menu-heading fw-bold text-uppercase fs-7 text-muted">Financial Records</span>
                <i class="ki-duotone ki-chart-line-up fs-6 text-muted ms-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </div>
            </div>
            <!--end:Menu content-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - Income & Expenses-->
          <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
              <span class="menu-icon">
                <i class="ki-duotone ki-arrows-loop fs-2 text-success">
                  <span class="path1"></span>
                  <span class="path2"></span>
                </i>
              </span>
              <span class="menu-title">Income & Expenses</span>
              <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
              <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('incomes.*') ? 'active' : '' }}" href="{{ route('incomes.index') }}">
                  <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                  </span>
                  <span class="menu-title">Income Logs</span>
                  <span class="menu-badge">
                    <span class="badge badge-light-success badge-sm">+</span>
                  </span>
                </a>
              </div>
              <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                  <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                  </span>
                  <span class="menu-title">Expense Logs</span>
                  <span class="menu-badge">
                    <span class="badge badge-light-danger badge-sm">-</span>
                  </span>
                </a>
              </div>
            </div>
            <!--end:Menu sub-->
          </div>
          <!--end:Menu item-->

          <!--begin:Menu item - Invoice Management-->
          <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
            <!--begin:Menu link-->
            <span class="menu-link">
              <span class="menu-icon">
                <i class="ki-duotone ki-document-copy fs-2 text-primary">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
              </span>
              <span class="menu-title">Invoice Management</span>
              <span class="menu-arrow"></span>
            </span>
            <!--end:Menu link-->
            <!--begin:Menu sub-->
            <div class="menu-sub menu-sub-accordion">
              <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('sales.invoices.*') ? 'active' : '' }}" href="{{ route('sales.invoices.index') }}">
                  <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                  </span>
                  <span class="menu-title">Sales Invoices</span>
                  <span class="menu-badge">
                    <span class="badge badge-light-success badge-sm">Sale</span>
                  </span>
                </a>
              </div>
              <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('purchases.invoices.*') ? 'active' : '' }}" href="{{ route('purchases.invoices.index') }}">
                  <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                  </span>
                  <span class="menu-title">Purchase Invoices</span>
                  <span class="menu-badge">
                    <span class="badge badge-light-info badge-sm">Buy</span>
                  </span>
                </a>
              </div>
            </div>
            <!--end:Menu sub-->
          </div>
          <!--end:Menu item-->
          <!--end:Menu item-->

        </div>
        <!--end::Menu-->
      </div>
      <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
  </div>
  <!--end::sidebar menu-->

  <!--begin::Footer-->
  <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
    <div class="d-flex align-items-center">
      <div class="d-flex align-items-center flex-grow-1">
        <div class="symbol symbol-35px me-3">
          <div class="symbol-label bg-light-primary">
            <i class="ki-duotone ki-user fs-3 text-primary">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
        </div>
        <div class="d-flex flex-column">
          <div class="fw-semibold fs-7 text-gray-800">{{ Auth::user()->name ?? 'User' }}</div>
          <div class="fw-normal fs-8 text-gray-600">Tax Professional</div>
        </div>
      </div>
      <div class="ms-2">
        <a href="#" class="btn btn-sm btn-icon btn-active-color-primary btn-outline btn-outline-secondary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
          <i class="ki-duotone ki-setting-3 fs-6">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
            <span class="path4"></span>
            <span class="path5"></span>
          </i>
        </a>
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
          <div class="menu-item px-3">
            <a href="{{ route('profile.edit') }}" class="menu-link px-3">
              <span class="menu-icon">
                <i class="ki-duotone ki-profile-user fs-6">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                </i>
              </span>
              Profile Settings
            </a>
          </div>
          <div class="separator my-2"></div>
          <div class="menu-item px-3">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="menu-link px-3">
                <span class="menu-icon">
                  <i class="ki-duotone ki-entrance-left fs-6">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
                Sign Out
              </a>
            </form>
          </div>
        </div>
        <!--end::Menu-->
      </div>
    </div>
  </div>
  <!--end::Footer-->
</div>
<!--end::Sidebar-->

@extends('layouts.app')
@section('title','Dashboard')

@push('head')
    {{-- Only needed if your main layout doesn’t already include this --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
    <style>
        /* Custom Dashboard Enhancements */
        .hover-elevate-up { 
            transition: all 0.3s ease !important; 
        }
        .hover-elevate-up:hover { 
            transform: translateY(-5px) !important; 
            box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important; 
        }
        .hoverable { 
            transition: all 0.3s ease !important; 
        }
        .hoverable:hover { 
            transform: translateY(-8px) !important; 
            box-shadow: 0 15px 50px rgba(0,0,0,0.15) !important; 
        }
        .bg-gradient-primary { 
            background: linear-gradient(135deg, #3E97FF 0%, #1B84FF 100%) !important; 
        }
        .bg-gradient-success { 
            background: linear-gradient(135deg, #50CD89 0%, #3AC977 100%) !important; 
        }
        .bg-gradient-warning { 
            background: linear-gradient(135deg, #FFC700 0%, #FFB800 100%) !important; 
        }
        .bg-gradient-info { 
            background: linear-gradient(135deg, #7239EA 0%, #5014D0 100%) !important; 
        }
        .bg-gradient-danger { 
            background: linear-gradient(135deg, #F1416C 0%, #E21E3A 100%) !important; 
        }
        .bg-gradient-dark { 
            background: linear-gradient(135deg, #181C32 0%, #0F1419 100%) !important; 
        }
        .chart-container { 
            border-radius: 8px; 
            background: #f9f9f9; 
            padding: 10px; 
        }
        .progress { 
            border-radius: 6px; 
            overflow: hidden; 
        }
        .progress-bar { 
            border-radius: 6px; 
            transition: width 0.6s ease; 
        }
        .symbol-label { 
            transition: all 0.3s ease; 
        }
        .card:hover .symbol-label { 
            transform: scale(1.1); 
        }
        .btn-outline-dashed { 
            border-style: dashed !important; 
            border-width: 2px !important; 
        }
        @keyframes fadeInUp { 
            from { 
                opacity: 0; 
                transform: translateY(30px); 
            } 
            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        }
        .card { 
            animation: fadeInUp 0.6s ease-out; 
        }
        .badge { 
            font-weight: 600; 
            letter-spacing: 0.5px; 
        }
        .btn-flex { 
            display: flex; 
            align-items: center; 
            justify-content: flex-start; 
            padding: 1rem; 
            border-radius: 8px; 
            transition: all 0.3s ease; 
        }
        .btn-flex:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.1); 
        }
        .mb-8 { 
            margin-bottom: 3rem !important; 
        }
        .mb-10 { 
            margin-bottom: 4rem !important; 
        }
        .pt-6 { 
            padding-top: 2rem !important; 
        }
        .pb-4 { 
            padding-bottom: 1rem !important; 
        }
        .p-8 { 
            padding: 3rem !important; 
        }
        .p-9 { 
            padding: 3.5rem !important; 
        }
        .h-80px { 
            height: 80px !important; 
        }
        .g-8 > * { 
            padding: 1.5rem !important; 
        }
        .g-8 { 
            --bs-gutter-x: 1.5rem; 
            --bs-gutter-y: 1.5rem; 
        }
        .g-5 { 
            --bs-gutter-x: 2rem; 
            --bs-gutter-y: 2rem; 
        }
        .fs-2x { 
            font-size: 1.75rem !important; 
        }
        .shadow-lg { 
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important; 
        }
        /* Enhanced Decorative Elements */
        .position-relative .position-absolute {
            z-index: 0;
        }
        .position-relative > .card-header,
        .position-relative > .card-body {
            position: relative;
            z-index: 1;
        }
        /* Fallback for older browsers */
        .bg-gradient-primary {
            background-color: #3E97FF !important;
            background-image: linear-gradient(135deg, #3E97FF 0%, #1B84FF 100%) !important;
        }
        .bg-gradient-success {
            background-color: #50CD89 !important;
            background-image: linear-gradient(135deg, #50CD89 0%, #3AC977 100%) !important;
        }
        .bg-gradient-warning {
            background-color: #FFC700 !important;
            background-image: linear-gradient(135deg, #FFC700 0%, #FFB800 100%) !important;
        }
        .bg-gradient-info {
            background-color: #7239EA !important;
            background-image: linear-gradient(135deg, #7239EA 0%, #5014D0 100%) !important;
        }
        .bg-gradient-danger {
            background-color: #F1416C !important;
            background-image: linear-gradient(135deg, #F1416C 0%, #E21E3A 100%) !important;
        }
        .bg-gradient-dark {
            background-color: #181C32 !important;
            background-image: linear-gradient(135deg, #181C32 0%, #0F1419 100%) !important;
        }
        /* Button Enhancements */
        .btn-light-primary {
            background-color: rgba(62, 151, 255, 0.1) !important;
            border-color: rgba(62, 151, 255, 0.2) !important;
            color: #1B84FF !important;
        }
        .btn-light-primary:hover {
            background-color: rgba(62, 151, 255, 0.2) !important;
            border-color: rgba(62, 151, 255, 0.3) !important;
            color: #1B84FF !important;
        }
        .btn-light-success {
            background-color: rgba(80, 205, 137, 0.1) !important;
            border-color: rgba(80, 205, 137, 0.2) !important;
            color: #3AC977 !important;
        }
        .btn-light-success:hover {
            background-color: rgba(80, 205, 137, 0.2) !important;
            border-color: rgba(80, 205, 137, 0.3) !important;
            color: #3AC977 !important;
        }
        .btn-light-info {
            background-color: rgba(114, 57, 234, 0.1) !important;
            border-color: rgba(114, 57, 234, 0.2) !important;
            color: #7239EA !important;
        }
        .btn-light-info:hover {
            background-color: rgba(114, 57, 234, 0.2) !important;
            border-color: rgba(114, 57, 234, 0.3) !important;
            color: #7239EA !important;
        }
        .btn-light-warning {
            background-color: rgba(255, 199, 0, 0.1) !important;
            border-color: rgba(255, 199, 0, 0.2) !important;
            color: #FFB800 !important;
        }
        .btn-light-warning:hover {
            background-color: rgba(255, 199, 0, 0.2) !important;
            border-color: rgba(255, 199, 0, 0.3) !important;
            color: #FFB800 !important;
        }
        .btn-light-danger {
            background-color: rgba(241, 65, 108, 0.1) !important;
            border-color: rgba(241, 65, 108, 0.2) !important;
            color: #F1416C !important;
        }
        .btn-light-danger:hover {
            background-color: rgba(241, 65, 108, 0.2) !important;
            border-color: rgba(241, 65, 108, 0.3) !important;
            color: #F1416C !important;
        }
        .btn-light-dark {
            background-color: rgba(24, 28, 50, 0.1) !important;
            border-color: rgba(24, 28, 50, 0.2) !important;
            color: #181C32 !important;
        }
        .btn-light-dark:hover {
            background-color: rgba(24, 28, 50, 0.2) !important;
            border-color: rgba(24, 28, 50, 0.3) !important;
            color: #181C32 !important;
        }
        @media (max-width: 768px) {
            .fs-2hx { 
                font-size: 2rem !important; 
            }
            .fs-2x { 
                font-size: 1.5rem !important; 
            }
            .symbol-70px { 
                width: 50px !important; 
                height: 50px !important; 
            }
            .symbol-80px { 
                width: 60px !important; 
                height: 60px !important; 
            }
            .p-8 { 
                padding: 1.5rem !important; 
            }
            .p-9 { 
                padding: 2rem !important; 
            }
            .mb-8 { 
                margin-bottom: 2rem !important; 
            }
            .mb-10 { 
                margin-bottom: 2.5rem !important; 
            }
            .h-80px { 
                height: 70px !important; 
            }
        }
    </style>
@endsection

@section('content')
{{-- Hero Section with Welcome Banner --}}
<div class="row g-5 g-xl-8 mb-8">
  <div class="col-12">
    <div class="card bg-gradient-primary h-100">
      <div class="card-body d-flex align-items-center justify-content-between p-8">
        <div class="d-flex flex-column">
          <h1 class="text-white fw-bolder mb-3">Welcome to TaxEase Dashboard</h1>
          <p class="text-white opacity-75 fs-5 mb-4">Comprehensive tax management and compliance solution</p>
          <div class="d-flex gap-3">
            <span class="badge badge-light-success fs-6 px-4 py-2">GST Ready</span>
            <span class="badge badge-light-info fs-6 px-4 py-2">ITR Compliant</span>
            <span class="badge badge-light-warning fs-6 px-4 py-2">Real-time Analytics</span>
          </div>
        </div>
        <div class="d-none d-lg-block">
          <div class="symbol symbol-100px">
            <div class="symbol-label bg-white bg-opacity-20">
              <i class="ki-duotone ki-chart-pie-4 fs-2x text-white">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
              </i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Key Metrics Cards Row --}}
<div class="row g-5 g-xl-8 mb-8">
  <!-- GST Payable Card -->
  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
    <div class="card card-xl-stretch h-100 shadow-sm hover-elevate-up">
      <div class="card-body d-flex align-items-center p-6">
        <div class="symbol symbol-60px me-6 flex-shrink-0">
          <div class="symbol-label bg-light-danger">
            <i class="ki-duotone ki-wallet fs-2x text-danger">
              <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
            </i>
          </div>
        </div>
        <div class="d-flex flex-column flex-grow-1 min-w-0">
          <span class="text-muted fw-semibold fs-7 mb-1">GST Payable</span>
          <span class="text-dark fw-bold fs-2 text-truncate mb-1" id="gstPayable">₹0</span>
          <div class="d-flex align-items-center">
            <i class="ki-duotone ki-calendar-2 fs-7 text-muted me-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
            <span class="text-muted fw-semibold fs-8 text-truncate" id="nextDue">—</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ITC Available Card -->
  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
    <div class="card card-xl-stretch h-100 shadow-sm hover-elevate-up">
      <div class="card-body d-flex align-items-center p-6">
        <div class="symbol symbol-60px me-6 flex-shrink-0">
          <div class="symbol-label bg-light-success">
            <i class="ki-duotone ki-cheque fs-2x text-success">
              <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span>
            </i>
          </div>
        </div>
        <div class="d-flex flex-column flex-grow-1 min-w-0">
          <span class="text-muted fw-semibold fs-7 mb-1">ITC Available</span>
          <span class="text-dark fw-bold fs-2 text-truncate mb-1" id="itcAvailable">₹0</span>
          <span class="text-success fw-semibold fs-8">Input Tax Credit</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Sales Tax Collected Card -->
  <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
    <div class="card card-xl-stretch h-100 shadow-sm hover-elevate-up">
      <div class="card-body d-flex align-items-center p-6">
        <div class="symbol symbol-60px me-6 flex-shrink-0">
          <div class="symbol-label bg-light-primary">
            <i class="ki-duotone ki-chart-line-up fs-2x text-primary">
              <span class="path1"></span><span class="path2"></span>
            </i>
          </div>
        </div>
        <div class="d-flex flex-column flex-grow-1 min-w-0">
          <span class="text-muted fw-semibold fs-7 mb-1">Sales Tax Collected</span>
          <span class="text-dark fw-bold fs-2 text-truncate mb-1" id="salesTaxCollected">₹0</span>
          <span class="text-primary fw-semibold fs-8">Output Tax</span>
        </div>
      </div>
    </div>
  </div>

  <!-- ITR Progress Card -->
  <div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card card-xl-stretch h-100 shadow-sm hover-elevate-up">
      <div class="card-body d-flex align-items-center p-6">
        <div class="symbol symbol-60px me-6 flex-shrink-0">
          <div class="symbol-label bg-light-warning">
            <i class="ki-duotone ki-document fs-2x text-warning">
              <span class="path1"></span><span class="path2"></span>
            </i>
          </div>
        </div>
        <div class="d-flex flex-column flex-grow-1 min-w-0">
          <span class="text-muted fw-semibold fs-7 mb-2">ITR Progress</span>
          <div class="progress h-10px bg-light-warning mb-2">
            <div id="itrProgress" class="progress-bar bg-warning" role="progressbar" style="width:0%"></div>
          </div>
          <span class="text-dark fw-bold fs-7" id="itrProgressText">0%</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ITR Financial Overview Cards --}}
<div class="row g-5 mb-8" id="itr-cards">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bold fs-2 mb-1 text-dark">Financial Year Overview</span>
          <span class="text-muted fw-semibold fs-7">Income Tax Return summary and calculations</span>
        </h3>
        <div class="card-toolbar">
          <div class="symbol symbol-30px">
            <div class="symbol-label bg-light-success">
              <i class="ki-duotone ki-chart-pie-simple fs-5 text-success"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="row g-6">
          <div class="col-md-4">
            <div class="card bg-light-success border-0 h-100">
              <div class="card-body p-6 text-center">
                <div class="symbol symbol-50px mx-auto mb-4">
                  <div class="symbol-label bg-success">
                    <i class="ki-duotone ki-arrow-up fs-2x text-white">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </div>
                </div>
                <div class="text-gray-600 fw-semibold fs-6 mb-2">ITR – Gross Receipts (FY)</div>
                <div class="fs-2hx fw-bolder text-gray-900 mb-2" id="itr-gross">₹0.00</div>
                <div class="text-gray-500 fw-semibold fs-7" id="itr-fy-label">FY —</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-light-warning border-0 h-100">
              <div class="card-body p-6 text-center">
                <div class="symbol symbol-50px mx-auto mb-4">
                  <div class="symbol-label bg-warning">
                    <i class="ki-duotone ki-arrow-down fs-2x text-white">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </div>
                </div>
                <div class="text-gray-600 fw-semibold fs-6 mb-2">ITR – Total Expenses (FY)</div>
                <div class="fs-2hx fw-bolder text-gray-900 mb-2" id="itr-expenses">₹0.00</div>
                <div class="text-gray-500 fw-semibold fs-7">Includes ITC-eligible costs</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-light-primary border-0 h-100">
              <div class="card-body p-6 text-center">
                <div class="symbol symbol-50px mx-auto mb-4">
                  <div class="symbol-label bg-primary">
                    <i class="ki-duotone ki-chart-line-up fs-2x text-white">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </div>
                </div>
                <div class="text-gray-600 fw-semibold fs-6 mb-2">ITR – Net Profit (FY)</div>
                <div class="fs-2hx fw-bolder text-gray-900 mb-2" id="itr-profit">₹0.00</div>
                <div class="text-gray-500 fw-semibold fs-7">Income − Expenses</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Analytics & Data Section --}}
<div class="row g-5 mb-8">
  <!-- GST Chart Section -->
  <div class="col-xl-8 col-lg-12">
    <div class="card card-xl-stretch h-100 shadow-sm">
      <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bold fs-3 mb-1 text-dark">GST Analytics Dashboard</span>
          <span class="text-muted fw-semibold fs-7">Monthly trends and performance metrics</span>
        </h3>
        <div class="card-toolbar">
          <div class="d-flex align-items-center gap-3">
            <div class="symbol symbol-25px">
              <div class="symbol-label bg-light-primary">
                <i class="ki-duotone ki-chart-simple fs-6 text-primary"></i>
              </div>
            </div>
            <span class="badge badge-light-primary fs-8">Real-time Data</span>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div id="gstChart" style="height:350px; min-height:350px;" class="chart-container"></div>
      </div>
    </div>
  </div>

  <!-- Recent Invoices Section -->
  <div class="col-xl-4 col-lg-12">
    <div class="card card-xl-stretch h-100 shadow-sm">
      <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bold fs-3 mb-1 text-dark">Recent Transactions</span>
          <span class="text-muted fw-semibold fs-7">Latest invoice activities</span>
        </h3>
        <div class="card-toolbar d-flex gap-2">
          <button type="button" class="btn btn-sm btn-light-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#jsonImportModal">
            <i class="ki-duotone ki-upload fs-6 me-1">
              <span class="path1"></span><span class="path2"></span>
            </i>
            Import JSON
          </button>
          <button type="button" class="btn btn-sm btn-icon btn-light btn-active-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-dots-vertical fs-5">
              <span class="path1"></span><span class="path2"></span><span class="path3"></span>
            </i>
          </button>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="table-responsive">
          <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
            <thead>
              <tr class="fw-bold text-muted border-bottom-2 border-gray-200">
                <th class="min-w-120px">Invoice</th>
                <th class="min-w-70px">Type</th>
                <th class="min-w-80px">Date</th>
                <th class="min-w-80px text-end">Amount</th>
              </tr>
            </thead>
            <tbody id="recentInvoicesBody">
              <tr>
                <td colspan="4" class="text-center py-8">
                  <div class="d-flex flex-column align-items-center">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 2.5rem; height: 2.5rem;">
                      <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="text-muted fw-semibold fs-6">Loading transactions...</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Recent Activities Section --}}
<div class="row g-5 mb-8">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header border-0 pt-6">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bold fs-3 mb-1 text-dark">Recent Financial Activities</span>
          <span class="text-muted fw-semibold fs-7">Latest income and expense transactions</span>
        </h3>
        <div class="card-toolbar d-flex gap-3">
          @if(Route::has('incomes.index'))
            <a href="{{ route('incomes.index') }}" class="btn btn-sm btn-light-success d-flex align-items-center">
              <i class="ki-duotone ki-arrow-up fs-6 me-1">
                <span class="path1"></span><span class="path2"></span>
              </i>
              View Incomes
            </a>
          @else
            <a href="#" class="btn btn-sm btn-light-success d-flex align-items-center" onclick="showComingSoon('Income Management')">
              <i class="ki-duotone ki-arrow-up fs-6 me-1">
                <span class="path1"></span><span class="path2"></span>
              </i>
              View Incomes
            </a>
          @endif
          @if(Route::has('expenses.index'))
            <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-light-danger d-flex align-items-center">
              <i class="ki-duotone ki-arrow-down fs-6 me-1">
                <span class="path1"></span><span class="path2"></span>
              </i>
              View Expenses
            </a>
          @else
            <a href="#" class="btn btn-sm btn-light-danger d-flex align-items-center" onclick="showComingSoon('Expense Management')">
              <i class="ki-duotone ki-arrow-down fs-6 me-1">
                <span class="path1"></span><span class="path2"></span>
              </i>
              View Expenses
            </a>
          @endif
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="row g-6">
          <!-- Incomes Section -->
          <div class="col-md-6">
            <div class="card bg-light-success border-0 h-100">
              <div class="card-header border-0 pb-4">
                <h5 class="card-title d-flex align-items-center text-success">
                  <i class="ki-duotone ki-arrow-up fs-3 me-2">
                    <span class="path1"></span><span class="path2"></span>
                  </i>
                  Recent Incomes
                </h5>
              </div>
              <div class="card-body pt-0">
                <div class="table-responsive">
                  <table class="table table-borderless align-middle">
                    <thead>
                      <tr class="fw-bold text-gray-600 border-bottom border-gray-300">
                        <th class="ps-0">Date</th>
                        <th>Income Head</th>
                        <th class="text-end pe-0">Amount</th>
                      </tr>
                    </thead>
                    <tbody id="recent-incomes-body" class="text-gray-700">
                      <tr>
                        <td colspan="3" class="text-center py-6">
                          <div class="spinner-border spinner-border-sm text-success me-2"></div>
                          <span class="text-muted">Loading income data...</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Expenses Section -->
          <div class="col-md-6">
            <div class="card bg-light-danger border-0 h-100">
              <div class="card-header border-0 pb-4">
                <h5 class="card-title d-flex align-items-center text-danger">
                  <i class="ki-duotone ki-arrow-down fs-3 me-2">
                    <span class="path1"></span><span class="path2"></span>
                  </i>
                  Recent Expenses
                </h5>
              </div>
              <div class="card-body pt-0">
                <div class="table-responsive">
                  <table class="table table-borderless align-middle">
                    <thead>
                      <tr class="fw-bold text-gray-600 border-bottom border-gray-300">
                        <th class="ps-0">Date</th>
                        <th>Category</th>
                        <th class="text-end pe-0">Amount</th>
                      </tr>
                    </thead>
                    <tbody id="recent-expenses-body" class="text-gray-700">
                      <tr>
                        <td colspan="3" class="text-center py-6">
                          <div class="spinner-border spinner-border-sm text-danger me-2"></div>
                          <span class="text-muted">Loading expense data...</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Enhanced Reports Dashboard Section --}}
<div class="row g-5 mb-8">
  <div class="col-12">
    <div class="card card-xl-stretch shadow-lg border-0 bg-white position-relative overflow-hidden">
      {{-- Header Background Pattern --}}
      <div class="position-absolute top-0 end-0 w-50 h-100 opacity-5" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 20px 20px;">
      </div>
      
      <div class="card-header border-0 pt-8 pb-6 position-relative">
        <div class="d-flex align-items-center justify-content-between w-100">
          <div class="d-flex align-items-center">
            <div class="symbol symbol-70px me-4">
              <div class="symbol-label bg-gradient-primary">
                <i class="ki-duotone ki-element-11 fs-2x text-white">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                </i>
              </div>
            </div>
            <div>
              <h2 class="fw-bolder text-gray-900 mb-2 fs-2x">Business Intelligence & Reports</h2>
              <p class="text-muted fw-semibold fs-6 mb-0">Comprehensive analytics and compliance reporting suite with advanced insights</p>
            </div>
          </div>
          <div class="d-flex align-items-center gap-4">
            <div class="d-flex flex-column text-end">
              <span class="badge badge-light-primary fs-7 px-4 py-2 mb-2">6 Report Categories</span>
              <span class="badge badge-light-success fs-8 px-3 py-1">Real-time Data</span>
            </div>
            <div class="symbol symbol-50px">
              <div class="symbol-label bg-light-warning">
                <i class="ki-duotone ki-chart-pie-4 fs-2 text-warning">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body pt-0 pb-8 position-relative">
        <!-- Enhanced Report Categories Grid -->
        <div class="row g-5 g-xxl-8 mb-8">
          <!-- GST Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-primary h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 me-n3 mt-3">
                <div class="symbol symbol-100px opacity-15">
                  <div class="symbol-label bg-white bg-opacity-10 rounded-circle">
                    <i class="ki-duotone ki-receipt fs-3x text-white opacity-50"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-receipt fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">GST Reports</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Comprehensive GST compliance, analytics and automated filing solutions</p>
                
                <div class="d-flex flex-column gap-3">
                  @if(Route::has('gst.returns.index'))
                    <a href="{{ route('gst.returns.index') }}" class="btn btn-bg-white btn-color-primary btn-sm fw-bold py-3 shadow-sm hover-elevate-up">
                      <i class="ki-duotone ki-document-text me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      GST Returns
                    </a>
                  @else
                    <a href="#" class="btn btn-bg-white btn-color-primary btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('GST Returns')">
                      <i class="ki-duotone ki-document-text me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      GST Returns
                    </a>
                  @endif
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('GSTR-1 Report')">
                    <i class="ki-duotone ki-file-up me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    GSTR-1 Report
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('GSTR-3B Report')">
                    <i class="ki-duotone ki-file-down me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    GSTR-3B Report
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('ITC Report')">
                    <i class="ki-duotone ki-chart-line-down me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    ITC Report
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- ITR Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-success h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 translate-middle-y me-n3">
                <div class="symbol symbol-100px opacity-20">
                  <div class="symbol-label bg-white bg-opacity-10">
                    <i class="ki-duotone ki-document fs-4x text-white"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-document fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">ITR Reports</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Income tax returns, profit analysis and comprehensive tax computations</p>
                
                <div class="d-flex flex-column gap-3">
                  @if(Route::has('itr.summary'))
                    <a href="{{ route('itr.summary') }}" class="btn btn-bg-white btn-color-success btn-sm fw-bold py-3 shadow-sm hover-elevate-up">
                      <i class="ki-duotone ki-chart-pie-simple me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      ITR Summary
                    </a>
                  @else
                    <a href="#" class="btn btn-bg-white btn-color-success btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('ITR Summary')">
                      <i class="ki-duotone ki-chart-pie-simple me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      ITR Summary
                    </a>
                  @endif
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('P&L Report')">
                    <i class="ki-duotone ki-chart-line-up me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    P&L Report
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Detailed ITR')">
                    <i class="ki-duotone ki-file-text me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Detailed ITR
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Tax Computation')">
                    <i class="ki-duotone ki-calculator me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Tax Computation
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Financial Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-warning h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 translate-middle-y me-n3">
                <div class="symbol symbol-100px opacity-20">
                  <div class="symbol-label bg-white bg-opacity-10">
                    <i class="ki-duotone ki-chart-line-up fs-4x text-white"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-chart-line-up fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">Financial Reports</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Balance sheets, cash flow analysis and advanced financial insights</p>
                
                <div class="d-flex flex-column gap-3">
                  <a href="#" class="btn btn-bg-white btn-color-warning btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('Balance Sheet')">
                    <i class="ki-duotone ki-chart-simple me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Balance Sheet
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Cash Flow')">
                    <i class="ki-duotone ki-arrows-circle me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Cash Flow
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Trial Balance')">
                    <i class="ki-duotone ki-equalizer me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Trial Balance
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Financial Ratios')">
                    <i class="ki-duotone ki-chart-pie-3 me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Financial Ratios
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Invoice Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-info h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 translate-middle-y me-n3">
                <div class="symbol symbol-100px opacity-20">
                  <div class="symbol-label bg-white bg-opacity-10">
                    <i class="ki-duotone ki-bill fs-4x text-white"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-bill fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">Invoice Analytics</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Sales and purchase invoice insights with aging and trend analysis</p>
                
                <div class="d-flex flex-column gap-3">
                  @if(Route::has('sales.invoices.index'))
                    <a href="{{ route('sales.invoices.index') }}" class="btn btn-bg-white btn-color-info btn-sm fw-bold py-3 shadow-sm hover-elevate-up">
                      <i class="ki-duotone ki-financial-schedule me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      Sales Invoices
                    </a>
                  @else
                    <a href="#" class="btn btn-bg-white btn-color-info btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('Sales Invoices')">
                      <i class="ki-duotone ki-financial-schedule me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      Sales Invoices
                    </a>
                  @endif
                  @if(Route::has('purchases.invoices.index'))
                    <a href="{{ route('purchases.invoices.index') }}" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up">
                      <i class="ki-duotone ki-shopping-cart me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      Purchase Invoices
                    </a>
                  @else
                    <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Purchase Invoices')">
                      <i class="ki-duotone ki-shopping-cart me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                      Purchase Invoices
                    </a>
                  @endif
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Sales Summary')">
                    <i class="ki-duotone ki-chart-simple-3 me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Sales Summary
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Invoice Aging')">
                    <i class="ki-duotone ki-timer me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Invoice Aging
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Audit Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-danger h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 translate-middle-y me-n3">
                <div class="symbol symbol-100px opacity-20">
                  <div class="symbol-label bg-white bg-opacity-10">
                    <i class="ki-duotone ki-security-check fs-4x text-white"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-security-check fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">Audit & Compliance</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Audit trails, compliance verification and data validation reports</p>
                
                <div class="d-flex flex-column gap-3">
                  <a href="#" class="btn btn-bg-white btn-color-danger btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('Audit Trail')">
                    <i class="ki-duotone ki-search-list me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Audit Trail
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Compliance Report')">
                    <i class="ki-duotone ki-verify me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Compliance Report
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Data Validation')">
                    <i class="ki-duotone ki-check-square me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Data Validation
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Discrepancies')">
                    <i class="ki-duotone ki-information me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Discrepancies
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Management Reports -->
          <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card bg-gradient-dark h-100 hoverable shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 translate-middle-y me-n3">
                <div class="symbol symbol-100px opacity-20">
                  <div class="symbol-label bg-white bg-opacity-10">
                    <i class="ki-duotone ki-graph-up fs-4x text-white"></i>
                  </div>
                </div>
              </div>
              
              <div class="card-body p-8 text-center position-relative">
                <div class="symbol symbol-80px mx-auto mb-6">
                  <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                    <i class="ki-duotone ki-graph-up fs-2x text-white">
                      <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                    </i>
                  </div>
                </div>
                <h3 class="fw-bolder text-white mb-4 fs-2">Executive Dashboard</h3>
                <p class="text-white opacity-85 fw-semibold mb-6 fs-6">Business insights, KPI analytics and strategic forecasting tools</p>
                
                <div class="d-flex flex-column gap-3">
                  <a href="#" class="btn btn-bg-white btn-color-dark btn-sm fw-bold py-3 shadow-sm hover-elevate-up" onclick="showComingSoon('Executive Dashboard')">
                    <i class="ki-duotone ki-chart-line me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Executive Dashboard
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Trend Analysis')">
                    <i class="ki-duotone ki-arrow-up-right me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Trend Analysis
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('Forecasting')">
                    <i class="ki-duotone ki-abstract-26 me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    Forecasting
                  </a>
                  <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-white btn-sm text-white py-3 hover-elevate-up" onclick="showComingSoon('KPI Reports')">
                    <i class="ki-duotone ki-element-plus me-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    KPI Reports
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Enhanced Advanced Action Center -->
        <div class="separator separator-dashed my-10"></div>
        
        <div class="row g-8">
          <!-- Premium Quick Actions Panel -->
          <div class="col-lg-8">
            <div class="card bg-light-primary border-0 h-100 shadow-sm position-relative overflow-hidden">
              {{-- Background Pattern --}}
              <div class="position-absolute top-0 start-0 w-100 h-100 opacity-5">
                <svg viewBox="0 0 200 200" class="w-100 h-100">
                  <defs>
                    <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                      <circle cx="10" cy="10" r="1.5" fill="#1B84FF"/>
                    </pattern>
                  </defs>
                  <rect width="200" height="200" fill="url(#dots)" />
                </svg>
              </div>
              
              <div class="card-header border-0 pb-6 position-relative">
                <div class="d-flex align-items-center">
                  <div class="symbol symbol-60px me-4">
                    <div class="symbol-label bg-gradient-primary">
                      <i class="ki-duotone ki-flash fs-2x text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </div>
                  </div>
                  <div>
                    <h4 class="fw-bolder text-gray-900 mb-2">Quick Actions Hub</h4>
                    <p class="text-muted fw-semibold mb-0 fs-6">Streamlined reporting and data management tools</p>
                  </div>
                </div>
              </div>
              
              <div class="card-body pt-0 position-relative">
                <div class="row g-6">
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-primary w-100 h-80px shadow-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#generateReportModal">
                      <i class="ki-duotone ki-document-up fs-2x text-primary mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Generate Report</span>
                        <span class="fs-8 text-muted">Custom analytics</span>
                      </div>
                    </button>
                  </div>
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-success w-100 h-80px shadow-sm hover-elevate-up" onclick="exportToExcel()">
                      <i class="ki-duotone ki-file-down fs-2x text-success mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Export Excel</span>
                        <span class="fs-8 text-muted">Data export</span>
                      </div>
                    </button>
                  </div>
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-info w-100 h-80px shadow-sm hover-elevate-up" onclick="emailReport()">
                      <i class="ki-duotone ki-sms fs-2x text-info mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Email Report</span>
                        <span class="fs-8 text-muted">Send via email</span>
                      </div>
                    </button>
                  </div>
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-warning w-100 h-80px shadow-sm hover-elevate-up" onclick="scheduleReport()">
                      <i class="ki-duotone ki-calendar fs-2x text-warning mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Schedule</span>
                        <span class="fs-8 text-muted">Automate reports</span>
                      </div>
                    </button>
                  </div>
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-danger w-100 h-80px shadow-sm hover-elevate-up" onclick="printReport()">
                      <i class="ki-duotone ki-printer fs-2x text-danger mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Print</span>
                        <span class="fs-8 text-muted">Physical copy</span>
                      </div>
                    </button>
                  </div>
                  <div class="col-md-6 col-xl-4">
                    <button type="button" class="btn btn-flex flex-column btn-light-dark w-100 h-80px shadow-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#reportSettingsModal">
                      <i class="ki-duotone ki-setting-2 fs-2x text-dark mb-2">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                      <div class="d-flex flex-column text-center">
                        <span class="fw-bold fs-6 text-gray-800">Settings</span>
                        <span class="fs-8 text-muted">Configure</span>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Enhanced Analytics Summary Panel -->
          <div class="col-lg-4">
            <div class="card bg-gradient-dark h-100 shadow-lg border-0 position-relative overflow-hidden">
              {{-- Decorative Elements --}}
              <div class="position-absolute top-0 end-0 w-100 h-100 opacity-10">
                <svg viewBox="0 0 100 100" class="w-100 h-100">
                  <circle cx="50" cy="50" r="30" fill="none" stroke="#ffffff" stroke-width="0.5"/>
                  <circle cx="50" cy="50" r="20" fill="none" stroke="#ffffff" stroke-width="0.3"/>
                  <circle cx="50" cy="50" r="10" fill="none" stroke="#ffffff" stroke-width="0.2"/>
                </svg>
              </div>
              
              <div class="card-header border-0 pb-6 position-relative">
                <div class="d-flex align-items-center">
                  <div class="symbol symbol-60px me-4">
                    <div class="symbol-label bg-white bg-opacity-20">
                      <i class="ki-duotone ki-chart-simple fs-2x text-white">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                      </i>
                    </div>
                  </div>
                  <div>
                    <h4 class="fw-bolder text-white mb-2">Analytics Summary</h4>
                    <p class="text-white opacity-75 fw-semibold mb-0 fs-6">Real-time insights</p>
                  </div>
                </div>
              </div>
              
              <div class="card-body pt-0 position-relative">
                <div class="d-flex align-items-center mb-8">
                  <div class="symbol symbol-60px me-4">
                    <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                      <i class="ki-duotone ki-chart-simple fs-2 text-white">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="text-white opacity-85 fw-semibold d-block fs-6 mb-1">Reports Generated</span>
                    <span class="text-white fw-bolder fs-2x" id="reportsGenerated">0</span>
                    <span class="badge badge-light-success fs-8 ms-2">+12% this month</span>
                  </div>
                </div>
                
                <div class="d-flex align-items-center mb-8">
                  <div class="symbol symbol-60px me-4">
                    <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                      <i class="ki-duotone ki-time fs-2 text-white">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="text-white opacity-85 fw-semibold d-block fs-6 mb-1">Last Generated</span>
                    <span class="text-white fw-bold fs-5" id="lastReportTime">—</span>
                    <span class="badge badge-light-info fs-8 ms-2">Today</span>
                  </div>
                </div>
                
                <div class="d-flex align-items-center mb-6">
                  <div class="symbol symbol-60px me-4">
                    <div class="symbol-label bg-white bg-opacity-20 shadow-sm">
                      <i class="ki-duotone ki-star fs-2 text-white">
                        <span class="path1"></span><span class="path2"></span>
                      </i>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="text-white opacity-85 fw-semibold d-block fs-6 mb-1">Most Popular</span>
                    <span class="text-white fw-bold fs-5" id="popularReport">GST Returns</span>
                    <span class="badge badge-light-warning fs-8 ms-2">Popular</span>
                  </div>
                </div>
                
                {{-- Progress Indicators --}}
                <div class="separator separator-dashed border-white opacity-25 my-6"></div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-white opacity-75 fw-semibold fs-7">System Health</span>
                  <span class="badge badge-light-success px-3 py-2 fs-7">Excellent</span>
                </div>
                <div class="progress bg-white bg-opacity-20 h-4px mt-3">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- Upload JSON Modal --}}
<div class="modal fade" id="jsonImportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Import Invoices (JSON)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="jsonImportForm" enctype="multipart/form-data" onsubmit="return false;">
          @csrf
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Select JSON File</label>
              <input type="file" name="json_file" id="jsonFile" class="form-control" accept=".json" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Invoice Type</label>
              <select name="type" id="jsonType" class="form-select" required>
                <option value="">-- Select Type --</option>
                <option value="sales">Sales</option>
                <option value="purchase">Purchase</option>
              </select>
            </div>
            <div class="col-12">
              <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="ki-duotone ki-information-5 fs-2 me-3 text-info"><span class="path1"></span><span class="path2"></span></i>
                <div>
                  JSON should be either an <code>array</code> of invoices or an object with
                  <code>{ "type": "...", "items": [ ... ] }</code>. Required fields:
                  <code>invoice_no, invoice_date, supplier_name, supplier_gstin, hsn, qty, uom, taxable_value, tax_rate, cgst_amount, sgst_amount, igst_amount, place_of_supply, origin_state</code>.
                </div>
              </div>
            </div>
          </div>
        </form>
        <div id="uploadStatus" class="alert d-none mt-3" role="alert"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnUploadJson">
          <i class="ki-duotone ki-upload fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
          Upload
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Generate Report Modal --}}
<div class="modal fade" id="generateReportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Generate Custom Report</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="generateReportForm">
          @csrf
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label fw-bold">Report Type</label>
              <select name="report_type" id="reportType" class="form-select" required>
                <option value="">-- Select Report Type --</option>
                <option value="gst_summary">GST Summary</option>
                <option value="itr_analysis">ITR Analysis</option>
                <option value="invoice_summary">Invoice Summary</option>
                <option value="expense_analysis">Expense Analysis</option>
                <option value="income_analysis">Income Analysis</option>
                <option value="tax_computation">Tax Computation</option>
                <option value="compliance_report">Compliance Report</option>
                <option value="financial_overview">Financial Overview</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Period</label>
              <select name="period" id="reportPeriod" class="form-select" required>
                <option value="">-- Select Period --</option>
                <option value="current_month">Current Month</option>
                <option value="current_quarter">Current Quarter</option>
                <option value="current_fy">Current Financial Year</option>
                <option value="last_month">Last Month</option>
                <option value="last_quarter">Last Quarter</option>
                <option value="last_fy">Last Financial Year</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            <div class="col-md-6" id="customDateRange" style="display: none;">
              <label class="form-label fw-bold">From Date</label>
              <input type="date" name="from_date" id="fromDate" class="form-control">
            </div>
            <div class="col-md-6" id="customDateRange2" style="display: none;">
              <label class="form-label fw-bold">To Date</label>
              <input type="date" name="to_date" id="toDate" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Format</label>
              <select name="format" id="reportFormat" class="form-select" required>
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
                <option value="csv">CSV</option>
                <option value="html">HTML</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Include Details</label>
              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" id="includeDetails" name="include_details" checked>
                <label class="form-check-label" for="includeDetails">Include detailed breakdown</label>
              </div>
            </div>
          </div>
        </form>
        <div id="generateStatus" class="alert d-none mt-3" role="alert"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnGenerateReport">
          <i class="ki-duotone ki-document-up fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
          Generate Report
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Report Settings Modal --}}
<div class="modal fade" id="reportSettingsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Report Settings</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="row g-6">
          <div class="col-12">
            <h6 class="fw-bold mb-4">Default Settings</h6>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label fw-bold">Default Format</label>
                <select class="form-select" id="defaultFormat">
                  <option value="pdf">PDF</option>
                  <option value="excel">Excel</option>
                  <option value="csv">CSV</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Default Period</label>
                <select class="form-select" id="defaultPeriod">
                  <option value="current_month">Current Month</option>
                  <option value="current_quarter">Current Quarter</option>
                  <option value="current_fy">Current Financial Year</option>
                </select>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="separator separator-dashed my-4"></div>
            <h6 class="fw-bold mb-4">Email Settings</h6>
            <div class="row g-4">
              <div class="col-md-6">
                <label class="form-label fw-bold">Auto Email Reports</label>
                <div class="form-check form-switch mt-2">
                  <input class="form-check-input" type="checkbox" id="autoEmail">
                  <label class="form-check-label" for="autoEmail">Enable automatic email delivery</label>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Email Recipients</label>
                <input type="email" class="form-control" id="emailRecipients" placeholder="email@example.com">
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="separator separator-dashed my-4"></div>
            <h6 class="fw-bold mb-4">Display Settings</h6>
            <div class="row g-4">
              <div class="col-md-6">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="showCharts" checked>
                  <label class="form-check-label fw-semibold" for="showCharts">Include Charts & Graphs</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="showSummary" checked>
                  <label class="form-check-label fw-semibold" for="showSummary">Include Executive Summary</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="showDetails" checked>
                  <label class="form-check-label fw-semibold" for="showDetails">Include Detailed Breakdown</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="showComparisons">
                  <label class="form-check-label fw-semibold" for="showComparisons">Include Period Comparisons</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-dark" onclick="saveReportSettings()">
          <i class="ki-duotone ki-check fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>
          Save Settings
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
// ---------- Helpers ----------
const nz = (v, d=0) => (v===undefined || v===null || v==='' || isNaN(v)) ? d : v;
const num = (v, d=0) => {
  if (typeof v === 'string') v = v.replace(/[, ]+/g,'');
  const n = parseFloat(v);
  return isFinite(n) ? n : d;
};
const inr = (v) => {
  try {
    return new Intl.NumberFormat('en-IN', { style:'currency', currency:'INR', maximumFractionDigits:2 }).format(num(v));
  } catch(e) {
    const n = num(v).toFixed(2);
    return '₹' + n.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }
};

// Compute GST monthly due date (20th of next month by default)
function nextGstDueLabel(todayStr) {
  try {
    const today = todayStr ? new Date(todayStr) : new Date();
    const y = today.getFullYear();
    const m = today.getMonth(); // 0-based
    const next = new Date(y, m+1, 20);
    if (today > next) return new Date(y, m+2, 20).toLocaleDateString('en-IN');
    return next.toLocaleDateString('en-IN');
  } catch {
    return '—';
  }
}

// ---------- METRICS / KPIs ----------
async function loadMetrics() {
  try {
    const res = await fetch('{{ url("/api/metrics") }}');
    const m = await res.json();

    const outwardTax = num(m.salesTaxCollected ?? m.outward_tax ?? m.output_tax ?? 0);
    const itcAvail   = num(m.itcAvailable ?? m.input_tax_credit ?? m.inward_itc ?? 0);

    // GST payable = max(Output Tax - ITC, 0) unless API already gives a trusted value
    const gstPayable = Math.max(num(m.gstPayable ?? (outwardTax - itcAvail), 0), 0);

    const itrProgress = (() => {
      if (m.itrProgress !== undefined && m.itrProgress !== null) return Math.max(0, Math.min(100, num(m.itrProgress)));
      const stepsDone = num(m.itr_steps_done ?? 0);
      const stepsTotal = Math.max(num(m.itr_steps_total ?? 0), 1);
      return Math.round((stepsDone/stepsTotal) * 100);
    })();

    document.getElementById('gstPayable').textContent        = inr(gstPayable);
    document.getElementById('itcAvailable').textContent      = inr(itcAvail);
    document.getElementById('salesTaxCollected').textContent = inr(outwardTax);
    document.getElementById('nextDue').textContent           = m.nextDue || nextGstDueLabel(m.today);

    const p = document.getElementById('itrProgress');
    const t = document.getElementById('itrProgressText');
    p.style.width = `${itrProgress}%`;
    p.className = 'progress-bar';
    if (itrProgress < 30) p.classList.add('bg-danger');
    else if (itrProgress < 70) p.classList.add('bg-warning');
    else p.classList.add('bg-success');
    t.textContent = `${itrProgress}%`;
  } catch (error) {
    console.error('Error loading metrics:', error);
  }
}

// ---------- GST SUMMARY CHART ----------
async function loadChart() {
  try {
    const res = await fetch('{{ url("/api/gst-summary") }}');
    const data = await res.json();

    const categories = Array.isArray(data.categories) ? data.categories : [];
    const series = Array.isArray(data.series) ? data.series.map(s => ({
      name: s.name ?? 'Series',
      data: (s.data ?? []).map(num)
    })) : [];

    const options = {
      chart: { type:'line', height:320, toolbar:{show:false}, fontFamily:'inherit' },
      series,
      xaxis: {
        categories,
        axisBorder: { show:false }, axisTicks: { show:false },
        labels: { style: { colors:'#a1a5b7', fontSize:'12px' } }
      },
      yaxis: { labels: { style: { colors:'#a1a5b7', fontSize:'12px' } } },
      stroke: { width:3, curve:'smooth' },
      markers: { size:4, colors:['#ffffff'], strokeColors:['#3E97FF', '#F1416C'], strokeWidth:3 },
      colors: ['#3E97FF', '#F1416C'],
      grid: { borderColor:'#e4e6ef', strokeDashArray:4, padding:{ top:0, bottom:0, left:20, right:20 } },
      legend: { labels:{ colors:'#7e8299' } },
      tooltip: { y: { formatter: (val) => inr(val) } }
    };

    const el = document.querySelector("#gstChart");
    if (window.ApexCharts) {
      new ApexCharts(el, options).render();
    } else {
      el.innerHTML = '<div class="text-muted text-center py-10">Chart library not loaded.</div>';
    }
  } catch (error) {
    console.error('Error loading chart:', error);
  }
}

// ---------- RECENT INVOICES ----------
async function loadRecentInvoices() {
  try {
    const res = await fetch('{{ url("/api/recent-invoices") }}');
    const d = await res.json();
    const tb = document.getElementById('recentInvoicesBody');
    tb.innerHTML = '';

    const rows = Array.isArray(d?.data) ? d.data : [];
    if (rows.length === 0) {
      tb.innerHTML = `
        <tr>
          <td colspan="4" class="text-center py-10">
            <div class="d-flex flex-column align-items-center">
              <i class="ki-duotone ki-document fs-3x text-muted mb-3"><span class="path1"></span><span class="path2"></span></i>
              <span class="text-muted fw-semibold fs-6">No recent invoices found</span>
            </div>
          </td>
        </tr>`;
      return;
    }

    rows.forEach(r => {
      const type  = (r.type ?? r.invoice_type ?? 'sales').toString().toLowerCase();
      const inv   = r.invoice_no ?? r.number ?? r.no ?? '-';
      const date  = r.date ?? r.invoice_date ?? '-';
      const gstin = r.gstin ?? r.supplier_gstin ?? r.customer_gstin ?? '';
      const total = num(r.total_amount ?? r.total ?? (num(r.taxable_value)+num(r.cgst_amount)+num(r.sgst_amount)+num(r.igst_amount)));

      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <div class="d-flex align-items-center">
            <div class="symbol symbol-40px me-3 flex-shrink-0">
              <div class="symbol-label bg-light-${type === 'sales' ? 'success' : 'info'}">
                <i class="ki-duotone ki-receipt fs-5 text-${type === 'sales' ? 'success' : 'info'}">
                  <span class="path1"></span><span class="path2"></span>
                </i>
              </div>
            </div>
            <div class="d-flex justify-content-start flex-column min-w-0">
              <span class="text-dark fw-bold text-hover-primary fs-6 text-truncate">${inv}</span>
              <span class="text-muted fw-semibold d-block fs-7 text-truncate">${gstin || '—'}</span>
            </div>
          </div>
        </td>
        <td><span class="badge badge-light-${type === 'sales' ? 'success' : 'info'} fs-7 fw-bold">${type.toUpperCase()}</span></td>
        <td><span class="text-dark fw-semibold d-block fs-6">${date}</span></td>
        <td class="text-end"><span class="text-dark fw-bold fs-6">${inr(total)}</span></td>
      `;
      tb.appendChild(tr);
    });
  } catch (error) {
    console.error('Error loading recent invoices:', error);
    const tb = document.getElementById('recentInvoicesBody');
    tb.innerHTML = `
      <tr>
        <td colspan="4" class="text-center py-10">
          <div class="d-flex flex-column align-items-center">
            <i class="ki-duotone ki-cross-circle fs-3x text-danger mb-3"><span class="path1"></span><span class="path2"></span></i>
            <span class="text-danger fw-semibold fs-6">Error loading invoices</span>
          </div>
        </td>
      </tr>`;
  }
}

// ---------- ITR CARDS + RECENT INCOME/EXPENSES ----------
async function loadItrAndIE() {
  // ITR summary
  fetch('/api/itr-summary')
    .then(r => r.json())
    .then(d => {
      const income   = num(d.income_total ?? d.income ?? 0);
      const expense  = num(d.expense_total ?? d.expense ?? 0);
      const profit   = num(d.profit ?? (income - expense));
      const fy       = d.fy ?? d.fiscal_year ?? '';

      document.getElementById('itr-gross').textContent    = inr(income);
      document.getElementById('itr-expenses').textContent = inr(expense);
      document.getElementById('itr-profit').textContent   = inr(Math.max(profit, 0)); // or remove clamp if you want negatives
      document.getElementById('itr-fy-label').textContent = 'FY ' + fy;
    })
    .catch(() => {/* noop */});

  // Recent incomes & expenses
  fetch('/api/recent-income-expenses?limit=5')
    .then(r => r.json())
    .then(d => {
      const incBody = document.getElementById('recent-incomes-body');
      const expBody = document.getElementById('recent-expenses-body');

      const renderRows = (rows, type) => {
        if (!Array.isArray(rows) || rows.length === 0) {
          return `<tr><td colspan="3" class="text-muted text-center">No ${type} yet.</td></tr>`;
        }
        return rows.map(r => {
          const amt  = num(r.amount ?? r.total ?? r.value ?? 0);
          const head = (r.head || r.category || r.sub_head || r.ref_no || r.note || '').toString();
          return `
            <tr>
              <td>${r.date ?? r.txn_date ?? '-'}</td>
              <td title="${head}">${head.substring(0,40)}</td>
              <td class="text-end">${inr(amt)}</td>
            </tr>`;
        }).join('');
      };

      incBody.innerHTML = renderRows(d.incomes ?? d.income ?? [], 'incomes');
      expBody.innerHTML = renderRows(d.expenses ?? d.expense ?? [], 'expenses');
    })
    .catch(() => {/* noop */});
}

// ---------- Upload JSON Modal Logic ----------
document.getElementById('btnUploadJson')?.addEventListener('click', async () => {
  const fileInput = document.getElementById('jsonFile');
  const typeSel   = document.getElementById('jsonType');
  const statusDiv = document.getElementById('uploadStatus');

  statusDiv.classList.add('d-none'); statusDiv.textContent = '';

  if (!fileInput.files.length) { alert('Please select a JSON file.'); return; }
  if (!typeSel.value) { alert('Please select the invoice type.'); return; }

  const file = fileInput.files[0];
  const text = await file.text();
  let payload;
  try {
    payload = JSON.parse(text);
  } catch (e) {
    alert('Invalid JSON format!'); return;
  }

  // If array, wrap to API shape; if object, enforce type
  if (Array.isArray(payload)) {
    payload = { type: typeSel.value, items: payload };
  } else {
    payload.type = typeSel.value;
    // if they gave "invoices" key, normalize
    if (Array.isArray(payload.invoices) && !Array.isArray(payload.items)) {
      payload.items = payload.invoices;
    }
  }

  const res = await fetch('{{ url("/invoices/import-json") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Accept': 'application/json'
    },
    body: JSON.stringify(payload)
  });

  let data;
  try { data = await res.json(); } catch { data = { ok:false, errors:{ server:['Invalid server response'] } }; }

  statusDiv.classList.remove('d-none','alert-success','alert-danger');
  if (data.ok) {
    statusDiv.classList.add('alert-success');
    statusDiv.innerHTML = `<b>Success:</b> Imported <b>${data.imported ?? 0}</b> invoices. Failed: <b>${data.failed ?? 0}</b>.`;
    // Refresh widgets that depend on invoices
    loadRecentInvoices();
    loadMetrics();
    loadChart();
  } else {
    statusDiv.classList.add('alert-danger');
    const err = (data.errors && typeof data.errors === 'object') ? JSON.stringify(data.errors) : (data.message || 'Unknown error');
    statusDiv.innerHTML = `<b>Failed:</b> ${err}`;
  }
});

// ---------- Report Generation Functions ----------
document.getElementById('btnGenerateReport')?.addEventListener('click', async () => {
  const form = document.getElementById('generateReportForm');
  const statusDiv = document.getElementById('generateStatus');
  const formData = new FormData(form);

  statusDiv.classList.add('d-none');

  if (!formData.get('report_type') || !formData.get('period') || !formData.get('format')) {
    alert('Please fill in all required fields.');
    return;
  }

  try {
    statusDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
    statusDiv.classList.add('alert-info');
    statusDiv.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Generating report...';

    const response = await fetch('{{ url("/api/generate-report") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      body: formData
    });

    const result = await response.json();

    statusDiv.classList.remove('alert-info');
    if (result.success) {
      statusDiv.classList.add('alert-success');
      statusDiv.innerHTML = `<b>Success:</b> Report generated successfully! <a href="${result.download_url}" class="btn btn-sm btn-success ms-2">Download</a>`;
      
      // Update report analytics
      loadReportAnalytics();
    } else {
      statusDiv.classList.add('alert-danger');
      statusDiv.innerHTML = `<b>Error:</b> ${result.message || 'Failed to generate report'}`;
    }
  } catch (error) {
    statusDiv.classList.remove('alert-info');
    statusDiv.classList.add('alert-danger');
    statusDiv.innerHTML = `<b>Error:</b> ${error.message}`;
  }
});

// Period selection handler for custom date range
document.getElementById('reportPeriod')?.addEventListener('change', function() {
  const customRange1 = document.getElementById('customDateRange');
  const customRange2 = document.getElementById('customDateRange2');
  
  if (this.value === 'custom') {
    customRange1.style.display = 'block';
    customRange2.style.display = 'block';
    document.getElementById('fromDate').required = true;
    document.getElementById('toDate').required = true;
  } else {
    customRange1.style.display = 'none';
    customRange2.style.display = 'none';
    document.getElementById('fromDate').required = false;
    document.getElementById('toDate').required = false;
  }
});

// Export to Excel function
async function exportToExcel() {
  try {
    const response = await fetch('{{ url("/api/export-excel") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        type: 'dashboard_summary',
        period: 'current_month'
      })
    });

    if (response.ok) {
      const blob = await response.blob();
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `dashboard-export-${new Date().toISOString().split('T')[0]}.xlsx`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
      
      showNotification('Excel export completed successfully!', 'success');
    } else {
      showNotification('Failed to export data', 'error');
    }
  } catch (error) {
    showNotification('Export failed: ' + error.message, 'error');
  }
}

// Email Report function
async function emailReport() {
  const email = prompt('Enter email address to send the report:');
  if (!email) return;

  try {
    const response = await fetch('{{ url("/api/email-report") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email,
        report_type: 'dashboard_summary',
        period: 'current_month'
      })
    });

    const result = await response.json();
    if (result.success) {
      showNotification(`Report sent successfully to ${email}!`, 'success');
    } else {
      showNotification('Failed to send report: ' + result.message, 'error');
    }
  } catch (error) {
    showNotification('Email failed: ' + error.message, 'error');
  }
}

// Schedule Report function
async function scheduleReport() {
  // This would open a more sophisticated modal for scheduling
  const schedule = prompt('Enter schedule (daily/weekly/monthly):');
  if (!schedule) return;

  try {
    const response = await fetch('{{ url("/api/schedule-report") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        schedule: schedule,
        report_type: 'dashboard_summary'
      })
    });

    const result = await response.json();
    if (result.success) {
      showNotification(`Report scheduled ${schedule}!`, 'success');
    } else {
      showNotification('Failed to schedule report: ' + result.message, 'error');
    }
  } catch (error) {
    showNotification('Scheduling failed: ' + error.message, 'error');
  }
}

// Print Report function
function printReport() {
  window.print();
}

// Save Report Settings function
async function saveReportSettings() {
  const settings = {
    default_format: document.getElementById('defaultFormat').value,
    default_period: document.getElementById('defaultPeriod').value,
    auto_email: document.getElementById('autoEmail').checked,
    email_recipients: document.getElementById('emailRecipients').value,
    show_charts: document.getElementById('showCharts').checked,
    show_summary: document.getElementById('showSummary').checked,
    show_details: document.getElementById('showDetails').checked,
    show_comparisons: document.getElementById('showComparisons').checked
  };

  try {
    const response = await fetch('{{ url("/api/save-report-settings") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(settings)
    });

    const result = await response.json();
    if (result.success) {
      showNotification('Settings saved successfully!', 'success');
      const modal = bootstrap.Modal.getInstance(document.getElementById('reportSettingsModal'));
      modal.hide();
    } else {
      showNotification('Failed to save settings: ' + result.message, 'error');
    }
  } catch (error) {
    showNotification('Save failed: ' + error.message, 'error');
  }
}

// Load Report Analytics
async function loadReportAnalytics() {
  try {
    const response = await fetch('{{ url("/api/report-analytics") }}');
    const data = await response.json();
    
    document.getElementById('reportsGenerated').textContent = data.total_reports || '0';
    document.getElementById('lastReportTime').textContent = data.last_generated || '—';
    document.getElementById('popularReport').textContent = data.popular_report || 'GST Returns';
  } catch (error) {
    console.error('Error loading report analytics:', error);
  }
}

// Notification helper
function showNotification(message, type = 'info') {
  // This would integrate with your notification system (SweetAlert, Toastr, etc.)
  if (window.Swal) {
    Swal.fire({
      text: message,
      icon: type === 'error' ? 'error' : 'success',
      buttonsStyling: false,
      confirmButtonText: 'Ok',
      customClass: {
        confirmButton: 'btn btn-primary'
      }
    });
  } else {
    alert(message);
  }
}

// Coming Soon notification for reports under development
function showComingSoon(reportName) {
  if (window.Swal) {
    Swal.fire({
      title: 'Coming Soon!',
      text: `${reportName} feature is currently under development and will be available in a future update.`,
      icon: 'info',
      buttonsStyling: false,
      confirmButtonText: 'Got it!',
      customClass: {
        confirmButton: 'btn btn-primary'
      }
    });
  } else {
    alert(`${reportName} feature is coming soon!`);
  }
}

// Initialize Business Intelligence Section
function initBusinessIntelligence() {
  // Add hover effects for better interactivity
  const cards = document.querySelectorAll('.hoverable');
  cards.forEach(card => {
    card.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-8px)';
    });
    
    card.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });

  // Animate cards on scroll (if Intersection Observer is supported)
  if (window.IntersectionObserver) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.hoverable').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(30px)';
      card.style.transition = 'all 0.6s ease-out';
      observer.observe(card);
    });
  }
  
  // Load analytics data
  loadReportAnalytics();
}

// ---------- Init ----------
document.addEventListener('DOMContentLoaded', () => {
  loadMetrics();
  loadChart();
  loadRecentInvoices();
  loadItrAndIE();
  initBusinessIntelligence();
});
</script>
@endsection

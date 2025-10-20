@extends('layouts.app')
@section('title','ITR Summary')

@section('content')
    <!-- Header Card -->
    <div class="card mb-5">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h3 class="card-title">
                <i class="ki-duotone ki-calculator fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                ITR Summary - Profit &amp; Loss
            </h3>
            <form class="d-flex align-items-center gap-2" method="get" action="{{ route('itr.summary') }}">
                <label class="form-label mb-0 fw-semibold">Financial Year</label>
                <select name="fy" class="form-select form-select-solid" style="width:auto">
                    @php
                        $curr = (int)date('Y');
                        $fys = [];
                        for ($y = $curr - 2; $y <= $curr + 1; $y++) { 
                            $fys[] = $y.'-'.substr($y+1,2,2); 
                        }
                        $sel = request('fy') ?? ($fys[count($fys)-2] ?? (date('Y').'-'.substr(date('Y')+1,2,2)));
                    @endphp
                    @foreach($fys as $fyOpt)
                        <option value="{{ $fyOpt }}" {{ ($fyOpt==($pl['fy'] ?? $sel))?'selected':'' }}>{{ $fyOpt }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary">
                    <i class="ki-duotone ki-arrows-circle fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Load
                </button>
                <a class="btn btn-light-success" href="{{ route('itr.export.json',['fy'=>$pl['fy']]) }}">
                    <i class="ki-duotone ki-file-down fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Export JSON
                </a>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-5 g-xl-8 mb-5">
        <!-- Gross Receipts -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-success">
                                <i class="ki-duotone ki-arrow-up-right fs-2x text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Gross Receipts</a>
                            <div class="fw-semibold text-muted fs-7">Total Income for FY {{ $pl['fy'] }}</div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            <div class="text-dark fw-bold fs-2hx">₹{{ number_format($pl['income_total'],2) }}</div>
                        </div>
                        <div class="badge badge-light-success fs-8 fw-semibold">Income</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-danger">
                                <i class="ki-duotone ki-arrow-down-left fs-2x text-danger">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Total Expenses</a>
                            <div class="fw-semibold text-muted fs-7">Deductible Expenses for FY {{ $pl['fy'] }}</div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            <div class="text-dark fw-bold fs-2hx">₹{{ number_format($pl['expense_total'],2) }}</div>
                        </div>
                        <div class="badge badge-light-danger fs-8 fw-semibold">Expenses</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <div class="symbol-label bg-light-{{ $pl['profit'] >= 0 ? 'primary' : 'warning' }}">
                                @if($pl['profit'] >= 0)
                                    <i class="ki-duotone ki-chart-line-up fs-2x text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                @else
                                    <i class="ki-duotone ki-chart-line-down fs-2x text-warning">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                @endif
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <a href="#" class="text-dark fw-bold text-hover-primary fs-6">Net {{ $pl['profit'] >= 0 ? 'Profit' : 'Loss' }}</a>
                            <div class="fw-semibold text-muted fs-7">Taxable Income for FY {{ $pl['fy'] }}</div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-4"></div>
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="flex-grow-1">
                            <div class="text-dark fw-bold fs-2hx">₹{{ number_format($pl['profit'],2) }}</div>
                        </div>
                        <div class="badge badge-light-{{ $pl['profit'] >= 0 ? 'primary' : 'warning' }} fs-8 fw-semibold">
                            {{ $pl['profit'] >= 0 ? 'Profit' : 'Loss' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Breakdown -->
    <div class="row g-5 g-xl-8">
        <!-- Income by Head -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Income by Head</span>
                        <span class="text-muted fw-semibold fs-7">Breakdown of income sources</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="badge badge-light-success fs-8 fw-semibold">
                            {{ count($pl['income_by_head']) }} Head{{ count($pl['income_by_head']) !== 1 ? 's' : '' }}
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5">
                    @if(empty($pl['income_by_head']) || count($pl['income_by_head']) === 0)
                        <div class="text-center py-10">
                            <div class="text-gray-400 fs-6">
                                <i class="ki-duotone ki-chart-pie-simple fs-3x mb-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <br>
                                No income recorded for this financial year.
                                <br>
                                <a href="{{ route('incomes.index') }}" class="fw-bold text-primary">Add income entries</a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Income Head</th>
                                        <th class="min-w-100px text-end">Amount</th>
                                        <th class="min-w-80px text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pl['income_by_head'] as $head => $amt)
                                        @php $percentage = $pl['income_total'] > 0 ? ($amt / $pl['income_total']) * 100 : 0; @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-3">
                                                        <div class="symbol-label bg-light-success">
                                                            <i class="ki-duotone ki-wallet fs-2 text-success">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                                <span class="path4"></span>
                                                            </i>
                                                        </div>
                                                    </div>
                                                    <div class="fw-semibold text-gray-800">{{ $head }}</div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="text-gray-800 fw-bold">₹{{ number_format($amt, 2) }}</div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-primary">{{ number_format($percentage, 1) }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expense by Category -->
        <div class="col-xl-6">
            <div class="card card-xl-stretch mb-xl-8">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Expense by Category</span>
                        <span class="text-muted fw-semibold fs-7">Breakdown of business expenses</span>
                    </h3>
                    <div class="card-toolbar">
                        <div class="badge badge-light-danger fs-8 fw-semibold">
                            {{ count($pl['expense_by_category']) }} Categories
                        </div>
                    </div>
                </div>
                <div class="card-body pt-5">
                    @if(empty($pl['expense_by_category']) || count($pl['expense_by_category']) === 0)
                        <div class="text-center py-10">
                            <div class="text-gray-400 fs-6">
                                <i class="ki-duotone ki-chart-pie-simple fs-3x mb-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <br>
                                No expenses recorded for this financial year.
                                <br>
                                <a href="{{ route('expenses.index') }}" class="fw-bold text-primary">Add expense entries</a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Expense Category</th>
                                        <th class="min-w-100px text-end">Amount</th>
                                        <th class="min-w-80px text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pl['expense_by_category'] as $cat => $amt)
                                        @php $percentage = $pl['expense_total'] > 0 ? ($amt / $pl['expense_total']) * 100 : 0; @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40px me-3">
                                                        <div class="symbol-label bg-light-danger">
                                                            <i class="ki-duotone ki-bill fs-2 text-danger">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                                <span class="path3"></span>
                                                                <span class="path4"></span>
                                                                <span class="path5"></span>
                                                                <span class="path6"></span>
                                                            </i>
                                                        </div>
                                                    </div>
                                                    <div class="fw-semibold text-gray-800">{{ $cat }}</div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="text-gray-800 fw-bold">₹{{ number_format($amt, 2) }}</div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-warning">{{ number_format($percentage, 1) }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tax Calculation Estimate -->
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ki-duotone ki-calculator fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Tax Calculation Estimate
            </h3>
        </div>
        <div class="card-body">
            <div class="row g-5">
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <div class="text-muted fs-7 fw-semibold">Taxable Income</div>
                        <div class="text-gray-800 fw-bold fs-4">₹{{ number_format(max(0, $pl['profit']), 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <div class="text-muted fs-7 fw-semibold">Basic Exemption</div>
                        <div class="text-gray-800 fw-bold fs-4">₹2,50,000</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <div class="text-muted fs-7 fw-semibold">Net Taxable</div>
                        <div class="text-gray-800 fw-bold fs-4">₹{{ number_format(max(0, $pl['profit'] - 250000), 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <div class="text-muted fs-7 fw-semibold">Est. Tax (Old Regime)</div>
                        @php
                            $netTaxable = max(0, $pl['profit'] - 250000);
                            $estimatedTax = 0;
                            if ($netTaxable > 0) {
                                if ($netTaxable <= 500000) {
                                    $estimatedTax = $netTaxable * 0.05;
                                } elseif ($netTaxable <= 1000000) {
                                    $estimatedTax = 25000 + (($netTaxable - 500000) * 0.20);
                                } else {
                                    $estimatedTax = 125000 + (($netTaxable - 1000000) * 0.30);
                                }
                            }
                        @endphp
                        <div class="text-{{ $estimatedTax > 0 ? 'warning' : 'success' }} fw-bold fs-4">₹{{ number_format($estimatedTax, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-6 mt-5">
                <i class="ki-duotone ki-information-5 fs-2tx text-warning me-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-semibold">
                        <div class="fs-6 text-gray-700">
                            <strong>Disclaimer:</strong> This is an estimated tax calculation based on old tax regime and basic exemption limit. 
                            Actual tax liability may vary based on additional deductions, rebates, and current tax laws. 
                            Please consult a tax professional for accurate calculations.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

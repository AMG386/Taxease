@extends('layouts.app')
@section('title','Incomes')

@section('content')
    <!-- Add New Income Card -->
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ki-duotone ki-wallet fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                </i>
                Add New Income
            </h3>
        </div>
        <div class="card-body">
            @if(session('ok')) 
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('ok') }}
                </div> 
            @endif

            <form method="post" action="{{ route('incomes.store') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-2">
                    <label class="form-label fw-semibold required">Date</label>
                    <input type="date" name="date" class="form-control form-control-solid" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold required">Income Head</label>
                    <select name="head" class="form-select form-select-solid" required>
                        <option value="">-- Select Income Head --</option>
                        <option value="business">Business Income</option>
                        <option value="interest">Interest Income</option>
                        <option value="rental">Rental Income</option>
                        <option value="capital_gains">Capital Gains</option>
                        <option value="other">Other Sources</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sub Head</label>
                    <input name="sub_head" class="form-control form-control-solid" placeholder="e.g., Professional, Salary">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Reference No</label>
                    <input name="ref_no" class="form-control form-control-solid" placeholder="Receipt/Invoice No">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold required">Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" name="amount" class="form-control form-control-solid" required placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Income Records Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="ki-duotone ki-chart-line-up fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Income Records
            </h3>
            <div class="d-flex gap-2">
                <button class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="ki-duotone ki-file-down fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Export
                </button>
                <button class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="ki-duotone ki-funnel fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Filter
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Summary Stats -->
            <div class="row g-5 mb-8">
                <div class="col-xl-3">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100" style="background-color: #00A3FF; background-image:url('{{ asset('assets/media/patterns/vector-1.png') }}')">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 fw-semibold text-white me-1 align-self-start">₹</span>
                                    <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ number_format($rows->sum('amount'), 0) }}</span>
                                </div>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Income</span>
                            </div>
                        </div>
                        <div class="card-body d-flex align-items-end pt-0">
                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                    <span>{{ $rows->count() }} Entries</span>
                                    <span>100%</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                                    <div class="bg-white rounded h-8px" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $rows->where('head', 'business')->count() }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Business Income</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">₹{{ number_format($rows->where('head', 'business')->sum('amount'), 2) }}</span>
                            <div class="d-flex align-items-center flex-column mt-3 w-100">
                                @php $businessPercentage = $rows->count() > 0 ? ($rows->where('head', 'business')->count() / $rows->count()) * 100 : 0; @endphp
                                <div class="d-flex justify-content-between fw-bold fs-6 text-gray-400 w-100 mt-auto mb-2">
                                    <span>{{ number_format($businessPercentage, 1) }}%</span>
                                    <span>of total</span>
                                </div>
                                <div class="h-8px mx-3 w-100 bg-gray-300 rounded">
                                    <div class="bg-primary rounded h-8px" role="progressbar" style="width: {{ $businessPercentage }}%;" aria-valuenow="{{ $businessPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $rows->pluck('head')->unique()->count() }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Income Heads</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">
                                Top: {{ $rows->groupBy('head')->sortByDesc(function($group) { return $group->sum('amount'); })->keys()->first() ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card card-flush h-xl-100">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">₹{{ number_format($rows->avg('amount') ?? 0, 0) }}</span>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Avg Amount</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-end pe-0">
                            <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">
                                Max: ₹{{ number_format($rows->max('amount') ?? 0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_incomes">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-100px">Date</th>
                            <th class="min-w-150px">Income Head</th>
                            <th class="min-w-120px">Sub Head</th>
                            <th class="min-w-120px">Reference</th>
                            <th class="min-w-120px text-end">Amount</th>
                            <th class="min-w-80px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr>
                                <td>
                                    <div class="text-gray-800 fw-semibold">{{ \Carbon\Carbon::parse($r->date)->format('d M Y') }}</div>
                                    <div class="text-muted fs-7">{{ \Carbon\Carbon::parse($r->date)->format('l') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label bg-light-{{ ['business' => 'primary', 'interest' => 'success', 'rental' => 'warning', 'capital_gains' => 'info', 'other' => 'secondary'][$r->head] ?? 'primary' }}">
                                                @php
                                                    $headIcons = [
                                                        'business' => 'ki-briefcase',
                                                        'interest' => 'ki-percentage',
                                                        'rental' => 'ki-home-2',
                                                        'capital_gains' => 'ki-chart-pie-simple',
                                                        'other' => 'ki-dollar'
                                                    ];
                                                @endphp
                                                <i class="ki-duotone {{ $headIcons[$r->head] ?? 'ki-wallet' }} fs-2 text-{{ ['business' => 'primary', 'interest' => 'success', 'rental' => 'warning', 'capital_gains' => 'info', 'other' => 'secondary'][$r->head] ?? 'primary' }}">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="fw-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $r->head)) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-800">{{ $r->sub_head ?: '-' }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-800">{{ $r->ref_no ?: '-' }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold fs-6">₹{{ number_format($r->amount, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Income">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Income" 
                                           onclick="return confirm('Are you sure you want to delete this income entry?')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">
                                    <div class="text-gray-400 fs-6">
                                        <i class="ki-duotone ki-wallet fs-3x mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        <br>
                                        No income recorded yet.
                                        <br>
                                        Use the form above to add your first income entry.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white">
                        <i class="ki-duotone ki-funnel fs-2 text-white me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter Income
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body">
                    <form method="get">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">From Date</label>
                                <input type="date" name="from" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">To Date</label>
                                <input type="date" name="to" class="form-control form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Income Head</label>
                                <select name="head" class="form-select form-select-solid">
                                    <option value="">All Heads</option>
                                    <option value="business">Business Income</option>
                                    <option value="interest">Interest Income</option>
                                    <option value="rental">Rental Income</option>
                                    <option value="capital_gains">Capital Gains</option>
                                    <option value="other">Other Sources</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Min Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="min_amount" class="form-control form-control-solid" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Apply Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h3 class="modal-title text-white">
                        <i class="ki-duotone ki-file-down fs-2 text-white me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Export Income
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-success ms-2" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-5">
                        <i class="ki-duotone ki-file-down fs-3x text-success mb-3">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <h4>Export Options</h4>
                        <p class="text-muted">Choose your preferred export format</p>
                    </div>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-light-success">
                            <i class="ki-duotone ki-file-text fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export CSV
                        </a>
                        <a href="#" class="btn btn-light-primary">
                            <i class="ki-duotone ki-file-code fs-2 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Export JSON
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

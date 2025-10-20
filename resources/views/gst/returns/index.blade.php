@extends('layouts.app')

@section('title', 'GST Returns')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="ki-duotone ki-file-text fs-2 me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                GST Returns
            </h3>
            <div class="d-flex gap-2">
                <a href="{{ route('gst.cmp.dashboard') }}" class="btn btn-light-secondary">
                    <i class="ki-duotone ki-calculator fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Composition
                </a>
                <a href="{{ route('gst.returns.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    New Return
                </a>
            </div>
        </div>
        <div class="card-body">

            @if (session('ok'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('ok') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="card card-flush mb-5">
                <div class="card-header pt-7">
                    <h3 class="card-title">
                        <span class="card-label fw-bold text-gray-800">Filter Returns</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" method="get">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Return Type</label>
                            <select name="type" class="form-select form-select-solid">
                                <option value="">All Types</option>
                                @foreach(['gstr1'=>'GSTR-1','gstr3b'=>'GSTR-3B','gstr4'=>'GSTR-4','gstr9'=>'GSTR-9','gstr9a'=>'GSTR-9A','gstr9c'=>'GSTR-9C','cmp08'=>'CMP-08'] as $k=>$v)
                                    <option value="{{ $k }}" @selected(request('type')===$k)>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select form-select-solid">
                                <option value="">All Status</option>
                                @foreach(['draft','prepared','filed','rejected','cancelled'] as $s)
                                    <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Period From</label>
                            <input type="date" name="from" class="form-control form-control-solid" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button class="btn btn-primary me-2" type="submit">
                                <i class="ki-duotone ki-funnel fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Filter
                            </button>
                            <a href="{{ route('gst.returns.index') }}" class="btn btn-light">
                                <i class="ki-duotone ki-cross fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- GST Returns Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_gst_returns">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-100px">Type</th>
                            <th class="min-w-200px">Period</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-120px text-end">Taxable Value</th>
                            <th class="min-w-100px text-end">CGST</th>
                            <th class="min-w-100px text-end">SGST</th>
                            <th class="min-w-100px text-end">IGST</th>
                            <th class="min-w-120px text-end">Net Payable</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $r)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-3">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="ki-duotone ki-file-text fs-2 text-primary">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                        <div class="fw-bold text-gray-800">{{ strtoupper($r->type) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-gray-800">
                                        <div class="fw-semibold">{{ $r->period_from->format('d M Y') }}</div>
                                        <div class="text-muted fs-7">to {{ $r->period_to->format('d M Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'filed' => 'success',
                                            'prepared' => 'info',
                                            'draft' => 'warning',
                                            'rejected' => 'danger',
                                            'cancelled' => 'secondary'
                                        ];
                                        $statusColor = $statusColors[$r->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-light-{{ $statusColor }} fw-bold">{{ ucfirst($r->status) }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold">₹{{ number_format($r->taxable_value, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($r->cgst, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($r->sgst, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($r->igst, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold fs-6">₹{{ number_format($r->net_payable, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="{{ route('gst.returns.show', $r->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View Return">
                                            <i class="ki-duotone ki-eye fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </a>
                                        @if($r->status !== 'filed')
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Return">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        @endif
                                        @if($r->status === 'draft')
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Return" 
                                           onclick="return confirm('Are you sure you want to delete this return?')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                        @endif
                                        @if($r->status === 'prepared')
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm" title="File Return">
                                            <i class="ki-duotone ki-check fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10">
                                    <div class="text-gray-400 fs-6">
                                        <i class="ki-duotone ki-file-text fs-3x mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <br>
                                        No GST returns found. 
                                        <a href="{{ route('gst.returns.create') }}" class="fw-bold text-primary">Create your first return</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($returns->hasPages())
            <div class="d-flex flex-stack flex-wrap pt-10">
                <div class="fs-6 fw-semibold text-gray-700">
                    Showing {{ $returns->firstItem() }} to {{ $returns->lastItem() }} of {{ $returns->total() }} results
                </div>
                <div>
                    {{ $returns->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Status Legend -->
    <div class="card mt-5">
        <div class="card-header">
            <h3 class="card-title">Status Legend</h3>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-auto">
                    <span class="badge badge-light-warning fw-bold me-2">Draft</span>
                    <span class="text-muted">Return is in draft state</span>
                </div>
                <div class="col-auto">
                    <span class="badge badge-light-info fw-bold me-2">Prepared</span>
                    <span class="text-muted">Return is prepared and ready to file</span>
                </div>
                <div class="col-auto">
                    <span class="badge badge-light-success fw-bold me-2">Filed</span>
                    <span class="text-muted">Return has been successfully filed</span>
                </div>
                <div class="col-auto">
                    <span class="badge badge-light-danger fw-bold me-2">Rejected</span>
                    <span class="text-muted">Return was rejected by GST portal</span>
                </div>
                <div class="col-auto">
                    <span class="badge badge-light-secondary fw-bold me-2">Cancelled</span>
                    <span class="text-muted">Return has been cancelled</span>
                </div>
            </div>
        </div>
    </div>
@endsection

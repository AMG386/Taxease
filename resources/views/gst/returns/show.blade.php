@extends('layouts.app')

@section('title', 'GST Return Details - ' . strtoupper($gstReturn->type))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">GST {{ strtoupper($gstReturn->type) }} Return Details</h3>
        <div class="d-flex gap-2">
            @if($gstReturn->status === 'draft')
                <form method="POST" action="{{ route('gst.returns.prepare', $gstReturn->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-primary btn-sm">
                        <i class="ki-duotone ki-gear fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Prepare Return
                    </button>
                </form>
            @endif
            @if($gstReturn->status === 'prepared')
                <form method="POST" action="{{ route('gst.returns.file', $gstReturn->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">
                        <i class="ki-duotone ki-check fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Mark as Filed
                    </button>
                </form>
            @endif
            <a href="{{ route('gst.returns.export', $gstReturn->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="ki-duotone ki-file-down fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Export
            </a>
            <a href="{{ route('gst.returns.index') }}" class="btn btn-light btn-sm">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        {{-- Alerts --}}
        @if(session('ok'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('ok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong class="me-2">Please review:</strong>
                <ul class="mb-0 ps-4">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-6">
            {{-- Basic Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Return Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Return Type</label>
                                <div class="fs-6">{{ strtoupper($gstReturn->type) }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Status</label>
                                <div class="fs-6">
                                    @php
                                        $statusColors = [
                                            'draft' => 'warning',
                                            'prepared' => 'primary',
                                            'filed' => 'success',
                                            'processed' => 'info'
                                        ];
                                        $statusColor = $statusColors[$gstReturn->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-light-{{ $statusColor }} text-uppercase">{{ $gstReturn->status }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Period From</label>
                                <div class="fs-6">{{ optional($gstReturn->period_from)->format('d M, Y') ?? 'Not set' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Period To</label>
                                <div class="fs-6">{{ optional($gstReturn->period_to)->format('d M, Y') ?? 'Not set' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Preparation & Filing Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Processing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Created On</label>
                                <div class="fs-6">{{ $gstReturn->created_at->format('d M, Y H:i') }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Prepared On</label>
                                <div class="fs-6">
                                    @if($gstReturn->prepared_on)
                                        {{ $gstReturn->prepared_on->format('d M, Y H:i') }}
                                    @else
                                        <span class="text-muted">Not prepared yet</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Filed On</label>
                                <div class="fs-6">
                                    @if($gstReturn->filed_on)
                                        {{ $gstReturn->filed_on->format('d M, Y H:i') }}
                                        <span class="badge badge-light-success ms-2">Filed</span>
                                    @else
                                        <span class="text-muted">Not filed yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction Summary --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Transaction Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Total Items</label>
                                <div class="fs-6">
                                    <span class="badge badge-light-info">{{ is_countable($items) ? count($items) : 0 }} transactions</span>
                                </div>
                            </div>
                            @if(($gstReturn->audits_count ?? 0) > 0)
                            <div class="col-12">
                                <label class="form-label fw-bold">Attachments</label>
                                <div class="fs-6">
                                    <span class="badge badge-light-primary">{{ $gstReturn->audits_count }} document(s)</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Document Upload --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Document Upload</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('gst.returns.audit', $gstReturn->id) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label fw-bold">Attach File</label>
                                <input type="file" name="file" class="form-control" required>
                                <div class="form-text">Supported: PDF, Excel, Word (Max: 10MB)</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <input type="text" name="remarks" class="form-control" placeholder="Optional remarks...">
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100">
                                    <i class="ki-duotone ki-file-up fs-2 me-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Upload Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tax Breakdown --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Financial Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Key Financial Metrics --}}
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Taxable Value</span>
                                    <span class="fs-3 fw-bold text-primary">₹{{ number_format($gstReturn->taxable_value ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Total Tax Amount</span>
                                    <span class="fs-3 fw-bold text-warning">₹{{ number_format(($gstReturn->cgst ?? 0) + ($gstReturn->sgst ?? 0) + ($gstReturn->igst ?? 0), 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">ITC Eligible</span>
                                    <span class="fs-3 fw-bold text-info">₹{{ number_format($gstReturn->itc_eligible ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Net Payable</span>
                                    <span class="fs-2 fw-bolder text-success">₹{{ number_format($gstReturn->net_payable ?? 0, 2) }}</span>
                                </div>
                            </div>

                            {{-- Tax Components --}}
                            <div class="col-12">
                                <div class="separator my-4"></div>
                                <h6 class="mb-3">Tax Components</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 text-center">
                                            <div class="fs-7 text-muted">CGST</div>
                                            <div class="fs-6 fw-bold">Central GST</div>
                                            <div class="fs-5 text-primary">₹{{ number_format($gstReturn->cgst ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 text-center">
                                            <div class="fs-7 text-muted">SGST</div>
                                            <div class="fs-6 fw-bold">State GST</div>
                                            <div class="fs-5 text-info">₹{{ number_format($gstReturn->sgst ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 text-center">
                                            <div class="fs-7 text-muted">IGST</div>
                                            <div class="fs-6 fw-bold">Integrated GST</div>
                                            <div class="fs-5 text-warning">₹{{ number_format($gstReturn->igst ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                    @if($gstReturn->cess && $gstReturn->cess > 0)
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 text-center">
                                            <div class="fs-7 text-muted">CESS</div>
                                            <div class="fs-6 fw-bold">Compensation Cess</div>
                                            <div class="fs-5 text-secondary">₹{{ number_format($gstReturn->cess, 2) }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attached Documents --}}
            @if(($gstReturn->audits_count ?? 0) > 0)
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Attached Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($audits as $audit)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 border rounded">
                                    <div class="symbol symbol-40px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="ki-duotone ki-file fs-2 text-primary">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-gray-800">{{ $audit->original_name }}</div>
                                        @if($audit->remarks)
                                            <div class="text-muted fs-7">{{ $audit->remarks }}</div>
                                        @endif
                                        <div class="text-muted fs-8">{{ $audit->created_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                    <a href="{{ route('gst.returns.audit.download', $audit->id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ki-duotone ki-arrow-down fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Transaction Items --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Transaction Items</h5>
                    </div>
                    <div class="card-body">
                        @if(is_countable($items) && count($items) > 0)
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-80px">Section</th>
                                            <th class="min-w-120px">Invoice</th>
                                            <th class="min-w-100px">Date</th>
                                            <th class="min-w-150px">Party</th>
                                            <th class="min-w-120px">GSTIN</th>
                                            <th class="min-w-80px text-center">HSN</th>
                                            <th class="min-w-100px text-end">Taxable</th>
                                            <th class="min-w-80px text-end">CGST</th>
                                            <th class="min-w-80px text-end">SGST</th>
                                            <th class="min-w-80px text-end">IGST</th>
                                            <th class="min-w-100px text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr>
                                            <td>
                                                <span class="badge badge-light-primary fs-7 fw-semibold">{{ $item->section }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold text-hover-primary fs-6">{{ $item->invoice_no }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold fs-7">
                                                    {{ optional($item->invoice_date)->format('d M Y') ?? '—' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-6" title="{{ $item->party_name }}">
                                                        {{ Str::limit($item->party_name, 25) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold fs-7 font-monospace">
                                                    {{ $item->counterparty_gstin }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($item->hsn)
                                                    <span class="badge badge-light-info fs-8">{{ $item->hsn }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span class="text-dark fw-bold fs-6 font-monospace">
                                                    ₹{{ number_format((float)($item->taxable_value ?? 0), 2) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-primary fw-bold fs-7 font-monospace">
                                                    ₹{{ number_format((float)($item->cgst ?? 0), 2) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-info fw-bold fs-7 font-monospace">
                                                    ₹{{ number_format((float)($item->sgst ?? 0), 2) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-warning fw-bold fs-7 font-monospace">
                                                    ₹{{ number_format((float)($item->igst ?? 0), 2) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="text-success fw-bold fs-6 font-monospace">
                                                    ₹{{ number_format((float)($item->total ?? 0), 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="symbol symbol-100px symbol-circle mb-7">
                                        <div class="symbol-label fs-1 bg-light-primary text-primary">
                                            <i class="ki-duotone ki-abstract-35 fs-2x">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </div>
                                    </div>
                                    <div class="text-gray-500 fs-6 fw-semibold mb-4">No transactions found.</div>
                                    <div class="text-gray-400 fs-7">Click <strong class="text-primary">Prepare Return</strong> to import transactions.</div>
                                    @if($gstReturn->status === 'draft')
                                        <form method="POST" action="{{ route('gst.returns.prepare', $gstReturn->id) }}" class="mt-4">
                                            @csrf
                                            <button class="btn btn-primary">
                                                <i class="ki-duotone ki-gear fs-3 me-2">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                Prepare Return Now
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Purchase Invoice Details - ' . $purchaseInvoice->invoice_no)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Purchase Invoice Details</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('purchases.invoices.edit', $purchaseInvoice) }}" class="btn btn-primary btn-sm">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Edit
            </a>
            <a href="{{ route('purchases.invoices.index') }}" class="btn btn-light btn-sm">
                <i class="ki-duotone ki-arrow-left fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-6">
            {{-- Basic Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Invoice No</label>
                                <div class="fs-6">{{ $purchaseInvoice->invoice_no }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Invoice Date</label>
                                <div class="fs-6">{{ $purchaseInvoice->invoice_date?->format('d M, Y') ?? 'Not set' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">HSN Code</label>
                                <div class="fs-6">{{ $purchaseInvoice->hsn ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Created</label>
                                <div class="fs-6">{{ $purchaseInvoice->created_at->format('d M, Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vendor Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Vendor Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Vendor Name</label>
                                <div class="fs-6">{{ $purchaseInvoice->supplier_name ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Vendor GSTIN</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->supplier_gstin)
                                        <span class="badge badge-light-primary">{{ $purchaseInvoice->supplier_gstin }}</span>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Vendor Type</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->vendor_type == 'registered')
                                        <span class="badge badge-light-success">Registered</span>
                                    @elseif($purchaseInvoice->vendor_type == 'unregistered')
                                        <span class="badge badge-light-warning">Unregistered</span>
                                    @elseif($purchaseInvoice->vendor_type == 'sez')
                                        <span class="badge badge-light-info">SEZ</span>
                                    @elseif($purchaseInvoice->vendor_type == 'import')
                                        <span class="badge badge-light-primary">Import</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Supply Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Supply Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Place of Supply</label>
                                <div class="fs-6">{{ $purchaseInvoice->place_of_supply ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Origin State</label>
                                <div class="fs-6">{{ $purchaseInvoice->origin_state ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Supply Type</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->supply_type == 'intra')
                                        <span class="badge badge-light-success">Intra-State</span>
                                    @elseif($purchaseInvoice->supply_type == 'inter')
                                        <span class="badge badge-light-primary">Inter-State</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Reverse Charge</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->reverse_charge)
                                        <span class="badge badge-light-danger">Yes</span>
                                    @else
                                        <span class="badge badge-light-success">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Item & Pricing --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Item & Pricing</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Quantity</label>
                                <div class="fs-6">{{ $purchaseInvoice->qty ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">UOM</label>
                                <div class="fs-6">{{ $purchaseInvoice->uom ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Unit Price</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->unit_price)
                                        ₹{{ number_format($purchaseInvoice->unit_price, 2) }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Tax Inclusive</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->tax_inclusive)
                                        <span class="badge badge-light-info">Yes</span>
                                    @else
                                        <span class="badge badge-light-secondary">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ITC Information --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">ITC Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">ITC Eligibility</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->itc_eligibility == 'eligible')
                                        <span class="badge badge-light-success">Eligible</span>
                                    @elseif($purchaseInvoice->itc_eligibility == 'ineligible')
                                        <span class="badge badge-light-danger">Ineligible</span>
                                    @elseif($purchaseInvoice->itc_eligibility == 'blocked')
                                        <span class="badge badge-light-warning">Blocked</span>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">ITC Type</label>
                                <div class="fs-6">
                                    @if($purchaseInvoice->itc_type)
                                        {{ ucfirst(str_replace('_', ' ', $purchaseInvoice->itc_type)) }}
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </div>
                            </div>
                            @if($purchaseInvoice->itc_avail_month)
                            <div class="col-12">
                                <label class="form-label fw-bold">ITC Availment Month</label>
                                <div class="fs-6">{{ date('F Y', strtotime($purchaseInvoice->itc_avail_month . '-01')) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Import Information --}}
            @if($purchaseInvoice->vendor_type == 'import' || $purchaseInvoice->boe_no)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Import Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Bill of Entry No</label>
                                <div class="fs-6">{{ $purchaseInvoice->boe_no ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Bill of Entry Date</label>
                                <div class="fs-6">{{ $purchaseInvoice->boe_date?->format('d M, Y') ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tax Breakdown --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tax Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Taxable Value & Tax Rate --}}
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Taxable Value</span>
                                    <span class="fs-3 fw-bold text-primary">₹{{ number_format($purchaseInvoice->taxable_value ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">GST Rate</span>
                                    <span class="fs-3 fw-bold text-info">{{ number_format($purchaseInvoice->tax_rate ?? 0, 2) }}%</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Total Tax Amount</span>
                                    <span class="fs-3 fw-bold text-warning">₹{{ number_format($purchaseInvoice->tax_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex flex-column">
                                    <span class="text-muted fs-7">Total Invoice Value</span>
                                    <span class="fs-2 fw-bolder text-success">₹{{ number_format($purchaseInvoice->total_invoice_value ?? 0, 2) }}</span>
                                </div>
                            </div>

                            {{-- Tax Components --}}
                            <div class="col-12">
                                <div class="separator my-4"></div>
                                <h6 class="mb-3">Tax Components</h6>
                                <div class="row g-3">
                                    @if($purchaseInvoice->supply_type == 'intra')
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">CGST</div>
                                                <div class="fs-6 fw-bold">{{ number_format($purchaseInvoice->cgst_rate ?? 0, 2) }}%</div>
                                                <div class="fs-5 text-primary">₹{{ number_format($purchaseInvoice->cgst_amount ?? 0, 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">SGST</div>
                                                <div class="fs-6 fw-bold">{{ number_format($purchaseInvoice->sgst_rate ?? 0, 2) }}%</div>
                                                <div class="fs-5 text-primary">₹{{ number_format($purchaseInvoice->sgst_amount ?? 0, 2) }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">IGST</div>
                                                <div class="fs-6 fw-bold">0.00%</div>
                                                <div class="fs-5 text-muted">₹0.00</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">CGST</div>
                                                <div class="fs-6 fw-bold">0.00%</div>
                                                <div class="fs-5 text-muted">₹0.00</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">SGST</div>
                                                <div class="fs-6 fw-bold">0.00%</div>
                                                <div class="fs-5 text-muted">₹0.00</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border rounded p-3 text-center">
                                                <div class="fs-7 text-muted">IGST</div>
                                                <div class="fs-6 fw-bold">{{ number_format($purchaseInvoice->igst_rate ?? 0, 2) }}%</div>
                                                <div class="fs-5 text-primary">₹{{ number_format($purchaseInvoice->igst_amount ?? 0, 2) }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Round Off --}}
                            @if($purchaseInvoice->round_off && $purchaseInvoice->round_off != 0)
                            <div class="col-12">
                                <div class="alert alert-light-info d-flex align-items-center">
                                    <i class="ki-duotone ki-information-5 fs-2 text-info me-4">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <div>
                                        <strong>Round Off Applied:</strong> ₹{{ number_format($purchaseInvoice->round_off, 2) }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'View Sales Invoice - ' . $salesInvoice->invoice_no)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Sales Invoice Details</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('sales.invoices.edit', $salesInvoice) }}" class="btn btn-primary btn-sm">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                Edit
            </a>
            <a href="{{ route('sales.invoices.index') }}" class="btn btn-light btn-sm">
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
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <h4 class="card-title">Basic Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Invoice No:</label>
                                <div class="text-gray-800">{{ $salesInvoice->invoice_no }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Invoice Date:</label>
                                <div class="text-gray-800">{{ $salesInvoice->invoice_date?->format('d M Y') }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">HSN Code:</label>
                                <div class="text-gray-800">{{ $salesInvoice->hsn ?? '-' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Invoice Type:</label>
                                <span class="badge badge-light-primary">{{ strtoupper($salesInvoice->invoice_type ?? 'B2B') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <h4 class="card-title">Customer Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Customer Name:</label>
                                <div class="text-gray-800">{{ $salesInvoice->customer_name ?? '-' }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Customer GSTIN:</label>
                                <div class="text-gray-800">{{ $salesInvoice->customer_gstin ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Supply & Location Details -->
            <div class="col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <h4 class="card-title">Supply & Location</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-bold">Supply Type:</label>
                                <span class="badge {{ $salesInvoice->supply_type === 'inter' ? 'badge-light-warning' : 'badge-light-success' }}">
                                    {{ strtoupper($salesInvoice->supply_type ?? 'INTRA') }}
                                </span>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Reverse Charge:</label>
                                <span class="badge {{ $salesInvoice->reverse_charge ? 'badge-light-danger' : 'badge-light-success' }}">
                                    {{ $salesInvoice->reverse_charge ? 'YES' : 'NO' }}
                                </span>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Place of Supply:</label>
                                <div class="text-gray-800">{{ $salesInvoice->place_of_supply ?? '-' }}</div>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Origin State:</label>
                                <div class="text-gray-800">{{ $salesInvoice->origin_state ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Details -->
            <div class="col-md-6">
                <div class="card card-flush h-100">
                    <div class="card-header">
                        <h4 class="card-title">Item Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-4">
                                <label class="form-label fw-bold">Quantity:</label>
                                <div class="text-gray-800">{{ $salesInvoice->qty ?? 1 }}</div>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-bold">UOM:</label>
                                <div class="text-gray-800">{{ $salesInvoice->uom ?? '-' }}</div>
                            </div>
                            <div class="col-4">
                                <label class="form-label fw-bold">Unit Price:</label>
                                <div class="text-gray-800">₹{{ number_format($salesInvoice->unit_price ?? 0, 2) }}</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Tax Inclusive:</label>
                                <span class="badge {{ $salesInvoice->tax_inclusive ? 'badge-light-warning' : 'badge-light-info' }}">
                                    {{ $salesInvoice->tax_inclusive ? 'YES' : 'NO' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Breakdown -->
            <div class="col-12">
                <div class="card card-flush">
                    <div class="card-header">
                        <h4 class="card-title">Tax Breakdown</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Taxable Value</th>
                                        <th>Tax Rate</th>
                                        <th>CGST ({{ $salesInvoice->cgst_rate }}%)</th>
                                        <th>SGST ({{ $salesInvoice->sgst_rate }}%)</th>
                                        <th>IGST ({{ $salesInvoice->igst_rate }}%)</th>
                                        <th>Total Tax</th>
                                        <th>Round Off</th>
                                        <th class="fw-bold">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">₹{{ number_format($salesInvoice->taxable_value, 2) }}</td>
                                        <td>{{ $salesInvoice->tax_rate }}%</td>
                                        <td>₹{{ number_format($salesInvoice->cgst_amount, 2) }}</td>
                                        <td>₹{{ number_format($salesInvoice->sgst_amount, 2) }}</td>
                                        <td>₹{{ number_format($salesInvoice->igst_amount, 2) }}</td>
                                        <td class="fw-bold">₹{{ number_format($salesInvoice->tax_amount ?? ($salesInvoice->cgst_amount + $salesInvoice->sgst_amount + $salesInvoice->igst_amount), 2) }}</td>
                                        <td>₹{{ number_format($salesInvoice->round_off ?? 0, 2) }}</td>
                                        <td class="fw-bold text-primary fs-5">₹{{ number_format($salesInvoice->total_invoice_value, 2) }}</td>
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
@endsection
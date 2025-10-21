@extends('layouts.app')

@section('title', 'Edit Sales Invoice - ' . $salesInvoice->invoice_no)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit Sales Invoice</h3>
        <a href="{{ route('sales.invoices.show', $salesInvoice) }}" class="btn btn-light btn-sm">
            <i class="ki-duotone ki-arrow-left fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            Back to View
        </a>
    </div>
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('sales.invoices.update', $salesInvoice) }}" id="invoiceForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Core --}}
                <div class="col-md-4">
                    <label class="form-label">Invoice No</label>
                    <input name="invoice_no" class="form-control" required value="{{ old('invoice_no', $salesInvoice->invoice_no) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control" required value="{{ old('invoice_date', $salesInvoice->invoice_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HSN</label>
                    <input name="hsn" class="form-control" value="{{ old('hsn', $salesInvoice->hsn) }}">
                </div>

                {{-- Parties --}}
                <div class="col-md-6">
                    <label class="form-label">Customer Name</label>
                    <input name="customer_name" class="form-control" value="{{ old('customer_name', $salesInvoice->customer_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Customer GSTIN</label>
                    <input name="customer_gstin" id="customer_gstin" class="form-control" value="{{ old('customer_gstin', $salesInvoice->customer_gstin) }}" placeholder="Required for B2B">
                    <small class="text-muted d-block">Shown/required only for B2B.</small>
                </div>

                {{-- Place / Origin --}}
                <div class="col-md-6">
                    <label class="form-label">Place of Supply (State)</label>
                    <input name="place_of_supply" id="place_of_supply" class="form-control" value="{{ old('place_of_supply', $salesInvoice->place_of_supply) }}" placeholder="e.g., Kerala">
                    <small class="text-muted">Determines inter/intra state.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Origin State</label>
                    <input name="origin_state" id="origin_state" class="form-control" required value="{{ old('origin_state', $salesInvoice->origin_state) }}" placeholder="e.g., Kerala">
                </div>

                {{-- Invoice Classification --}}
                <div class="col-md-4">
                    <label class="form-label">Invoice Type</label>
                    <select name="invoice_type" id="invoice_type" class="form-select" required>
                        <option value="b2b" {{ old('invoice_type', $salesInvoice->invoice_type)=='b2b'?'selected':'' }}>B2B - Business to Business</option>
                        <option value="b2c" {{ old('invoice_type', $salesInvoice->invoice_type)=='b2c'?'selected':'' }}>B2C - Business to Customer</option>
                        <option value="export" {{ old('invoice_type', $salesInvoice->invoice_type)=='export'?'selected':'' }}>Export</option>
                        <option value="sez" {{ old('invoice_type', $salesInvoice->invoice_type)=='sez'?'selected':'' }}>SEZ</option>
                        <option value="exempted" {{ old('invoice_type', $salesInvoice->invoice_type)=='exempted'?'selected':'' }}>Exempted / Nil Rated</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Supply Type</label>
                    <select name="supply_type" id="supply_type" class="form-select" required>
                        <option value="intra" {{ old('supply_type', $salesInvoice->supply_type)=='intra'?'selected':'' }}>Intra-State (CGST+SGST)</option>
                        <option value="inter" {{ old('supply_type', $salesInvoice->supply_type)=='inter'?'selected':'' }}>Inter-State (IGST)</option>
                    </select>
                    <small class="text-muted">Auto-set when Origin ≠ POS.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reverse Charge</label>
                    <select name="reverse_charge" id="reverse_charge" class="form-select">
                        <option value="no" {{ old('reverse_charge', $salesInvoice->reverse_charge ? 'yes' : 'no')=='no'?'selected':'' }}>No</option>
                        <option value="yes" {{ old('reverse_charge', $salesInvoice->reverse_charge ? 'yes' : 'no')=='yes'?'selected':'' }}>Yes</option>
                    </select>
                </div>

                {{-- Item / Amounts --}}
                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" id="qty" class="form-control" min="1" required value="{{ old('qty', $salesInvoice->qty ?? 1) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">UOM</label>
                    <input name="uom" class="form-control" value="{{ old('uom', $salesInvoice->uom) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Unit Price (₹)</label>
                    <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price', $salesInvoice->unit_price) }}">
                    <small class="text-muted">If given, taxable = qty × unit price (unless tax-inclusive).</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tax Inclusive?</label>
                    <select name="tax_inclusive" id="tax_inclusive" class="form-select">
                        <option value="no" {{ old('tax_inclusive', $salesInvoice->tax_inclusive ? 'yes' : 'no')=='no'?'selected':'' }}>No (Exclusive)</option>
                        <option value="yes" {{ old('tax_inclusive', $salesInvoice->tax_inclusive ? 'yes' : 'no')=='yes'?'selected':'' }}>Yes (Inclusive)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Taxable Value (₹)</label>
                    <input type="number" step="0.01" name="taxable_value" id="taxable_value" class="form-control" required value="{{ old('taxable_value', $salesInvoice->taxable_value) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">GST Rate (%)</label>
                    <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" required value="{{ old('tax_rate', $salesInvoice->tax_rate) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Invoice Value (₹)</label>
                    <input type="number" step="0.01" name="total_invoice_value" id="total_invoice_value" class="form-control" readonly value="{{ old('total_invoice_value', $salesInvoice->total_invoice_value) }}">
                </div>

                {{-- Auto Split (read-only) --}}
                <div class="col-md-3">
                    <label class="form-label">CGST Rate (%)</label>
                    <input type="number" step="0.01" name="cgst_rate" id="cgst_rate" class="form-control" readonly value="{{ $salesInvoice->cgst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SGST Rate (%)</label>
                    <input type="number" step="0.01" name="sgst_rate" id="sgst_rate" class="form-control" readonly value="{{ $salesInvoice->sgst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IGST Rate (%)</label>
                    <input type="number" step="0.01" name="igst_rate" id="igst_rate" class="form-control" readonly value="{{ $salesInvoice->igst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tax Amount (₹)</label>
                    <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" readonly value="{{ $salesInvoice->tax_amount }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">CGST Amount (₹)</label>
                    <input type="number" step="0.01" name="cgst_amount" id="cgst_amount" class="form-control" readonly value="{{ $salesInvoice->cgst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SGST Amount (₹)</label>
                    <input type="number" step="0.01" name="sgst_amount" id="sgst_amount" class="form-control" readonly value="{{ $salesInvoice->sgst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IGST Amount (₹)</label>
                    <input type="number" step="0.01" name="igst_amount" id="igst_amount" class="form-control" readonly value="{{ $salesInvoice->igst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Round Off (₹)</label>
                    <input type="number" step="0.01" name="round_off" id="round_off" class="form-control" value="{{ old('round_off', $salesInvoice->round_off ?? 0) }}">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Update Invoice</button>
                <a href="{{ route('sales.invoices.show', $salesInvoice) }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- Same calculation script as create page --}}
@push('scripts')
<script>
// You can include the same calculation JavaScript from the create page here
// or create a shared JS file
</script>
@endpush
@endsection
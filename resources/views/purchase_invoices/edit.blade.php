@extends('layouts.app')

@section('title', 'Edit Purchase Invoice - ' . $purchaseInvoice->invoice_no)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit Purchase Invoice</h3>
        <a href="{{ route('purchases.invoices.show', $purchaseInvoice) }}" class="btn btn-light btn-sm">
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

        <form method="POST" action="{{ route('purchases.invoices.update', $purchaseInvoice) }}" id="invoiceForm">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- Core --}}
                <div class="col-md-4">
                    <label class="form-label">Invoice No</label>
                    <input name="invoice_no" class="form-control" required value="{{ old('invoice_no', $purchaseInvoice->invoice_no) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="invoice_date" class="form-control" required value="{{ old('invoice_date', $purchaseInvoice->invoice_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">HSN</label>
                    <input name="hsn" class="form-control" value="{{ old('hsn', $purchaseInvoice->hsn) }}">
                </div>

                {{-- Vendor --}}
                <div class="col-md-6">
                    <label class="form-label">Vendor Name</label>
                    <input name="supplier_name" class="form-control" value="{{ old('supplier_name', $purchaseInvoice->supplier_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Vendor GSTIN</label>
                    <input name="supplier_gstin" id="supplier_gstin" class="form-control" value="{{ old('supplier_gstin', $purchaseInvoice->supplier_gstin) }}" placeholder="Required for registered vendors">
                </div>

                {{-- Vendor Type --}}
                <div class="col-md-4">
                    <label class="form-label">Vendor Type</label>
                    <select name="vendor_type" id="vendor_type" class="form-select" required>
                        <option value="registered" {{ old('vendor_type', $purchaseInvoice->vendor_type)=='registered'?'selected':'' }}>Registered</option>
                        <option value="unregistered" {{ old('vendor_type', $purchaseInvoice->vendor_type)=='unregistered'?'selected':'' }}>Unregistered</option>
                        <option value="sez" {{ old('vendor_type', $purchaseInvoice->vendor_type)=='sez'?'selected':'' }}>SEZ</option>
                        <option value="import" {{ old('vendor_type', $purchaseInvoice->vendor_type)=='import'?'selected':'' }}>Import</option>
                    </select>
                </div>

                {{-- Invoice Type --}}
                <div class="col-md-4">
                    <label class="form-label">Invoice Type</label>
                    <select name="invoice_type" id="invoice_type" class="form-select" required>
                        <option value="b2b" {{ old('invoice_type', $purchaseInvoice->invoice_type)=='b2b'?'selected':'' }}>B2B</option>
                        <option value="import" {{ old('invoice_type', $purchaseInvoice->invoice_type)=='import'?'selected':'' }}>Import</option>
                        <option value="sez" {{ old('invoice_type', $purchaseInvoice->invoice_type)=='sez'?'selected':'' }}>SEZ</option>
                        <option value="exempted" {{ old('invoice_type', $purchaseInvoice->invoice_type)=='exempted'?'selected':'' }}>Exempted / Nil Rated</option>
                    </select>
                </div>

                {{-- Reverse Charge --}}
                <div class="col-md-4">
                    <label class="form-label">Reverse Charge</label>
                    <select name="reverse_charge" id="reverse_charge" class="form-select">
                        <option value="no" {{ old('reverse_charge', $purchaseInvoice->reverse_charge ? 'yes' : 'no')=='no'?'selected':'' }}>No</option>
                        <option value="yes" {{ old('reverse_charge', $purchaseInvoice->reverse_charge ? 'yes' : 'no')=='yes'?'selected':'' }}>Yes</option>
                    </select>
                </div>

                {{-- Place / Origin --}}
                <div class="col-md-6">
                    <label class="form-label">Supplier State (Origin)</label>
                    <input name="origin_state" id="origin_state" class="form-control" required value="{{ old('origin_state', $purchaseInvoice->origin_state) }}" placeholder="e.g., Karnataka">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Place of Supply (Your Registration State)</label>
                    <input name="place_of_supply" id="place_of_supply" class="form-control" value="{{ old('place_of_supply', $purchaseInvoice->place_of_supply) }}" placeholder="e.g., Kerala">
                </div>

                {{-- Supply Type --}}
                <div class="col-md-4">
                    <label class="form-label">Supply Type</label>
                    <select name="supply_type" id="supply_type" class="form-select" required>
                        <option value="intra" {{ old('supply_type', $purchaseInvoice->supply_type)=='intra'?'selected':'' }}>Intra-State (CGST+SGST)</option>
                        <option value="inter" {{ old('supply_type', $purchaseInvoice->supply_type)=='inter'?'selected':'' }}>Inter-State (IGST)</option>
                    </select>
                    <small class="text-muted">Auto-set from states & vendor type.</small>
                </div>

                {{-- Optional import details --}}
                <div class="col-md-4">
                    <label class="form-label">Bill of Entry No (Import)</label>
                    <input name="boe_no" id="boe_no" class="form-control" value="{{ old('boe_no', $purchaseInvoice->boe_no) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Bill of Entry Date</label>
                    <input type="date" name="boe_date" id="boe_date" class="form-control" value="{{ old('boe_date', $purchaseInvoice->boe_date?->format('Y-m-d')) }}">
                </div>

                {{-- Item / Amounts --}}
                <div class="col-md-3">
                    <label class="form-label">Qty</label>
                    <input type="number" name="qty" id="qty" class="form-control" min="1" required value="{{ old('qty', $purchaseInvoice->qty ?? 1) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">UOM</label>
                    <input name="uom" class="form-control" value="{{ old('uom', $purchaseInvoice->uom) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Unit Price (₹)</label>
                    <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price', $purchaseInvoice->unit_price) }}">
                    <small class="text-muted">If given, taxable auto-calculates (incl/excl).</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tax Inclusive?</label>
                    <select name="tax_inclusive" id="tax_inclusive" class="form-select">
                        <option value="no" {{ old('tax_inclusive', $purchaseInvoice->tax_inclusive ? 'yes' : 'no')=='no'?'selected':'' }}>No (Exclusive)</option>
                        <option value="yes" {{ old('tax_inclusive', $purchaseInvoice->tax_inclusive ? 'yes' : 'no')=='yes'?'selected':'' }}>Yes (Inclusive)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Taxable Value (₹)</label>
                    <input type="number" step="0.01" name="taxable_value" id="taxable_value" class="form-control" required value="{{ old('taxable_value', $purchaseInvoice->taxable_value) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">GST Rate (%)</label>
                    <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" required value="{{ old('tax_rate', $purchaseInvoice->tax_rate) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Total Bill Value (₹)</label>
                    <input type="number" step="0.01" name="total_invoice_value" id="total_invoice_value" class="form-control" readonly value="{{ old('total_invoice_value', $purchaseInvoice->total_invoice_value) }}">
                </div>

                {{-- Auto Split (read-only) --}}
                <div class="col-md-3">
                    <label class="form-label">CGST Rate (%)</label>
                    <input type="number" step="0.01" name="cgst_rate" id="cgst_rate" class="form-control" readonly value="{{ $purchaseInvoice->cgst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SGST Rate (%)</label>
                    <input type="number" step="0.01" name="sgst_rate" id="sgst_rate" class="form-control" readonly value="{{ $purchaseInvoice->sgst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IGST Rate (%)</label>
                    <input type="number" step="0.01" name="igst_rate" id="igst_rate" class="form-control" readonly value="{{ $purchaseInvoice->igst_rate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tax Amount (₹)</label>
                    <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" readonly value="{{ $purchaseInvoice->tax_amount }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">CGST Amount (₹)</label>
                    <input type="number" step="0.01" name="cgst_amount" id="cgst_amount" class="form-control" readonly value="{{ $purchaseInvoice->cgst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SGST Amount (₹)</label>
                    <input type="number" step="0.01" name="sgst_amount" id="sgst_amount" class="form-control" readonly value="{{ $purchaseInvoice->sgst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">IGST Amount (₹)</label>
                    <input type="number" step="0.01" name="igst_amount" id="igst_amount" class="form-control" readonly value="{{ $purchaseInvoice->igst_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Round Off (₹)</label>
                    <input type="number" step="0.01" name="round_off" id="round_off" class="form-control" value="{{ old('round_off', $purchaseInvoice->round_off ?? 0) }}">
                </div>

                {{-- ITC Section --}}
                <div class="col-12"><h6 class="mt-3 mb-0">Input Tax Credit (ITC)</h6><hr class="my-2"></div>

                <div class="col-md-4">
                    <label class="form-label">ITC Eligibility</label>
                    <select name="itc_eligibility" id="itc_eligibility" class="form-select" required>
                        <option value="eligible" {{ old('itc_eligibility', $purchaseInvoice->itc_eligibility)=='eligible'?'selected':'' }}>Eligible</option>
                        <option value="ineligible" {{ old('itc_eligibility', $purchaseInvoice->itc_eligibility)=='ineligible'?'selected':'' }}>Ineligible</option>
                        <option value="blocked" {{ old('itc_eligibility', $purchaseInvoice->itc_eligibility)=='blocked'?'selected':'' }}>Blocked (Sec 17(5))</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">ITC Type</label>
                    <select name="itc_type" id="itc_type" class="form-select">
                        <option value="inputs" {{ old('itc_type', $purchaseInvoice->itc_type)=='inputs'?'selected':'' }}>Inputs</option>
                        <option value="capital_goods" {{ old('itc_type', $purchaseInvoice->itc_type)=='capital_goods'?'selected':'' }}>Capital Goods</option>
                        <option value="input_services" {{ old('itc_type', $purchaseInvoice->itc_type)=='input_services'?'selected':'' }}>Input Services</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">ITC Availment Month</label>
                    <input type="month" name="itc_avail_month" id="itc_avail_month" class="form-control" value="{{ old('itc_avail_month', $purchaseInvoice->itc_avail_month) }}">
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">Update Invoice</button>
                <a href="{{ route('purchases.invoices.show', $purchaseInvoice) }}" class="btn btn-light">Cancel</a>
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
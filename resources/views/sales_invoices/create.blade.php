{{-- resources/views/sales/invoices/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Sales Invoice')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Sales Invoice</h3>
  </div>
  <div class="card-body">

    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('sales.invoices.store') }}" id="invoiceForm">
      @csrf

      <div class="row g-3">
        {{-- Core --}}
        <div class="col-md-4">
          <label class="form-label">Invoice No</label>
          <input name="invoice_no" class="form-control" required value="{{ old('invoice_no') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Invoice Date</label>
          <input type="date" name="invoice_date" class="form-control" required value="{{ old('invoice_date') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">HSN</label>
          <input name="hsn" class="form-control" value="{{ old('hsn') }}">
        </div>

        {{-- Parties --}}
        <div class="col-md-6">
          <label class="form-label">Customer Name</label>
          <input name="customer_name" class="form-control" value="{{ old('customer_name') }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Customer GSTIN</label>
          <input name="customer_gstin" id="customer_gstin" class="form-control" value="{{ old('customer_gstin') }}" placeholder="Required for B2B">
          <small class="text-muted d-block">Shown/required only for B2B.</small>
        </div>

        {{-- Place / Origin --}}
        <div class="col-md-6">
          <label class="form-label">Place of Supply (State)</label>
          <input name="place_of_supply" id="place_of_supply" class="form-control" value="{{ old('place_of_supply') }}" placeholder="e.g., Kerala">
          <small class="text-muted">Determines inter/intra state.</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Origin State</label>
          <input name="origin_state" id="origin_state" class="form-control" required value="{{ old('origin_state') }}" placeholder="e.g., Kerala">
        </div>

        {{-- Invoice Classification --}}
        <div class="col-md-4">
          <label class="form-label">Invoice Type</label>
          <select name="invoice_type" id="invoice_type" class="form-select" required>
            <option value="b2b" {{ old('invoice_type')=='b2b'?'selected':'' }}>B2B - Business to Business</option>
            <option value="b2c" {{ old('invoice_type')=='b2c'?'selected':'' }}>B2C - Business to Customer</option>
            <option value="export" {{ old('invoice_type')=='export'?'selected':'' }}>Export</option>
            <option value="sez" {{ old('invoice_type')=='sez'?'selected':'' }}>SEZ</option>
            <option value="exempted" {{ old('invoice_type')=='exempted'?'selected':'' }}>Exempted / Nil Rated</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Supply Type</label>
          <select name="supply_type" id="supply_type" class="form-select" required>
            <option value="intra" {{ old('supply_type')=='intra'?'selected':'' }}>Intra-State (CGST+SGST)</option>
            <option value="inter" {{ old('supply_type')=='inter'?'selected':'' }}>Inter-State (IGST)</option>
          </select>
          <small class="text-muted">Auto-set when Origin ≠ POS.</small>
        </div>
        <div class="col-md-4">
          <label class="form-label">Reverse Charge</label>
          <select name="reverse_charge" id="reverse_charge" class="form-select">
            <option value="no" {{ old('reverse_charge')=='no'?'selected':'' }}>No</option>
            <option value="yes" {{ old('reverse_charge')=='yes'?'selected':'' }}>Yes</option>
          </select>
        </div>

        {{-- Item / Amounts --}}
        <div class="col-md-3">
          <label class="form-label">Qty</label>
          <input type="number" name="qty" id="qty" class="form-control" min="1" required value="{{ old('qty', 1) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">UOM</label>
          <input name="uom" class="form-control" value="{{ old('uom') }}">
        </div>

        {{-- Optional unit price to auto-derive taxable --}}
        <div class="col-md-3">
          <label class="form-label">Unit Price (₹)</label>
          <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price') }}">
          <small class="text-muted">If given, taxable = qty × unit price (unless tax-inclusive).</small>
        </div>
        <div class="col-md-3">
          <label class="form-label">Tax Inclusive?</label>
          <select name="tax_inclusive" id="tax_inclusive" class="form-select">
            <option value="no" {{ old('tax_inclusive')=='no'?'selected':'' }}>No (Exclusive)</option>
            <option value="yes" {{ old('tax_inclusive')=='yes'?'selected':'' }}>Yes (Inclusive)</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Taxable Value (₹)</label>
          <input type="number" step="0.01" name="taxable_value" id="taxable_value" class="form-control" required value="{{ old('taxable_value') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">GST Rate (%)</label>
          <input type="number" step="0.01" name="tax_rate" id="tax_rate" class="form-control" required value="{{ old('tax_rate', 18) }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Total Invoice Value (₹)</label>
          <input type="number" step="0.01" name="total_invoice_value" id="total_invoice_value" class="form-control" readonly value="{{ old('total_invoice_value') }}">
        </div>

        {{-- Auto Split (read-only to store) --}}
        <div class="col-md-3">
          <label class="form-label">CGST Rate (%)</label>
          <input type="number" step="0.01" name="cgst_rate" id="cgst_rate" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">SGST Rate (%)</label>
          <input type="number" step="0.01" name="sgst_rate" id="sgst_rate" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">IGST Rate (%)</label>
          <input type="number" step="0.01" name="igst_rate" id="igst_rate" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Tax Amount (₹)</label>
          <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" readonly>
        </div>

        <div class="col-md-3">
          <label class="form-label">CGST Amount (₹)</label>
          <input type="number" step="0.01" name="cgst_amount" id="cgst_amount" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">SGST Amount (₹)</label>
          <input type="number" step="0.01" name="sgst_amount" id="sgst_amount" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">IGST Amount (₹)</label>
          <input type="number" step="0.01" name="igst_amount" id="igst_amount" class="form-control" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label">Round Off (₹)</label>
          <input type="number" step="0.01" name="round_off" id="round_off" class="form-control" value="{{ old('round_off', 0) }}">
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('sales.invoices.index') }}" class="btn btn-light">Cancel</a>
      </div>
    </form>
  </div>
</div>

{{-- Lightweight auto-calc --}}
@push('scripts')
<script>
(function() {
  const byId = id => document.getElementById(id);

  const invoiceType     = byId('invoice_type');
  const customerGstin   = byId('customer_gstin');
  const posInput        = byId('place_of_supply');
  const originInput     = byId('origin_state');
  const supplyType      = byId('supply_type');

  const qty             = byId('qty');
  const unitPrice       = byId('unit_price');
  const taxInclusive    = byId('tax_inclusive');
  const taxableValue    = byId('taxable_value');
  const taxRate         = byId('tax_rate');

  const cgstRate        = byId('cgst_rate');
  const sgstRate        = byId('sgst_rate');
  const igstRate        = byId('igst_rate');

  const taxAmount       = byId('tax_amount');
  const cgstAmount      = byId('cgst_amount');
  const sgstAmount      = byId('sgst_amount');
  const igstAmount      = byId('igst_amount');
  const roundOff        = byId('round_off');
  const totalInvoiceVal = byId('total_invoice_value');

  function stateKey(s) {
    return (s || '').toString().trim().toLowerCase();
  }

  function decideSupplyType() {
    const pos = stateKey(posInput.value);
    const origin = stateKey(originInput.value);
    // Export/SEZ is treated as inter for IGST; exempted follows normal logic
    if (['export','sez'].includes(invoiceType.value)) return 'inter';
    return (pos && origin && pos !== origin) ? 'inter' : 'intra';
  }

  function toggleB2BFields() {
    if (invoiceType.value === 'b2b') {
      customerGstin.removeAttribute('disabled');
      customerGstin.setAttribute('required', 'required');
    } else {
      customerGstin.value = customerGstin.value || '';
      customerGstin.removeAttribute('required');
      customerGstin.removeAttribute('aria-required');
      customerGstin.removeAttribute('data-val');
      // keep it enabled for optional capture; uncomment to disable:
      // customerGstin.setAttribute('disabled', 'disabled');
    }
  }

  function toNum(v) { return isNaN(parseFloat(v)) ? 0 : parseFloat(v); }
  function round2(n) { return Math.round((n + Number.EPSILON) * 100) / 100; }

  function recalc() {
    // Decide supply type automatically
    const st = decideSupplyType();
    supplyType.value = st;

    const q  = Math.max(1, parseInt(qty.value || 1));
    const up = toNum(unitPrice.value);
    const rate = Math.max(0, toNum(taxRate.value)); // % e.g., 18

    let tv = toNum(taxableValue.value);

    // If unit price provided, auto derive taxable/amounts
    if (up > 0) {
      if (taxInclusive.value === 'yes') {
        // unit price includes tax → derive base
        const basePerUnit = up / (1 + (rate / 100));
        tv = q * basePerUnit;
      } else {
        // exclusive: base is qty × unit price
        tv = q * up;
      }
      taxableValue.value = tv ? round2(tv) : '';
    }

    // Split rates & compute tax
    let cgst_r = 0, sgst_r = 0, igst_r = 0;
    if (st === 'intra') {
      cgst_r = rate / 2;
      sgst_r = rate / 2;
      igst_r = 0;
    } else {
      cgst_r = 0;
      sgst_r = 0;
      igst_r = rate;
    }

    cgstRate.value = round2(cgst_r);
    sgstRate.value = round2(sgst_r);
    igstRate.value = round2(igst_r);

    // Compute tax amounts
    let cgst_a = tv * (cgst_r / 100);
    let sgst_a = tv * (sgst_r / 100);
    let igst_a = tv * (igst_r / 100);
    let tax_a  = cgst_a + sgst_a + igst_a;

    // If inclusive & unitPrice provided, recompute tax from gross
    if (up > 0 && taxInclusive.value === 'yes') {
      const gross = q * up;
      tax_a = gross - tv;
      if (st === 'intra') {
        cgst_a = tax_a / 2;
        sgst_a = tax_a / 2;
        igst_a = 0;
      } else {
        cgst_a = 0; sgst_a = 0; igst_a = tax_a;
      }
    }

    cgstAmount.value = round2(cgst_a);
    sgstAmount.value = round2(sgst_a);
    igstAmount.value = round2(igst_a);
    taxAmount.value  = round2(tax_a);

    // Total
    const roff = toNum(roundOff.value);
    const total = tv + tax_a + roff;
    totalInvoiceVal.value = round2(total);
  }

  ['change','keyup','blur'].forEach(ev => {
    [invoiceType, customerGstin, posInput, originInput, supplyType,
     qty, unitPrice, taxInclusive, taxableValue, taxRate, roundOff].forEach(el => {
      el.addEventListener(ev, () => {
        if (el === invoiceType) toggleB2BFields();
        recalc();
      });
    });
  });

  // Initial boot
  toggleB2BFields();
  recalc();
})();
</script>
@endpush
@endsection

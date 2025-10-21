{{-- resources/views/purchases/invoices/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add Purchase Bill')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Add Purchase Bill</h3>
  </div>
  <div class="card-body">

    @if ($errors->any())
      <div class="alert alert-danger">
        @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('purchases.invoices.store') }}" id="purchaseForm">
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

        {{-- Supplier --}}
        <div class="col-md-6">
          <label class="form-label">Supplier Name</label>
          <input name="supplier_name" class="form-control" value="{{ old('supplier_name') }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Supplier GSTIN</label>
          <input name="supplier_gstin" id="supplier_gstin" class="form-control" value="{{ old('supplier_gstin') }}" placeholder="If Registered/SEZ">
        </div>

        {{-- Classification --}}
        <div class="col-md-4">
          <label class="form-label">Vendor Type</label>
          <select name="vendor_type" id="vendor_type" class="form-select" required>
            <option value="registered" {{ old('vendor_type')=='registered'?'selected':'' }}>Registered</option>
            <option value="unregistered" {{ old('vendor_type')=='unregistered'?'selected':'' }}>Unregistered</option>
            <option value="sez" {{ old('vendor_type')=='sez'?'selected':'' }}>SEZ</option>
            <option value="import" {{ old('vendor_type')=='import'?'selected':'' }}>Import</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Invoice Type</label>
          <select name="invoice_type" id="invoice_type" class="form-select" required>
            <option value="b2b" {{ old('invoice_type')=='b2b'?'selected':'' }}>B2B</option>
            <option value="import" {{ old('invoice_type')=='import'?'selected':'' }}>Import</option>
            <option value="sez" {{ old('invoice_type')=='sez'?'selected':'' }}>SEZ</option>
            <option value="exempted" {{ old('invoice_type')=='exempted'?'selected':'' }}>Exempted / Nil Rated</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Reverse Charge (RCM)</label>
          <select name="reverse_charge" id="reverse_charge" class="form-select">
            <option value="no" {{ old('reverse_charge')=='no'?'selected':'' }}>No</option>
            <option value="yes" {{ old('reverse_charge')=='yes'?'selected':'' }}>Yes</option>
          </select>
          <small class="text-muted d-block">Legal services, GTA, imports (IGST on BoE), etc.</small>
        </div>

        {{-- Place / Origin (for tax split) --}}
        <div class="col-md-6">
          <label class="form-label">Supplier State (Origin)</label>
          <input name="origin_state" id="origin_state" class="form-control" required value="{{ old('origin_state') }}" placeholder="e.g., Karnataka">
        </div>
        <div class="col-md-6">
          <label class="form-label">Place of Supply (Your Registration State)</label>
          <input name="place_of_supply" id="place_of_supply" class="form-control" value="{{ old('place_of_supply') }}" placeholder="e.g., Kerala">
        </div>

        <div class="col-md-4">
          <label class="form-label">Supply Type</label>
          <select name="supply_type" id="supply_type" class="form-select" required>
            <option value="intra" {{ old('supply_type')=='intra'?'selected':'' }}>Intra-State (CGST+SGST)</option>
            <option value="inter" {{ old('supply_type')=='inter'?'selected':'' }}>Inter-State (IGST)</option>
          </select>
          <small class="text-muted">Auto-set from states & vendor type.</small>
        </div>

        {{-- Optional import details --}}
        <div class="col-md-4">
          <label class="form-label">Bill of Entry No (Import)</label>
          <input name="boe_no" id="boe_no" class="form-control" value="{{ old('boe_no') }}">
        </div>
        <div class="col-md-4">
          <label class="form-label">Bill of Entry Date</label>
          <input type="date" name="boe_date" id="boe_date" class="form-control" value="{{ old('boe_date') }}">
        </div>

        {{-- Qty / Pricing --}}
        <div class="col-md-3">
          <label class="form-label">Qty</label>
          <input type="number" name="qty" id="qty" class="form-control" min="1" required value="{{ old('qty', 1) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">UOM</label>
          <input name="uom" class="form-control" value="{{ old('uom') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Unit Price (₹)</label>
          <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" value="{{ old('unit_price') }}">
          <small class="text-muted">If given, taxable auto-calculates (incl/excl).</small>
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
          <label class="form-label">Total Bill Value (₹)</label>
          <input type="number" step="0.01" name="total_invoice_value" id="total_invoice_value" class="form-control" readonly value="{{ old('total_invoice_value') }}">
        </div>

        {{-- Tax split (auto / read-only to persist) --}}
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

        {{-- ITC Section --}}
        <div class="col-12"><h6 class="mt-3 mb-0">Input Tax Credit (ITC)</h6><hr class="my-2"></div>

        <div class="col-md-4">
          <label class="form-label">ITC Eligibility</label>
          <select name="itc_eligibility" id="itc_eligibility" class="form-select" required>
            <option value="eligible" {{ old('itc_eligibility')=='eligible'?'selected':'' }}>Eligible</option>
            <option value="ineligible" {{ old('itc_eligibility')=='ineligible'?'selected':'' }}>Ineligible</option>
            <option value="blocked" {{ old('itc_eligibility')=='blocked'?'selected':'' }}>Blocked (Sec 17(5))</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">ITC Type</label>
          <select name="itc_type" id="itc_type" class="form-select">
            <option value="inputs" {{ old('itc_type')=='inputs'?'selected':'' }}>Inputs</option>
            <option value="capital_goods" {{ old('itc_type')=='capital_goods'?'selected':'' }}>Capital Goods</option>
            <option value="input_services" {{ old('itc_type')=='input_services'?'selected':'' }}>Input Services</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">ITC Availment Month</label>
          <input type="month" name="itc_avail_month" id="itc_avail_month" class="form-control" value="{{ old('itc_avail_month') }}">
        </div>

        <div class="col-md-12">
          <label class="form-label">ITC Ineligible / Blocked Reason (optional)</label>
          <input name="itc_reason" id="itc_reason" class="form-control" value="{{ old('itc_reason') }}" placeholder="E.g., Motor vehicle, personal use, beyond time limit, etc.">
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('purchases.invoices.index') }}" class="btn btn-light">Cancel</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function() {
  const byId = id => document.getElementById(id);

  const vendorType   = byId('vendor_type');
  const invoiceType  = byId('invoice_type');
  const supplierGST  = byId('supplier_gstin');
  const originState  = byId('origin_state');
  const posState     = byId('place_of_supply');
  const supplyType   = byId('supply_type');

  const qty          = byId('qty');
  const unitPrice    = byId('unit_price');
  const taxInclusive = byId('tax_inclusive');
  const taxableValue = byId('taxable_value');
  const taxRate      = byId('tax_rate');

  const cgstRate     = byId('cgst_rate');
  const sgstRate     = byId('sgst_rate');
  const igstRate     = byId('igst_rate');

  const cgstAmount   = byId('cgst_amount');
  const sgstAmount   = byId('sgst_amount');
  const igstAmount   = byId('igst_amount');
  const taxAmount    = byId('tax_amount');
  const roundOff     = byId('round_off');
  const totalValue   = byId('total_invoice_value');

  const boeNo        = byId('boe_no');
  const boeDate      = byId('boe_date');

  function k(s){ return (s||'').toString().trim().toLowerCase(); }
  function toNum(v){ return isNaN(parseFloat(v)) ? 0 : parseFloat(v); }
  function r2(n){ return Math.round((n+Number.EPSILON)*100)/100; }

  function toggleSupplierFields() {
    if (vendorType.value === 'registered' || vendorType.value === 'sez') {
      supplierGST.removeAttribute('disabled');
    } else {
      supplierGST.removeAttribute('required');
      // supplierGST.setAttribute('disabled','disabled'); // keep optional
    }

    const isImport = vendorType.value === 'import' || invoiceType.value === 'import';
    [boeNo, boeDate].forEach(el => {
      if (isImport) el.removeAttribute('disabled'); else el.setAttribute('disabled','disabled');
    });
  }

  function decideSupplyType() {
    // Imports & SEZ purchases treated as inter-state (IGST)
    if (vendorType.value === 'import' || invoiceType.value === 'import' || vendorType.value === 'sez' || invoiceType.value === 'sez') {
      return 'inter';
    }
    const o = k(originState.value), p = k(posState.value);
    if (!o || !p) return supplyType.value || 'intra';
    return (o !== p) ? 'inter' : 'intra';
  }

  function recalc() {
    // auto supply type
    const st = decideSupplyType();
    supplyType.value = st;

    const q   = Math.max(1, parseInt(qty.value || 1));
    const up  = toNum(unitPrice.value);
    const rt  = Math.max(0, toNum(taxRate.value));
    let tv    = toNum(taxableValue.value);

    if (up > 0) {
      if (taxInclusive.value === 'yes') {
        const basePerUnit = up / (1 + (rt/100));
        tv = q * basePerUnit;
      } else {
        tv = q * up;
      }
      taxableValue.value = tv ? r2(tv) : '';
    }

    let cgst_r=0, sgst_r=0, igst_r=0;
    if (st === 'intra') { cgst_r = rt/2; sgst_r = rt/2; }
    else { igst_r = rt; }

    cgstRate.value = r2(cgst_r);
    sgstRate.value = r2(sgst_r);
    igstRate.value = r2(igst_r);

    let cgst_a = tv * (cgst_r/100);
    let sgst_a = tv * (sgst_r/100);
    let igst_a = tv * (igst_r/100);
    let tax_a  = cgst_a + sgst_a + igst_a;

    if (up > 0 && taxInclusive.value === 'yes') {
      const gross = q * up;
      tax_a = gross - tv;
      if (st === 'intra') { cgst_a = tax_a/2; sgst_a = tax_a/2; igst_a = 0; }
      else { cgst_a=0; sgst_a=0; igst_a = tax_a; }
    }

    cgstAmount.value = r2(cgst_a);
    sgstAmount.value = r2(sgst_a);
    igstAmount.value = r2(igst_a);
    taxAmount.value  = r2(tax_a);

    const ro = toNum(roundOff.value);
    totalValue.value = r2(tv + tax_a + ro);
  }

  ['change','keyup','blur'].forEach(ev => {
    [vendorType, invoiceType, supplierGST, originState, posState, supplyType,
     qty, unitPrice, taxInclusive, taxableValue, taxRate, roundOff, boeNo, boeDate]
    .forEach(el => el.addEventListener(ev, () => { toggleSupplierFields(); recalc(); }));
  });

  toggleSupplierFields();
  recalc();
})();
</script>
@endpush
@endsection

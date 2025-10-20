@extends('layouts.app')
@section('title','GSTR-3B Filing')

@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">GSTR-3B Filing</h3></div>
  <div class="card-body">
    <div class="stepper stepper-pills" id="kt_stepper">
      <div class="stepper-nav flex-center flex-wrap mb-10">
        <div class="stepper-item current" data-kt-stepper-element="nav"><h3 class="stepper-title">1. Select Period</h3></div>
        <div class="stepper-item" data-kt-stepper-element="nav"><h3 class="stepper-title">2. Review Summary</h3></div>
        <div class="stepper-item" data-kt-stepper-element="nav"><h3 class="stepper-title">3. Confirm & Generate</h3></div>
      </div>

      <div class="stepper-content">
        <div class="flex-column current" data-kt-stepper-element="content">
          <div class="row g-5"><div class="col-md-6">
            <label class="form-label">Return Period (YYYY-MM)</label>
            <input type="month" class="form-control" id="period" max="{{ date('Y-m') }}">
          </div></div>
        </div>

        <div class="flex-column" data-kt-stepper-element="content">
          <div id="summaryBox" class="border rounded p-4 bg-light d-none">
            <h5 class="mb-3">Summary</h5>
            <div class="row g-3">
              <div class="col-md-4"><div class="fw-bold">Sales Tax</div><div id="sSales">₹0</div></div>
              <div class="col-md-4"><div class="fw-bold">ITC</div><div id="sItc">₹0</div></div>
              <div class="col-md-4"><div class="fw-bold">Payable</div><div id="sPayable">₹0</div></div>
            </div><hr>
            <div class="row g-3">
              <div class="col-md-6"><div class="fw-bold">Sales</div><div class="text-muted" id="sSalesDetail"></div></div>
              <div class="col-md-6"><div class="fw-bold">Purchase (ITC)</div><div class="text-muted" id="sPurchDetail"></div></div>
            </div>
          </div>
          <div id="summaryEmpty" class="alert alert-info">Choose a period in Step 1 and click <b>Next</b> to load summary.</div>
        </div>

        <div class="flex-column" data-kt-stepper-element="content">
          <div class="alert alert-warning">Please confirm the computed summary and click <b>Generate GSTR-3B</b>.</div>
          <div id="genResult" class="alert d-none"></div>
          <div class="d-flex gap-2 mt-3">
            <a id="btnPdf" href="#" class="btn btn-light">Download PDF</a>
            <a id="btnCsv" href="#" class="btn btn-light">Download CSV</a>
          </div>
        </div>
      </div>

      <div class="d-flex flex-stack pt-10">
        <button type="button" class="btn btn-light" data-kt-stepper-action="previous">Back</button>
        <div>
          <button type="button" class="btn btn-primary" data-kt-stepper-action="submit" id="btnGenerate"><span class="indicator-label">Generate GSTR-3B</span></button>
          <button type="button" class="btn btn-primary" data-kt-stepper-action="next" id="btnNext">Next</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
const stepperEl = document.querySelector('#kt_stepper');
const stepper = new KTStepper(stepperEl);

stepper.on('kt.stepper.next', async function() {
  if (stepper.getCurrentStepIndex() === 1) {
    const period = document.getElementById('period').value;
    if (!period) { alert('Select period'); return; }
    const res = await fetch('{{ route("gst.summary") }}', {
      method: 'POST',
      headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content},
      body: JSON.stringify({ period })
    });
    const d = await res.json();
    document.getElementById('summaryEmpty').classList.add('d-none'); 
    document.getElementById('summaryBox').classList.remove('d-none');
    document.getElementById('sSales').textContent   = '₹' + d.sales_tax;
    document.getElementById('sItc').textContent     = '₹' + d.itc;
    document.getElementById('sPayable').textContent = '₹' + d.payable;
    const sB = d.buckets.sales, pB = d.buckets.purchase;
    document.getElementById('sSalesDetail').textContent = `Taxable ₹${sB.taxable}, CGST ₹${sB.cgst}, SGST ₹${sB.sgst}, IGST ₹${sB.igst}`;
    document.getElementById('sPurchDetail').textContent = `Taxable ₹${pB.taxable}, CGST ₹${pB.cgst}, SGST ₹${pB.sgst}, IGST ₹${pB.igst}`;
  }
  stepper.goNext();
});

stepper.on('kt.stepper.previous', function(){ stepper.goPrevious(); });

document.querySelector('[data-kt-stepper-action="submit"]').addEventListener('click', async function () {
  const period = document.getElementById('period').value;
  if (!period) return alert('Select period');
  const res = await fetch('{{ route("gst.generate3b") }}', {
    method: 'POST',
    headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content},
    body: JSON.stringify({ period })
  });
  const out = await res.json();
  const box = document.getElementById('genResult');
  box.className = 'alert alert-success';
  box.textContent = 'GSTR-3B JSON generated. Filing ID: ' + out.filing_id;
  box.classList.remove('d-none');
  document.getElementById('btnPdf').href = '{{ route("reports.gst.pdf") }}' + '?period=' + encodeURIComponent(period);
  document.getElementById('btnCsv').href = '{{ route("reports.gst.csv") }}' + '?period=' + encodeURIComponent(period);
});
</script>
@endsection

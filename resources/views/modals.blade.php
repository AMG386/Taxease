{{-- Upload Invoices (JSON) Modal --}}
<div class="modal fade" id="uploadInvoicesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="ki-duotone ki-upload me-2"></i> Upload Invoices (JSON)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Choose JSON file</label>
          <input type="file" id="invJsonFile" class="form-control" accept="application/json">
        </div>
        <div class="text-center text-muted my-2">— OR —</div>
        <div class="mb-3">
          <label class="form-label">Paste JSON</label>
          <textarea id="invJsonText" class="form-control" rows="6" placeholder='[{"type":"sales","invoice_no":"S-1001","date":"2025-10-15","gstin":"29ABCDE1234F1Z5","taxable_amount":12000,"cgst":540,"sgst":540,"igst":0,"total_amount":13080}]'></textarea>
        </div>
        <div id="invUploadAlert" class="alert d-none"></div>
        <small class="text-muted">JSON must be an <b>array of invoices</b> with keys:
          type (sales|purchase), invoice_no, date (YYYY-MM-DD), gstin (opt), taxable_amount, cgst, sgst, igst, total_amount.</small>
      </div>
      <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" id="btnInvUpload"><i class="ki-duotone ki-send me-1"></i> Process</button>
      </div>
    </div>
  </div>
</div>

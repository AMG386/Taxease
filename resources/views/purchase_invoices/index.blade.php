@extends('layouts.app')

@section('title', 'Purchase Invoices')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Purchase Invoices</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#jsonImportModal">
                    <i class="ki-duotone ki-upload fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Import JSON
                </button>
                <a href="{{ route('purchases.invoices.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add Purchase Bill
                </a>
            </div>
        </div>
        <div class="card-body">

            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="card card-flush mb-5">
                <div class="card-header pt-7">
                    <h3 class="card-title">
                        <span class="card-label fw-bold text-gray-800">Filter Purchase Invoices</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" method="get">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from" class="form-control form-control-solid" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to" class="form-control form-control-solid" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button class="btn btn-primary me-2">
                                <i class="ki-duotone ki-funnel fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Filter
                            </button>
                            <a href="{{ route('purchases.invoices.index') }}" class="btn btn-light">
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

            <!-- Purchase Invoices Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_purchase_invoices">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-50px">#</th>
                            <th class="min-w-150px">Invoice No</th>
                            <th class="min-w-100px">Date</th>
                            <th class="min-w-150px">Supplier</th>
                            <th class="min-w-100px text-end">Taxable</th>
                            <th class="min-w-80px text-center">Rate %</th>
                            <th class="min-w-100px text-end">CGST</th>
                            <th class="min-w-100px text-end">SGST</th>
                            <th class="min-w-100px text-end">IGST</th>
                            <th class="min-w-80px text-center">POS</th>
                            <th class="min-w-100px">Origin</th>
                            <th class="min-w-100px text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td>
                                    <div class="text-gray-800 fw-bold">{{ $inv->id }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-800 fw-bold">{{ $inv->invoice_no }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-800">{{ $inv->invoice_date?->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-800 fw-semibold">{{ $inv->supplier_name }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold">₹{{ number_format($inv->taxable_value, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-primary">{{ $inv->tax_rate }}%</span>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($inv->cgst_amount, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($inv->sgst_amount, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800">₹{{ number_format($inv->igst_amount, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-info">{{ $inv->place_of_supply }}</span>
                                </td>
                                <td>
                                    <div class="text-gray-800">{{ $inv->origin_state }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View Invoice">
                                            <i class="ki-duotone ki-eye fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Invoice">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Invoice" 
                                           onclick="return confirm('Are you sure you want to delete this invoice?')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-10">
                                    <div class="text-gray-400 fs-6">
                                        <i class="ki-duotone ki-files fs-3x mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <br>
                                        No purchase invoices found. 
                                        <a href="{{ route('purchases.invoices.create') }}" class="fw-bold text-primary">Create your first purchase bill</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
            <div class="d-flex flex-stack flex-wrap pt-10">
                <div class="fs-6 fw-semibold text-gray-700">
                    Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                </div>
                <div>
                    {{ $invoices->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Upload JSON Modal -->
    <div class="modal fade" id="jsonImportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h3 class="modal-title text-white">
                        <i class="ki-duotone ki-upload fs-2 text-white me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Import Purchase Invoices (JSON)
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1 text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>

                <div class="modal-body">
                    <form id="jsonImportForm" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-7">
                            <label class="form-label fw-bold required">Select JSON File</label>
                            <input type="file" name="json_file" id="jsonFile" class="form-control form-control-solid" accept=".json" required>
                            <div class="form-text">Upload a JSON file containing purchase invoice data.</div>
                        </div>

                        <div class="mb-7">
                            <label class="form-label fw-bold required">Invoice Type</label>
                            <select name="type" id="type" class="form-select form-select-solid" required>
                                <option value="">-- Select Type --</option>
                                <option value="sales">Sales</option>
                                <option value="purchase" selected>Purchase</option>
                            </select>
                        </div>
                    </form>

                    <div id="uploadStatus" class="alert d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnUploadJson">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-upload fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Upload & Import
                        </span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('btnUploadJson').addEventListener('click', async () => {
        const btn = document.getElementById('btnUploadJson');
        const fileInput = document.getElementById('jsonFile');
        const type = document.getElementById('type').value;
        const statusDiv = document.getElementById('uploadStatus');

        if (!fileInput.files.length) {
            Swal.fire({
                text: "Please select a JSON file.",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return;
        }

        if (!type) {
            Swal.fire({
                text: "Please select the invoice type.",
                icon: "warning",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            return;
        }

        // Show loading state
        btn.setAttribute("data-kt-indicator", "on");
        btn.disabled = true;

        try {
            const file = fileInput.files[0];
            const text = await file.text();
            let jsonData;
            
            try {
                jsonData = JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON format!');
            }

            // If array, wrap into the structure your API expects
            if (Array.isArray(jsonData)) {
                jsonData = {
                    type: type,
                    items: jsonData
                };
            } else {
                jsonData.type = type;
            }

            const response = await fetch('/invoices/import-json', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            });

            const data = await response.json();

            statusDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
            if (data.ok) {
                statusDiv.classList.add('alert-success');
                statusDiv.innerHTML = `<i class="ki-duotone ki-check-circle fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i><strong>Success:</strong> Imported ${data.imported} purchase invoices.`;
                
                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                statusDiv.classList.add('alert-danger');
                statusDiv.innerHTML = `<i class="ki-duotone ki-cross-circle fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i><strong>Failed:</strong> ${JSON.stringify(data.errors)}`;
            }
        } catch (error) {
            statusDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
            statusDiv.classList.add('alert-danger');
            statusDiv.innerHTML = `<i class="ki-duotone ki-cross-circle fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i><strong>Error:</strong> ${error.message}`;
        } finally {
            // Hide loading state
            btn.removeAttribute("data-kt-indicator");
            btn.disabled = false;
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Sales Invoices')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Sales Invoices</h3>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#jsonImportModal">
                    <i class="ki-duotone ki-upload fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Import JSON
                </button>
                <a href="{{ route('sales.invoices.create') }}" class="btn btn-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>
                    Add Sales Invoice
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

            <!-- Summary Cards -->
            <div class="row g-6 g-xl-9 mb-6">
                <div class="col-lg-3 col-md-6">
                    <div class="card card-flush">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₹</span>
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($invoices->sum('taxable_value'), 0) }}</span>
                                </div>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Taxable Value</span>
                            </div>
                        </div>
                        <div class="card-body pt-2 pb-4 d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label">
                                        <i class="ki-duotone ki-calculator fs-2x text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-flush">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₹</span>
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($invoices->sum('tax_amount'), 0) }}</span>
                                </div>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Tax Amount</span>
                            </div>
                        </div>
                        <div class="card-body pt-2 pb-4 d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label">
                                        <i class="ki-duotone ki-percentage fs-2x text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-flush">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-4 fw-semibold text-gray-400 me-1 align-self-start">₹</span>
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ number_format($invoices->sum('total_invoice_value'), 0) }}</span>
                                </div>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Invoice Value</span>
                            </div>
                        </div>
                        <div class="card-body pt-2 pb-4 d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label">
                                        <i class="ki-duotone ki-chart-line fs-2x text-warning">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-flush">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <div class="d-flex align-items-center">
                                    <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $invoices->total() }}</span>
                                </div>
                                <span class="text-gray-400 pt-1 fw-semibold fs-6">Total Invoices</span>
                            </div>
                        </div>
                        <div class="card-body pt-2 pb-4 d-flex align-items-center">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label">
                                        <i class="ki-duotone ki-files fs-2x text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card card-flush mb-5">
                <div class="card-header pt-7">
                    <h3 class="card-title">
                        <span class="card-label fw-bold text-gray-800">Filter Invoices</span>
                    </h3>
                </div>
                <div class="card-body">
                    <form class="row g-3" method="get">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">From Date</label>
                            <input type="date" name="from" class="form-control form-control-solid" value="{{ request('from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">To Date</label>
                            <input type="date" name="to" class="form-control form-control-solid" value="{{ request('to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Invoice Type</label>
                            <select name="invoice_type" class="form-select form-select-solid">
                                <option value="">All Types</option>
                                <option value="b2b" {{ request('invoice_type') == 'b2b' ? 'selected' : '' }}>B2B</option>
                                <option value="b2c" {{ request('invoice_type') == 'b2c' ? 'selected' : '' }}>B2C</option>
                                <option value="export" {{ request('invoice_type') == 'export' ? 'selected' : '' }}>Export</option>
                                <option value="sez" {{ request('invoice_type') == 'sez' ? 'selected' : '' }}>SEZ</option>
                                <option value="exempted" {{ request('invoice_type') == 'exempted' ? 'selected' : '' }}>Exempted</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Supply Type</label>
                            <select name="supply_type" class="form-select form-select-solid">
                                <option value="">All Supply Types</option>
                                <option value="intra" {{ request('supply_type') == 'intra' ? 'selected' : '' }}>Intra-State</option>
                                <option value="inter" {{ request('supply_type') == 'inter' ? 'selected' : '' }}>Inter-State</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-solid" 
                                   value="{{ request('customer_name') }}" placeholder="Search by customer name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Customer GSTIN</label>
                            <input type="text" name="customer_gstin" class="form-control form-control-solid" 
                                   value="{{ request('customer_gstin') }}" placeholder="Search by GSTIN">
                        </div>
                        <div class="col-md-4 align-self-end">
                            <button class="btn btn-primary me-2">
                                <i class="ki-duotone ki-funnel fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Filter
                            </button>
                            <a href="{{ route('sales.invoices.index') }}" class="btn btn-light">
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

            <!-- Invoices Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_invoices">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-50px">#</th>
                            <th class="min-w-150px">Invoice No</th>
                            <th class="min-w-100px">Date</th>
                            <th class="min-w-150px">Customer</th>
                            <th class="min-w-120px d-none d-lg-table-cell">GSTIN</th>
                            <th class="min-w-80px d-none d-xl-table-cell">HSN</th>
                            <th class="min-w-100px">Type</th>
                            <th class="min-w-80px d-none d-lg-table-cell">Supply</th>
                            <th class="min-w-60px text-center d-none d-md-table-cell">Qty</th>
                            <th class="min-w-100px text-end d-none d-lg-table-cell">Unit Price</th>
                            <th class="min-w-100px text-end">Taxable</th>
                            <th class="min-w-80px text-center">Rate %</th>
                            <th class="min-w-100px text-end d-none d-md-table-cell">CGST</th>
                            <th class="min-w-100px text-end d-none d-md-table-cell">SGST</th>
                            <th class="min-w-100px text-end d-none d-md-table-cell">IGST</th>
                            <th class="min-w-80px text-center d-none d-lg-table-cell">POS</th>
                            <th class="min-w-100px d-none d-lg-table-cell">Origin</th>
                            <th class="min-w-100px text-end">Total</th>
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
                                    <div class="text-gray-500 fs-8 d-block d-md-none">{{ $inv->invoice_date?->format('d M Y') }}</div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="text-gray-800">{{ $inv->invoice_date?->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="text-gray-800 fw-semibold">{{ $inv->customer_name ?? '-' }}</div>
                                    <div class="text-gray-500 fs-8 d-block d-lg-none">{{ $inv->customer_gstin ?? 'No GSTIN' }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="text-gray-600 fs-7">{{ $inv->customer_gstin ?? '-' }}</div>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <div class="text-gray-600">{{ $inv->hsn ?? '-' }}</div>
                                </td>
                                <td>
                                    @php
                                        $typeClass = match($inv->invoice_type) {
                                            'b2b' => 'badge-light-primary',
                                            'b2c' => 'badge-light-success', 
                                            'export' => 'badge-light-warning',
                                            'sez' => 'badge-light-info',
                                            'exempted' => 'badge-light-secondary',
                                            default => 'badge-light-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $typeClass }}">{{ strtoupper($inv->invoice_type ?? 'B2B') }}</span>
                                    <div class="text-gray-500 fs-8 d-block d-lg-none">{{ strtoupper($inv->supply_type ?? 'INTRA') }}</div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @php
                                        $supplyClass = $inv->supply_type === 'inter' ? 'badge-light-warning' : 'badge-light-success';
                                    @endphp
                                    <span class="badge {{ $supplyClass }}">{{ strtoupper($inv->supply_type ?? 'INTRA') }}</span>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    <div class="text-gray-800">{{ $inv->qty ?? 1 }}</div>
                                    @if($inv->uom)
                                        <div class="text-gray-500 fs-8">{{ $inv->uom }}</div>
                                    @endif
                                </td>
                                <td class="text-end d-none d-lg-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->unit_price ?? 0, 2) }}</div>
                                    @if($inv->tax_inclusive)
                                        <div class="text-gray-500 fs-8">Tax Incl.</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold">₹{{ number_format($inv->taxable_value, 2) }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light-primary">{{ $inv->tax_rate }}%</span>
                                </td>
                                <td class="text-end d-none d-md-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->cgst_amount, 2) }}</div>
                                    @if($inv->cgst_rate > 0)
                                        <div class="text-gray-500 fs-8">{{ $inv->cgst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-end d-none d-md-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->sgst_amount, 2) }}</div>
                                    @if($inv->sgst_rate > 0)
                                        <div class="text-gray-500 fs-8">{{ $inv->sgst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-end d-none d-md-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->igst_amount, 2) }}</div>
                                    @if($inv->igst_rate > 0)
                                        <div class="text-gray-500 fs-8">{{ $inv->igst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-center d-none d-lg-table-cell">
                                    <span class="badge badge-light-info">{{ $inv->place_of_supply ?? '-' }}</span>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="text-gray-800">{{ $inv->origin_state ?? '-' }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold">₹{{ number_format($inv->total_invoice_value ?? 0, 2) }}</div>
                                    @if($inv->round_off != 0)
                                        <div class="text-gray-500 fs-8">Round: ₹{{ number_format($inv->round_off, 2) }}</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="{{ route('sales.invoices.show', $inv) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View Invoice">
                                            <i class="ki-duotone ki-eye fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </a>
                                        <a href="{{ route('sales.invoices.edit', $inv) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Invoice">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <form method="POST" action="{{ route('sales.invoices.destroy', $inv) }}" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Invoice">
                                                <i class="ki-duotone ki-trash fs-3">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                </i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="19" class="text-center py-10">
                                    <div class="text-gray-400 fs-6">
                                        <i class="ki-duotone ki-files fs-3x mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <br>
                                        No invoices found. 
                                        <a href="{{ route('sales.invoices.create') }}" class="fw-bold text-primary">Create your first invoice</a>
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
                        Import Invoices (JSON)
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
                            <div class="form-text">Upload a JSON file containing invoice data.</div>
                        </div>

                        <div class="mb-7">
                            <label class="form-label fw-bold required">Invoice Type</label>
                            <select name="type" id="type" class="form-select form-select-solid" required>
                                <option value="">-- Select Type --</option>
                                <option value="sales">Sales</option>
                                <option value="purchase">Purchase</option>
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
                statusDiv.innerHTML = `<i class="ki-duotone ki-check-circle fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i><strong>Success:</strong> Imported ${data.imported} invoices.`;
                
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

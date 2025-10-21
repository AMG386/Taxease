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

            <!-- Summary Cards -->
            <div class="row g-6 g-xl-9 mb-6">
                <div class="col-md-6 col-lg-3">
                    <div class="card card-flush">
                        <div class="card-body">
                            <div class="fw-bold text-gray-400 mb-3">Total Invoices</div>
                            <div class="fs-2hx fw-bold text-gray-800">{{ $invoices->total() ?? 0 }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-flush">
                        <div class="card-body">
                            <div class="fw-bold text-gray-400 mb-3">Total Taxable</div>
                            <div class="fs-2hx fw-bold text-primary">₹{{ number_format($invoices->sum('taxable_value') ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-flush">
                        <div class="card-body">
                            <div class="fw-bold text-gray-400 mb-3">Total Tax</div>
                            <div class="fs-2hx fw-bold text-warning">₹{{ number_format($invoices->sum('tax_amount') ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-flush">
                        <div class="card-body">
                            <div class="fw-bold text-gray-400 mb-3">Total Invoice Value</div>
                            <div class="fs-2hx fw-bold text-success">₹{{ number_format($invoices->sum('total_invoice_value') ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filter Section -->
            <div class="card card-flush mb-6">
                <div class="card-header pt-7">
                    <h3 class="card-title">
                        <span class="card-label fw-bold text-gray-800">Advanced Filters</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Toggle Filters
                        </button>
                    </div>
                </div>
                <div class="collapse" id="filterCollapse">
                    <div class="card-body border-top">
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
                                <label class="form-label fw-semibold">Vendor Type</label>
                                <select name="vendor_type" class="form-select form-select-solid">
                                    <option value="">All Types</option>
                                    <option value="registered" {{ request('vendor_type') == 'registered' ? 'selected' : '' }}>Registered</option>
                                    <option value="unregistered" {{ request('vendor_type') == 'unregistered' ? 'selected' : '' }}>Unregistered</option>
                                    <option value="sez" {{ request('vendor_type') == 'sez' ? 'selected' : '' }}>SEZ</option>
                                    <option value="import" {{ request('vendor_type') == 'import' ? 'selected' : '' }}>Import</option>
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
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Search Vendor</label>
                                <input type="text" name="vendor_search" class="form-control form-control-solid" placeholder="Search vendor name or GSTIN" value="{{ request('vendor_search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Min Amount</label>
                                <input type="number" step="0.01" name="min_amount" class="form-control form-control-solid" placeholder="0.00" value="{{ request('min_amount') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Max Amount</label>
                                <input type="number" step="0.01" name="max_amount" class="form-control form-control-solid" placeholder="999999.99" value="{{ request('max_amount') }}">
                            </div>
                            <div class="col-md-3 align-self-end">
                                <button class="btn btn-primary me-2">
                                    <i class="ki-duotone ki-funnel fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Apply Filters
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
            </div>

            <!-- Purchase Invoices Table -->
            <div class="table-responsive">
                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4" id="kt_table_purchase_invoices">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-50px">#</th>
                            <th class="min-w-120px">Invoice Details</th>
                            <th class="min-w-150px">Vendor Info</th>
                            <th class="min-w-100px d-none d-lg-table-cell">Vendor Type</th>
                            <th class="min-w-100px d-none d-xl-table-cell">HSN</th>
                            <th class="min-w-80px d-none d-xl-table-cell">Qty/UOM</th>
                            <th class="min-w-100px text-end">Taxable</th>
                            <th class="min-w-80px text-center d-none d-lg-table-cell">Tax Rate</th>
                            <th class="min-w-100px text-end d-none d-xl-table-cell">CGST</th>
                            <th class="min-w-100px text-end d-none d-xl-table-cell">SGST</th>
                            <th class="min-w-100px text-end d-none d-xl-table-cell">IGST</th>
                            <th class="min-w-100px text-end">Total Tax</th>
                            <th class="min-w-120px text-end">Invoice Value</th>
                            <th class="min-w-100px d-none d-lg-table-cell">Supply Info</th>
                            <th class="min-w-80px d-none d-xl-table-cell">Rev. Charge</th>
                            <th class="min-w-80px d-none d-xl-table-cell">ITC</th>
                            <th class="min-w-80px d-none d-xl-table-cell">Bill Entry</th>
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
                                    <div class="d-flex flex-column">
                                        <div class="text-gray-800 fw-bold">{{ $inv->invoice_no }}</div>
                                        <div class="text-muted fs-7">{{ $inv->invoice_date?->format('d M Y') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <div class="text-gray-800 fw-semibold">{{ $inv->supplier_name ?? 'N/A' }}</div>
                                        @if($inv->supplier_gstin)
                                            <div class="text-muted fs-7">{{ $inv->supplier_gstin }}</div>
                                        @else
                                            <div class="text-muted fs-7">No GSTIN</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @if($inv->vendor_type == 'registered')
                                        <span class="badge badge-light-success">Registered</span>
                                    @elseif($inv->vendor_type == 'unregistered')
                                        <span class="badge badge-light-warning">Unregistered</span>
                                    @elseif($inv->vendor_type == 'sez')
                                        <span class="badge badge-light-info">SEZ</span>
                                    @elseif($inv->vendor_type == 'import')
                                        <span class="badge badge-light-primary">Import</span>
                                    @else
                                        <span class="badge badge-light-secondary">Unknown</span>
                                    @endif
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    <div class="text-gray-800">{{ $inv->hsn ?? 'N/A' }}</div>
                                </td>
                                <td class="d-none d-xl-table-cell">
                                    @if($inv->qty && $inv->uom)
                                        <div class="text-gray-800">{{ $inv->qty }} {{ $inv->uom }}</div>
                                    @elseif($inv->qty)
                                        <div class="text-gray-800">{{ $inv->qty }}</div>
                                    @else
                                        <div class="text-muted">N/A</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="text-gray-800 fw-bold">₹{{ number_format($inv->taxable_value, 2) }}</div>
                                    @if($inv->unit_price)
                                        <div class="text-muted fs-7">@ ₹{{ number_format($inv->unit_price, 2) }}</div>
                                    @endif
                                </td>
                                <td class="text-center d-none d-lg-table-cell">
                                    <span class="badge badge-light-primary">{{ $inv->tax_rate }}%</span>
                                </td>
                                <td class="text-end d-none d-xl-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->cgst_amount ?? 0, 2) }}</div>
                                    @if($inv->cgst_rate)
                                        <div class="text-muted fs-7">{{ $inv->cgst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-end d-none d-xl-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->sgst_amount ?? 0, 2) }}</div>
                                    @if($inv->sgst_rate)
                                        <div class="text-muted fs-7">{{ $inv->sgst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-end d-none d-xl-table-cell">
                                    <div class="text-gray-800">₹{{ number_format($inv->igst_amount ?? 0, 2) }}</div>
                                    @if($inv->igst_rate)
                                        <div class="text-muted fs-7">{{ $inv->igst_rate }}%</div>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="text-warning fw-bold">₹{{ number_format($inv->tax_amount ?? 0, 2) }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="text-success fw-bold">₹{{ number_format($inv->total_invoice_value ?? 0, 2) }}</div>
                                    @if($inv->round_off && $inv->round_off != 0)
                                        <div class="text-muted fs-7">Round: ₹{{ number_format($inv->round_off, 2) }}</div>
                                    @endif
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <div class="d-flex flex-column">
                                        @if($inv->supply_type == 'intra')
                                            <span class="badge badge-light-success mb-1">Intra-State</span>
                                        @elseif($inv->supply_type == 'inter')
                                            <span class="badge badge-light-primary mb-1">Inter-State</span>
                                        @endif
                                        @if($inv->place_of_supply)
                                            <div class="text-muted fs-7">{{ $inv->place_of_supply }}</div>
                                        @endif
                                        @if($inv->origin_state && $inv->origin_state != $inv->place_of_supply)
                                            <div class="text-muted fs-7">from {{ $inv->origin_state }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center d-none d-xl-table-cell">
                                    @if($inv->reverse_charge)
                                        <span class="badge badge-light-danger">Yes</span>
                                    @else
                                        <span class="badge badge-light-success">No</span>
                                    @endif
                                </td>
                                <td class="text-center d-none d-xl-table-cell">
                                    @if($inv->itc_eligibility == 'eligible')
                                        <span class="badge badge-light-success">Eligible</span>
                                    @elseif($inv->itc_eligibility == 'ineligible')
                                        <span class="badge badge-light-danger">Ineligible</span>
                                    @elseif($inv->itc_eligibility == 'blocked')
                                        <span class="badge badge-light-warning">Blocked</span>
                                    @else
                                        <span class="badge badge-light-secondary">N/A</span>
                                    @endif
                                    @if($inv->itc_type)
                                        <div class="text-muted fs-7">{{ ucfirst(str_replace('_', ' ', $inv->itc_type)) }}</div>
                                    @endif
                                </td>
                                <td class="text-center d-none d-xl-table-cell">
                                    @if($inv->boe_no)
                                        <div class="text-gray-800 fw-semibold">{{ $inv->boe_no }}</div>
                                        @if($inv->boe_date)
                                            <div class="text-muted fs-7">{{ $inv->boe_date->format('d M Y') }}</div>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end flex-shrink-0">
                                        <a href="{{ route('purchases.invoices.show', $inv) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="View Invoice">
                                            <i class="ki-duotone ki-eye fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </a>
                                        <a href="{{ route('purchases.invoices.edit', $inv) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Edit Invoice">
                                            <i class="ki-duotone ki-pencil fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <form method="POST" action="{{ route('purchases.invoices.destroy', $inv) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Delete Invoice" 
                                                   onclick="return confirm('Are you sure you want to delete this invoice?')">
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
                                <td colspan="18" class="text-center py-10">
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

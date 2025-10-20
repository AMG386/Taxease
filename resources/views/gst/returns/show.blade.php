@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            GST {{ strtoupper($gstReturn->type) }}
            • {{ optional($gstReturn->period_from)->format('M Y') ?? '—' }}
        </h3>

        <div class="d-flex gap-2">
            @if($gstReturn->status === 'draft')
                <form method="POST" action="{{ route('gst.returns.prepare', $gstReturn->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-primary">Prepare from Transactions</button>
                </form>
            @endif

            @if($gstReturn->status === 'prepared')
                <form method="POST" action="{{ route('gst.returns.file', $gstReturn->id) }}" class="d-inline">
                    @csrf
                    <button class="btn btn-success">Mark as Filed</button>
                </form>
            @endif

            <a class="btn btn-outline-secondary" href="{{ route('gst.returns.export', $gstReturn->id) }}">Export JSON</a>
            <a class="btn btn-light" href="{{ route('gst.returns.index') }}">Back</a>
        </div>
    </div>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card p-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-info text-uppercase">{{ $gstReturn->status }}</span>
                </div>

                <div class="mt-2">
                    <small class="text-muted d-block">Period</small>
                    <div class="fw-semibold">
                        {{ optional($gstReturn->period_from)->toFormattedDateString() ?? '—' }} →
                        {{ optional($gstReturn->period_to)->toFormattedDateString() ?? '—' }}
                    </div>
                </div>

                <div class="mt-2">
                    <small class="text-muted d-block">Prepared</small>
                    <div>{{ optional($gstReturn->prepared_on)->toDayDateTimeString() ?? '—' }}</div>
                </div>

                <div class="mt-2">
                    <small class="text-muted d-block">Filed</small>
                    <div>{{ optional($gstReturn->filed_on)->toDayDateTimeString() ?? '—' }}</div>
                </div>

                <hr>

                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('gst.returns.audit', $gstReturn->id) }}" enctype="multipart/form-data">
                        @csrf
                        <label class="form-label">Upload Audit / Working</label>
                        <input type="file" name="file" class="form-control" required>
                        <input type="text" name="remarks" class="form-control mt-2" placeholder="Remarks (optional)">
                        <button class="btn btn-outline-primary mt-2">Upload</button>
                    </form>
                </div>
            </div>

            <div class="card p-3 mt-3">
                <h6 class="mb-2">Totals</h6>
                <div class="d-flex justify-content-between">
                    <span>Taxable</span>
                    <span class="fw-semibold">{{ number_format((float)($gstReturn->taxable_value ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>CGST</span>
                    <span>{{ number_format((float)($gstReturn->cgst ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>SGST</span>
                    <span>{{ number_format((float)($gstReturn->sgst ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>IGST</span>
                    <span>{{ number_format((float)($gstReturn->igst ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>CESS</span>
                    <span>{{ number_format((float)($gstReturn->cess ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>ITC Eligible</span>
                    <span class="text-success">{{ number_format((float)($gstReturn->itc_eligible ?? 0), 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Net Payable</span>
                    <span class="fw-bold">{{ number_format((float)($gstReturn->net_payable ?? 0), 2) }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Section</th>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Party</th>
                                <th>GSTIN</th>
                                <th>HSN</th>
                                <th class="text-end">Taxable</th>
                                <th class="text-end">CGST</th>
                                <th class="text-end">SGST</th>
                                <th class="text-end">IGST</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $i)
                                <tr>
                                    <td>{{ $i->section }}</td>
                                    <td>{{ $i->invoice_no }}</td>
                                    <td>{{ optional($i->invoice_date)->format('d M Y') ?? '—' }}</td>
                                    <td>{{ $i->party_name }}</td>
                                    <td>{{ $i->counterparty_gstin }}</td>
                                    <td>{{ $i->hsn }}</td>
                                    <td class="text-end">{{ number_format((float)($i->taxable_value ?? 0), 2) }}</td>
                                    <td class="text-end">{{ number_format((float)($i->cgst ?? 0), 2) }}</td>
                                    <td class="text-end">{{ number_format((float)($i->sgst ?? 0), 2) }}</td>
                                    <td class="text-end">{{ number_format((float)($i->igst ?? 0), 2) }}</td>
                                    <td class="text-end">{{ number_format((float)($i->total ?? 0), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        No items. Click <strong>Prepare</strong> to auto-import.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    @if($items instanceof \Illuminate\Pagination\AbstractPaginator)
                        {{ $items->onEachSide(1)->links() }}
                    @elseif(method_exists($items, 'links'))
                        {{-- In case a custom paginator is returned --}}
                        {{ $items->links() }}
                    @else
                        {{-- Non-paginated collection: show a compact count --}}
                        <div class="text-muted small">
                            Showing {{ is_countable($items) ? count($items) : 0 }} item(s).
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- OLD (crashes when audits is null)
@if($gstReturn->audits->count())
--}}
@if(($gstReturn->audits_count ?? 0) > 0)
    <hr>
    <h6 class="mb-2">Attached Audit / Working</h6>
    <ul class="list-group">
        @foreach($audits as $a)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="me-3">
                    <div class="fw-semibold">
                        <a href="{{ route('gst.returns.audit.download', $a->id) }}">
                            {{ $a->original_name }}
                        </a>
                    </div>
                    <div class="small text-muted">
                        {{ number_format($a->size / 1024, 1) }} KB •
                        {{ $a->mime ?? 'file' }} •
                        Uploaded {{ $a->created_at->format('d M Y, h:i A') }}
                        @if($a->remarks)
                            • Remarks: {{ $a->remarks }}
                        @endif
                        @if($a->uploader)
                            • by {{ $a->uploader->name }}
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('gst.returns.audit.delete', $a->id) }}" onsubmit="return confirm('Delete this file?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endif

@endsection

@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Composition Dashboard (CMP-08 / GSTR-4)</h3>

    @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

    <div class="card p-3">
        <form method="post" action="{{ route('gst.cmp.update', $rec->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Period From</label>
                    <input type="date" class="form-control" value="{{ $rec->period_from->toDateString() }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Period To</label>
                    <input type="date" class="form-control" value="{{ $rec->period_to->toDateString() }}" disabled>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Total Turnover</label>
                    <input type="number" step="0.01" name="total_turnover" class="form-control" value="{{ $rec->total_turnover }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tax %</label>
                    <input type="number" step="0.001" name="tax_rate" class="form-control" value="{{ $rec->tax_rate }}">
                </div>
            </div>
            <div class="mt-3">
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Calculated Tax</span>
                    <span class="fw-bold">{{ number_format($rec->tax_amount,2) }}</span>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">Save</button>
                <a href="{{ route('gst.returns.index') }}" class="btn btn-light">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

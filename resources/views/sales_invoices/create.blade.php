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

    <form method="POST" action="{{ route('sales.invoices.store') }}">
      @csrf
      <div class="row g-3">
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

        <div class="col-md-6">
          <label class="form-label">Customer Name</label>
          <input name="customer_name" class="form-control" value="{{ old('customer_name') }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Customer GSTIN</label>
          <input name="customer_gstin" class="form-control" value="{{ old('customer_gstin') }}">
        </div>

        <div class="col-md-3">
          <label class="form-label">Qty</label>
          <input type="number" name="qty" class="form-control" min="1" required value="{{ old('qty', 1) }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">UOM</label>
          <input name="uom" class="form-control" value="{{ old('uom') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">Taxable Value (₹)</label>
          <input type="number" step="0.01" name="taxable_value" class="form-control" required value="{{ old('taxable_value') }}">
        </div>
        <div class="col-md-3">
          <label class="form-label">GST Rate (%)</label>
          <input type="number" step="0.01" name="tax_rate" class="form-control" required value="{{ old('tax_rate') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Place of Supply (State)</label>
          <input name="place_of_supply" class="form-control" value="{{ old('place_of_supply') }}" placeholder="e.g., Kerala">
        </div>
        <div class="col-md-6">
          <label class="form-label">Origin State</label>
          <input name="origin_state" class="form-control" required value="{{ old('origin_state') }}" placeholder="e.g., Kerala">
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('sales.invoices.index') }}" class="btn btn-light">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

{{-- resources/views/gst/settings.blade.php --}}
@extends('layouts.app')
@section('title','GST Settings')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">GST Settings</h3>
    @if($profile->exists)
      <div class="card-toolbar">
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
          <i class="fas fa-trash"></i> Delete Profile
        </button>
      </div>
    @endif
  </div>
  <div class="card-body">
    @if(session('ok'))
      <div class="alert alert-success alert-dismissible fade show">
        {{ session('ok') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form method="post" action="{{ route('gst.settings.update') }}" class="row g-3">
      @csrf

      {{-- Firm Details --}}
      <div class="col-12"><h5 class="mb-0">Firm Details</h5><hr class="mt-2"></div>

      <div class="col-md-6">
        <label class="form-label">Firm / Legal Name</label>
        <input name="firm_name" class="form-control"
               value="{{ old('firm_name', $profile->firm_name ?? '') }}" placeholder="e.g. FFT360 Health Club">
      </div>

      <div class="col-md-6">
        <label class="form-label">Trade Name (optional)</label>
        <input name="trade_name" class="form-control"
               value="{{ old('trade_name', $profile->trade_name ?? '') }}" placeholder="If different from legal name">
      </div>

      <div class="col-md-8">
        <label class="form-label">Address</label>
        <input name="address_line1" class="form-control mb-2"
               value="{{ old('address_line1', $profile->address_line1 ?? '') }}" placeholder="Building / Street / Area">
        <input name="address_line2" class="form-control"
               value="{{ old('address_line2', $profile->address_line2 ?? '') }}" placeholder="Locality / Landmark (optional)">
      </div>

      <div class="col-md-4">
        <label class="form-label">Pincode</label>
        <input name="pincode" class="form-control" maxlength="6"
               value="{{ old('pincode', $profile->pincode ?? '') }}" placeholder="e.g. 695582">
      </div>

      <div class="col-md-4">
        <label class="form-label">State</label>
        <input name="state" class="form-control"
               value="{{ old('state', $profile->state ?? '') }}" placeholder="e.g. Kerala">
      </div>

      <div class="col-md-4">
        <label class="form-label">City / District</label>
        <input name="city" class="form-control"
               value="{{ old('city', $profile->city ?? '') }}" placeholder="e.g. Thiruvananthapuram">
      </div>

      <div class="col-md-4">
        <label class="form-label">GSTIN (GST Number)</label>
        <input name="gstin" class="form-control"
               value="{{ old('gstin', $profile->gstin ?? '') }}" placeholder="15-digit GSTIN">
      </div>

      {{-- GST Profile --}}
      <div class="col-12"><h5 class="mb-0 mt-2">GST Profile</h5><hr class="mt-2"></div>

      <div class="col-md-4">
        <label class="form-label">GST Type</label>
        <select name="gst_type" class="form-select">
          @php $gt = $profile->gst_type ?? 'regular'; @endphp
          <option value="regular" {{ $gt=='regular'?'selected':'' }}>Regular (GSTR-1 / 3B)</option>
          <option value="composition" {{ $gt=='composition'?'selected':'' }}>Composition (CMP-08 / GSTR-4)</option>
        </select>
        <small class="text-muted">Regular: CGST/SGST/IGST with ITC. Composition: simplified taxes, no ITC.</small>
      </div>

      <div class="col-md-4">
        <label class="form-label">Business Type</label>
        @php $bt = $profile->business_type ?? ''; @endphp
        <select name="business_type" class="form-select">
          <option value="">—</option>
          <option value="manufacturer" {{ $bt=='manufacturer'?'selected':'' }}>Manufacturer</option>
          <option value="trader" {{ $bt=='trader'?'selected':'' }}>Trader</option>
          <option value="restaurant" {{ $bt=='restaurant'?'selected':'' }}>Restaurant</option>
          <option value="service" {{ $bt=='service'?'selected':'' }}>Service Provider</option>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Filing Frequency</label>
        @php $ff = $profile->filing_frequency ?? ''; @endphp
        <select name="filing_frequency" class="form-select">
          <option value="">—</option>
          {{-- Regular taxpayers --}}
          <option value="monthly" {{ $ff=='monthly'?'selected':'' }}>Monthly (GSTR-1/3B)</option>
          <option value="qrmp" {{ $ff=='qrmp'?'selected':'' }}>Quarterly (QRMP: GSTR-1 quarterly, 3B monthly payment)</option>
          {{-- Composition taxpayers --}}
          <option value="cmp_quarterly" {{ $ff=='cmp_quarterly'?'selected':'' }}>Composition Quarterly (CMP-08)</option>
          <option value="cmp_annual" {{ $ff=='cmp_annual'?'selected':'' }}>Composition Annual (GSTR-4)</option>
        </select>
      </div>

      {{-- Rates --}}
      <div class="col-md-3">
        <label class="form-label">Default GST Rate (%)</label>
        <input name="default_gst_rate" type="number" step="0.01" class="form-control"
               value="{{ old('default_gst_rate', $profile->default_gst_rate ?? '') }}" placeholder="e.g. 18.00">
        <small class="text-muted">Used as default on invoices (can be overridden per item).</small>
      </div>

      <div class="col-md-3">
        <label class="form-label">Composition Rate (%)</label>
        <input name="composition_rate" type="number" step="0.01" class="form-control"
               value="{{ old('composition_rate', $profile->composition_rate ?? '') }}" placeholder="e.g. 1.00">
        <small class="text-muted">Applicable if GST Type is Composition.</small>
      </div>

      <div class="col-md-2 align-self-end">
        <button class="btn btn-primary w-100">Save Settings</button>
      </div>
    </form>
  </div>
</div>

{{-- Delete Confirmation Modal --}}
@if($profile->exists)
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete GST Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <i class="fas fa-exclamation-triangle"></i>
          <strong>Warning!</strong> This action cannot be undone.
        </div>
        <p>Are you sure you want to delete your GST profile? This will remove:</p>
        <ul>
          <li>All firm details and address information</li>
          <li>GST registration and filing preferences</li>
          <li>Tax rate configurations</li>
        </ul>
        <p class="text-muted">You can recreate your profile anytime by filling out the form again.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="post" action="{{ route('gst.settings.destroy') }}" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Yes, Delete Profile</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

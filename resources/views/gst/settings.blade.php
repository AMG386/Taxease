{{-- resources/views/gst/settings.blade.php --}}
@extends('layouts.app')
@section('title','GST Settings')
@section('content')
<div class="card">
  <div class="card-header"><h3 class="card-title">GST Settings</h3></div>
  <div class="card-body">
    @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
    <form method="post" action="{{ route('gst.settings.update') }}" class="row g-3">
      @csrf
      <div class="col-md-4">
        <label class="form-label">GSTIN</label>
        <input name="gstin" class="form-control" value="{{ old('gstin', $profile->gstin) }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">GST Type</label>
        <select name="gst_type" class="form-select">
          <option value="regular" {{ $profile->gst_type=='regular'?'selected':'' }}>Regular (GSTR-3B)</option>
          <option value="composition" {{ $profile->gst_type=='composition'?'selected':'' }}>Composition (GSTR-4)</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Business Type</label>
        <select name="business_type" class="form-select">
          @php $bt = $profile->business_type; @endphp
          <option value="">—</option>
          <option value="manufacturer" {{ $bt=='manufacturer'?'selected':'' }}>Manufacturer</option>
          <option value="trader" {{ $bt=='trader'?'selected':'' }}>Trader</option>
          <option value="restaurant" {{ $bt=='restaurant'?'selected':'' }}>Restaurant</option>
          <option value="service" {{ $bt=='service'?'selected':'' }}>Service Provider</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Composition Rate (%)</label>
        <input name="composition_rate" type="number" step="0.01" class="form-control"
               value="{{ old('composition_rate', $profile->composition_rate ?? '') }}" placeholder="e.g. 1.00">
      </div>
      <div class="col-md-2 align-self-end">
        <button class="btn btn-primary w-100">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection

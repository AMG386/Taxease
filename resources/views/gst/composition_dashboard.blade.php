{{-- resources/views/gst/composition_dashboard.blade.php --}}
@extends('layouts.app')
@section('title','GSTR-4 (Composition)')
@section('content')
<div class="card">
  <div class="card-header d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <h3 class="card-title">GSTR-4 — Composition Summary</h3>
    <form class="d-flex gap-2" method="get" action="{{ route('gst.composition.dashboard') }}">
      <select name="fy" class="form-select" style="width:auto">
        @php $curr = (int)date('Y'); $fys = []; for($y=$curr-1;$y<=$curr+1;$y++) $fys[]=$y.'-'.substr($y+1,2,2); @endphp
        @foreach($fys as $f) <option value="{{ $f }}" {{ $fy==$f?'selected':'' }}>{{ $f }}</option> @endforeach
      </select>
      <select name="q" class="form-select" style="width:auto">
        @foreach(['Q1','Q2','Q3','Q4'] as $qq) <option {{ $q==$qq?'selected':'' }}>{{ $qq }}</option> @endforeach
      </select>
      <button class="btn btn-primary">Load</button>
      <a class="btn btn-light" href="{{ route('gst.composition.export.json',['fy'=>$fy,'q'=>$q]) }}">Export JSON</a>
    </form>
  </div>
  <div class="card-body">
    @if(!empty($data['error']))
      <div class="alert alert-warning mb-0">{{ $data['error'] }}</div>
    @else
      <div class="row g-6">
        <div class="col-md-3"><div class="border rounded p-4">
          <div class="text-muted">FY / Quarter</div>
          <div class="fs-2 fw-bolder">{{ $data['fy'] }} / {{ $data['quarter'] }}</div>
        </div></div>
        <div class="col-md-3"><div class="border rounded p-4">
          <div class="text-muted">Turnover</div>
          <div class="fs-2 fw-bolder">₹{{ number_format($data['turnover'],2) }}</div>
        </div></div>
        <div class="col-md-3"><div class="border rounded p-4">
          <div class="text-muted">Composition Rate</div>
          <div class="fs-2 fw-bolder">{{ number_format($data['rate'],2) }}%</div>
        </div></div>
        <div class="col-md-3"><div class="border rounded p-4">
          <div class="text-muted">Tax Payable</div>
          <div class="fs-2 fw-bolder">₹{{ number_format($data['tax'],2) }}</div>
        </div></div>
      </div>
    @endif
  </div>
</div>
@endsection

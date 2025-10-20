@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Create GST Return</h3>

    <form method="post" action="{{ route('gst.returns.store') }}" class="card p-3">
        @csrf
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    @foreach(['gstr1'=>'GSTR-1','gstr3b'=>'GSTR-3B','gstr4'=>'GSTR-4','gstr9'=>'GSTR-9','gstr9a'=>'GSTR-9A','gstr9c'=>'GSTR-9C','cmp08'=>'CMP-08'] as $k=>$v)
                        <option value="{{ $k }}" @selected(($defaults['type']??'')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Period From</label>
                <input type="date" name="period_from" value="{{ $defaults['period_from'] ?? '' }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Period To</label>
                <input type="date" name="period_to" value="{{ $defaults['period_to'] ?? '' }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Frequency (Meta)</label>
                <select name="meta[frequency]" class="form-select">
                    <option value="">—</option>
                    <option value="monthly" @selected(($defaults['frequency']??'')==='monthly')>Monthly</option>
                    <option value="quarterly" @selected(($defaults['frequency']??'')==='quarterly')>Quarterly</option>
                    <option value="annual" @selected(($defaults['frequency']??'')==='annual')>Annual</option>
                </select>
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-success">Create</button>
            <a href="{{ route('gst.returns.index') }}" class="btn btn-light">Cancel</a>
        </div>
    </form>
</div>
@endsection

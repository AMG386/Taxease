@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h3 class="mb-3">Edit GST Return</h3>

    <form method="post" action="{{ route('gst.returns.update',$gstReturn->id) }}" class="card p-3">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['draft','prepared','filed','rejected','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($gstReturn->status===$s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Notes (Meta)</label>
                <input type="text" name="meta[notes]" value="{{ $gstReturn->meta['notes'] ?? '' }}" class="form-control" placeholder="Optional notes">
            </div>
        </div>
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('gst.returns.show',$gstReturn->id) }}" class="btn btn-light">Back</a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">Edit &amp; Resubmit Expense Claim</h4>

        @if($claim->manager_comment)
            <div class="alert alert-warning">Manager's note: {{ $claim->manager_comment }}</div>
        @endif

        <form method="POST" action="{{ route('claims.update', $claim) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $claim->title) }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Travel From</label>
                    <input type="text" name="travel_from" class="form-control" value="{{ old('travel_from', $claim->travel_from) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Travel To</label>
                    <input type="text" name="travel_to" class="form-control" value="{{ old('travel_to', $claim->travel_to) }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Travel Date</label>
                    <input type="date" name="travel_date" class="form-control" value="{{ old('travel_date', $claim->travel_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $claim->amount) }}" onwheel="this.blur()" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $claim->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Bill Upload (jpg, png, pdf - max 2MB)</label>
                <input type="file" name="bill" class="form-control">
                @if($claim->bill_path)
                    <small class="text-muted">Current file: <a href="{{ asset('storage/'.$claim->bill_path) }}" target="_blank">view</a>. Leave empty to keep it.</small>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Resubmit Claim</button>
            <a href="{{ route('claims.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
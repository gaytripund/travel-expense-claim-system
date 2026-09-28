@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">{{ $claim->title }}</h4>

        <table class="table table-borderless">
            <tr>
                <th style="width: 200px;">Employee</th>
                <td>{{ $claim->user->name }}</td>
            </tr>
            <tr>
                <th>Travel From</th>
                <td>{{ $claim->travel_from }}</td>
            </tr>
            <tr>
                <th>Travel To</th>
                <td>{{ $claim->travel_to }}</td>
            </tr>
            <tr>
                <th>Travel Date</th>
                <td>{{ $claim->travel_date->format('Y-m-d') }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{ number_format($claim->amount, 2) }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $claim->description ?: '-' }}</td>
            </tr>
            <tr>
                <th>Bill</th>
                <td>
                    @if($claim->bill_path)
                        <a href="{{ asset('storage/'.$claim->bill_path) }}" target="_blank">View Uploaded Bill</a>
                    @else
                        No file uploaded
                    @endif
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($claim->status) }}</td>
            </tr>
            @if($claim->manager_comment)
                <tr>
                    <th>Manager Comment</th>
                    <td>{{ $claim->manager_comment }}</td>
                </tr>
            @endif
        </table>

        @if(!auth()->user()->isManager() && $claim->user_id === auth()->id() && $claim->status === 'returned')
            <a href="{{ route('claims.edit', $claim) }}" class="btn btn-primary">Edit &amp; Resubmit</a>
        @endif

        @if(auth()->user()->isManager() && $claim->status === 'pending')
            <hr>
            <div class="d-flex gap-2 mb-3">
                <form method="POST" action="{{ route('claims.approve', $claim) }}">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>

            <form method="POST" action="{{ route('claims.reject', $claim) }}" class="mb-2">
                @csrf
                @method('PUT')
                <div class="input-group">
                    <input type="text" name="manager_comment" class="form-control" placeholder="Reason for rejection" required>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>

            <form method="POST" action="{{ route('claims.return', $claim) }}">
                @csrf
                @method('PUT')
                <div class="input-group">
                    <input type="text" name="manager_comment" class="form-control" placeholder="What needs correction?" required>
                    <button type="submit" class="btn btn-warning">Send Back for Correction</button>
                </div>
            </form>
        @endif

        <a href="{{ route('claims.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</div>
@endsection

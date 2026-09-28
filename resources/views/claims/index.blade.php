@extends('layouts.app')

@section('content')
<h4 class="mb-3">
    @if(auth()->user()->isManager())
        All Expense Claims
    @else
        My Expense Claims
    @endif
</h4>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Title</th>
            @if(auth()->user()->isManager())
                <th>Employee</th>
            @endif
            <th>From</th>
            <th>To</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($claims as $claim)
            <tr>
                <td>{{ $claim->title }}</td>
                @if(auth()->user()->isManager())
                    <td>{{ $claim->user->name }}</td>
                @endif
                <td>{{ $claim->travel_from }}</td>
                <td>{{ $claim->travel_to }}</td>
                <td>{{ $claim->travel_date->format('Y-m-d') }}</td>
                <td>{{ number_format($claim->amount, 2) }}</td>
                <td>
                    <span class="badge bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : ($claim->status === 'returned' ? 'warning' : 'secondary')) }}">
                        {{ ucfirst($claim->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('claims.show', $claim) }}" class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No expense claims found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
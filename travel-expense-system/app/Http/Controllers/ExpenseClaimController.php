<?php

namespace App\Http\Controllers;

use App\Models\ExpenseClaim;
use Illuminate\Http\Request;

class ExpenseClaimController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isManager()) {
            $claims = ExpenseClaim::with('user')->latest()->get();
        } else {
            $claims = $user->expenseClaims()->latest()->get();
        }

        return view('claims.index', compact('claims'));
    }

    public function create()
    {
        return view('claims.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateClaim($request);

        if ($request->hasFile('bill')) {
            $data['bill_path'] = $request->file('bill')->store('bills', 'public');
        }

        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';

        ExpenseClaim::create($data);

        return redirect('/claims')->with('status', 'Expense claim submitted successfully.');
    }

    public function show(Request $request, ExpenseClaim $claim)
    {
        $this->authorizeAccess($request, $claim);

        return view('claims.show', compact('claim'));
    }

    public function edit(Request $request, ExpenseClaim $claim)
    {
        $this->authorizeAccess($request, $claim);

        if ($claim->user_id !== $request->user()->id || $claim->status !== 'returned') {
            abort(403, 'This claim cannot be edited.');
        }

        return view('claims.edit', compact('claim'));
    }

    public function update(Request $request, ExpenseClaim $claim)
    {
        if ($claim->user_id !== $request->user()->id || $claim->status !== 'returned') {
            abort(403, 'This claim cannot be edited.');
        }

        $data = $this->validateClaim($request);

        if ($request->hasFile('bill')) {
            $data['bill_path'] = $request->file('bill')->store('bills', 'public');
        }

        $data['status'] = 'pending';
        $data['manager_comment'] = null;

        $claim->update($data);

        return redirect('/claims')->with('status', 'Expense claim resubmitted for approval.');
    }

    public function approve(ExpenseClaim $claim)
    {
        $claim->update([
            'status' => 'approved',
            'manager_comment' => null,
        ]);

        return back()->with('status', 'Claim approved.');
    }

    public function reject(Request $request, ExpenseClaim $claim)
    {
        $request->validate([
            'manager_comment' => 'required|string|max:1000',
        ]);

        $claim->update([
            'status' => 'rejected',
            'manager_comment' => $request->manager_comment,
        ]);

        return back()->with('status', 'Claim rejected.');
    }

    public function returnForCorrection(Request $request, ExpenseClaim $claim)
    {
        $request->validate([
            'manager_comment' => 'required|string|max:1000',
        ]);

        $claim->update([
            'status' => 'returned',
            'manager_comment' => $request->manager_comment,
        ]);

        return back()->with('status', 'Claim sent back for correction.');
    }

    private function validateClaim(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'travel_from' => 'required|string|max:255',
            'travel_to' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:2000',
            'bill' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
    }

    private function authorizeAccess(Request $request, ExpenseClaim $claim)
    {
        $user = $request->user();

        if (! $user->isManager() && $claim->user_id !== $user->id) {
            abort(403, 'You are not authorized to view this claim.');
        }
    }
}

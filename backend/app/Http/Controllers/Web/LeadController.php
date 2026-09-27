<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::with(['assignedUser', 'customer']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }

        $perPage = (int) $request->input('per_page', 5);
        $leads = $query->latest()->paginate($perPage)->withQueryString();
        $users = User::all();

        return view('leads.index', compact('leads', 'users'));
    }

    public function create(): View
    {
        $users = User::all();
        $sources = Lead::SOURCES;
        $statuses = Lead::STATUSES;

        return view('leads.create', compact('users', 'sources', 'statuses'));
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $lead = Lead::create($request->validated());

        $message = 'Lead created successfully.';
        if ($lead->status === Lead::STATUS_WON && $lead->customer_id) {
            $message .= ' Lead automatically converted into a Customer!';
        }

        return redirect()->route('leads.index')->with('success', $message);
    }

    public function edit(Lead $lead): View
    {
        $users = User::all();
        $sources = Lead::SOURCES;
        $statuses = Lead::STATUSES;

        return view('leads.edit', compact('lead', 'users', 'sources', 'statuses'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $previousStatus = $lead->status;
        $lead->update($request->validated());
        $lead->refresh();

        $message = 'Lead updated successfully.';
        if ($previousStatus !== Lead::STATUS_WON && $lead->status === Lead::STATUS_WON && $lead->customer_id) {
            $message .= ' Lead status marked as Won and converted to Customer!';
        }

        return redirect()->route('leads.index')->with('success', $message);
    }

    public function destroy(Request $request, Lead $lead): RedirectResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Only Admins are allowed to delete leads.');
        }

        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully.');
    }

    /**
     * Optional manual conversion button on web UI
     */
    public function convert(Lead $lead): RedirectResponse
    {
        if ($lead->status !== Lead::STATUS_WON) {
            $lead->update(['status' => Lead::STATUS_WON]);
        }

        return redirect()->route('customers.index')->with('success', "Lead {$lead->name} converted to Customer successfully!");
    }
}

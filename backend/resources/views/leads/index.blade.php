@extends('layouts.app')

@section('title', 'Leads Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-funnel-fill text-primary me-2"></i> Lead Module</h2>
        <p class="text-secondary mb-0">Track, manage, and automatically convert qualified leads into customers</p>
    </div>
    <div>
        <a href="{{ route('leads.create') }}" class="btn btn-accent shadow-sm">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Lead
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">Total Leads</div>
            <div class="stat-value text-dark mt-1">{{ $leads->total() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">New Leads</div>
            <div class="stat-value text-primary mt-1">{{ \App\Models\Lead::where('status', 'New')->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">In Progress</div>
            <div class="stat-value text-warning mt-1">{{ \App\Models\Lead::where('status', 'In Progress')->count() }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">Won & Converted</div>
            <div class="stat-value text-success mt-1">{{ \App\Models\Lead::where('status', 'Won')->count() }}</div>
        </div>
    </div>
</div>

<!-- Filters and Search Card -->
<div class="crm-card p-3 mb-4">
    <form method="GET" action="{{ route('leads.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search name, email, company..." value="{{ request('search') }}">
            </div>
        </div>
        
        <div class="col-6 col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(\App\Models\Lead::STATUSES as $st)
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <select name="source" class="form-select">
                <option value="">All Sources</option>
                @foreach(\App\Models\Lead::SOURCES as $src)
                    <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-2">
            <select name="assigned_to" class="form-select">
                <option value="">All Assigned</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('assigned_to') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6 col-md-1">
            <select name="per_page" class="form-select" title="Items per page">
                <option value="5" {{ request('per_page', '5') == '5' ? 'selected' : '' }}>5/page</option>
                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10/page</option>
                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25/page</option>
            </select>
        </div>

        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-dark w-100 fw-semibold"><i class="bi bi-filter me-1"></i> Filter</button>
            @if(request()->anyFilled(['search', 'status', 'source', 'assigned_to', 'per_page']))
                <a href="{{ route('leads.index') }}" class="btn btn-outline-danger" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Leads Data Table -->
<div class="crm-card overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Contact Info</th>
                    <th class="text-nowrap">Source</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Assigned To</th>
                    <th class="text-nowrap">Follow-up</th>
                    <th class="text-nowrap">Customer Link</th>
                    <th class="text-end text-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $lead->name }}</div>
                        <div class="text-secondary small"><i class="bi bi-building me-1"></i>{{ $lead->company ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="text-dark"><i class="bi bi-envelope me-1 text-secondary"></i>{{ $lead->email }}</div>
                        <div class="text-secondary small"><i class="bi bi-telephone me-1"></i>{{ $lead->phone ?? 'N/A' }}</div>
                    </td>
                    <td class="text-nowrap">
                        <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">
                            {{ $lead->source }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        @php
                            $statusClass = strtolower(str_replace(' ', '_', $lead->status));
                        @endphp
                        <span class="badge-status status-{{ $statusClass }}">
                            <i class="bi bi-record-fill me-1"></i>{{ $lead->status }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        @if($lead->assignedUser)
                            <div class="d-flex align-items-center gap-1 text-dark fw-medium">
                                <i class="bi bi-person-circle text-primary"></i>
                                <span>{{ $lead->assignedUser->name }}</span>
                            </div>
                        @else
                            <span class="text-muted fst-italic">Unassigned</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        @if($lead->follow_up_date)
                            <span class="text-dark fw-medium">
                                <i class="bi bi-calendar-event me-1 text-primary"></i>{{ $lead->follow_up_date->format('M d, Y') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        @if($lead->customer)
                            <a href="{{ route('customers.index', ['search' => $lead->customer->email]) }}" class="badge bg-success bg-opacity-10 text-success border border-success text-decoration-none px-2.5 py-1.5 fw-bold" title="Converted to Customer">
                                <i class="bi bi-check-circle-fill me-1"></i> Converted (#{{ $lead->customer->id }})
                            </a>
                        @else
                            <span class="text-muted small">Not Converted</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <div class="btn-group">
                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-sm btn-outline-primary" title="Edit Lead">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            @if($lead->status !== 'Won')
                                <form action="{{ route('leads.convert', $lead) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Won & Convert to Customer" onclick="return confirm('Mark status as Won and convert this lead into a Customer?')">
                                        <i class="bi bi-trophy-fill"></i>
                                    </button>
                                </form>
                            @endif

                            @if(Auth::user()->isAdmin())
                                <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Lead">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-secondary opacity-50" title="Delete restricted to Admin role" disabled>
                                    <i class="bi bi-lock-fill"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                        No leads found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Always Visible Pagination Footer -->
    <div class="p-3 border-top bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="text-secondary small fw-semibold">
            Showing <strong>{{ $leads->firstItem() ?? 0 }}</strong> to <strong>{{ $leads->lastItem() ?? 0 }}</strong> of <strong>{{ $leads->total() }}</strong> leads
        </div>
        <div>
            @if($leads->hasPages())
                {{ $leads->links('pagination::bootstrap-5') }}
            @else
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary opacity-50" disabled><i class="bi bi-chevron-left me-1"></i> Previous</button>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-1.5 fw-bold">Page 1 of 1</span>
                    <button class="btn btn-sm btn-outline-secondary opacity-50" disabled>Next <i class="bi bi-chevron-right ms-1"></i></button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

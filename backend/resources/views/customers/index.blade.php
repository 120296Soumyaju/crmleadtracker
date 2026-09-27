@extends('layouts.app')

@section('title', 'Customer Module')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-people-fill text-success me-2"></i> Customer Module</h2>
        <p class="text-secondary mb-0">Verified active customers automatically converted from won leads</p>
    </div>
    <div>
        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 fs-6 fw-bold">
            <i class="bi bi-shield-check me-1"></i> Read-Only Listing (Auto-Converted from Won Leads)
        </span>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">Total Converted Customers</div>
            <div class="stat-value text-success mt-1">{{ $customers->total() }}</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="text-secondary small fw-bold uppercase">Linked Revenue Opportunities</div>
            <div class="stat-value text-dark mt-1">{{ \App\Models\Lead::whereNotNull('customer_id')->count() }} Leads</div>
        </div>
    </div>
</div>

<!-- Search Form -->
<div class="crm-card p-3 mb-4">
    <form method="GET" action="{{ route('customers.index') }}" class="row g-2 align-items-center">
        <div class="col-12 col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search customer name, email, company..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="per_page" class="form-select" title="Items per page">
                <option value="5" {{ request('per_page', '5') == '5' ? 'selected' : '' }}>5/page</option>
                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10/page</option>
                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25/page</option>
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-outline-dark w-100 fw-semibold"><i class="bi bi-search me-1"></i> Search</button>
            @if(request()->anyFilled(['search', 'per_page']))
                <a href="{{ route('customers.index') }}" class="btn btn-outline-danger" title="Clear Search"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Customer Data Table -->
<div class="crm-card overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th class="text-nowrap"># Customer ID</th>
                    <th class="text-nowrap">Customer Name</th>
                    <th class="text-nowrap">Email Address</th>
                    <th class="text-nowrap">Phone Number</th>
                    <th class="text-nowrap">Company</th>
                    <th class="text-nowrap">Linked Won Leads</th>
                    <th class="text-nowrap">Conversion Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="text-secondary fw-mono text-nowrap">#{{ $customer->id }}</td>
                    <td class="text-nowrap">
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-person-circle text-success fs-5"></i>
                            <span>{{ $customer->name }}</span>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <span class="text-dark"><i class="bi bi-envelope me-1 text-secondary"></i>{{ $customer->email }}</span>
                    </td>
                    <td class="text-nowrap">
                        <span class="text-dark"><i class="bi bi-telephone me-1 text-secondary"></i>{{ $customer->phone ?? 'N/A' }}</span>
                    </td>
                    <td class="text-nowrap">
                        <span class="text-dark fw-medium"><i class="bi bi-building me-1 text-secondary"></i>{{ $customer->company ?? 'N/A' }}</span>
                    </td>
                    <td class="text-nowrap">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2.5 py-1.5 fw-bold">
                            <i class="bi bi-link-45deg me-1"></i> {{ $customer->leads_count }} Lead(s)
                        </span>
                    </td>
                    <td class="text-secondary small text-nowrap">
                        {{ $customer->created_at ? $customer->created_at->format('M d, Y H:i') : 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-secondary">
                        <i class="bi bi-people fs-1 d-block mb-2 text-muted"></i>
                        No converted customers found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Always Visible Pagination Footer -->
    <div class="p-3 border-top bg-light d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="text-secondary small fw-semibold">
            Showing <strong>{{ $customers->firstItem() ?? 0 }}</strong> to <strong>{{ $customers->lastItem() ?? 0 }}</strong> of <strong>{{ $customers->total() }}</strong> customers
        </div>
        <div>
            @if($customers->hasPages())
                {{ $customers->links('pagination::bootstrap-5') }}
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

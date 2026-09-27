@extends('layouts.app')

@section('title', 'Edit Lead - ' . $lead->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Lead</h2>
                <p class="text-secondary mb-0">Update information and tracking status for lead #{{ $lead->id }}</p>
            </div>
            <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        @if($lead->customer)
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4 d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                <div>
                    <strong>Converted Customer Link:</strong> This lead is linked to Customer 
                    <a href="{{ route('customers.index', ['search' => $lead->customer->email]) }}" class="text-success fw-bold text-decoration-underline">
                        {{ $lead->customer->name }} (#{{ $lead->customer->id }})
                    </a>.
                </div>
            </div>
        @endif

        <div class="crm-card p-4 p-md-5">
            <form method="POST" action="{{ route('leads.update', $lead) }}">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label text-secondary small fw-bold">Lead Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $lead->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label text-secondary small fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $lead->email) }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label text-secondary small fw-bold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $lead->phone) }}">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="company" class="form-label text-secondary small fw-bold">Company Name</label>
                        <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company', $lead->company) }}">
                        @error('company')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="source" class="form-label text-secondary small fw-bold">Lead Source <span class="text-danger">*</span></label>
                        <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" required>
                            @foreach($sources as $src)
                                <option value="{{ $src }}" {{ old('source', $lead->source) == $src ? 'selected' : '' }}>{{ $src }}</option>
                            @endforeach
                        </select>
                        @error('source')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label text-secondary small fw-bold">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ old('status', $lead->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-primary small mt-1">
                            <i class="bi bi-lightning-charge me-1"></i> Changing status to <strong>"Won"</strong> triggers automatic creation/linking of a Customer.
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="assigned_to" class="form-label text-secondary small fw-bold">Assigned Sales User</label>
                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                            <option value="">-- Unassigned --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $lead->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->role)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="follow_up_date" class="form-label text-secondary small fw-bold">Follow-Up Date</label>
                        <input type="date" class="form-control @error('follow_up_date') is-invalid @enderror" id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date', optional($lead->follow_up_date)->format('Y-m-d')) }}">
                        @error('follow_up_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label text-secondary small fw-bold">Notes / Conversation Logs</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4">{{ old('notes', $lead->notes) }}</textarea>
                        @error('notes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-accent px-4">
                        <i class="bi bi-save me-1"></i> Update Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

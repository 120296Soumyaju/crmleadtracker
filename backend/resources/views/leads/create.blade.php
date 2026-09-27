@extends('layouts.app')

@section('title', 'Add New Lead')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1"><i class="bi bi-person-plus-fill text-primary me-2"></i> Add New Lead</h2>
                <p class="text-secondary mb-0">Enter details to capture a new sales lead opportunity</p>
            </div>
            <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="crm-card p-4 p-md-5">
            <form method="POST" action="{{ route('leads.store') }}">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <label for="name" class="form-label text-secondary small fw-bold">Lead Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Acme Innovations">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label text-secondary small fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="contact@acme.com">
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="phone" class="form-label text-secondary small fw-bold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                        @error('phone')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="company" class="form-label text-secondary small fw-bold">Company Name</label>
                        <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company') }}" placeholder="e.g. Acme Corp">
                        @error('company')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="source" class="form-label text-secondary small fw-bold">Lead Source <span class="text-danger">*</span></label>
                        <select class="form-select @error('source') is-invalid @enderror" id="source" name="source" required>
                            @foreach($sources as $src)
                                <option value="{{ $src }}" {{ old('source', 'Web') == $src ? 'selected' : '' }}>{{ $src }}</option>
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
                                <option value="{{ $st }}" {{ old('status', 'New') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-primary small mt-1">
                            <i class="bi bi-info-circle me-1"></i> Selecting <strong>"Won"</strong> will automatically convert this lead into a Customer record.
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="assigned_to" class="form-label text-secondary small fw-bold">Assigned Sales User</label>
                        <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                            <option value="">-- Unassigned --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
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
                        <input type="date" class="form-control @error('follow_up_date') is-invalid @enderror" id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date') }}">
                        @error('follow_up_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label text-secondary small fw-bold">Notes / Conversation Logs</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" placeholder="Enter key notes, evaluation feedback or customer requirements...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                    <a href="{{ route('leads.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-accent px-4">
                        <i class="bi bi-check-lg me-1"></i> Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

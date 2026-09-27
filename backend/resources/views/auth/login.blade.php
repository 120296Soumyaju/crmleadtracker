@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center py-5" style="min-height: 75vh;">
    <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
        <div class="crm-card p-4 p-sm-5 shadow-sm">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">CRM Sign In</h3>
                <p class="text-secondary small">Enter your credentials to access Lead & Customer Tracker</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@crm.com">
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-secondary small fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-secondary border-end-0"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-secondary small" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2.5 mb-4 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Sign In to Dashboard
                </button>
            </form>

            <div class="border-top pt-4 mt-2">
                <p class="text-secondary text-center small mb-3 fw-bold">1-Click Machine Test Quick Credentials:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-between px-3 fw-semibold" onclick="fillCreds('admin@crm.com', 'password123')">
                        <span><i class="bi bi-shield-check me-2"></i> Fill Admin Account</span>
                        <small class="opacity-75">admin@crm.com</small>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-between px-3 fw-semibold" onclick="fillCreds('sales@crm.com', 'password123')">
                        <span><i class="bi bi-person-badge me-2"></i> Fill Sales User Account</span>
                        <small class="opacity-75">sales@crm.com</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fillCreds(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection

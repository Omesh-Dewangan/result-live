@extends('layouts.app')

@section('title', 'Admin Login | Result Management System')

@section('content')
<div class="row justify-content-center align-items-center animate-fade-in" style="min-height: 85vh;">
    <div class="col-12 col-sm-8 col-md-6 col-lg-4">
        <!-- Professional Login Card -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-body p-4 p-sm-5">
                
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-halved fa-2x text-primary"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                    <p class="text-muted small">Please sign in to the admin console.</p>
                </div>

                <!-- Session Errors -->
                @if(session('error'))
                    <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 px-3 py-2 rounded-3 small">
                        <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                        <span class="fw-bold">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Email Address</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden login-input-group">
                            <span class="input-group-text bg-light border-0 px-3 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control bg-light border-0 py-2 ps-1 @error('email') is-invalid @enderror" placeholder="name@domain.com" required autofocus autocomplete="email">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-2 fw-bold"><i class="fas fa-info-circle me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Password</label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden login-input-group">
                            <span class="input-group-text bg-light border-0 px-3 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control bg-light border-0 py-2 ps-1" placeholder="***********" required autocomplete="current-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 mb-4 shadow-sm" id="btn-login" style="transition: all 0.3s ease;">
                        Sign In <i class="fas fa-arrow-right ms-2 transition-transform"></i>
                    </button>

                    <div class="text-center">
                        <a href="{{ route('student.search') }}" class="text-decoration-none text-muted small hover-primary" style="transition: color 0.2s;">
                            <i class="fas fa-arrow-left me-1"></i> Return to Result Portal
                        </a>
                    </div>
                </form>

            </div>
        </div>
        
        <!-- Footer info -->
        <div class="text-center mt-4">
            <p class="small text-muted mb-0">&copy; {{ date('Y') }} Result Management System. All rights reserved.</p>
        </div>
    </div>
</div>

<style>
    /* Professional minimalistic inputs */
    .login-input-group {
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    .login-input-group:focus-within {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.15) !important;
    }
    .login-input-group:focus-within .input-group-text,
    .login-input-group:focus-within .form-control {
        background-color: #fff !important;
    }
    
    .form-control:focus {
        box-shadow: none;
    }
    
    /* Hover effects */
    .hover-primary:hover { 
        color: var(--primary-color) !important; 
    }
    
    .btn-primary:active, .btn-primary:focus {
        transform: translateY(1px);
    }
    .btn-primary:hover .transition-transform {
        transform: translateX(4px);
    }
</style>

@section('scripts')
<script>
    // Simple robust submit button loader
    $(document).ready(function() {
        $('#loginForm').on('submit', function() {
            let btn = $('#btn-login');
            btn.prop('disabled', true);
            btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Authenticating...');
        });
    });
</script>
@endsection
@endsection

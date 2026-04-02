@extends('layouts.app')

@section('title', 'Check Your Result | University Result Portal')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100 animate-fade-in bg-light" style="margin-top: -60px; margin-bottom: -60px;">
    <div class="col-12 col-md-6 py-5">
        
        <div class="text-center mb-4">
            <!-- Logo Section -->
            <div class="mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="University Logo" class="img-fluid" style="max-height: 90px; object-fit: contain;" onerror="this.onerror=null; this.src='https://placehold.co/300x100/f8fafc/4f46e5?text=Your+Logo+Here';">
            </div>
            <h2 class="fw-bold text-dark mb-1"></h2>
        </div>

        <div class="card p-0 border-0 shadow-sm rounded-4">
            @if($loginAccess)
                <!-- Portal Status Banner -->
                <div class="px-4 py-3 d-flex align-items-center rounded-top" style="background: rgba(248, 250, 252, 0.8); border-bottom: 1px solid rgba(0,0,0,0.03);">
                    <div class="pulse-indicator bg-{{ $portalStatus === 'active' ? 'success' : ($portalStatus === 'scheduled' ? 'warning' : 'danger') }} me-3"></div>
                    <div>
                        <span class="small fw-bold text-uppercase text-muted d-block" style="font-size: 0.65rem; letter-spacing: 1px;">System Status</span>
                        <span class="small fw-bold text-{{ $portalStatus === 'active' ? 'success' : ($portalStatus === 'scheduled' ? 'warning' : 'danger') }}">
                            {{ $statusMessage }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-sm-5">
                    <!-- Session Feedback -->
                    <div id="error-alert" class="alert alert-danger border-0 shadow-sm d-none align-items-center mb-4 px-3 py-2 rounded-3 small">
                        <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                        <span class="fw-bold" id="error-text"></span>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4 px-3 py-2 rounded-3 small animate-fade-in">
                            <i class="fas fa-exclamation-circle me-2 text-danger"></i>
                            <span class="fw-bold">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('student.search.post') }}" method="POST" id="search-form">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">University Roll Number <span class="text-danger">*</span></label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden login-input-group">
                                <span class="input-group-text bg-white border-0 px-3 text-muted"><i class="fas fa-id-card"></i></span>
                                <input type="text" name="roll_number" id="roll_number" class="form-control bg-white border-0 py-3 ps-1 fw-medium" placeholder="e.g. 1001" required autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label class="form-label fw-bold text-muted small text-uppercase" style="letter-spacing: 0.5px;">Candidate Name <span class="text-muted fw-normal text-lowercase">(Optional)</span></label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden login-input-group">
                                <span class="input-group-text bg-white border-0 px-3 text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-white border-0 py-3 ps-1 fw-medium" placeholder="As per admit card" autocomplete="off">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" id="btn-search" style="transition: all 0.3s ease;">
                            <span id="btn-text">Login</span>
                            <i class="fas fa-arrow-right ms-2 transition-transform" id="btn-icon"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- Login Access Denied / Window Closed -->
                <div class="card-body p-5 text-center bg-light bg-opacity-50">
                    <div class="mb-4 d-inline-block p-4 bg-danger bg-opacity-10 rounded-circle text-danger">
                        <i class="fas fa-user-lock fa-3x opacity-75"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">Login Restricted</h4>
                    <p class="text-muted mb-4 small">
                        {{ $loginMessage }}
                    </p>
                </div>
            @endif
        </div>
        
        <div class="text-center mt-4">
            <p class="small text-muted mb-0"><i class="fas fa-lock text-success me-1 opacity-75"></i> Secured by State-of-the-Art Encryption</p>
        </div>
    </div>
</div>

<style>
    /* Professional minimalistic inputs */
    .login-input-group {
        border: 2px solid #f1f5f9;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .login-input-group:focus-within {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
        transform: translateY(-1px);
    }
    .login-input-group:focus-within .input-group-text {
        color: var(--primary-color) !important;
    }
    .form-control:focus {
        box-shadow: none;
    }
    
    /* Button Hover */
    .btn-primary:hover .transition-transform {
        transform: translateX(4px);
    }
    
    /* Status Indicator */
    .pulse-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: relative;
    }
    .pulse-indicator::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        animation: pulse 2s infinite;
        opacity: 0.8;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(2.5); opacity: 0; }
    }
</style>

@section('scripts')
<script>
    $(document).ready(function() {
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            
            let form = $(this);
            let btn = $('#btn-search');
            let originalContent = btn.html();
            
            // UI Loading State
            btn.prop('disabled', true);
            btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing Request...');
            $('#error-alert').addClass('d-none').removeClass('d-flex');

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // On success, backend currently returns dynamic JSON or a 302 redirect.
                    // Because we are using AJAX, if it's a 302, jQuery handles it seamlessly,
                    // BUT if it's a JSON response with the target URL, we redirect manually.
                    // Using .replace() OVERWRITES the history stack, so they cannot press "Back" to return to search!
                    if (response.redirect) {
                        window.location.replace(response.redirect);
                    } else if (response.error) {
                        $('#error-text').text(response.error);
                        $('#error-alert').removeClass('d-none').addClass('d-flex');
                        btn.prop('disabled', false).html(originalContent);
                    }
                },
                error: function(xhr) {
                    // If backend sends a 302 redirect back (like default Laravel response behavior)
                    // We can catch and follow it if it's in the responseURL or if it's an error block
                    if(xhr.status === 302 || xhr.status === 200) {
                        // Normally happens if redirect gets caught as successful response but HTML
                        // Let's just submit standard form if AJAX gets messy, making it 100% robust:
                        form.off('submit').submit();
                    } else {
                        let errorMsg = 'Invalid Credentials or Result Not Found.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        $('#error-text').text(errorMsg);
                        $('#error-alert').removeClass('d-none').addClass('d-flex');
                        btn.prop('disabled', false).html(originalContent);
                    }
                }
            });
        });
    });
</script>
@endsection
@endsection

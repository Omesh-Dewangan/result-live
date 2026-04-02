@extends('layouts.app')

@section('title', 'System Settings | Admin Panel')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" class="animate-fade-in">
    @csrf
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold text-dark"><i class="fas fa-cog text-primary me-2"></i> System Settings</h2>
                <p class="text-muted mb-0">Manage global portal access, scheduling, and result declaration parameters.</p>
            </div>
            <div>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-bold d-none d-md-block shadow-sm">
                    <i class="fas fa-save me-2"></i> Save All Settings
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="col-12 mb-4">
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-sign-in-alt text-primary me-2"></i> Student Portal Login</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small mb-4">Restrict when students can access the search page.</p>
                    
                    <div class="form-group mb-4 p-3 bg-light rounded-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span class="fw-bold font-sm">Portal Login Switch</span>
                            <div class="form-check form-switch">
                                <input type="hidden" name="login_active" value="0">
                                <input class="form-check-input h4 cursor-pointer" type="checkbox" name="login_active" value="1" {{ $settings->login_active ? 'checked' : '' }}>
                            </div>
                        </label>
                        <small class="text-muted">Globally enable or disable the student login screen.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Login Enabled From</label>
                            <input type="text" class="form-control datetime-picker bg-white" name="login_from" value="{{ $settings->login_from ? $settings->login_from->format('Y-m-d H:i') : '' }}" placeholder="Select Start Date & Time">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Login Enabled To</label>
                            <input type="text" class="form-control datetime-picker bg-white" name="login_to" value="{{ $settings->login_to ? $settings->login_to->format('Y-m-d H:i') : '' }}" placeholder="Select End Date & Time">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info py-2 px-3 small border-0 mb-0">
                            <i class="fas fa-info-circle me-1"></i> Students can't see the search page outside this window.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0"><i class="fas fa-eye text-primary me-2"></i> Marks Visibility</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small mb-4">Control when students can see their subject-wise marks.</p>
                    
                    <div class="form-group mb-4 p-3 bg-light rounded-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span class="fw-bold font-sm">Show Marks Switch</span>
                            <div class="form-check form-switch">
                                <input type="hidden" name="result_live" value="0">
                                <input class="form-check-input h4 cursor-pointer" type="checkbox" name="result_live" value="1" {{ $settings->result_live ? 'checked' : '' }}>
                            </div>
                        </label>
                        <small class="text-muted">Enable/Disable the marksheet display after search.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Active From</label>
                            <input type="text" class="form-control datetime-picker bg-white" name="result_from" value="{{ $settings->result_from ? $settings->result_from->format('Y-m-d H:i') : '' }}" placeholder="Select Start Date & Time">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Active To</label>
                            <input type="text" class="form-control datetime-picker bg-white" name="result_to" value="{{ $settings->result_to ? $settings->result_to->format('Y-m-d H:i') : '' }}" placeholder="Select End Date & Time">
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-warning py-2 px-3 small border-0 mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i> Marks remain hidden until they reach the "Active From" time.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 d-block d-md-none mb-5">
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                <i class="fas fa-save me-2"></i> Save All Settings
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Flatpickr for Date-Time fields
        flatpickr(".datetime-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            theme: "material_blue",
            allowInput: true
        });

        // Initialize Flatpickr for plain Date field
        flatpickr(".date-picker", {
            dateFormat: "Y-m-d",
            theme: "material_blue",
            allowInput: true
        });
    });
</script>
<style>
    .cursor-pointer { cursor: pointer; }
</style>
@endsection

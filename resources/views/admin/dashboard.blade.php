@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row animate-fade-in">
    <div class="col-12 mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold text-dark mb-1">Welcome back, Admin</h2>
                <p class="text-muted mb-0">Here's what's happening with the examination system today.</p>
            </div>
            <div class="text-end d-none d-md-block">
                <div class="fw-bold h5 mb-0" id="current-time"></div>
                <div class="text-muted small">{{ date('l, d F Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-muted text-uppercase mb-1">Total Candidates</div>
                        <div class="h3 fw-bold mb-0">{{ number_format($totalResults) }}</div>
                    </div>
                    <div class="ms-3 bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                        <i class="fas fa-users-viewfinder fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-muted text-uppercase mb-1">Passed</div>
                        <div class="h3 fw-bold mb-0 text-success">{{ number_format($passCount) }}</div>
                    </div>
                    <div class="ms-3 bg-success bg-opacity-10 text-success rounded-3 p-3">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-muted text-uppercase mb-1">Failed</div>
                        <div class="h3 fw-bold mb-0 text-danger">{{ number_format($failCount) }}</div>
                    </div>
                    <div class="ms-3 bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                        <i class="fas fa-user-xmark fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-muted text-uppercase mb-1">Success Rate</div>
                        <div class="h3 fw-bold mb-0 text-indigo">
                            {{ $totalResults > 0 ? round(($passCount / $totalResults) * 100, 1) : 0 }}%
                        </div>
                    </div>
                    <div class="ms-3 bg-info bg-opacity-10 text-info rounded-3 p-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Control Center -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-shield-halved text-primary me-2"></i> System Control Center</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 text-center h-100">
                            <i class="fas fa-globe fa-2x {{ $settings->result_live ? 'text-success' : 'text-muted' }} mb-3"></i>
                            <div class="fw-bold mb-1">Public Results</div>
                            <p class="small text-muted mb-3">Allow students to query visibility.</p>
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input h4 toggle-setting cursor-pointer" type="checkbox" data-setting="result_live" {{ $settings->result_live ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 border rounded-4 text-center h-100">
                            <i class="fas fa-door-open fa-2x {{ $settings->login_active ? 'text-primary' : 'text-muted' }} mb-3"></i>
                            <div class="fw-bold mb-1">Portal Login</div>
                            <p class="small text-muted mb-3">Enable individual candidate login.</p>
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input h4 toggle-setting cursor-pointer" type="checkbox" data-setting="login_active" {{ $settings->login_active ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CSV Upload Section -->
                <div class="mt-5 p-4 bg-light rounded-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-1">Bulk Import Records</h5>
                            <p class="text-muted small mb-0">Excel/CSV parsing optimized for 500k+ data points using chunked processing.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <form action="{{ route('admin.results.import') }}" method="POST" enctype="multipart/form-data" id="import-form">
                                @csrf
                                <input type="file" name="file" id="file-upload" class="d-none" accept=".csv" onchange="submitImport()">
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('file-upload').click()">
                                    <i class="fas fa-file-import me-2"></i> Start Import
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Tools -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.results.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-0">Manage Database</div>
                            <div class="small text-muted">Update, Edit or Delete student records</div>
                        </div>
                        <i class="fas fa-chevron-right ms-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-0">Scheduling & Timers</div>
                            <div class="small text-muted">Configure access windows</div>
                        </div>
                        <i class="fas fa-chevron-right ms-auto text-muted small"></i>
                    </a>
                    <a href="{{ route('student.search') }}" target="_blank" class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <div class="fw-bold mb-0">Public Portal Preview</div>
                            <div class="small text-muted">Check UX from student perspective</div>
                        </div>
                        <i class="fas fa-external-link-alt ms-auto text-muted small"></i>
                    </a>
                </div>
            </div>
            <div class="card-footer bg-white border-0 p-4 pt-0">
                <div class="alert alert-info border-0 rounded-4 py-3 mb-0">
                    <i class="fas fa-info-circle me-2"></i> <strong>Pro Tip:</strong> Use the sidebar toggle to maximize your data view during bulk edits.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: true, hour: '2-digit', minute: '2-digit', second: '2-digit' });
        $('#current-time').text(timeStr);
    }
    
    $(document).ready(function() {
        updateTime();
        setInterval(updateTime, 1000);

        flatpickr(".flatpickr-input", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            onChange: function(selectedDates, dateStr, instance) {
                const setting = $(instance.element).data('setting');
                updateSetting(setting, dateStr);
            }
        });

        $('.toggle-setting').on('change', function() {
            const setting = $(this).data('setting');
            const value = $(this).is(':checked') ? 1 : 0;
            updateSetting(setting, value);
        });
    });

    function updateSetting(key, value) {
        $.ajax({
            url: "{{ route('admin.settings.update-ajax') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                key: key,
                value: value
            },
            success: function(response) {
                if(response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Security settings updated successfully',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            }
        });
    }

    function submitImport() {
        Swal.fire({
            title: 'Confirm Import?',
            text: "This will process the data in background chunks. This might take a few moments.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Yes, Start Import'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#import-form').submit();
            } else {
                $('#file-upload').val('');
            }
        });
    }
</script>

<style>
    .text-indigo { color: #6366f1; }
    .cursor-pointer { cursor: pointer; }
    .bg-opacity-10 { --bs-bg-opacity: 0.1; }
    .rounded-4 { border-radius: 1rem !important; }
    .list-group-item-action:hover { background-color: #f8fafc !important; }
</style>
@endsection

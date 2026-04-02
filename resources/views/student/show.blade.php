@extends('layouts.app')

@section('title', 'Mark Statement - ' . $result->roll_number)

@section('content')
<!-- Floating Print Button (Mobile/Desktop Friendly) -->
<div class="no-print position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
    <button id="printButton" class="btn btn-primary btn-lg rounded-pill shadow-lg px-4 py-3 fw-bold animate-bounce">
        <i class="fas fa-print me-2"></i> PRINT MARKSHEET
    </button>
</div>

<!-- Back Header -->
<div class="no-print bg-white border-bottom py-3 mb-4 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a href="{{ route('student.clear') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
            <i class="fas fa-arrow-left me-2"></i> New Search
        </a>
        <div class="text-muted small fw-bold">
            <i class="fas fa-user-shield me-1"></i> SECURE SESSION ACTIVE
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {!! $parsedHTML !!}
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce { animation: bounce 3s infinite ease-in-out; }

    @media print {
        body { background: #fff !important; }
        .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .no-print { display: none !important; }
        .col-lg-10 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Back Button → Force Re-Login
        history.pushState(null, '', window.location.href);
        window.addEventListener('popstate', function() {
            window.location.replace('/result/clear');
        });

        // 2. Disable Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // 3. Disable Keyboard Shortcuts (F12, Ctrl+Shift+I, Ctrl+U)
        document.onkeydown = function(e) {
            if (e.keyCode === 123 || (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || (e.ctrlKey && e.keyCode === 85)) {
                e.preventDefault();
                return false;
            }
        };

        // 4. Secure Print Tracking
        const printBtn = document.getElementById('printButton');
        if (printBtn) {
            printBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Show loading state
                const originalContent = printBtn.innerHTML;
                printBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> PREPARING...';
                printBtn.disabled = true;

                // Log print activity to server via AJAX
                fetch("{{ route('student.record_print', ['roll_number' => $result->roll_number]) }}?auth={{ request()->query('auth') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Trigger native print regardless of success for better UX
                    window.print();
                })
                .catch(error => {
                    console.error('Audit Log Error:', error);
                    window.print(); // Fallback: still allow printing
                })
                .finally(() => {
                    printBtn.innerHTML = originalContent;
                    printBtn.disabled = false;
                });
            });
        }
    });
</script>
@endsection

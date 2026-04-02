@extends('layouts.app')

@section('title', 'Manage Results')

@section('content')
<div class="row animate-fade-in g-3">
    <div class="col-md-12">
        <form action="{{ route('admin.results.bulk-pdf') }}" method="POST" id="bulk-form">
            @csrf
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-2">
                    <!-- Compact Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2 p-1 border-bottom pb-2">
                        <div>
                            <h6 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="fas fa-list text-primary me-2"></i> 
                                <span>Student Records <span class="badge bg-light text-muted border fw-normal ms-2" style="font-size: 0.6rem;">{{ $results->total() }} TOTAL</span></span>
                            </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-2 fw-bold px-3 d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                <i class="fas fa-file-pdf"></i> BULK PRINT
                            </button>
                            <label class="btn btn-outline-secondary btn-sm rounded-2 fw-bold px-3 d-flex align-items-center gap-1 mb-0" style="font-size: 0.7rem; cursor: pointer;">
                                <input type="checkbox" id="selectAll" class="form-check-input mt-0 me-1" style="width: 12px; height: 12px;"> SELECT ALL
                            </label>

                            <div class="input-group input-group-sm overflow-hidden rounded-2 border shadow-xs ms-2" style="max-width: 200px;">
                                <span class="input-group-text bg-white border-0 ps-2"><i class="fas fa-search text-muted opacity-50"></i></span>
                                <input class="form-control border-0 ps-1" type="search" name="search" placeholder="Search..." value="{{ request('search') }}" style="font-size: 0.75rem;">
                            </div>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-xs py-1 px-3 mb-2 rounded-2 animate-fade-in text-center" style="font-size: 0.75rem;">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Micro-Table -->
                    <div class="table-responsive rounded-2 overflow-hidden border shadow-xs bg-white">
                        <table class="table table-hover table-sm mb-0 align-middle">
                            <thead class="bg-light">
                                <tr style="background: #f8fafc; font-size: 0.6rem; letter-spacing: 0.5px;" class="text-uppercase text-muted fw-black border-bottom">
                                    <th class="ps-3 py-2 border-0" width="40">#</th>
                                    <th class="py-2 border-0">ROLL ID</th>
                                    <th class="py-2 border-0">CANDIDATE NAME</th>
                                    <th class="py-2 border-0">COURSE</th>
                                    <th class="py-2 border-0">MARKS</th>
                                    <th class="py-2 border-0">STATUS</th>
                                    <th class="text-center py-2 border-0">HISTORY</th>
                                    <th class="text-end pe-3 py-2 border-0">MARKSHEET</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 0.7rem;">
                                @forelse($results as $result)
                                <tr class="border-bottom-0">
                                    <td class="ps-3">
                                        <input type="checkbox" name="selected_results[]" value="{{ $result->id }}" class="form-check-input result-checkbox" style="width: 13px; height: 13px;">
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $result->roll_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $result->name }}</div>
                                        <div class="text-muted opacity-50 small">{{ $result->father_name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border border-primary border-opacity-10 fw-bold px-2">{{ $result->course }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 3px; border-radius: 10px; background: #f1f5f9; width: 40px;">
                                                <div class="progress-bar {{ $result->result_status == 'Pass' ? 'bg-success' : 'bg-danger' }}" style="width: {{ $result->total/5 }}%;"></div>
                                            </div>
                                            <span class="fw-bold opacity-75">{{ $result->total }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $result->result_status == 'Pass' ? 'text-success' : 'text-danger' }}" style="font-size: 0.65rem;">
                                            {{ strtoupper($result->result_status) }}
                                        </span>
                                    </td>
                                    <td class="text-center text-muted">
                                        <div class="d-flex justify-content-center gap-2 border-start ps-2">
                                            <span title="Views"><i class="fas fa-eye me-1 opacity-50"></i>{{ $result->view_count }}</span>
                                            <span title="Prints"><i class="fas fa-print me-1 opacity-50"></i>{{ $result->print_count }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            <button type="button" onclick="openPreview({{ $result->id }}, '{{ $result->roll_number }}')" class="btn btn-primary btn-sm rounded-pill px-3 shadow-xs border-0" style="font-size: 9px; padding-top: 2px; padding-bottom: 2px;">
                                                PREVIEW
                                            </button>
                                            <a href="{{ route('admin.results.pdf', $result->id) }}" class="btn btn-outline-danger btn-sm rounded-pill px-2 border-0 shadow-xs" title="Download PDF" style="padding-top: 2px; padding-bottom: 2px;">
                                                <i class="fas fa-file-pdf" style="font-size: 10px;"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">No records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

        <!-- Compact Pagination -->
        <div class="mt-2 d-flex justify-content-between align-items-center px-1">
            <div class="text-muted small fw-bold" style="font-size: 0.6rem;">
                 Page {{ $results->currentPage() }} of {{ $results->lastPage() }}
            </div>
            <div>
                {{ $results->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(8px);">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-2">
                <h6 class="modal-title fw-bold text-uppercase mb-0" id="previewTitle" style="font-size: 0.7rem; letter-spacing: 1px;">
                    RESULT PREVIEW
                </h6>
                <div class="d-flex gap-2">
                    <button id="modalDownloadBtn" class="btn btn-sm btn-link text-white text-decoration-none p-0" title="Download PDF">
                        <i class="fas fa-file-pdf me-2"></i>
                    </button>
                    <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.5rem;"></button>
                </div>
            </div>
            <div class="modal-body p-0 bg-light" style="min-height: 400px; max-height: 80vh; overflow-y: auto;">
                <div id="modal-loader" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary opacity-50" role="status"></div>
                    <p class="text-muted small mt-2">Generating mark statement...</p>
                </div>
                <div id="modal-content-area" class="p-4" style="background: white;"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 950; }
    .table-sm td, .table-sm th { padding-top: 0.5rem; padding-bottom: 0.5rem; }
    .page-link { padding: 4px 10px; font-size: 0.65rem; border-radius: 6px !important; margin: 0 2px; border: none; background: #fff; color: #64748b; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background: #3b82f6; color: #fff; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .progress-bar { transition: width 1s ease-in-out; }
    .result-checkbox:checked { background-color: #3b82f6; border-color: #3b82f6; }
</style>

@endsection

@section('scripts')
<script>
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    const modalLoader = document.getElementById('modal-loader');
    const modalContent = document.getElementById('modal-content-area');
    const previewTitle = document.getElementById('previewTitle');
    const modalDownloadBtn = document.getElementById('modalDownloadBtn');

    function openPreview(id, roll) {
        previewTitle.innerText = `MARK STATEMENT | ${roll}`;
        modalDownloadBtn.onclick = () => window.location.href = `/admin/results/${id}/pdf`;
        
        modalContent.innerHTML = '';
        modalLoader.classList.remove('d-none');
        previewModal.show();

        fetch(`/admin/results/${id}/preview`)
            .then(res => res.json())
            .then(data => {
                modalLoader.classList.add('d-none');
                modalContent.innerHTML = data.html;
            })
            .catch(err => {
                modalLoader.classList.add('d-none');
                modalContent.innerHTML = '<div class="alert alert-danger mx-4 mt-4">Failed to load preview. Please try again.</div>';
            });
    }

    // Select All Checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.result-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>
@endsection

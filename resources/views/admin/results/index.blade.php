@extends('layouts.app')

@section('title', 'Manage Results')

@section('content')
<div class="row animate-fade-in g-3">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-2">
                <!-- Compact Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2 p-1 border-bottom pb-2">
                    <div>
                        <h6 class="fw-bold mb-0 d-flex align-items-center">
                            <i class="fas fa-list text-primary me-2"></i> 
                            <span>Manage Student Results <span class="badge bg-light text-muted border fw-normal ms-2" style="font-size: 0.6rem;">{{ $results->total() }} Records</span></span>
                        </h6>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <form class="d-flex align-items-center" method="GET" action="{{ route('admin.results.index') }}">
                            <div class="input-group input-group-sm overflow-hidden rounded-2 border shadow-xs" style="max-width: 250px;">
                                <span class="input-group-text bg-white border-0 ps-2"><i class="fas fa-search text-muted opacity-50"></i></span>
                                <input class="form-control border-0 ps-1" type="search" name="search" placeholder="Roll Number / Name" value="{{ request('search') }}" style="font-size: 0.75rem;">
                                <button class="btn btn-primary px-3 fw-bold" type="submit" style="font-size: 0.7rem;">Filter</button>
                            </div>
                        </form>
                        <a href="{{ route('admin.results.create') }}" class="btn btn-primary btn-sm rounded-2 fw-bold px-3 shadow-sm d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                            <i class="fas fa-plus"></i> ADD RESULT
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-xs py-1 px-3 mb-2 rounded-2 animate-fade-in" style="font-size: 0.75rem;">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif
                
                <!-- Micro-Table -->
                <div class="table-responsive rounded-2 overflow-hidden border shadow-xs bg-white">
                    <table class="table table-hover table-sm mb-0 align-middle">
                        <thead class="bg-light">
                            <tr style="background: #f8fafc; font-size: 0.65rem; letter-spacing: 0.5px;" class="text-uppercase text-muted fw-black border-bottom">
                                <th class="ps-3 py-2 border-0">ROLL ID</th>
                                <th class="py-2 border-0">CANDIDATE NAME</th>
                                <th class="py-2 border-0">FATHER NAME</th>
                                <th class="py-2 border-0">COURSE/PROGRAM</th>
                                <th class="py-2 border-0">PROGRESS</th>
                                <th class="py-2 border-0">STATUS</th>
                                <th class="text-center py-2 border-0">VIEWS</th>
                                <th class="text-center py-2 border-0">PRINTS</th>
                                <th class="text-end pe-3 py-2 border-0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 0.7rem;">
                            @forelse($results as $result)
                            <tr class="border-bottom-0">
                                <td class="ps-3">
                                    <span class="fw-bold text-dark">{{ $result->roll_number }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $result->name }}</div>
                                </td>
                                <td class="text-muted">{{ $result->father_name }}</td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary border-opacity-10 fw-bold px-2">{{ $result->course }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 4px; border-radius: 10px; background: #f1f5f9; width: 60px;">
                                            <div class="progress-bar {{ $result->result_status == 'Pass' ? 'bg-success' : 'bg-danger' }} shadow-sm" style="width: {{ $result->total/5 }}%;"></div>
                                        </div>
                                        <span class="fw-bold opacity-75">{{ $result->total }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if($result->result_status == 'Pass')
                                        <span class="text-success fw-bold d-flex align-items-center gap-1">
                                            <i class="fas fa-check-circle" style="font-size: 8px;"></i> PASS
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold d-flex align-items-center gap-1">
                                            <i class="fas fa-times-circle" style="font-size: 8px;"></i> FAIL
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-primary border fw-bold" style="font-size: 0.6rem;">{{ number_format($result->view_count) }} v</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-info border fw-bold" style="font-size: 0.6rem;">{{ number_format($result->print_count) }} p</span>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.results.edit', $result->id) }}" class="btn btn-light border-0 p-1 text-primary shadow-xs rounded-2" title="Edit Result" style="width: 24px; height: 24px;">
                                            <i class="fas fa-edit" style="font-size: 0.65rem;"></i>
                                        </a>
                                        <form action="{{ route('admin.results.destroy', $result->id) }}" method="POST" onsubmit="return confirm('Delete this record permanently?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light border-0 p-1 text-danger shadow-xs rounded-2" title="Delete" style="width: 24px; height: 24px;">
                                                <i class="fas fa-trash" style="font-size: 0.65rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <div class="py-3">
                                        <i class="fas fa-folder-open fa-2x opacity-25 mb-2"></i>
                                        <p class="mb-0 small">No student records found matching your search.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Compact Pagination -->
                <div class="mt-2 d-flex justify-content-between align-items-center px-2">
                    <div class="text-muted small fw-bold" style="font-size: 0.65rem;">
                         Showing {{ $results->firstItem() ?? 0 }} to {{ $results->lastItem() ?? 0 }} of {{ $results->total() }} students
                    </div>
                    <div>
                        {{ $results->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 950; }
    .table-sm td, .table-sm th { padding-top: 0.4rem; padding-bottom: 0.4rem; }
    .pagination { margin-bottom: 0; }
    .page-link { padding: 4px 10px; font-size: 0.7rem; border-radius: 6px !important; margin: 0 2px; border: none; background: #fff; color: #64748b; font-weight: 700; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .page-item.active .page-link { background: var(--primary-color); color: #fff; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
</style>
@endsection

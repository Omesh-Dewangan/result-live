@extends('layouts.app')

@section('title', 'Manage Results')

@section('content')
<div class="row animate-fade-in">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0"><i class="fas fa-list text-primary me-2"></i> Manage Results</h4>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.results.create') }}" class="btn btn-primary me-3">
                            <i class="fas fa-plus me-1"></i> Add New Result
                        </a>
                        <form class="d-flex align-items-center" method="GET" action="{{ route('admin.results.index') }}">
                            <input class="form-control me-2" type="search" name="search" placeholder="Roll Number / Name" value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">Search</button>
                        </form>
                    </div>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Roll Number</th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Course</th>
                                <th>Total Marks</th>
                                <th>Status</th>
                                <th class="text-center">Views</th>
                                <th class="text-center">Prints</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                            <tr>
                                <td class="fw-bold">{{ $result->roll_number }}</td>
                                <td>{{ $result->name }}</td>
                                <td>{{ $result->father_name }}</td>
                                <td>{{ $result->course }}</td>
                                <td>{{ $result->total }} / 500</td>
                                <td>
                                    <span class="badge {{ $result->result_status == 'Pass' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $result->result_status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ number_format($result->view_count) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ number_format($result->print_count) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">No results found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $results->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

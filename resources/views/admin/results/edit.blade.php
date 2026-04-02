@extends('layouts.app')

@section('title', 'Edit Result')

@section('content')
<div class="row justify-content-center animate-fade-in">
    <div class="col-md-8">
        <div class="card p-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0"><i class="fas fa-edit text-primary me-2"></i> Edit Result: {{ $result->roll_number }}</h4>
                    <a href="{{ route('admin.results.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.results.update', $result->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Roll Number</label>
                            <input type="text" name="roll_number" class="form-control" value="{{ old('roll_number', $result->roll_number) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Candidate Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $result->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Father's Name</label>
                            <input type="text" name="father_name" class="form-control" value="{{ old('father_name', $result->father_name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Course</label>
                            <input type="text" name="course" class="form-control" value="{{ old('course', $result->course) }}" required>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3 text-muted">Subject Wise Marks (Out of 100)</h5>
                        
                        <div class="col-md-4">
                            <label class="form-label">Subject 1</label>
                            <input type="number" name="subject1" class="form-control" value="{{ old('subject1', $result->subject1) }}" min="0" max="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subject 2</label>
                            <input type="number" name="subject2" class="form-control" value="{{ old('subject2', $result->subject2) }}" min="0" max="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subject 3</label>
                            <input type="number" name="subject3" class="form-control" value="{{ old('subject3', $result->subject3) }}" min="0" max="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subject 4</label>
                            <input type="number" name="subject4" class="form-control" value="{{ old('subject4', $result->subject4) }}" min="0" max="100" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Subject 5</label>
                            <input type="number" name="subject5" class="form-control" value="{{ old('subject5', $result->subject5) }}" min="0" max="100" required>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="fas fa-save me-2"></i> UPDATE RESULT
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

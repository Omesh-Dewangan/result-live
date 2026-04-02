@extends('layouts.app')

@section('title', 'Result Marksheet Designer | Admin Panel')

@section('content')
<div class="row animate-fade-in g-4">
    <!-- Page Header & Global Actions -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white p-4 rounded-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 shadow-hover border-top border-5 border-primary">
            <div>
                <h2 class="fw-bold text-dark mb-1 d-flex align-items-center">
                    <i class="fas fa-magic text-primary me-3"></i>
                    <span>Result Designer <span class="badge bg-primary bg-opacity-10 text-primary fw-normal h6 mb-0 ms-2 px-3">Enterprise Editor</span></span>
                </h2>
                <p class="text-muted mb-0">Professional HTML template builder for complex student marksheets.</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.template.reset') }}" method="POST" onsubmit="return confirm('Reset to default? Your current changes will be lost permanently.')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger border-2 rounded-3 px-4 py-2 fw-bold text-uppercase small">
                        <i class="fas fa-undo me-2"></i> Reset
                    </button>
                </form>
                <button type="submit" form="template-form" class="btn btn-primary rounded-3 px-4 py-2 fw-bold text-uppercase shadow-sm small">
                    <i class="fas fa-save me-2"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 animate-fade-in">
            <div class="alert alert-success border-0 shadow-sm rounded-4 py-3 px-4 mb-0">
                <i class="fas fa-check-circle me-3 fa-lg"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    @error('result_template')
        <div class="col-12 animate-fade-in">
            <div class="alert alert-danger border-0 shadow-sm rounded-4 py-3 px-4 mb-0">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i> {{ $message }}
            </div>
        </div>
    @enderror

    <!-- Source Code Editor Card -->
    <div class="col-lg-7 d-flex flex-column">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white flex-grow-1 d-flex flex-column">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Source Editor</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-3 px-3 py-2 fw-bold text-primary dropdown-toggle d-flex align-items-center gap-2 shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus-circle"></i> Insert Component
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-2 rounded-3 mt-2">
                        <li><a class="dropdown-item py-2 rounded-2" href="javascript:void(0)" onclick="insertSnippet('table')"><i class="fas fa-table me-2 text-primary opacity-75"></i> Marks Table</a></li>
                        <li><a class="dropdown-item py-2 rounded-2" href="javascript:void(0)" onclick="insertSnippet('header')"><i class="fas fa-id-card me-2 text-primary opacity-75"></i> Official Header</a></li>
                        <li><a class="dropdown-item py-2 rounded-2" href="javascript:void(0)" onclick="insertSnippet('signature')"><i class="fas fa-pen-nib me-2 text-primary opacity-75"></i> Signature Block</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4 pt-4 d-flex flex-column flex-grow-1">
                <div id="editor-container" style="flex-grow: 1; min-height: 600px; width: 100%; border: 1px solid #eef2f7; border-radius: 12px; font-size: 15px;">{{ $settings->result_template }}</div>
                <form id="template-form" action="{{ route('admin.template.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="result_template" id="result_template_input">
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side: Smart Matrix & Canvas -->
    <div class="col-lg-5">
        <div class="d-flex flex-column gap-4 h-100">
            <!-- Smart Requirements Matrix (50+ Fields Support) -->
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">Data Binding Matrix</h6>
                            <p class="text-muted small mb-0">Real-time validation for 50+ system fields.</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 fw-black text-primary mb-0" id="progress-percent">0%</div>
                            <span class="text-muted small fw-bold">LINKED</span>
                        </div>
                    </div>
                    
                    <!-- Global Progress Bar -->
                    <div class="progress mb-3" style="height: 6px; border-radius: 100px; background: #f1f5f9;">
                        <div id="global-progress" class="progress-bar progress-bar-striped progress-bar-animated bg-primary shadow-sm" role="progressbar" style="width: 0%; border-radius: 100px;"></div>
                    </div>

                    <!-- Search Fields Bar -->
                    <div class="input-group mb-2 border-0 shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0 ps-3 py-1"><i class="fas fa-search text-muted small"></i></span>
                        <input type="text" id="matrix-search" class="form-control border-0 py-1 small" style="font-size: 13px;" placeholder="Search parameters..." onkeyup="filterMatrix()">
                    </div>
                </div>

                <div class="card-body p-0 pt-0 bg-light bg-opacity-50">
                    <div id="matrix-body" class="p-3" style="max-height: 480px; overflow-y: auto;">
                        <div class="mandatory-tags-grid">
                            <!-- Categories are rendered dynamically via JS or Static -->
                            <div class="matrix-category mb-4" id="cat-core">
                                <h6 class="fw-bold small text-uppercase text-primary mb-3"><i class="fas fa-star me-2"></i> Mandatory Core</h6>
                                <div class="row row-cols-2 g-2" id="grid-core"></div>
                            </div>
                            <div class="matrix-category mb-4" id="cat-profile">
                                <h6 class="fw-bold small text-uppercase text-secondary mb-3"><i class="fas fa-user-circle me-2"></i> Student Details</h6>
                                <div class="row row-cols-2 g-2" id="grid-profile"></div>
                            </div>
                            <div class="matrix-category mb-4" id="cat-subjects">
                                <h6 class="fw-bold small text-uppercase text-info mb-3"><i class="fas fa-book-open me-2"></i> Subject Data (1-20)</h6>
                                <div class="row row-cols-2 g-2" id="grid-subjects"></div>
                            </div>
                            <div class="matrix-category" id="cat-security">
                                <h6 class="fw-bold small text-uppercase text-warning mb-3"><i class="fas fa-shield-alt me-2"></i> Security & Tracking</h6>
                                <div class="row row-cols-2 g-2" id="grid-security"></div>
                            </div>
                            <div id="no-results" class="text-center py-5 d-none">
                                <i class="fas fa-search fa-3x text-light mb-3"></i>
                                <p class="text-muted small">No fields matching your search.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden flex-grow-1 bg-white">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Live Canvas View</h6>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-2 px-3 small d-flex align-items-center gap-2">
                        <span class="pulse-dot"></span> SYNC ACTIVE
                    </span>
                </div>
                <div class="card-body p-0 pt-3">
                    <iframe id="preview-iframe" style="width: 100%; height: 400px; border: none; background: white;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .pointer { cursor: pointer; }
    .display-tag { padding: 8px 10px; border-radius: 8px; border: 1px solid #eef2f7; background: #fff; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    .display-tag.active { border-color: #10b981; background: #f0fdf4; color: #10b981; }
    .display-tag.missing { opacity: 0.6; }
    .display-tag label { font-size: 10px; margin-bottom: 0px; color: #94a3b8; }
    .display-tag .tag-text { font-size: 11px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;}
    
    #matrix-body::-webkit-scrollbar { width: 3px; }
    #matrix-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .pulse-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; animation: pulse-green 2s infinite; }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .ace_editor { border: 1px solid #eef2f7; border-radius: 12px; }
    .ace_gutter { background: #f8fafc; color: #94a3b8; }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
<script>
    // ── CONFIGURATION FOR 50+ PARAMETERS ───────────────────────────────────────
    const matrixConfiguration = {
        core: [
            { tag: 'ROLL_NUMBER', label: 'Candidate Roll ID' },
            { tag: 'STUDENT_NAME', label: 'Full Legal Name' },
            { tag: 'TOTAL_MARKS', label: 'Grand Total Score' },
            { tag: 'STATUS', label: 'Result Status' },
            { tag: 'VERIFICATION_QR', label: 'Security QR Code' }
        ],
        profile: [
            { tag: 'FATHER_NAME', label: 'Father / Guardian' },
            { tag: 'COURSE', label: 'Course Code' },
            { tag: 'ENROLLMENT_NO', label: 'Enrollment' },
            { tag: 'DOB', label: 'Date of Birth' },
            { tag: 'GENDER', label: 'Candidate Gender' },
            { tag: 'ACADEMIC_YEAR', label: 'Academic Year' },
            { tag: 'SEMESTER', label: 'Semester / Term' },
            { tag: 'INSTITUTION', label: 'Institution Name' },
            { tag: 'PHOTO_PLACEHOLDER', label: 'Candidate Photo' }
        ],
        subjects: Array.from({length: 20}, (_, i) => ({ tag: `SUBJECT_${i+1}`, label: `Subject Marks ${i+1}` })),
        security: [
            { tag: 'DECLARED_DATE', label: 'Declaration Date' },
            { tag: 'VERIFIED_AT', label: 'Verification Time' },
            { tag: 'STATUS_CLASS', label: 'Dynamic CSS Class' },
            { tag: 'TRACKING_ID', label: 'Unique Audit Link' },
            { tag: 'DIGITAL_SIGN', label: 'Authority Sign' }
        ]
    };

    // Build the Matrix UI dynamically
    function renderMatrix() {
        Object.keys(matrixConfiguration).forEach(cat => {
            const grid = document.getElementById(`grid-${cat}`);
            matrixConfiguration[cat].forEach(item => {
                const div = document.createElement('div');
                div.className = 'col animate-fade-in';
                div.innerHTML = `
                    <div class="display-tag missing shadow-sm" data-tag-box="${item.tag}">
                        <label>${item.label}</label>
                        <span class="tag-text d-flex align-items-center justify-content-between">
                            [${item.tag}]
                            <i class="fas fa-circle-notch fa-spin small opacity-25"></i>
                        </span>
                    </div>
                `;
                grid.appendChild(div);
            });
        });
    }
    renderMatrix();

    // ── EDITOR INITIALIZATION ────────────────────────────────────────────────
    const editor = ace.edit("editor-container");
    editor.setTheme("ace/theme/tomorrow");
    editor.session.setMode("ace/mode/html");
    editor.setOptions({ fontPadding: 20, fontSize: "14pt", showPrintMargin: false });

    // ── SEARCH FILTER ────────────────────────────────────────────────────────
    function filterMatrix() {
        const query = document.getElementById('matrix-search').value.toLowerCase();
        let totalVisible = 0;
        
        document.querySelectorAll('.matrix-category').forEach(cat => {
            let catHasResults = false;
            cat.querySelectorAll('.col').forEach(col => {
                const text = col.innerText.toLowerCase();
                if (text.includes(query)) {
                    col.classList.remove('d-none');
                    catHasResults = true;
                    totalVisible++;
                } else {
                    col.classList.add('d-none');
                }
            });
            cat.classList.toggle('d-none', !catHasResults);
        });
        
        document.getElementById('no-results').classList.toggle('d-none', totalVisible > 0);
    }

    // ── LIVE UPDATE & PROGRESS ────────────────────────────────────────────────
    const iframe = document.getElementById('preview-iframe');
    const templateInput = document.getElementById('result_template_input');

    function updatePreview() {
        const content = editor.getValue();
        templateInput.value = content;
        
        // Count progress
        let totalTags = 0;
        let foundTags = 0;

        Object.keys(matrixConfiguration).forEach(cat => {
            matrixConfiguration[cat].forEach(item => {
                totalTags++;
                const box = document.querySelector(`[data-tag-box="${item.tag}"]`);
                const icon = box.querySelector('i');
                if (content.includes(`[${item.tag}]`)) {
                    foundTags++;
                    box.classList.remove('missing');
                    box.classList.add('active');
                    icon.className = 'fas fa-check-circle text-success opacity-100';
                } else {
                    box.classList.remove('active');
                    box.classList.add('missing');
                    icon.className = 'fas fa-circle-notch fa-spin opacity-25';
                }
            });
        });

        // Update Progress UI
        const percent = Math.round((foundTags / totalTags) * 100);
        document.getElementById('progress-percent').innerText = `${percent}%`;
        document.getElementById('global-progress').style.width = `${percent}%`;

        // Render Mock Preview
        let previewContent = content;
        const mockMap = {
            '\\[ROLL_NUMBER\\]': '1001552', '\\[STUDENT_NAME\\]': 'JOHN DOE', '\\[TOTAL_MARKS\\]': '398', '\\[STATUS\\]': 'PASS',
            '\\[STATUS_CLASS\\]': 'text-success', '\\[TRACKING_ID\\]': 'RT-9982-XYZ', '\\[VERIFICATION_QR\\]': '<div class="bg-light border text-center py-4 rounded-3" style="border: 2px dashed #ddd;">QR CODE</div>'
        };
        for (let key in mockMap) { previewContent = previewContent.replace(new RegExp(key, 'g'), mockMap[key]); }

        iframe.srcdoc = `<html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"><style>body{padding:30px; font-family:'Outfit', sans-serif;} .mx-auto{margin-top:0 !important;}</style></head><body>${previewContent}</body></html>`;
    }

    editor.on('change', updatePreview);
    window.addEventListener('load', updatePreview);

    // ── SNIPPETS ─────────────────────────────────────────────────────────────
    const snippets = {
        table: `<div class="table-responsive border rounded-4 overflow-hidden mb-4 shadow-sm bg-white">\n<table class="table table-bordered mb-0 align-middle text-center">\n<thead class="bg-light text-dark small fw-bold">\n<tr style="background:#f8fafc;">\n<th class="py-3 px-4 border-0">CODE</th>\n<th class="py-3 border-0 text-start">SUBJECT DESCRIPTION</th>\n<th class="py-3 border-0">MAX</th>\n<th class="py-3 px-4 border-0">OBTAINED</th>\n</tr>\n</thead>\n<tbody>\n<tr><td>CORE 01</td><td class="text-start fw-medium">Core Subject Description</td><td>100</td><td class="fw-bold">[SUBJECT_1]</td></tr>\n</tbody>\n<tfoot>\n<tr class="h5 mb-0">\n<td colspan="3" class="text-end py-3 px-4">TOTAL Marks</td>\n<td class="text-primary py-3 px-4">[TOTAL_MARKS] / 500</td>\n</tr>\n</tfoot>\n</table>\n</div>`,
        header: `<div class="p-5 text-center text-white rounded-4 mb-4" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">\n<h1 class="fw-black text-uppercase mb-1" style="letter-spacing:2px;">EXAMINATION BOARD</h1>\n<p class="opacity-75 small fw-bold text-uppercase mb-0">Academic Mark Statement</p>\n</div>`,
        signature: `<div class="mt-5 pt-4 d-flex justify-content-between align-items-end">\n<div class="text-center">[VERIFICATION_QR]</div>\n<div class="text-center px-5" style="border-top:1px solid #ddd;">\n<h6 class="fw-bold mb-0">Controller of Exams</h6>\n</div>\n</div>`
    };

    function insertSnippet(type) {
        editor.insert(snippets[type]);
        updatePreview();
    }
</script>
@endsection

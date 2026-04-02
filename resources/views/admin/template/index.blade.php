@extends('layouts.app')

@section('title', 'Result Marksheet Designer | Admin Panel')

@section('content')
<div class="row animate-fade-in g-2">
    <!-- Page Header & Global Actions -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-white p-2 px-3 rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 shadow-hover border-top border-3 border-primary">
            <div>
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                    <i class="fas fa-magic text-primary me-2"></i>
                    <span>Result Designer <span class="badge bg-primary bg-opacity-10 text-primary fw-normal ms-2 px-2 py-1" style="font-size: 0.65rem;">ENT-V2</span></span>
                </h6>
                <p class="text-muted mb-0" style="font-size: 0.7rem;">Professional HTML template builder for custom marksheets.</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.template.reset') }}" method="POST" onsubmit="return confirm('Reset to default?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger border-1 rounded-2 px-2 py-1 fw-bold text-uppercase" style="font-size: 10px;">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                </form>
                <button type="submit" form="template-form" class="btn btn-primary rounded-2 px-3 py-1 fw-bold text-uppercase shadow-sm" style="font-size: 10px;">
                    <i class="fas fa-save me-1"></i> Save
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="col-12 animate-fade-in">
            <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-0 small">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Source Code Editor Card -->
    <div class="col-lg-7 d-flex flex-column">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white flex-grow-1 d-flex flex-column">
            <div class="card-header bg-white border-bottom-0 pt-2 px-2 pb-0 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark" style="font-size: 0.75rem;">Source Editor</span>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-2 px-2 py-0 fw-bold text-primary dropdown-toggle d-flex align-items-center gap-1 shadow-sm" type="button" data-bs-toggle="dropdown" style="font-size: 10px;">
                        <i class="fas fa-plus-circle"></i> Insert
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-1 rounded-3 mt-1">
                        <li><a class="dropdown-item py-1 rounded-2" href="javascript:void(0)" onclick="insertSnippet('table')" style="font-size: 0.7rem;"><i class="fas fa-table me-2"></i> marks Table</a></li>
                        <li><a class="dropdown-item py-1 rounded-2" href="javascript:void(0)" onclick="insertSnippet('header')" style="font-size: 0.7rem;"><i class="fas fa-id-card me-2 text-primary"></i> Academic Header</a></li>
                        <li><a class="dropdown-item py-1 rounded-2" href="javascript:void(0)" onclick="insertSnippet('table')" style="font-size: 0.7rem;"><i class="fas fa-table me-2 text-primary"></i> marks Table</a></li>
                        <li><a class="dropdown-item py-1 rounded-2" href="javascript:void(0)" onclick="insertSnippet('footer')" style="font-size: 0.7rem;"><i class="fas fa-pen-nib me-2 text-primary"></i> Official Footer</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-2 pt-2 d-flex flex-column flex-grow-1">
                <div id="editor-container" style="flex-grow: 1; min-height: 400px; width: 100%; border: 1px solid #eef2f7; border-radius: 8px;">{{ $settings->result_template }}</div>
                <form id="template-form" action="{{ route('admin.template.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="result_template" id="result_template_input">
                </form>
            </div>
        </div>
    </div>

    <!-- Right Side -->
    <div class="col-lg-5">
        <div class="d-flex flex-column gap-3 h-100">
            <!-- Matrix Card -->
            <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom-0 pt-2 px-2 pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="fw-bold text-dark" style="font-size: 0.75rem;">Requirements Matrix</span>
                        <div class="d-flex align-items-center gap-2">
                             <span id="progress-percent" class="fw-black text-primary" style="font-size: 0.8rem;">0%</span>
                             <div class="progress" style="width: 60px; height: 4px; border-radius: 100px; background: #f1f5f9;">
                                <div id="global-progress" class="progress-bar bg-primary" role="progressbar" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-group mb-1 border-0 shadow-sm rounded-2 overflow-hidden bg-light">
                        <span class="input-group-text bg-transparent border-0 ps-2 py-0"><i class="fas fa-search text-muted" style="font-size: 0.6rem;"></i></span>
                        <input type="text" id="matrix-search" class="form-control bg-transparent border-0 py-1" style="font-size: 11px;" placeholder="Search fields..." onkeyup="filterMatrix()">
                    </div>
                </div>

                <div class="card-body p-1 pt-0">
                    <div id="matrix-body" class="p-2" style="max-height: 300px; overflow-y: auto;">
                        <div class="matrix-grid">
                            <div class="matrix-category mb-2" id="cat-core">
                                <span class="fw-bold text-uppercase text-primary" style="font-size: 0.6rem;">Mandatory Core</span>
                                <div class="row row-cols-3 g-1 mt-1" id="grid-core"></div>
                            </div>
                            <div class="matrix-category mb-2" id="cat-profile">
                                <span class="fw-bold text-uppercase text-secondary" style="font-size: 0.6rem;">Student Details</span>
                                <div class="row row-cols-3 g-1 mt-1" id="grid-profile"></div>
                            </div>
                            <div class="matrix-category mb-2" id="cat-subjects">
                                <span class="fw-bold text-uppercase text-info" style="font-size: 0.65rem;">Subject Data (1-20)</span>
                                <div class="row row-cols-3 g-1 mt-1" id="grid-subjects"></div>
                            </div>
                            <div class="matrix-category" id="cat-security">
                                <span class="fw-bold text-uppercase text-warning" style="font-size: 0.6rem;">Security</span>
                                <div class="row row-cols-3 g-1 mt-1" id="grid-security"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden flex-grow-1 bg-white">
                <div class="card-header bg-white border-bottom-0 pt-2 px-2 pb-0 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark" style="font-size: 0.75rem;">Live Canvas</span>
                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25" style="font-size: 0.6rem;">SYNCING</span>
                </div>
                <div class="card-body p-0 pt-1">
                    <iframe id="preview-iframe" style="width: 100%; height: 350px; border: none; background: white;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Micro Reference Tags -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-3 bg-white p-2">
            <div class="d-flex flex-wrap gap-1">
                @foreach(['[ROLL_NUMBER]', '[STUDENT_NAME]', '[FATHER_NAME]', '[COURSE]', '[SUBJECT_1]', '[SUBJECT_2]', '[SUBJECT_3]', '[TOTAL_MARKS]', '[STATUS]', '[VERIFICATION_QR]'] as $tag)
                <div class="micro-chip py-0 px-2 border rounded-pill bg-light small text-muted pointer" onclick="copyTag('{{ $tag }}')" style="font-size: 10px;">{{ $tag }}</div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .display-tag { padding: 4px 6px; border-radius: 4px; border: 1px solid #f1f5f9; background: #fff; line-height: 1; transition: all 0.2s; position: relative;}
    .display-tag.active { border-color: #10b981; background: #f0fdf4; color: #10b981; }
    .display-tag.missing { opacity: 0.5; }
    .display-tag label { font-size: 8px; margin-bottom: 0px; color: #94a3b8; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;}
    .display-tag .tag-text { font-size: 9px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;}
    .display-tag i { position: absolute; right: 4px; top: 50%; transform: translateY(-50%); font-size: 8px; }
    
    #matrix-body::-webkit-scrollbar { width: 3px; }
    #matrix-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .pointer { cursor: pointer; }
    .micro-chip:hover { color: var(--primary-color) !important; border-color: var(--primary-color) !important; background: #fff !important; }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
<script>
    const matrixConfiguration = {
        core: [
            { tag: 'ROLL_NUMBER', label: 'Roll ID' },
            { tag: 'STUDENT_NAME', label: 'Name' },
            { tag: 'TOTAL_MARKS', label: 'Total' },
            { tag: 'STATUS', label: 'Status' },
            { tag: 'VERIFICATION_QR', label: 'QR' }
        ],
        profile: [
            { tag: 'FATHER_NAME', label: 'Father' },
            { tag: 'COURSE', label: 'Course' },
            { tag: 'DOB', label: 'DOB' },
            { tag: 'ACADEMIC_YEAR', label: 'Year' },
            { tag: 'SEMESTER', label: 'Sem' }
        ],
        subjects: Array.from({length: 15}, (_, i) => ({ tag: `SUBJECT_${i+1}`, label: `S-${i+1}` })),
        security: [
            { tag: 'DECLARED_DATE', label: 'Date' },
            { tag: 'TRACKING_ID', label: 'Audit ID' }
        ]
    };

    function renderMatrix() {
        Object.keys(matrixConfiguration).forEach(cat => {
            const grid = document.getElementById(`grid-${cat}`);
            matrixConfiguration[cat].forEach(item => {
                const div = document.createElement('div');
                div.className = 'col';
                div.innerHTML = `
                    <div class="display-tag missing shadow-xs" data-tag-box="${item.tag}">
                        <label>${item.label}</label>
                        <span class="tag-text">${item.tag}</span>
                        <i class="fas fa-circle-notch fa-spin opacity-25"></i>
                    </div>
                `;
                grid.appendChild(div);
            });
        });
    }
    renderMatrix();

    const editor = ace.edit("editor-container");
    editor.setTheme("ace/theme/tomorrow");
    editor.session.setMode("ace/mode/html");
    editor.setOptions({ fontPadding: 5, fontSize: "12px", showPrintMargin: false, wrap: true });

    function filterMatrix() {
        const query = document.getElementById('matrix-search').value.toLowerCase();
        document.querySelectorAll('.matrix-category').forEach(cat => {
            let catHasResults = false;
            cat.querySelectorAll('.col').forEach(col => {
                const text = col.innerText.toLowerCase();
                if (text.includes(query)) {
                    col.classList.remove('d-none');
                    catHasResults = true;
                } else {
                    col.classList.add('d-none');
                }
            });
            cat.classList.toggle('d-none', !catHasResults);
        });
    }

    const iframe = document.getElementById('preview-iframe');
    const templateInput = document.getElementById('result_template_input');

    function updatePreview() {
        const content = editor.getValue();
        templateInput.value = content;
        
        let totalTags = 0; let foundTags = 0;
        Object.keys(matrixConfiguration).forEach(cat => {
            matrixConfiguration[cat].forEach(item => {
                totalTags++;
                const box = document.querySelector(`[data-tag-box="${item.tag}"]`);
                if (content.includes(`[${item.tag}]`)) {
                    foundTags++; box.classList.remove('missing'); box.classList.add('active');
                    box.querySelector('i').className = 'fas fa-check-circle text-success';
                } else {
                    box.classList.remove('active'); box.classList.add('missing');
                    box.querySelector('i').className = 'fas fa-circle-notch fa-spin opacity-25';
                }
            });
        });

        const percent = Math.round((foundTags / totalTags) * 100);
        document.getElementById('progress-percent').innerText = `${percent}%`;
        document.getElementById('global-progress').style.width = `${percent}%`;

        let previewContent = content;
        const mockMap = {
            '\\[ROLL_NUMBER\\]': '1001552', '\\[STUDENT_NAME\\]': 'JOHN DOE', '\\[TOTAL_MARKS\\]': '398', '\\[STATUS\\]': 'PASS',
            '\\[VERIFICATION_QR\\]': '<div class="bg-light border text-center rounded-2" style="width:50px; height:50px; font-size: 8px;">QR</div>'
        };
        for (let key in mockMap) { previewContent = previewContent.replace(new RegExp(key, 'g'), mockMap[key]); }

        iframe.srcdoc = `<html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet"><style>body{padding:10px; font-family:'Outfit', sans-serif; font-size: 0.7rem;} .result-card{transform: scale(0.85); transform-origin: top center;}</style></head><body>${previewContent}</body></html>`;
    }

    editor.on('change', updatePreview);
    window.addEventListener('load', updatePreview);

    function insertSnippet(type) {
        const snippets = {
            header: `<div class="p-3 text-center text-white rounded-3 mb-3 shadow-sm" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
    <h5 class="fw-bold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 1rem;">UNIVERSITY EXAMINATION BOARD</h5>
    <p class="mb-0 opacity-75 fw-bold text-uppercase" style="font-size: 0.6rem;">OFFICIAL STATEMENT OF MARKS - 2026</p>
</div>

<div class="row g-2 mb-3 pb-2 border-bottom" style="font-size: 0.75rem;">
    <div class="col-7">
        <div class="mb-1 text-muted">NAME: <span class="text-dark fw-bold">[STUDENT_NAME]</span></div>
        <div class="mb-0 text-muted">ROLL NO: <span class="text-dark fw-bold">[ROLL_NUMBER]</span></div>
    </div>
    <div class="col-5 text-end border-start">
        <div class="mb-1 text-muted">COURSE: <span class="text-dark fw-bold">[COURSE]</span></div>
        <div class="mb-0 text-muted">FATHER: <span class="text-dark fw-bold">[FATHER_NAME]</span></div>
    </div>
</div>`,
            table: `<div class="table-responsive border rounded-3 overflow-hidden mb-3 shadow-xs bg-white">
    <table class="table table-bordered mb-0 align-middle text-center" style="font-size: 0.75rem;">
        <thead class="bg-light text-dark fw-bold">
            <tr style="background: #f8fafc;">
                <th class="py-1 px-2 border-0">SUBJECT</th>
                <th class="py-1 border-0">MAX</th>
                <th class="py-1 px-2 border-0">OBTAINED</th>
            </tr>
        </thead>
        <tbody>
            <tr><td class="text-start px-2 small">Subject 01</td><td>100</td><td class="fw-bold">[SUBJECT_1]</td></tr>
            <tr><td class="text-start px-2 small">Subject 02</td><td>100</td><td class="fw-bold">[SUBJECT_2]</td></tr>
            <tr><td class="text-start px-2 small">Subject 03</td><td>100</td><td class="fw-bold">[SUBJECT_3]</td></tr>
        </tbody>
        <tfoot class="bg-light fw-bold">
            <tr>
                <td colspan="2" class="text-end py-1 px-2 opacity-75">GRAND TOTAL</td>
                <td class="text-primary py-1 px-2">[TOTAL_MARKS] / 500</td>
            </tr>
            <tr>
                <td colspan="2" class="text-end py-1 px-2 opacity-75">RESULT STATUS</td>
                <td class="[STATUS_CLASS] py-1 px-2">[STATUS]</td>
            </tr>
        </tfoot>
    </table>
</div>`,
            footer: `<div class="mt-4 pt-2 d-flex justify-content-between align-items-end" style="font-size: 0.7rem;">
    <div class="text-center">
        <div class="mb-1">[VERIFICATION_QR]</div>
        <div class="text-muted fw-bold" style="font-size: 0.55rem;">ID: [TRACKING_ID]</div>
    </div>
    <div class="text-end">
        <div class="mb-3">
            <div class="fw-bold mb-0">Date: [DECLARED_DATE]</div>
        </div>
        <div class="pt-1" style="border-top: 1px dashed #cbd5e1; min-width: 150px;">
            <div class="fw-bold text-dark text-uppercase mb-0">Controller of Exams</div>
            <div class="text-muted" style="font-size: 0.55rem;">Authorized Digital Signed</div>
        </div>
    </div>
</div>`
        };

        if(snippets[type]) {
            editor.insert(snippets[type]);
            updatePreview();
        }
    }

    function copyTag(tag) {
        navigator.clipboard.writeText(tag).then(() => {
            const original = event.target.innerText;
            event.target.innerText = 'Copied!';
            setTimeout(() => event.target.innerText = original, 800);
        });
    }
</script>
@endsection

@extends('layouts.app')

@section('title', 'Result Marksheet Designer')

@section('content')
<div class="row animate-fade-in g-3">


    @if(session('success'))
        <div class="col-12 animate-fade-in">
            <div class="alert alert-success border-0 shadow-xs rounded-3 py-2 px-3 mb-0" style="font-size: 0.75rem;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Left Main Workbench (Col-8) -->
    <div class="col-lg-8 animate-fade-in">
        <div class="d-flex flex-column gap-3 h-100">
            
            <!-- Source Development Editor (Dark Mode) -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-dark">
                <div class="card-header border-bottom-0 pt-1 px-3 pb-1 d-flex justify-content-between align-items-center" style="background: #111;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-black text-white text-uppercase opacity-50" style="font-size: 0.65rem; letter-spacing: 1px;">SOURCE EDITOR</span>
                        <!-- Quick Controls -->
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.template.reset') }}" method="POST" onsubmit="return confirm('Reset to default?')">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger p-0 border-0 text-decoration-none fw-bold text-uppercase" style="font-size: 9px;">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </form>
                            <button type="submit" form="template-form" class="btn btn-link text-primary p-0 border-0 text-decoration-none fw-bold text-uppercase" style="font-size: 9px;">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-sm btn-dark border-secondary border-opacity-25 rounded-2 px-2 py-0 fw-bold text-light dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown" style="font-size: 10px; background: #222;">
                            <i class="fas fa-plus-circle text-primary"></i> Insert
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-1 rounded-3 mt-1 bg-dark" style="font-size: 0.7rem;">
                            <li><a class="dropdown-item py-1 rounded-2 text-light" href="javascript:void(0)" onclick="insertSnippet('header')"><i class="fas fa-id-card me-2 text-primary opacity-75"></i> Academic Header</a></li>
                            <li><a class="dropdown-item py-1 rounded-2 text-light" href="javascript:void(0)" onclick="insertSnippet('table')"><i class="fas fa-table me-2 text-primary opacity-75"></i> Marks Table</a></li>
                            <li><a class="dropdown-item py-1 rounded-2 text-light" href="javascript:void(0)" onclick="insertSnippet('footer')"><i class="fas fa-pen-nib me-2 text-primary opacity-75"></i> Official Footer</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-2 pt-2">
                    <div id="editor-container" style="height: 380px; width: 100%; border: 1px solid #eef2f7; border-radius: 8px;">{{ $settings->result_template }}</div>
                    <form id="template-form" action="{{ route('admin.template.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="result_template" id="result_template_input">
                    </form>
                </div>
            </div>

            <!-- Real-time Live Canvas -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                <div class="card-header bg-white border-bottom-0 pt-1 px-3 pb-1 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-black text-dark text-uppercase opacity-75" style="font-size: 0.65rem; letter-spacing: 1px;">PRODUCTION PREVIEW</span>
                        <button class="btn btn-link text-primary p-0 border-0 text-decoration-none fw-bold text-uppercase d-flex align-items-center gap-1" onclick="openModalPreview()" style="font-size: 9px;">
                            <i class="fas fa-expand-alt"></i> Full View
                        </button>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 0.55rem; letter-spacing: 1px;">
                        <span class="pulse-dot me-1"></span> LIVE-SYNC ACTIVE
                    </span>
                </div>
                <div class="card-body p-0 pt-1" style="background: #f8fafc;">
                    <iframe id="preview-iframe" style="width: 100%; height: 500px; border: none; background: #fff;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Interaction Matrix (Col-4) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 bg-white h-100 flex-column d-flex">
            <div class="card-header bg-white border-bottom-0 pt-2 px-3 pb-0">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-black text-dark text-uppercase opacity-75" style="font-size: 0.7rem; letter-spacing: 1px;">Data Matrix</span>
                    <div class="d-flex align-items-center gap-2">
                         <span id="progress-percent" class="fw-black text-primary" style="font-size: 0.85rem;">0%</span>
                         <div class="progress" style="width: 60px; height: 5px; border-radius: 100px; background: #f1f5f9;">
                            <div id="global-progress" class="progress-bar bg-primary shadow-sm" role="progressbar" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                <!-- Mini Search -->
                <div class="input-group mb-1 border-0 shadow-xs rounded-2 overflow-hidden bg-light">
                    <span class="input-group-text bg-transparent border-0 ps-2 py-0"><i class="fas fa-search text-muted" style="font-size: 0.65rem;"></i></span>
                    <input type="text" id="matrix-search" class="form-control bg-transparent border-0 py-1" style="font-size: 11px;" placeholder="Filter tags..." onkeyup="filterMatrix()">
                </div>
            </div>

            <div class="card-body p-0 flex-grow-1 overflow-hidden d-flex flex-column">
                <div id="matrix-body" class="p-3 bg-light bg-opacity-50 flex-grow-1" style="max-height: 1000px; overflow-y: auto;">
                    <div class="matrix-grid">
                        <div class="matrix-category mb-2" id="cat-core">
                            <span class="fw-bold text-uppercase text-primary d-flex align-items-center gap-2" style="font-size: 0.6rem;">
                                <i class="fas fa-star shadow-sm"></i> MANDATORY CORE
                            </span>
                            <div class="row row-cols-2 g-1 mt-1" id="grid-core"></div>
                        </div>
                        <div class="matrix-category mb-2" id="cat-profile">
                            <span class="fw-bold text-uppercase text-secondary d-flex align-items-center gap-2" style="font-size: 0.6rem;">
                                <i class="fas fa-id-card-clip shadow-sm"></i> CANDIDATE INFO
                            </span>
                            <div class="row row-cols-2 g-1 mt-1" id="grid-profile"></div>
                        </div>
                        <div class="matrix-category mb-2" id="cat-subjects">
                            <span class="fw-bold text-uppercase text-info d-flex align-items-center gap-2" style="font-size: 0.6rem;">
                                <i class="fas fa-book-open shadow-sm"></i> SUBJECT DATA (1-20)
                            </span>
                            <div class="row row-cols-2 g-1 mt-1" id="grid-subjects"></div>
                        </div>
                        <div class="matrix-category" id="cat-security">
                            <span class="fw-bold text-uppercase text-warning d-flex align-items-center gap-2" style="font-size: 0.6rem;">
                                <i class="fas fa-shield-halved shadow-sm"></i> SECURITY
                            </span>
                            <div class="row row-cols-2 g-1 mt-1" id="grid-security"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer References Chips -->
                <div class="p-2 border-top bg-white">
                    <div class="d-flex flex-wrap gap-1">
                        @foreach(['[ROLL_NUMBER]', '[STUDENT_NAME]', '[VERIFICATION_QR]', '[STATUS]'] as $tag)
                        <div class="micro-chip text-muted pointer" onclick="copyTag('{{ $tag }}')" style="font-size: 9px;">{{ $tag }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .display-tag { padding: 5px 8px; border-radius: 5px; border: 1px solid #eef2f7; background: #fff; line-height: 1; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); position: relative; cursor: pointer; }
    .display-tag:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    .display-tag.active { border-color: #10b981; background: #f0fdf4; color: #10b981; }
    .display-tag.missing { opacity: 0.55; }
    .display-tag label { font-size: 8px; margin-bottom: 2px; color: #94a3b8; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 90%; font-weight: 700;}
    .display-tag .tag-text { font-size: 10px; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; letter-spacing: -0.2px;}
    .display-tag i { position: absolute; right: 6px; top: 50%; transform: translateY(-50%); font-size: 8px; }
    
    #matrix-body::-webkit-scrollbar { width: 3px; }
    #matrix-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .pointer { cursor: pointer; }
    .micro-chip { padding: 1px 6px; border: 1px solid #f1f5f9; border-radius: 4px; background: #f8fafc; transition: 0.2s; }
    .micro-chip:hover { border-color: #4f46e5; color: #4f46e5 !important; background: #fff; }

    .pulse-dot { width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block; animation: pulse-green 2s infinite; }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>
@endsection

<!-- Full Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true" style="backdrop-filter: blur(5px);">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" style="max-width: 90%;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 py-2">
                <h6 class="modal-title fw-bold text-uppercase mb-0" style="font-size: 0.7rem; letter-spacing: 1.5px;">
                    <i class="fas fa-desktop me-2 text-primary"></i> Production Full View
                </h6>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.6rem;"></button>
            </div>
            <div class="modal-body p-0 bg-light">
                <iframe id="modal-preview-iframe" style="width: 100%; height: 82vh; border: none; background: #fff;"></iframe>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
<script>
    function openModalPreview() {
        const modalIframe = document.getElementById('modal-preview-iframe');
        const mainIframe = document.getElementById('preview-iframe');
        modalIframe.srcdoc = mainIframe.srcdoc;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

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
            { tag: 'GENDER', label: 'Gender' },
            { tag: 'ACADEMIC_YEAR', label: 'Year' },
            { tag: 'SEMESTER', label: 'Sem' },
            { tag: 'ENROLLMENT_NO', label: 'Enroll No' }
        ],
        subjects: Array.from({length: 20}, (_, i) => ({ tag: `SUBJECT_${i+1}`, label: `Subject Marks ${i+1}` })),
        security: [
            { tag: 'DECLARED_DATE', label: 'Date' },
            { tag: 'TRACKING_ID', label: 'Audit ID' },
            { tag: 'STATUS_CLASS', label: 'CSS Class' },
            { tag: 'VERIFIED_AT', label: 'Sync Time' }
        ]
    };

    function renderMatrix() {
        Object.keys(matrixConfiguration).forEach(cat => {
            const grid = document.getElementById(`grid-${cat}`);
            matrixConfiguration[cat].forEach(item => {
                const div = document.createElement('div');
                div.className = 'col animate-fade-in';
                div.innerHTML = `
                    <div class="display-tag missing shadow-xs" data-tag-box="${item.tag}" onclick="insertTag('${item.tag}')">
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

    function insertTag(tag) {
        editor.insert(`[${tag}]`);
        editor.focus();
        updatePreview();
        
        // Brief visual success on the tag box
        const box = document.querySelector(`[data-tag-box="${tag}"]`);
        if(box) {
            const originalColor = box.style.borderColor;
            box.style.borderColor = "#4f46e5";
            setTimeout(() => box.style.borderColor = originalColor, 300);
        }
    }

    const editor = ace.edit("editor-container");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/html");
    editor.setOptions({ fontPadding: 5, fontSize: "11px", showPrintMargin: false, wrap: true, highlightActiveLine: true });

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
            '\\[ROLL_NUMBER\\]': '1001-552', '\\[STUDENT_NAME\\]': 'RAJESH KUMAR', '\\[FATHER_NAME\\]': 'SH. MOHAN DAS', 
            '\\[COURSE\\]': 'B.TECH (CSE)', '\\[TOTAL_MARKS\\]': '398', '\\[STATUS\\]': 'PASS', '\\[STATUS_CLASS\\]': 'text-success', 
            '\\[DECLARED_DATE\\]': '2026-06-15', '\\[VERIFIED_AT\\]': '2026-06-20 14:30', '\\[TRACKING_ID\\]': 'RT-9982-XYZ',
            '\\[VERIFICATION_QR\\]': '<div class="bg-light border text-center rounded-2" style="width:40px; height:40px; font-size: 8px; border: 1px dashed #bbb !important;">QR</div>',
            '\\[SUBJECT_1\\]': '85', '\\[SUBJECT_2\\]': '78', '\\[SUBJECT_3\\]': '92', '\\[SUBJECT_4\\]': '74', '\\[SUBJECT_5\\]': '69'
        };
        for (let key in mockMap) { previewContent = previewContent.replace(new RegExp(key, 'g'), mockMap[key]); }

        iframe.srcdoc = `<html><head><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@600;800&display=swap" rel="stylesheet"><style>body{padding:20px; font-family:'Inter', sans-serif; font-size: 0.75rem; background: #f8fafc; color: #1e293b;} .result-card{transform: scale(0.9); transform-origin: top center; margin: 0 auto; box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1) !important;}</style></head><body>${previewContent}</body></html>`;
    }

    function openModalPreview() {
        const modalIframe = document.getElementById('modal-preview-iframe');
        modalIframe.srcdoc = iframe.srcdoc;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
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
                <th class="py-1 px-2 border-0 text-start ps-3">SUBJECT</th>
                <th class="py-1 border-0">MAX</th>
                <th class="py-1 px-2 border-0">OBTAINED</th>
            </tr>
        </thead>
        <tbody>
            <tr><td class="text-start px-3 small">Subject 01</td><td>100</td><td class="fw-bold">[SUBJECT_1]</td></tr>
            <tr><td class="text-start px-3 small">Subject 02</td><td>100</td><td class="fw-bold">[SUBJECT_2]</td></tr>
        </tbody>
        <tfoot class="bg-light fw-bold">
            <tr>
                <td colspan="2" class="text-end py-1 px-3 opacity-75">GRAND TOTAL</td>
                <td class="text-primary py-1 px-2">[TOTAL_MARKS] / 500</td>
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
            if (type === 'header') {
                editor.session.insert({row: 0, column: 0}, snippets[type] + "\n\n");
                editor.gotoLine(1, 0);
            } else if (type === 'footer') {
                const lastLine = editor.session.getLength();
                editor.session.insert({row: lastLine, column: 0}, "\n\n" + snippets[type]);
                editor.scrollToLine(lastLine + 5);
            } else {
                editor.insert(snippets[type]);
            }
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

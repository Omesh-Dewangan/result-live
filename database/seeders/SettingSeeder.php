<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultTemplate = <<<'HTML'
<div class="result-card shadow-lg border-0 rounded-4 overflow-hidden bg-white mb-4" style="max-width: 900px; margin: 0 auto; font-family: 'Outfit', sans-serif;">
    <!-- Header -->
    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
        <h2 class="fw-bold mb-1 text-uppercase tracking-wider" style="letter-spacing: 2px;">University Examination Board</h2>
        <p class="mb-0 opacity-75 small fw-bold">OFFICIAL STATEMENT OF MARKS - ANNUAL EXAMINATION 2026</p>
    </div>

    <div class="p-4 p-md-5">
        <!-- Student Details Grid -->
        <div class="row g-4 mb-5 pb-4 border-bottom">
            <div class="col-md-7">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Candidate Name</div>
                        <div class="h5 fw-bold mb-0 text-dark">[STUDENT_NAME]</div>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-2 me-3">
                        <i class="fas fa-id-badge fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Roll Number</div>
                        <div class="h5 fw-bold mb-0 text-dark">[ROLL_NUMBER]</div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-3 p-2 me-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold">Father's Name</div>
                        <div class="h5 fw-bold mb-0 text-dark">[FATHER_NAME]</div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card bg-light border-0 rounded-4 p-3 h-100 d-flex flex-column justify-content-center">
                    <div class="text-muted small text-uppercase fw-bold text-center mb-1">Course / Programme</div>
                    <div class="h6 fw-bold mb-0 text-center text-primary text-uppercase">[COURSE]</div>
                </div>
            </div>
        </div>

        <!-- Marks Table -->
        <div class="table-responsive rounded-4 border overflow-hidden mb-5">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light text-dark">
                    <tr>
                        <th class="ps-4 py-3 border-0">Subject Code</th>
                        <th class="py-3 border-0">Subject description</th>
                        <th class="py-3 border-0 text-center">Max Marks</th>
                        <th class="pe-4 py-3 border-0 text-center">Marks Obtained</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <tr>
                        <td class="ps-4">SUB-101</td>
                        <td class="fw-medium text-dark">Core Subject 01</td>
                        <td class="text-center">100</td>
                        <td class="pe-4 text-center fw-bold">[SUBJECT_1]</td>
                    </tr>
                    <tr>
                        <td class="ps-4">SUB-102</td>
                        <td class="fw-medium text-dark">Core Subject 02</td>
                        <td class="text-center">100</td>
                        <td class="pe-4 text-center fw-bold">[SUBJECT_2]</td>
                    </tr>
                    <tr>
                        <td class="ps-4">SUB-103</td>
                        <td class="fw-medium text-dark">Core Subject 03</td>
                        <td class="text-center">100</td>
                        <td class="pe-4 text-center fw-bold">[SUBJECT_3]</td>
                    </tr>
                    <tr>
                        <td class="ps-4">SUB-104</td>
                        <td class="fw-medium text-dark">Elective Subject 04</td>
                        <td class="text-center">100</td>
                        <td class="pe-4 text-center fw-bold">[SUBJECT_4]</td>
                    </tr>
                    <tr>
                        <td class="ps-4">SUB-105</td>
                        <td class="fw-medium text-dark">Elective Subject 05</td>
                        <td class="text-center">100</td>
                        <td class="pe-4 text-center fw-bold">[SUBJECT_5]</td>
                    </tr>
                </tbody>
                <tfoot class="bg-light">
                    <tr class="fw-bold h5 mb-0">
                        <td colspan="2" class="ps-4 py-3 text-end">GRAND TOTAL</td>
                        <td class="py-3 text-center">500</td>
                        <td class="pe-4 py-3 text-center text-primary fw-black">[TOTAL_MARKS]</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer / Status -->
        <div class="row align-items-center pt-4">
            <div class="col-sm-6 text-center text-sm-start mb-4 mb-sm-0">
                <div class="display-6 fw-black mb-1 [STATUS_CLASS]">[STATUS]</div>
                <div class="text-muted small">Result Declared On: <strong>[DECLARED_DATE]</strong></div>
            </div>
            <div class="col-sm-6">
                <div class="d-flex justify-content-center justify-content-sm-end align-items-center">
                    <div class="me-4 text-center">
                         <div class="mb-2">[VERIFICATION_QR]</div>
                         <div class="text-muted" style="font-size: 10px;">SCAN TO VERIFY</div>
                    </div>
                    <div class="text-center px-3 border-start py-2">
                        <div class="mb-2"><img src="https://via.placeholder.com/120x40/ffffff/000000?text=SIGNATURE" alt="Sign" style="max-height: 40px; filter: contrast(150%) grayscale(100%);"></div>
                        <div class="fw-bold small text-dark border-top pt-1">CONTROLLER OF EXAMS</div>
                        <div class="text-muted" style="font-size: 9px;">AUTHENTIC DIGITAL COPY</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Footer -->
    <div class="bg-light p-3 border-top text-center">
        <p class="mb-0 text-muted" style="font-size: 11px;">
            <i class="fas fa-lock me-1"></i> This is a computer generated mark-statement. Authenticity can be verified using the QR code or visiting the official portal.
            <br>Generated on: [VERIFIED_AT] | Tracking ID: [TRACKING_ID]
        </p>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .tracking-wider { letter-spacing: 0.1em; }
    .text-success { color: #10b981 !important; }
    .text-danger { color: #ef4444 !important; }
    @media print {
        .result-card { shadow: none !important; margin: 0 !important; width: 100% !important; border: 1px solid #ddd !important; }
        .bg-light { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
    }
</style>
HTML;

        Setting::updateOrCreate(
            ['id' => 1],
            [
                'result_template' => $defaultTemplate,
                'result_live' => true,
                'login_active' => true,
            ]
        );
    }
}

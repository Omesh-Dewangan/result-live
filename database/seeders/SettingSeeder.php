<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultTemplate = <<<'HTML'
<div class="result-card shadow-lg border-0 rounded-3 overflow-hidden bg-white mb-4" style="max-width: 800px; margin: 0 auto; font-family: 'Inter', sans-serif; border: 1px solid #e2e8f0 !important; color: #1e293b;">
    <!-- Header: University Brand -->
    <div class="p-3 text-center text-white" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); font-family: 'Outfit', sans-serif;">
        <h4 class="fw-bold mb-0 text-uppercase tracking-wider" style="letter-spacing: 1.5px;">University Examination Board</h4>
        <p class="mb-0 opacity-75 fw-bold" style="font-size: 0.65rem;">OFFICIAL STATEMENT OF MARKS - ANNUAL 2026</p>
    </div>

    <div class="p-4">
        <!-- Student Identity Grid -->
        <div class="row g-3 mb-4 pb-3 border-bottom" style="font-size: 0.8rem;">
            <div class="col-8">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.6rem;">Candidate Name</div>
                        <div class="fw-bold mb-0 text-dark">[STUDENT_NAME]</div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-2 p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.6rem;">Roll Number</div>
                        <div class="fw-bold mb-0 text-dark">[ROLL_NUMBER]</div>
                    </div>
                </div>
            </div>
            <div class="col-4 text-end border-start">
                <div class="mb-2">
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.6rem;">Course / Program</div>
                    <div class="fw-bold text-primary">[COURSE]</div>
                </div>
                <div>
                    <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.6rem;">Father Name</div>
                    <div class="fw-bold">[FATHER_NAME]</div>
                </div>
            </div>
        </div>

        <!-- Performance / Marks Table -->
        <div class="table-responsive rounded-3 border overflow-hidden mb-4 shadow-xs">
            <table class="table table-hover mb-0 align-middle text-center" style="font-size: 0.75rem;">
                <thead class="bg-light text-dark">
                    <tr style="background: #f8fafc;">
                        <th class="ps-3 py-2 border-0 text-start">Subject Description</th>
                        <th class="py-2 border-0">Max</th>
                        <th class="pe-3 py-2 border-0">Obtained</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <tr><td class="ps-3 text-start">Core Subject 01</td><td>100</td><td class="pe-3 fw-bold">[SUBJECT_1]</td></tr>
                    <tr><td class="ps-3 text-start">Core Subject 02</td><td>100</td><td class="pe-3 fw-bold">[SUBJECT_2]</td></tr>
                    <tr><td class="ps-3 text-start">Core Subject 03</td><td>100</td><td class="pe-3 fw-bold">[SUBJECT_3]</td></tr>
                    <tr><td class="ps-3 text-start">Elective Subject 04</td><td>100</td><td class="pe-3 fw-bold">[SUBJECT_4]</td></tr>
                    <tr><td class="ps-3 text-start">Elective Subject 05</td><td>100</td><td class="pe-3 fw-bold">[SUBJECT_5]</td></tr>
                </tbody>
                <tfoot class="bg-light fw-bold" style="background: #f8fafc;">
                    <tr>
                        <td colspan="2" class="ps-3 py-2 text-end opacity-75">GRAND TOTAL</td>
                        <td class="pe-3 py-2 text-primary fw-black" style="font-size: 0.9rem;">[TOTAL_MARKS] / 500</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Validation & Signature Section -->
        <div class="row align-items-end pt-2">
            <div class="col-6 text-center text-sm-start">
                <div class="h3 fw-black [STATUS_CLASS] mb-1">[STATUS]</div>
                <div class="text-muted small" style="font-size: 0.6rem;">Declared On: <strong>[DECLARED_DATE]</strong></div>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="me-3 text-center">
                         <div class="mb-1">[VERIFICATION_QR]</div>
                         <div class="text-muted fw-bold" style="font-size: 0.5rem;">TRACK ID: [TRACKING_ID]</div>
                    </div>
                    <div class="text-center px-2 border-start py-1" style="min-width: 120px;">
                        <div class="fw-bold small text-dark border-top pt-1" style="font-size: 0.7rem;">CONTROLLER</div>
                        <div class="text-muted" style="font-size: 0.5rem;">DIGITAL SIGNED</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Branding -->
    <div class="bg-light p-2 border-top text-center" style="font-size: 0.55rem;">
        <p class="mb-0 text-muted">
            <i class="fas fa-shield-alt me-1 text-primary"></i> This is a computer generated mark-statement. Authenticity can be verified via QR or [TRACKING_ID].
        </p>
    </div>
</div>

<style>
    .fw-black { font-weight: 900; }
    .text-success { color: #059669 !important; }
    .text-danger { color: #dc2626 !important; }
    @media print {
        .result-card { border: 1px solid #ddd !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; }
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

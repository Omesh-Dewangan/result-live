<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Adding result_template column...\n";
try {
    if (!Schema::hasColumn('settings', 'result_template')) {
        Schema::table('settings', function (Blueprint $table) {
            $table->longText('result_template')->nullable();
        });
        echo "Column added successfully!\n";
    } else {
        echo "Column already exists!\n";
    }

    $defaultTemplate = '
    <div class="mx-auto shadow-sm border rounded-3 bg-white overflow-hidden" style="max-width: 850px;">
        <div class="p-4 text-center border-bottom bg-light" style="border-top: 5px solid #1e3a8a !important;">
            <div class="mb-2"><i class="fas fa-university fa-3x text-dark opacity-25"></i></div>
            <h2 class="fw-bold text-dark mb-0">UNIVERSITY EXAMINATION BOARD</h2>
            <p class="text-secondary small fw-bold mt-1 text-uppercase mb-0">Official Statement of Marks - Session 2025-26</p>
        </div>
        <div class="p-4 p-md-5">
            <div class="table-responsive mb-4">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-muted small fw-bold py-1" style="width: 25%;">ROLL NUMBER</td>
                            <td class="fw-bold py-1 text-primary">: [ROLL_NUMBER]</td>
                            <td class="text-muted small fw-bold py-1" style="width: 25%;">COURSE</td>
                            <td class="fw-bold py-1">: [COURSE]</td>
                        </tr>
                        <tr>
                            <td class="text-muted small fw-bold py-1">CANDIDATE NAME</td>
                            <td class="fw-bold py-1">: [STUDENT_NAME]</td>
                            <td class="text-muted small fw-bold py-1">FATHER\'S NAME</td>
                            <td class="fw-bold py-1">: [FATHER_NAME]</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive border rounded mb-4">
                <table class="table table-bordered mb-0 align-middle text-center">
                    <thead class="bg-light text-dark small fw-bold">
                        <tr>
                            <th class="py-2" style="width: 10%;">CODE</th>
                            <th class="py-2 text-start px-3">SUBJECT DESCRIPTION</th>
                            <th class="py-2" style="width: 15%;">MAX</th>
                            <th class="py-2" style="width: 15%;">MIN</th>
                            <th class="py-2" style="width: 20%;">OBTAINED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>101</td><td class="text-start px-3 fw-bold">Communicative English</td><td>100</td><td>33</td><td class="fw-bold">[SUBJECT_1]</td></tr>
                        <tr><td>102</td><td class="text-start px-3 fw-bold">Applied Mathematics</td><td>100</td><td>33</td><td class="fw-bold">[SUBJECT_2]</td></tr>
                        <tr><td>103</td><td class="text-start px-3 fw-bold">Engineering Physics</td><td>100</td><td>33</td><td class="fw-bold">[SUBJECT_3]</td></tr>
                        <tr><td>104</td><td class="text-start px-3 fw-bold">Engineering Chemistry</td><td>100</td><td>33</td><td class="fw-bold">[SUBJECT_4]</td></tr>
                        <tr><td>105</td><td class="text-start px-3 fw-bold">Workshop Technology</td><td>100</td><td>33</td><td class="fw-bold">[SUBJECT_5]</td></tr>
                    </tbody>
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="2" class="text-end py-3 px-3">AGGREGATE SCORE</td>
                            <td>500</td>
                            <td>165</td>
                            <td class="text-primary h5 mb-0 py-3">[TOTAL_MARKS] / 500</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="row g-3 text-center mb-4">
                <div class="col-6 col-md-4">
                    <div class="p-3 border rounded bg-light h-100">
                        <span class="text-muted small fw-bold d-block mb-1">STATUS</span>
                        <h4 class="fw-bold mb-0">[STATUS]</h4>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="p-3 border rounded bg-light h-100">
                        <span class="text-muted small fw-bold d-block mb-1">DECLARED ON</span>
                        <h5 class="fw-bold mb-0 text-dark">[DECLARED_DATE]</h5>
                    </div>
                </div>
                <div class="col-12 col-md-4 no-print">
                    <button id="printButton" class="btn btn-primary w-100 h-100 py-3 shadow-none fw-bold" style="background-color: #1e3a8a; border-color: #1e3a8a;">
                        <i class="fas fa-print me-2"></i> PRINT RESULT
                    </button>
                </div>
            </div>
            <div class="small text-muted py-3 border-top mt-5">
                <p class="mb-1"><strong>Note:</strong> This is a computer-generated marksheet for immediate information only. The Original Marksheet issued by the University should be treated as final.</p>
                <p class="mb-0">This digital record was verified on: [VERIFIED_AT]</p>
            </div>
        </div>
    </div>';

    \App\Models\Setting::query()->update(['result_template' => $defaultTemplate]);
    echo "Default template seeded successfully!\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ─── Cache TTLs ────────────────────────────────────────────────────────────
    const STATS_TTL    = 300;   // 5 minutes  — dashboard counts
    const SETTINGS_TTL = 120;   // 2 minutes  — settings row

    // ─── Cache Invalidation Helpers ────────────────────────────────────────────

    /** Clear a single result from cache. */
    private function forgetResult(string $rollNumber): void
    {
        Cache::forget('result.' . $rollNumber);
    }

    /** Clear all dashboard stat caches. */
    private function forgetStats(): void
    {
        Cache::forget('stats.total');
        Cache::forget('stats.pass');
        Cache::forget('stats.fail');
    }

    /** Clear settings cache. */
    private function forgetSettings(): void
    {
        Cache::forget('portal.settings');
    }

    /** Full cache flush — used after bulk import. */
    private function flushAllCache(): void
    {
        Cache::flush();
    }

    // ─── Controllers ───────────────────────────────────────────────────────────

    public function dashboard()
    {
        $settings = Setting::firstOrCreate([], ['result_live' => false, 'login_active' => true]);

        $totalResults = Cache::remember('stats.total', self::STATS_TTL, function () {
            return Result::count();
        });
        $passCount = Cache::remember('stats.pass', self::STATS_TTL, function () {
            return Result::where('result_status', 'Pass')->count();
        });
        $failCount = Cache::remember('stats.fail', self::STATS_TTL, function () {
            return Result::where('result_status', 'Fail')->count();
        });

        return view('admin.dashboard', compact('settings', 'totalResults', 'passCount', 'failCount'));
    }

    public function settings()
    {
        $settings = Setting::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $setting->update([
            'result_live'  => $request->boolean('result_live'),
            'login_active' => $request->boolean('login_active'),
            'result_from'  => $request->filled('result_from') ? $request->result_from : null,
            'result_to'    => $request->filled('result_to')   ? $request->result_to   : null,
            'login_from'   => $request->filled('login_from')  ? $request->login_from  : null,
            'login_to'     => $request->filled('login_to')    ? $request->login_to    : null,
        ]);

        // Invalidate settings cache so students see changes immediately
        $this->forgetSettings();

        return back()->with('success', 'Settings updated successfully');
    }

    public function updateSettingAjax(Request $request)
    {
        $setting = Setting::firstOrCreate(['id' => 1]);

        $value = $request->value;
        if ($value === "" || $value === "null") {
            $value = null;
        }

        $setting->update([$request->key => $value]);

        // Invalidate settings cache immediately
        $this->forgetSettings();

        return response()->json(['success' => true]);
    }

    public function manageResults(Request $request)
    {
        $query = Result::query();

        // ── Consolidated Counters (One Record Per Student) ────────────────────
        $query->withCount([
            'activitySummary as view_count' => fn($q) => $q->select('view_count'),
            'activitySummary as print_count' => fn($q) => $q->select('print_count')
        ]);

        if ($request->search) {
            $query->where('roll_number', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
        }
        $results = $query->latest()->paginate(15);
        return view('admin.results.index', compact('results'));
    }

    public function create()
    {
        return view('admin.results.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'roll_number' => 'required|unique:results,roll_number',
            'name'        => 'required',
            'father_name' => 'required',
            'course'      => 'required',
            'subject1'    => 'required|numeric|min:0|max:100',
            'subject2'    => 'required|numeric|min:0|max:100',
            'subject3'    => 'required|numeric|min:0|max:100',
            'subject4'    => 'required|numeric|min:0|max:100',
            'subject5'    => 'required|numeric|min:0|max:100',
        ]);

        $total  = $request->subject1 + $request->subject2 + $request->subject3 + $request->subject4 + $request->subject5;
        $status = ($request->subject1 > 33 && $request->subject2 > 33 && $request->subject3 > 33 && $request->subject4 > 33 && $request->subject5 > 33) ? 'Pass' : 'Fail';

        Result::create(array_merge($request->all(), [
            'total'         => $total,
            'result_status' => $status
        ]));

        // Invalidate stats (count changed)
        $this->forgetStats();

        return redirect()->route('admin.results.index')->with('success', 'Result added successfully');
    }

    public function edit(Result $result)
    {
        return view('admin.results.edit', compact('result'));
    }

    public function update(Request $request, Result $result)
    {
        $request->validate([
            'roll_number' => 'required|unique:results,roll_number,' . $result->id,
            'name'        => 'required',
            'father_name' => 'required',
            'course'      => 'required',
            'subject1'    => 'required|numeric|min:0|max:100',
            'subject2'    => 'required|numeric|min:0|max:100',
            'subject3'    => 'required|numeric|min:0|max:100',
            'subject4'    => 'required|numeric|min:0|max:100',
            'subject5'    => 'required|numeric|min:0|max:100',
        ]);

        $total  = $request->subject1 + $request->subject2 + $request->subject3 + $request->subject4 + $request->subject5;
        $status = ($request->subject1 > 33 && $request->subject2 > 33 && $request->subject3 > 33 && $request->subject4 > 33 && $request->subject5 > 33) ? 'Pass' : 'Fail';

        $oldRollNumber = $result->roll_number;

        $result->update(array_merge($request->all(), [
            'total'         => $total,
            'result_status' => $status
        ]));

        // Invalidate this result's cache (old and new roll number in case it changed)
        $this->forgetResult($oldRollNumber);
        if ($request->roll_number !== $oldRollNumber) {
            $this->forgetResult($request->roll_number);
        }
        // Stats may change (pass/fail count)
        $this->forgetStats();

        return redirect()->route('admin.results.index')->with('success', 'Result updated successfully');
    }

    public function importResults(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file   = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle); // roll_number, name, father_name, course, s1, s2, s3, s4, s5

        $chunkSize = 1000;
        $data      = [];

        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) < 9) continue;

            $s1 = (int)$row[4]; $s2 = (int)$row[5]; $s3 = (int)$row[6]; $s4 = (int)$row[7]; $s5 = (int)$row[8];
            $total  = $s1 + $s2 + $s3 + $s4 + $s5;
            $status = ($s1 > 33 && $s2 > 33 && $s3 > 33 && $s4 > 33 && $s5 > 33) ? 'Pass' : 'Fail';

            $data[] = [
                'roll_number'   => $row[0],
                'name'          => $row[1],
                'father_name'   => $row[2],
                'course'        => $row[3],
                'subject1'      => $s1,
                'subject2'      => $s2,
                'subject3'      => $s3,
                'subject4'      => $s4,
                'subject5'      => $s5,
                'total'         => $total,
                'result_status' => $status,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];

            if (count($data) >= $chunkSize) {
                Result::upsert($data, ['roll_number'], ['name', 'father_name', 'course', 'subject1', 'subject2', 'subject3', 'subject4', 'subject5', 'total', 'result_status', 'updated_at']);
                $data = [];
            }
        }

        if (!empty($data)) {
            Result::upsert($data, ['roll_number'], ['name', 'father_name', 'course', 'subject1', 'subject2', 'subject3', 'subject4', 'subject5', 'total', 'result_status', 'updated_at']);
        }

        fclose($handle);

        // Full cache flush — all result and stat caches are now stale
        $this->flushAllCache();

        return back()->with('success', 'Results imported successfully');
    }

    public function destroy(Result $result)
    {
        $rollNumber = $result->roll_number;
        $result->delete();

        // Invalidate this result's cache + stats
        $this->forgetResult($rollNumber);
        $this->forgetStats();

        return back()->with('success', 'Result deleted successfully');
    }

    // ─── Result Template Designer ──────────────────────────────────────────────

    public function editTemplate()
    {
        $settings = Setting::firstOrCreate(['id' => 1]);
        return view('admin.template.index', compact('settings'));
    }

    public function updateTemplate(Request $request)
    {
        $request->validate([
            'result_template' => 'required'
        ]);

        $template = $request->result_template;

        // ── 1. Mandatory Placeholder Validation ──
        $mandatoryTags = [
            '[ROLL_NUMBER]'     => 'Roll Number Tag',
            '[STUDENT_NAME]'    => 'Student Name Tag',
            '[TOTAL_MARKS]'     => 'Total Marks Tag',
            '[VERIFICATION_QR]' => 'Verification QR Code Tag',
            '[STATUS]'          => 'Result Status Tag',
        ];

        $missingTags = [];
        foreach ($mandatoryTags as $tag => $label) {
            if (strpos($template, $tag) === false) {
                $missingTags[] = $label . " (" . $tag . ")";
            }
        }

        if (!empty($missingTags)) {
            return back()->withInput()->withErrors([
                'result_template' => 'Design submission blocked! The following mandatory tags are missing: ' . implode(', ', $missingTags)
            ]);
        }

        // ── 2. Basic HTML Sanitization ──
        // Stripping potentially dangerous tags while keeping UI/Styling intact.
        $dangerousTags = ['script', 'iframe', 'object', 'embed', 'form', 'base', 'meta'];
        foreach ($dangerousTags as $tag) {
            $template = preg_replace('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', '', $template);
            $template = preg_replace('/<' . $tag . '\b[^>]*>/is', '', $template);
        }

        $setting = Setting::firstOrCreate(['id' => 1]);
        $setting->update([
            'result_template' => $template
        ]);

        // Invalidate settings cache so students see the new design immediately
        $this->forgetSettings();

        return back()->with('success', 'Result template updated successfully');
    }

    public function resetTemplate()
    {
        $seeder = new \Database\Seeders\SettingSeeder();
        $seeder->run();

        $this->forgetSettings();

        return back()->with('success', 'Result template reset to default successfully');
    }
}

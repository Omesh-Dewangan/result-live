<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    // ─── Cache TTLs (in seconds) ───────────────────────────────────────────────
    const SETTINGS_TTL = 120;   // 2 minutes
    const RESULT_TTL   = 600;   // 10 minutes

    // ─── Clear Session (Back Button Handler) ───────────────────────────────────
    // Called when student presses back from result page.
    // Clears auth tokens so they must re-search to view result again.
    public function clearSession()
    {
        session()->forget(['result_auth_token', 'result_auth_roll']);
        return redirect()->route('student.search');
    }

    // ─── Cached Setting Helper ─────────────────────────────────────────────────
    private function getSetting(): ?Setting
    {
        return Cache::remember('portal.settings', self::SETTINGS_TTL, function () {
            return Setting::first();
        });
    }

    public function index()
    {
        $setting = $this->getSetting();

        // BOUNCE-BACK SECURITY: If a valid session token exists,
        // redirect user straight back to their result instead of showing the search form.
        if (session()->has('result_auth_token') && session()->has('result_auth_roll')) {
            $token = session('result_auth_token');
            $roll  = session('result_auth_roll');
            return redirect()->route('student.result', [
                'roll_number' => $roll,
                'auth'        => $token,
            ]);
        }

        // Stage 1: Portal Login Access (Controls Page Visibility)
        $loginAccess  = true;
        $loginMessage = '';

        if ($setting) {
            if (!$setting->login_active) {
                $loginAccess  = false;
                $loginMessage = 'Student Portal access is currently disabled by the administrator.';
            } else {
                $now = now();
                if ($setting->login_from && $now->lt($setting->login_from)) {
                    $loginAccess  = false;
                    $loginMessage = 'The login window is not yet active. Please check back on ' . date('d M Y, h:i A', strtotime($setting->login_from));
                } elseif ($setting->login_to && $now->gt($setting->login_to)) {
                    $loginAccess  = false;
                    $loginMessage = 'The student login period has ended and the portal is now closed.';
                }
            }
        }

        // Stage 2: Result Live Status (Controls "Fetch Result" Badge)
        $portalStatus  = 'active';
        $statusMessage = 'Results are now live and accessible.';

        if ($setting) {
            if (!$setting->result_live) {
                $portalStatus  = 'disabled';
                $statusMessage = 'Result checking is not yet enabled.';
            } else {
                $now = now();
                if ($setting->result_from && $now->lt($setting->result_from)) {
                    $portalStatus  = 'scheduled';
                    $statusMessage = 'Results will be available on ' . date('d M Y, h:i A', strtotime($setting->result_from));
                } elseif ($setting->result_to && $now->gt($setting->result_to)) {
                    $portalStatus  = 'expired';
                    $statusMessage = 'Result access window closed on ' . date('d M Y, h:i A', strtotime($setting->result_to));
                }
            }
        }

        return view('student.search', compact('loginAccess', 'loginMessage', 'portalStatus', 'statusMessage', 'setting'));
    }

    public function searchResult(Request $request)
    {
        // Clear any previous session before starting a fresh search
        session()->forget(['result_auth_token', 'result_auth_roll']);

        $setting = $this->getSetting();

        // Security checks - 1. Portal Login Access
        if (!$setting || !$setting->login_active) {
            return $this->handleErrorResponse($request, 'Student Portal access is currently disabled.');
        }

        $now = now();
        if ($setting->login_from && $now->lt($setting->login_from)) {
            return $this->handleErrorResponse($request, 'The login window is not yet open.');
        }
        if ($setting->login_to && $now->gt($setting->login_to)) {
            return $this->handleErrorResponse($request, 'The login window has closed.');
        }

        // Security checks - 2. Result Marks Visibility
        if (!$setting->result_live) {
            return $this->handleErrorResponse($request, 'Results are not yet declared.');
        }

        if ($setting->result_from && $now->lt($setting->result_from)) {
            return $this->handleErrorResponse($request, 'Results will be available from ' . date('d M Y h:i A', strtotime($setting->result_from)));
        }

        if ($setting->result_to && $now->gt($setting->result_to)) {
            return $this->handleErrorResponse($request, 'The result access window closed on ' . date('d M Y h:i A', strtotime($setting->result_to)));
        }

        $request->validate([
            'roll_number' => 'required'
        ]);

        // Try cache first for this roll number
        $cacheKey = 'result.' . $request->roll_number;
        $result = Cache::remember($cacheKey, self::RESULT_TTL, function () use ($request) {
            $query = Result::where('roll_number', $request->roll_number);
            if ($request->name) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            return $query->first();
        });

        if (!$result) {
            // Don't cache null — remove from cache and return error
            Cache::forget($cacheKey);
            return $this->handleErrorResponse($request, 'Invalid Roll Number or Student Details.');
        }

        // GENERATE SECURE BURN-ON-READ TOKEN
        $token = Str::random(40);
        session(['result_auth_token' => $token]);
        session(['result_auth_roll'  => $result->roll_number]);

        $redirectUrl = route('student.result', [
            'roll_number' => $result->roll_number,
            'auth'        => $token
        ]);

        if ($request->ajax()) {
            return response()->json(['redirect' => $redirectUrl]);
        }
        return redirect()->intended($redirectUrl);
    }

    private function handleErrorResponse(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json(['error' => $message]);
        }
        return back()->with('error', $message);
    }

    public function showResult(Request $request, $roll_number)
    {
        $setting = $this->getSetting();

        // "BURN-ON-READ" SECURITY VERIFICATION
        $sessionToken = session('result_auth_token');
        $sessionRoll  = session('result_auth_roll');
        $urlToken     = $request->query('auth');

        // Check if token exists in session AND matches URL token AND matches Roll Number
        if (!$sessionToken || !$urlToken || $sessionToken !== $urlToken || $sessionRoll != $roll_number) {
            return redirect()->route('student.search')->with('error', 'Security session expired or invalid access. Please perform a new search.');
        }

        // Final Security Check: Ensure portal login is still allowed
        $now = now();
        if (!$setting || !$setting->login_active || ($setting->login_from && $now->lt($setting->login_from)) || ($setting->login_to && $now->gt($setting->login_to))) {
            return redirect()->route('student.search')->with('error', 'Login session expired or portal access closed.');
        }

        // Final Security Check: Ensure results are still live
        if (!$setting->result_live || ($setting->result_from && $now->lt($setting->result_from)) || ($setting->result_to && $now->gt($setting->result_to))) {
            return redirect()->route('student.search')->with('error', 'Result access is currently restricted.');
        }

        // Fetch from cache (populated during searchResult) or fallback to DB
        $result = Result::where('roll_number', $roll_number)->firstOrFail();

        // ── Dynamic Template Parsing ──────────────────────────────────────────
        $template = $setting ? $setting->result_template : null;
        
        if (empty($template)) {
            // Fallback to a very basic structure if template is missing
            $parsedHTML = '<div class="alert alert-warning text-center py-5 shadow-sm rounded-4 border-0">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h4 class="fw-bold">Marksheet Template Not Found</h4>
                <p class="text-muted">The administrator has not yet configured the result marksheet design.</p>
            </div>';
        } else {
            // Generate Verification URL for QR Code
            $verificationUrl = route('student.result', ['roll_number' => $result->roll_number]);
            $qrUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=" . urlencode($verificationUrl) . "&choe=UTF-8";

            // Determine Status Color Class
            $statusClass = (strtolower($result->result_status) === 'pass') ? 'text-success' : 'text-danger';

            // Replace all placeholders with actual data
            $replaceMap = [
                '[ROLL_NUMBER]'     => $result->roll_number,
                '[STUDENT_NAME]'    => strtoupper($result->name),
                '[FATHER_NAME]'     => strtoupper($result->father_name),
                '[COURSE]'          => strtoupper($result->course),
                '[SUBJECT_1]'       => $result->subject1,
                '[SUBJECT_2]'       => $result->subject2,
                '[SUBJECT_3]'       => $result->subject3,
                '[SUBJECT_4]'       => $result->subject4,
                '[SUBJECT_5]'       => $result->subject5,
                '[TOTAL_MARKS]'     => $result->total,
                '[STATUS]'          => strtoupper($result->result_status),
                '[STATUS_CLASS]'    => $statusClass,
                '[DECLARED_DATE]'   => $result->created_at ? $result->created_at->format('d M, Y') : now()->format('d M, Y'),
                '[VERIFIED_AT]'     => now()->format('d-m-Y H:i:s'),
                '[TRACKING_ID]'     => strtoupper(Str::random(12)),
                '[VERIFICATION_QR]' => '<img src="' . $qrUrl . '" alt="Verification QR" class="img-fluid border p-1 bg-white shadow-sm" style="max-height: 100px;">',
            ];

            $parsedHTML = str_replace(array_keys($replaceMap), array_values($replaceMap), $template);
        }

        // ── Audit Trail & Activity Logging (Consolidated) ─────────────────────
        // We only maintain ONE summary record per student.
        // We increment the count and store the LATEST IP/UserAgent.
        ActivityLog::updateOrCreate(
            ['roll_number' => $roll_number],
            ['ip_address'  => $request->ip(), 'user_agent'  => $request->userAgent()]
        )->increment('view_count');

        // Prevent browser caching COMPLETELY
        return response()
            ->view('student.show', compact('result', 'setting', 'parsedHTML'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Securely record a print operation via AJAX.
     * Verified by session token.
     */
    public function recordPrint(Request $request, $roll_number)
    {
        $sessionToken = session('result_auth_token');
        $sessionRoll  = session('result_auth_roll');
        $urlToken     = $request->query('auth');

        if (!$sessionToken || !$urlToken || $sessionToken !== $urlToken || $sessionRoll != $roll_number) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $result = Result::where('roll_number', $roll_number)->first();
        if ($result) {
            // Update or create the summary record and increment print_count
            ActivityLog::updateOrCreate(
                ['roll_number' => $roll_number],
                ['ip_address'  => $request->ip(), 'user_agent'  => $request->userAgent()]
            )->increment('print_count');

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Result not found'], 404);
    }
}

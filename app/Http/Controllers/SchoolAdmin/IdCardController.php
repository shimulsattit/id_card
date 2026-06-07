<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class IdCardController extends Controller
{
    // --- Students ---

    public function studentSelect(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        if (!$schoolId && auth()->user()->hasRole('super_admin')) {
            $schoolId = \App\Models\School::first()->id ?? null;
        }
        $classes = Student::where('school_id', $schoolId)->distinct()->pluck('class');
        $sections = Student::where('school_id', $schoolId)->distinct()->pluck('section');
        $school = \App\Models\School::find($schoolId);
        $sessions = $school->sessions ?? [];

        $templates = \App\Models\IdCardTemplate::where('type', 'student')
            ->where(function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId)->orWhereNull('school_id');
            })->get();

        $students = collect();
        if ($request->has('class') || $request->has('section') || $request->input('session') || $request->student_id || $request->contact_no) {
            $query = Student::where('school_id', $schoolId);
            if ($request->class)
                $query->where('class', $request->class);
            if ($request->section)
                $query->where('section', $request->section);
            if ($request->input('session'))
                $query->where('session', $request->input('session'));
            if ($request->student_id)
                $query->where('student_id', $request->student_id);
            if ($request->contact_no)
                $query->where('contact_no', 'like', '%' . $request->contact_no . '%');
            $students = $query->get();
        }

        return view('school.id_cards.student_select', compact('classes', 'sections', 'sessions', 'students', 'templates'));
    }

    public function studentPreview(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('school.idcards.students');
        }

        $selectedIds = $request->input('student_ids', []);
        $studentIdListText = $request->input('student_id_list', '');

        $students = collect();
        $schoolId = auth()->user()->school_id;

        // 1. Process Checkbox Selected IDs (Physical DB IDs)
        if (!empty($selectedIds)) {
            $students = $students->merge(Student::whereIn('id', $selectedIds)->with('school')->get());
        }

        // 2. Process Textarea IDs (Logical Student IDs)
        if (!empty($studentIdListText)) {
            // Split by comma, space or newline
            $idsFromText = preg_split('/[\s,]+/', $studentIdListText, -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($idsFromText)) {
                $students = $students->merge(
                    Student::where('school_id', $schoolId)
                        ->whereIn('student_id', $idsFromText)
                        ->with('school')
                        ->get()
                );
            }
        }

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Please select students or enter Student IDs.');
        }

        // Ensure unique students by their logical Student ID
        $students = $students->unique('student_id');

        // Generate QR codes in base64 to embed
        foreach ($students as $student) {
            $student->qrcode = base64_encode(QrCode::format('svg')->size(100)->encoding('UTF-8')->generate($student->school->name . ' - ' . $student->name . ' - ' . $student->roll));
        }

        $templateInput = $request->input('template', 'default');
        $layout = $request->input('layout', 'single');

        if (is_numeric($templateInput)) {
            $template = \App\Models\IdCardTemplate::find($templateInput);
            if ($template) {
                $view = 'school.id_cards.student_preview_dynamic';
                // Special Template 2 layout (Only ID 2 uses specific legacy view)
                if ($template->id == 2) {
                    $view = 'school.id_cards.student_preview_tpl_02';
                }
                return view($view, compact('students', 'template', 'layout'));
            }
        }

        // Default behavior (or if template not found)
        return view('school.id_cards.student_preview', compact('students'));
    }

    public function studentDownload(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('school.idcards.students');
        }

        $selectedIds = $request->input('student_ids', []);
        $studentIdListText = $request->input('student_id_list', '');

        $students = collect();
        $schoolId = auth()->user()->school_id;

        if (!empty($selectedIds)) {
            $students = $students->merge(Student::whereIn('id', $selectedIds)->with('school')->get());
        }

        if (!empty($studentIdListText)) {
            $idsFromText = preg_split('/[\s,]+/', $studentIdListText, -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($idsFromText)) {
                $students = $students->merge(
                    Student::where('school_id', $schoolId)
                        ->whereIn('student_id', $idsFromText)
                        ->with('school')
                        ->get()
                );
            }
        }

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Please select students or enter Student IDs.');
        }

        $students = $students->unique('student_id');

        foreach ($students as $student) {
            // Generate QR codes for PDF (SVG might tricky in older dompdf, using PNG or SVG)
            // DomPDF supports SVG reasonably well now, or use PNG.
            // Using ID for download QR for now, or could match preview
            $student->qrcode = base64_encode(QrCode::format('svg')->size(80)->encoding('UTF-8')->generate($student->school->name . ' - ' . $student->name . ' - ' . $student->roll));
        }

        $templateInput = $request->input('template', 'default');
        $layout = $request->input('layout', 'single');

        if (is_numeric($templateInput)) {
            // Dynamic DB Template
            $template = \App\Models\IdCardTemplate::find($templateInput);
            if (!$template) {
                // Fallback
                $view = 'school.id_cards.student_pdf';
            } else {
                $view = 'school.id_cards.student_pdf_dynamic';
                // Special Template 2 layout (Only ID 2 uses specific legacy view)
                if ($template->id == 2) {
                    $view = 'school.id_cards.student_pdf_tpl_02';
                }
                // Pass template object to view
                $pdf = Pdf::loadView($view, compact('students', 'template', 'layout'));

                if ($layout === 'dice_90') {
                    // Custom large paper for 90 cards (10 columns): 560mm x 2298px
                    // 560mm (10 columns * 56mm) = 1587.4pt
                    $pdf->setPaper([0, 0, 1587.4, 2298]);
                } elseif ($layout === 'dice_36') {
                    // Custom large paper for 36 cards (6 columns): 342mm x 561.976mm
                    // 342mm (6 columns * 57mm) = 969.4pt
                    $pdf->setPaper([0, 0, 969.4, 1592.9]);
                } else {
                    // One card per page: 55mm x 86.753mm
                    // 1mm = 2.8346pt → 55mm = 155.9pt, 86.753mm = 245.9pt
                    $pdf->setPaper([0, 0, 155.9, 245.9]);
                }

                // Stream inline in browser (like reference) instead of downloading
                return $pdf->stream('student_id_cards.pdf');
            }
        } else {
            // Static Templates
            $view = $templateInput === 'modern' ? 'school.id_cards.student_pdf_modern' : 'school.id_cards.student_pdf';
        }

        $pdf = Pdf::loadView($view, compact('students'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('student_id_cards.pdf');
    }

    public function studentDownloadWord(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('school.idcards.students');
        }

        $selectedIds = $request->input('student_ids', []);
        $studentIdListText = $request->input('student_id_list', '');

        $students = collect();
        $schoolId = auth()->user()->school_id;

        if (!empty($selectedIds)) {
            $students = $students->merge(Student::whereIn('id', $selectedIds)->with('school')->get());
        }

        if (!empty($studentIdListText)) {
            $idsFromText = preg_split('/[\s,]+/', $studentIdListText, -1, PREG_SPLIT_NO_EMPTY);
            if (!empty($idsFromText)) {
                $students = $students->merge(
                    Student::where('school_id', $schoolId)
                        ->whereIn('student_id', $idsFromText)
                        ->with('school')
                        ->get()
                );
            }
        }

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Please select students or enter Student IDs.');
        }

        $students = $students->unique('student_id');

        $templateInput = $request->input('template', 'default');
        $template = \App\Models\IdCardTemplate::find($templateInput);

        // Fake Base Path for MHTML linking
        $basePath = "file:///C:/000_ID_CARDS/";

        // Prepare MHTML Parts
        $images = [];

        // 1. Template Background
        if ($template && $template->background_image) {
            $path = storage_path('app/public/' . $template->background_image);
            if (file_exists($path)) {
                $uniqueName = 'bg_' . $template->id . '_' . time() . '.jpg';
                $fullUri = $basePath . $uniqueName;

                $images[$fullUri] = $path; // Map URI to local path
                $template->bg_mhtml = $fullUri; // Valid URI for src
            } else {
                $template->bg_mhtml = null;
            }
        }

        // 2. Student Photos
        foreach ($students as $student) {
            if ($student->photo) {
                $path = storage_path('app/public/' . $student->photo);
                if (file_exists($path)) {
                    $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'jpg';
                    $uniqueName = 'student_' . $student->id . '_' . time() . '.' . $ext;
                    $fullUri = $basePath . $uniqueName;

                    $images[$fullUri] = $path;
                    $student->photo_mhtml = $fullUri;
                } else {
                    $student->photo_mhtml = null;
                }
            }
        }

        // Render HTML
        $html = view('school.id_cards.student_word_dynamic', compact('students', 'template'))->render();

        // Build MHTML
        $mhtml = $this->makeMhtml($html, $images, $basePath);

        return response($mhtml)
            ->header('Content-Type', 'application/msword') // MHTML opens in Word
            ->header('Content-Disposition', 'attachment; filename="student_id_cards.doc"');
    }

    private function makeMhtml($html, $images, $basePath)
    {
        $boundary = "----=_NextPart_" . strtoupper(md5(mt_rand()));
        $eol = "\r\n";

        $mhtml = "MIME-Version: 1.0$eol";
        $mhtml .= "Content-Type: multipart/related; boundary=\"$boundary\"; type=\"text/html\"$eol$eol";

        // HTML Part
        $mhtml .= "--$boundary$eol";
        $mhtml .= "Content-Type: text/html; charset=\"utf-8\"$eol";
        $mhtml .= "Content-Transfer-Encoding: quoted-printable$eol";
        $mhtml .= "Content-Location: {$basePath}index.htm$eol$eol";
        $mhtml .= quoted_printable_encode($html) . $eol . $eol;

        // Image Parts
        foreach ($images as $uri => $localPath) {
            if (file_exists($localPath)) {
                $mime = mime_content_type($localPath);
                $content = file_get_contents($localPath);
                $base64 = base64_encode($content);
                // Chunk split for valid MIME
                $base64 = chunk_split($base64);

                $mhtml .= "--$boundary$eol";
                $mhtml .= "Content-Type: $mime$eol";
                $mhtml .= "Content-Transfer-Encoding: base64$eol";
                // Content-Location match the src in HTML
                $mhtml .= "Content-Location: $uri$eol$eol";
                $mhtml .= $base64 . $eol . $eol;
            }
        }

        $mhtml .= "--$boundary--$eol";

        return $mhtml;
    }

    // --- Teachers ---

    public function teacherSelect(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $teachers = Teacher::where('school_id', $schoolId)->get();
        return view('school.id_cards.teacher_select', compact('teachers'));
    }

    public function teacherPreview(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('school.idcards.teachers');
        }
        $request->validate(['teacher_ids' => 'required|array']);
        $teachers = Teacher::whereIn('id', $request->teacher_ids)->with('school')->get();

        foreach ($teachers as $teacher) {
            $teacher->qrcode = base64_encode(QrCode::format('svg')->size(100)->encoding('UTF-8')->generate($teacher->school->name . ' - ' . $teacher->name));
        }

        return view('school.id_cards.teacher_preview', compact('teachers'));
    }

    public function teacherDownload(Request $request)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('school.idcards.teachers');
        }
        $request->validate(['teacher_ids' => 'required|array']);
        $teachers = Teacher::whereIn('id', $request->teacher_ids)->with('school')->get();

        foreach ($teachers as $teacher) {
            $teacher->qrcode = base64_encode(QrCode::format('svg')->size(80)->encoding('UTF-8')->generate($teacher->school->name . ' - ' . $teacher->name));
        }

        $pdf = Pdf::loadView('school.id_cards.teacher_pdf', compact('teachers'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('teacher_id_cards.pdf');
    }
}

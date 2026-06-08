<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IdCardTemplate;
use App\Models\Student;
use Illuminate\Support\Facades\View;

$template = IdCardTemplate::find(5);
$student = Student::where('school_id', 6)->first(); // School 6 is Kurigram Govt College

if (!$template || !$student) {
    die("Template or Student not found.\n");
}

$students = collect([$student]);

// Check Preview Output
$previewHtml = View::make('school.id_cards.student_preview_dynamic', [
    'template' => $template,
    'students' => $students,
    'layout' => 'single'
])->render();

echo "--- Preview Check ---\n";
$foundPhotoSize = str_contains($previewHtml, 'width: 90.7px') && str_contains($previewHtml, 'height: 98.3px');
$foundTop = str_contains($previewHtml, 'top-[148px]');
$foundValidUpTo = str_contains($previewHtml, 'Valid up to') && str_contains($previewHtml, 'color: red');
$sessionRemoved = !str_contains($previewHtml, '<td class="align-top w-[47px] whitespace-nowrap">Session</td>');

if ($foundPhotoSize && $foundTop && $foundValidUpTo && $sessionRemoved) {
    echo "SUCCESS: Preview layout is correct.\n";
} else {
    echo "FAILURE: Preview layout issues:\n";
    if (!$foundPhotoSize)
        echo "- Photo size incorrect\n";
    if (!$foundTop)
        echo "- Top position incorrect (expected top-[148px])\n";
    if (!$foundValidUpTo)
        echo "- Valid up to line missing or not red\n";
    if (!$sessionRemoved)
        echo "- Session field still present\n";
}

// Check PDF Output
$pdfHtml = View::make('school.id_cards.student_pdf_dynamic', [
    'template' => $template,
    'students' => $students,
    'layout' => 'single'
])->render();

echo "\n--- PDF Check ---\n";
$foundPhotoSizePdf = str_contains($pdfHtml, 'width: 24mm') && str_contains($pdfHtml, 'height: 26mm');
$foundTopPdf = str_contains($pdfHtml, 'top: 52.8mm');
$foundValidUpToPdf = str_contains($pdfHtml, 'Valid up to') && str_contains($pdfHtml, 'color: red');

if ($foundPhotoSizePdf && $foundTopPdf && $foundValidUpToPdf) {
    echo "SUCCESS: PDF layout is correct.\n";
} else {
    echo "FAILURE: PDF layout issues:\n";
    if (!$foundPhotoSizePdf)
        echo "- Photo size incorrect\n";
    if (!$foundTopPdf)
        echo "- Top position incorrect (expected 52.8mm)\n";
    if (!$foundValidUpToPdf)
        echo "- Valid up to line missing or not red\n";
}

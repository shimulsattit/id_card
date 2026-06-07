<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SchoolAdmin\StudentController;
use App\Http\Controllers\SchoolAdmin\TeacherController;
use App\Http\Controllers\SchoolAdmin\IdCardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/force-logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Super Admin Routes
    Route::middleware(['role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('schools', SchoolController::class);
        Route::get('schools/{school}/switch', [SchoolController::class, 'loginAsSchoolAdmin'])->name('schools.switch');
        Route::resource('id-card-templates', \App\Http\Controllers\SuperAdmin\IdCardTemplateController::class);
    });

    // School Admin Routes
    Route::middleware(['role:school_admin'])->prefix('school')->name('school.')->group(function () {
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::get('students/import-photos', function () {
            return redirect()->route('school.students.create');
        });
        Route::post('students/import-photos', [StudentController::class, 'importPhotos'])->name('students.import-photos');
        Route::get('students/export-report', [StudentController::class, 'exportReport'])->name('students.export-report');
        Route::get('students/download-sample', [StudentController::class, 'downloadSample'])->name('students.download-sample');
        Route::resource('students', StudentController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('designations', App\Http\Controllers\SchoolAdmin\DesignationController::class);

        // Academic (Classes & Sections)
        Route::get('academic', [App\Http\Controllers\SchoolAdmin\AcademicController::class, 'index'])->name('academic.index');
        Route::put('academic/classes', [App\Http\Controllers\SchoolAdmin\AcademicController::class, 'updateClasses'])->name('academic.classes.update');
        Route::put('academic/sections', [App\Http\Controllers\SchoolAdmin\AcademicController::class, 'updateSections'])->name('academic.sections.update');
        Route::put('academic/sessions', [App\Http\Controllers\SchoolAdmin\AcademicController::class, 'updateSessions'])->name('academic.sessions.update');

        // ID Card Generation
        Route::get('id-cards/students', [IdCardController::class, 'studentSelect'])->name('idcards.students');
        Route::match(['get', 'post'], 'id-cards/students/preview', [IdCardController::class, 'studentPreview'])->name('idcards.students.preview');
        Route::match(['get', 'post'], 'id-cards/students/download', [IdCardController::class, 'studentDownload'])->name('idcards.students.download');
        Route::match(['get', 'post'], 'id-cards/students/download-word', [IdCardController::class, 'studentDownloadWord'])->name('idcards.students.download_word');

        Route::get('id-cards/teachers', [IdCardController::class, 'teacherSelect'])->name('idcards.teachers');
        Route::match(['get', 'post'], 'id-cards/teachers/preview', [IdCardController::class, 'teacherPreview'])->name('idcards.teachers.preview');
        Route::match(['get', 'post'], 'id-cards/teachers/download', [IdCardController::class, 'teacherDownload'])->name('idcards.teachers.download');

        // ID Card Templates

    });
});

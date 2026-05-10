<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IssueController as AdminIssueController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ────────────────────────────────────────────

Route::get('/', [IssueController::class, 'index'])->name('home');

Route::get('/report', [ReportController::class, 'create'])->name('report.create');
Route::post('/report', [ReportController::class, 'store'])->name('report.store');

Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
Route::post('/issues/{issue}/comments', [CommentController::class, 'store'])->name('comments.store');

// ── API (stateless JSON) ─────────────────────────────────────

Route::get('/api/issues/map', [IssueController::class, 'mapData'])->name('api.issues.map');

// ── Admin Auth (unauthenticated) ─────────────────────────────

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// ── Admin Protected Routes ───────────────────────────────────

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/issues', [AdminIssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/{issue}/edit', [AdminIssueController::class, 'edit'])->name('issues.edit');
    Route::put('/issues/{issue}', [AdminIssueController::class, 'update'])->name('issues.update');
    Route::delete('/issues/{issue}', [AdminIssueController::class, 'destroy'])->name('issues.destroy');

    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CRM\ClientController;
use App\Http\Controllers\CRM\LeadController;
use App\Http\Controllers\CRM\ContactController;
use App\Http\Controllers\CRM\ReportController;
use App\Http\Controllers\CRM\NotificationController;
use App\Http\Controllers\CRM\ActivityLogController;
use App\Http\Controllers\CRM\GlobalSearchController;
use App\Http\Controllers\CRM\FileManagerController;
use App\Http\Controllers\CRM\ImportExportController;
use App\Http\Controllers\CRM\UserController;
use App\Http\Controllers\CRM\TeamController;
use App\Http\Controllers\CRM\CompanyController;
use App\Http\Controllers\CRM\RoleController;
use App\Http\Controllers\CRM\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CRM\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('global.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::post('/test', [NotificationController::class, 'testNotification'])->name('test');
    });

    // Activity Logs
    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CRM Routes
    Route::resource('clients', ClientController::class);
    Route::resource('leads', LeadController::class);
    
    Route::post('contacts/{contact}/primary', [ContactController::class, 'setPrimary'])->name('contacts.primary');
    Route::resource('contacts', ContactController::class);
    Route::resource('activities', \App\Http\Controllers\CRM\ActivityController::class);
    Route::post('follow-ups/{follow_up}/complete', [\App\Http\Controllers\CRM\FollowUpController::class, 'markCompleted'])->name('follow-ups.complete');
    Route::resource('follow-ups', \App\Http\Controllers\CRM\FollowUpController::class);
    
    // Notes & Documents
    Route::resource('notes', \App\Http\Controllers\CRM\NoteController::class)->only(['store', 'destroy']);
    Route::resource('documents', \App\Http\Controllers\CRM\DocumentController::class)->only(['store', 'destroy']);

    // File Manager
    Route::get('/file-manager', [FileManagerController::class, 'index'])->name('file-manager.index');
    Route::get('/file-manager/{file}/download', [FileManagerController::class, 'download'])->name('file-manager.download');
    Route::delete('/file-manager/{file}', [FileManagerController::class, 'destroy'])->name('file-manager.destroy');

    // Import / Export
    Route::prefix('import-export')->name('import-export.')->group(function () {
        Route::get('/', [ImportExportController::class, 'index'])->name('index');
        Route::post('/template', [ImportExportController::class, 'downloadTemplate'])->name('template');
        Route::post('/export', [ImportExportController::class, 'export'])->name('export');
        Route::post('/import', [ImportExportController::class, 'import'])->name('import');
    });
    
    // System Management (Admin Only)
    Route::middleware(['role:Administrator'])->group(function () {
        // User Management
        Route::resource('users', UserController::class)->except(['create', 'show']);

        // Team Management
        Route::resource('teams', TeamController::class)->except(['create']);
        Route::post('teams/{team}/members', [TeamController::class, 'addMember'])->name('teams.members.add');
        Route::delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

        // Company Management
        Route::resource('companies', CompanyController::class)->except(['create']);
        Route::post('companies/{company}/members', [CompanyController::class, 'addMember'])->name('companies.members.add');
        Route::delete('companies/{company}/members/{user}', [CompanyController::class, 'removeMember'])->name('companies.members.remove');

        // Role & Permission Management
        Route::resource('roles', RoleController::class)->except(['create', 'show']);
        Route::resource('permissions', PermissionController::class)->except(['create', 'show']);
    });

    // Projects
    Route::resource('projects', \App\Http\Controllers\CRM\ProjectController::class);
    Route::post('projects/{project}/ai-tasks', [\App\Http\Controllers\CRM\ProjectController::class, 'aiGenerateTasks'])->name('projects.ai-tasks');
    Route::resource('milestones', \App\Http\Controllers\CRM\MilestoneController::class)->only(['store', 'update', 'destroy']);
    Route::resource('tasks', \App\Http\Controllers\CRM\TaskController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    // Support Desk
    Route::resource('tickets', \App\Http\Controllers\CRM\TicketController::class);
    Route::post('tickets/{ticket}/replies', [\App\Http\Controllers\CRM\TicketReplyController::class, 'store'])->name('tickets.replies.store');
    Route::post('tickets/{ticket}/ai-summarize', [\App\Http\Controllers\CRM\TicketController::class, 'aiSummarize'])->name('tickets.ai-summarize');
    Route::post('tickets/{ticket}/ai-reply', [\App\Http\Controllers\CRM\TicketController::class, 'aiGenerateReply'])->name('tickets.ai-reply');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/clients', [ReportController::class, 'clients'])->name('clients');
        Route::get('/projects', [ReportController::class, 'projects'])->name('projects');
        Route::get('/tasks', [ReportController::class, 'tasks'])->name('tasks');
    });

    // AI Assistant
    Route::get('ai/email-generator', [\App\Http\Controllers\AI\AiEmailController::class, 'index'])->name('ai.email.index');
    Route::post('ai/email-generator/generate', [\App\Http\Controllers\AI\AiEmailController::class, 'generate'])->name('ai.email.generate');
    Route::post('ai/email-generator/send', [\App\Http\Controllers\AI\AiEmailController::class, 'send'])->name('ai.email.send');
    Route::get('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'index'])->name('ai.chat.index');
    Route::post('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'storeMessage'])->name('ai.chat.store');
    Route::delete('ai/chat/{id}', [\App\Http\Controllers\AI\AiChatController::class, 'destroy'])->name('ai.chat.destroy');
});

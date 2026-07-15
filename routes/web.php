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
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\EmailVerificationController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('public.landing');
    })->name('public.landing');
    
    Route::get('/about', function () {
        return view('public.about');
    })->name('public.about');

    Route::get('/pricing', function () {
        return view('public.pricing');
    })->name('public.pricing');

    Route::get('/contact', function () {
        return view('public.contact');
    })->name('public.contact');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.store');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware(['throttle:6,1'])->name('verification.send');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CRM\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('global.search');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        
        // Backups
        Route::get('/settings/backups', [\App\Http\Controllers\BackupController::class, 'index'])->name('backups.index');
        Route::post('/settings/backups', [\App\Http\Controllers\BackupController::class, 'store'])->name('backups.store');
        Route::get('/settings/backups/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backups.download');
        Route::delete('/settings/backups/delete', [\App\Http\Controllers\BackupController::class, 'destroy'])->name('backups.destroy');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('readAll');
        Route::get('/test', [NotificationController::class, 'testNotification'])->name('test');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    // Activity Logs
    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // CRM Bulk Actions & Soft Deletes
    Route::post('clients/bulk-delete', [ClientController::class, 'bulkDelete'])->name('clients.bulk-delete');
    Route::post('clients/bulk-update', [ClientController::class, 'bulkUpdate'])->name('clients.bulk-update');
    Route::post('clients/{client}/restore', [ClientController::class, 'restore'])->name('clients.restore')->withTrashed();
    Route::delete('clients/{client}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.force-delete')->withTrashed();
    
    Route::post('leads/bulk-delete', [LeadController::class, 'bulkDelete'])->name('leads.bulk-delete');
    Route::post('leads/bulk-update', [LeadController::class, 'bulkUpdate'])->name('leads.bulk-update');
    Route::post('leads/{lead}/restore', [LeadController::class, 'restore'])->name('leads.restore')->withTrashed();
    Route::delete('leads/{lead}/force-delete', [LeadController::class, 'forceDelete'])->name('leads.force-delete')->withTrashed();

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
    Route::post('projects/bulk-delete', [\App\Http\Controllers\CRM\ProjectController::class, 'bulkDelete'])->name('projects.bulk-delete');
    Route::post('projects/bulk-update', [\App\Http\Controllers\CRM\ProjectController::class, 'bulkUpdate'])->name('projects.bulk-update');
    Route::post('projects/{project}/restore', [\App\Http\Controllers\CRM\ProjectController::class, 'restore'])->name('projects.restore')->withTrashed();
    Route::delete('projects/{project}/force-delete', [\App\Http\Controllers\CRM\ProjectController::class, 'forceDelete'])->name('projects.force-delete')->withTrashed();
    Route::resource('projects', \App\Http\Controllers\CRM\ProjectController::class);
    Route::post('projects/{project}/ai-tasks', [\App\Http\Controllers\CRM\ProjectController::class, 'aiGenerateTasks'])->name('projects.ai-tasks');
    Route::resource('milestones', \App\Http\Controllers\CRM\MilestoneController::class)->only(['store', 'update', 'destroy']);
    Route::post('tasks/bulk-delete', [\App\Http\Controllers\CRM\TaskController::class, 'bulkDelete'])->name('tasks.bulk-delete');
    Route::post('tasks/bulk-update', [\App\Http\Controllers\CRM\TaskController::class, 'bulkUpdate'])->name('tasks.bulk-update');
    Route::post('tasks/{task}/restore', [\App\Http\Controllers\CRM\TaskController::class, 'restore'])->name('tasks.restore')->withTrashed();
    Route::delete('tasks/{task}/force-delete', [\App\Http\Controllers\CRM\TaskController::class, 'forceDelete'])->name('tasks.force-delete')->withTrashed();
    Route::resource('tasks', \App\Http\Controllers\CRM\TaskController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    // Support Desk
    Route::resource('tickets', \App\Http\Controllers\CRM\TicketController::class);
    Route::post('tickets/{ticket}/replies', [\App\Http\Controllers\CRM\TicketReplyController::class, 'store'])->name('tickets.replies.store');
    Route::post('tickets/{ticket}/ai-summarize', [\App\Http\Controllers\CRM\TicketController::class, 'aiSummarize'])->name('tickets.ai-summarize');
    Route::post('tickets/{ticket}/ai-reply', [\App\Http\Controllers\CRM\TicketController::class, 'aiGenerateReply'])->name('tickets.ai-reply');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/clients/export', [ReportController::class, 'exportClients'])->name('clients.export');
        Route::get('/clients', [ReportController::class, 'clients'])->name('clients');
        
        Route::get('/projects/export', [ReportController::class, 'exportProjects'])->name('projects.export');
        Route::get('/projects', [ReportController::class, 'projects'])->name('projects');
        
        Route::get('/tasks/export', [ReportController::class, 'exportTasks'])->name('tasks.export');
        Route::get('/tasks', [ReportController::class, 'tasks'])->name('tasks');
    });

    // AI Assistant
    Route::get('ai/meeting-notes', [\App\Http\Controllers\AI\AiMeetingNotesController::class, 'index'])->name('ai.meetings.index');
    Route::post('ai/meeting-notes/generate', [\App\Http\Controllers\AI\AiMeetingNotesController::class, 'generate'])->name('ai.meetings.generate');
    Route::post('ai/meeting-notes/tasks', [\App\Http\Controllers\AI\AiMeetingNotesController::class, 'storeTasks'])->name('ai.meetings.tasks');
    
    Route::get('ai/business-insights', [\App\Http\Controllers\AI\AiBusinessInsightsController::class, 'index'])->name('ai.insights.index');
    Route::post('ai/business-insights/generate', [\App\Http\Controllers\AI\AiBusinessInsightsController::class, 'generate'])->name('ai.insights.generate');

    Route::get('ai/dashboard', [\App\Http\Controllers\AI\AiAnalyticsController::class, 'index'])->name('ai.dashboard.index');
    
    Route::get('ai/report-generator', [\App\Http\Controllers\AI\AiReportController::class, 'index'])->name('ai.reports.index');
    Route::post('ai/report-generator/generate', [\App\Http\Controllers\AI\AiReportController::class, 'generate'])->name('ai.reports.generate');

    Route::resource('ai/prompt-templates', \App\Http\Controllers\AI\AiPromptTemplateController::class)->names([
        'index' => 'ai.templates.index',
        'store' => 'ai.templates.store',
        'update' => 'ai.templates.update',
        'destroy' => 'ai.templates.destroy',
    ])->except(['create', 'show', 'edit']);

    Route::get('ai/email-generator', [\App\Http\Controllers\AI\AiEmailController::class, 'index'])->name('ai.email.index');
    Route::post('ai/email-generator/generate', [\App\Http\Controllers\AI\AiEmailController::class, 'generate'])->name('ai.email.generate');
    Route::post('ai/email-generator/send', [\App\Http\Controllers\AI\AiEmailController::class, 'send'])->name('ai.email.send');
    Route::get('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'index'])->name('ai.chat.index');
    Route::post('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'storeMessage'])->name('ai.chat.store');
    Route::delete('ai/chat/{id}', [\App\Http\Controllers\AI\AiChatController::class, 'destroy'])->name('ai.chat.destroy');
});

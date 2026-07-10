<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CRM\ClientController;
use App\Http\Controllers\CRM\LeadController;
use App\Http\Controllers\CRM\ContactController;

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

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

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
    
    // Project Management
    Route::resource('projects', \App\Http\Controllers\CRM\ProjectController::class);
    Route::resource('milestones', \App\Http\Controllers\CRM\MilestoneController::class)->only(['store', 'update', 'destroy']);
    Route::resource('tasks', \App\Http\Controllers\CRM\TaskController::class)->only(['index', 'store', 'update', 'destroy']);

    // Support Desk
    Route::resource('tickets', \App\Http\Controllers\CRM\TicketController::class);
    Route::post('tickets/{ticket}/replies', [\App\Http\Controllers\CRM\TicketReplyController::class, 'store'])->name('tickets.replies.store');

    // AI Assistant
    Route::get('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'index'])->name('ai.chat.index');
    Route::post('ai/chat/{id?}', [\App\Http\Controllers\AI\AiChatController::class, 'storeMessage'])->name('ai.chat.store');
    Route::delete('ai/chat/{id}', [\App\Http\Controllers\AI\AiChatController::class, 'destroy'])->name('ai.chat.destroy');
});

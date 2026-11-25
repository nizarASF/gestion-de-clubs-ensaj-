<?php

use App\Http\Controllers\Admin\ClubController as AdminClubController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\ModerationController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\Manage\DashboardController as ManageDashboardController;
use App\Http\Controllers\Manage\MediaController as ManageMediaController;
use App\Http\Controllers\Manage\PostController as ManagePostController;
use App\Http\Controllers\Manage\TeamController as ManageTeamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ClubController as PublicClubController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\TeamController as PublicTeamController;
use Illuminate\Support\Facades\Route;

/*
 * Zone publique : tout le monde peut consulter, même sans compte.
 */
Route::get('/', HomeController::class)->name('home');
Route::get('/clubs/{club:slug}/team', PublicTeamController::class)->name('clubs.team');
Route::get('/clubs/{club:slug}', [PublicClubController::class, 'show'])->name('clubs.show');

/*
 * Visiteurs : inscription et connexion (avec limitation des tentatives).
 */
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.store');
});

/*
 * Utilisateurs connectés : profil, notifications et messagerie.
 */
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');

    Route::get('/clubs/{club:slug}/contact', [ConversationController::class, 'contact'])->name('conversations.contact');
    Route::post('/clubs/{club:slug}/conversations', [ConversationController::class, 'store'])->name('conversations.store');
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/messages', [ConversationController::class, 'storeMessage'])
        ->middleware('throttle:30,1')
        ->name('conversations.messages.store');
    Route::get('/conversations/{conversation}/messages', [ConversationController::class, 'messages'])
        ->name('conversations.messages.index');
});

/*
 * Espace membre : les routes partent toujours du club de l'utilisateur.
 */
Route::middleware(['auth', 'role:club_member'])->prefix('manage')->name('manage.')->group(function () {
    Route::get('/dashboard', ManageDashboardController::class)->name('dashboard');
    Route::resource('/posts', ManagePostController::class)->except(['destroy', 'show']);
    Route::post('/posts/{post}/submit', [ManagePostController::class, 'submit'])->name('posts.submit');
    Route::resource('/team', ManageTeamController::class)
        ->except(['destroy', 'show'])
        ->parameters(['team' => 'teamMember']);
    Route::get('/media', [ManageMediaController::class, 'index'])->name('media.index');
    Route::get('/media/create', [ManageMediaController::class, 'create'])->name('media.create');
    Route::post('/media', [ManageMediaController::class, 'store'])->name('media.store');
});

/*
 * Administration BDE : modération, utilisateurs, clubs et posts.
 */
Route::middleware(['auth', 'role:bde'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::get('/moderation', [ModerationController::class, 'index'])->name('moderation');
    Route::post('/moderation/{type}/{id}/approve', [ModerationController::class, 'approve'])
        ->where('type', 'user|post|team|media')
        ->name('moderation.approve');
    Route::post('/moderation/{type}/{id}/reject', [ModerationController::class, 'reject'])
        ->where('type', 'user|post|team|media')
        ->name('moderation.reject');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    Route::resource('/clubs', AdminClubController::class)->except(['destroy', 'show']);
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [AdminPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [AdminPostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/team/{teamMember}', [AdminTeamController::class, 'destroy'])->name('team.destroy');
    Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
});

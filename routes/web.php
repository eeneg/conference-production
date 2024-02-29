<?php

use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\MinutesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileSearchController;
use App\Http\Middleware\IsAdmin;
use App\Models\Minutes;
use GuzzleHttp\Client;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'assignRole'])->name('profile.assignRole');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
    Route::resource('conferences', ConferenceController::class);
    Route::get('attachment/{attachment}/content', [AttachmentController::class, 'content'])->name('attachment.content');
    Route::get('attachment/{attachment}/download', [AttachmentController::class, 'download'])->name('attachment.download');
    Route::resource('attachment', AttachmentController::class);
    Route::resource('minutes', MinutesController::class);
    Route::resource('files', FileController::class);
    Route::resource('/settings/storage', StorageController::class);
    Route::resource('/settings/category', CategoryController::class);
    Route::resource('/settings/reference', ReferenceController::class);
    Route::get('/storage_relation/{id}', [StorageController::class, 'checkStorageRelation'])->name('storage.check');
    Route::post('/file_check', [FileController::class, 'fileCheck'])->name('file.check');
    Route::get('/category_check/{id}', [CategoryController::class, 'checkCategoryRelation'])->name('category.check');

    Route::get('/findFiles', [FileSearchController::class, 'index'])->name('file.search');

    Route::get('/messages/{id}', [ChatController::class, 'show'])->name('messages.show');
    Route::post('/messages', [ChatController::class, 'store'])->name('messages.store');
    Route::get('/userChatList', [ChatController::class, 'userChatList'])->name('messages.users');
    Route::get('/usersToChat', [ChatController::class, 'getUsersToChat']);
});

Route::resource('users', UserController::class)->middleware([IsAdmin::class]);


require __DIR__.'/auth.php';

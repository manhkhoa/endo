<?php

use App\Http\Controllers\Config\ConfigController;
use App\Http\Controllers\Config\LocaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\OptionActionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OptionImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Search;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Team\PermissionController;
use App\Http\Controllers\Team\RoleController;
use App\Http\Controllers\TeamActionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserActionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Utility\ActivityLogController;
use App\Http\Controllers\Utility\BackupController;
use App\Http\Controllers\Utility\TodoActionController;
use App\Http\Controllers\Utility\TodoController;
use Illuminate\Support\Facades\Route;

// Team routes
Route::post('teams/{team}/select', [TeamActionController::class, 'select'])->name('teams.select');

Route::middleware('permission:team:manage')->group(function () {
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('teams.roles', RoleController::class)->except(['update']);

    Route::get('teams/{team}/permissions/pre-requisite', [PermissionController::class, 'preRequisite']);
    Route::post('teams/{team}/permissions/role/assign', [PermissionController::class, 'roleWiseAssign']);
    Route::get('teams/{team}/permissions/search', [PermissionController::class, 'search']);
    Route::get('teams/{team}/permissions/user/search', [PermissionController::class, 'searchUser']);
    Route::post('teams/{team}/permissions/user/assign', [PermissionController::class, 'userWiseAssign']);
});

// User Routes
Route::prefix('users')->group(function () {
    Route::get('pre-requisite', [UserController::class, 'preRequisite']);
    Route::post('{user}/status', [UserActionController::class, 'status']);
});

Route::apiResource('users', UserController::class);

Route::prefix('user')->group(function () {
    Route::post('preference', [ProfileController::class, 'preference'])
        ->name('preference');
});

Route::prefix('user')->middleware('test.mode.restriction')->group(function () {
    Route::post('password', [ProfileController::class, 'password'])
        ->name('password.change');

    Route::post('profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('profile/account', [ProfileController::class, 'account'])
        ->name('profile.account');

    Route::post('profile/verify', [ProfileController::class, 'verify'])
        ->name('profile.verify');

    Route::post('profile/avatar', [ProfileController::class, 'uploadAvatar'])
        ->name('profile.uploadAvatar');

    Route::delete('profile/avatar', [ProfileController::class, 'removeAvatar'])
        ->name('profile.removeAvatar');
});

// Dashboard Routes
Route::get('dashboard/stat', [DashboardController::class, 'stat'])->name('dashboard.stat');
Route::get('dashboard/favorite', [DashboardController::class, 'favorite'])->name('dashboard.favorite');
Route::get('dashboard/chart', [DashboardController::class, 'chart'])->name('dashboard.chart');
Route::get('dashboard/record', [DashboardController::class, 'record'])->name('dashboard.record');

// Any key search
Route::get('search', Search::class)
    ->name('search');

// Config Routes
Route::prefix('config')->group(function () {
    Route::get('', [ConfigController::class, 'fetch'])
        ->name('config.fetch');

    Route::post('', [ConfigController::class, 'store'])
        ->name('config.store');

    Route::get('mail/test', [ConfigController::class, 'testMailConnection'])
        ->name('config.testMailConnection');
    Route::get('pusher/test', [ConfigController::class, 'testPusherConnection'])
            ->name('config.testPusherConnection');

    Route::post('assets', [ConfigController::class, 'uploadAsset']);
    Route::delete('assets', [ConfigController::class, 'removeAsset']);

    Route::apiResource('locales', LocaleController::class)->middleware('permission:config:store');
});

// Option Routes
Route::prefix('')->group(function () {
    Route::get('options/pre-requisite', [OptionController::class, 'preRequisite'])->name('options.preRequisite')->middleware('option.verifier');
    Route::post('options/import', OptionImportController::class)->middleware('option.verifier');
    Route::post('options/reorder', [OptionActionController::class, 'reorder'])->middleware('option.verifier');
    Route::apiResource('options', OptionController::class)->middleware('option.verifier');
});

// Utility Routes
Route::prefix('utility')->group(function () {
    Route::prefix('todos')->middleware('permission:todo:manage')->group(function () {
        Route::get('pre-requisite', [TodoController::class, 'preRequisite'])->name('todos.preRequisite');
        Route::post('{todo}/status', [TodoActionController::class, 'status'])->name('todos.status');
        Route::post('{todo}/archive', [TodoActionController::class, 'archive'])->name('todos.archive');
        Route::post('{todo}/unarchive', [TodoActionController::class, 'unarchive'])->name('todos.unarchive');
        Route::post('reorder', [TodoActionController::class, 'reorder'])->name('todos.reorder');
        Route::post('lists/move', [TodoActionController::class, 'moveList'])->name('todos.moveList');
    });

    Route::post('todos/delete', [TodoController::class, 'destroyMultiple']);
    Route::apiResource('todos', TodoController::class)->middleware('permission:todo:manage');

    Route::apiResource('backups', BackupController::class)->only(['index', 'destroy'])->middleware('permission:backup:manage');

    Route::apiResource('activity-logs', ActivityLogController::class)->only(['index', 'destroy'])->middleware('permission:activity-log:manage');
});

Route::get('tags', [TagController::class, 'index'])->name('tags.index');

Route::resource('medias', MediaController::class)->only(['store', 'destroy']);

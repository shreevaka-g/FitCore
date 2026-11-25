<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipPlanController;


Route::get('/', function () {
    return redirect()->route('login');
})->middleware('redirect.home');

//Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');


        Route::post('status-change-member', [MemberController::class, 'updateMemberStatus'])->name('update.member.status');

        Route::resource('members', MemberController::class);
        Route::resource('membership-plans', MembershipPlanController::class);
    });

//Member
Route::middleware(['auth', 'role:member'])
    // ->prefix('member')
    ->name('member.')
    ->group(function () {

        Route::get('/dashboard', [MemberDashboardController::class, 'index'])
            ->name('dashboard');
    });

<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // User Management
    Route::get('users', UsersIndex::class)->name('users.index');
    Route::get('users/create', \App\Livewire\Users\Create::class)->name('users.create');
    Route::get('users/{user}/edit', \App\Livewire\Users\Edit::class)->name('users.edit');
    
    // Units Management
    Route::get('units', \App\Livewire\Units\Index::class)->name('units.index');
    Route::get('units/create', \App\Livewire\Units\Create::class)->name('units.create');
    Route::get('units/{unit}/edit', \App\Livewire\Units\Edit::class)->name('units.edit');
    
    // Ranks Management
    Route::get('ranks', \App\Livewire\Ranks\Index::class)->name('ranks.index');
    Route::get('ranks/create', \App\Livewire\Ranks\Create::class)->name('ranks.create');
    Route::get('ranks/{rank}/edit', \App\Livewire\Ranks\Edit::class)->name('ranks.edit');
    
    // Positions Management
    Route::get('positions', \App\Livewire\Positions\Index::class)->name('positions.index');
    Route::get('positions/create', \App\Livewire\Positions\Create::class)->name('positions.create');
    Route::get('positions/{position}/edit', \App\Livewire\Positions\Edit::class)->name('positions.edit');
    
    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/organization', \App\Livewire\Settings\OrganizationSettings::class)->name('settings.organization');
});

require __DIR__.'/auth.php';

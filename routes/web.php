<?php

use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;

use App\Livewire\Positions\Create as PositionCreate;
use App\Livewire\Positions\Edit as PositionEdit;
use App\Livewire\Positions\Index as PositionIndex;
use App\Livewire\Cities\Create as CityCreate;
use App\Livewire\Cities\Edit as CityEdit;
use App\Livewire\Cities\Index as CityIndex;
use App\Livewire\Districts\Create as DistrictCreate;
use App\Livewire\Districts\Edit as DistrictEdit;
use App\Livewire\Districts\Index as DistrictIndex;
use App\Livewire\OrgPlaces\Create as OrgPlaceCreate;
use App\Livewire\OrgPlaces\Edit as OrgPlaceEdit;
use App\Livewire\OrgPlaces\Index as OrgPlaceIndex;
use App\Livewire\Provinces\Create as ProvinceCreate;
use App\Livewire\Provinces\Edit as ProvinceEdit;
use App\Livewire\Provinces\Index as ProvinceIndex;
use App\Livewire\Ranks\Create as RankCreate;
use App\Livewire\Ranks\Edit as RankEdit;
use App\Livewire\Ranks\Index as RankIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\DeleteUserForm;
use App\Livewire\Settings\OrganizationSettings;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Units\Create as UnitCreate;
use App\Livewire\Units\Edit as UnitEdit;
use App\Livewire\Units\Index as UnitIndex;
use App\Livewire\Users\Create as UserCreate;
use App\Livewire\Users\Edit as UserEdit;
use App\Livewire\Users\Index as UserIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', VerifyEmail::class)->name('verification.notice');
    Route::get('confirm-password', ConfirmPassword::class)->name('password.confirm');
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // User CRUD
    Route::get('users', UserIndex::class)->name('users.index');
    Route::get('users/create', UserCreate::class)->name('users.create');
    Route::get('users/{user}/edit', UserEdit::class)->name('users.edit');

    // Rank CRUD
    Route::get('ranks', RankIndex::class)->name('ranks.index');
    Route::get('ranks/create', RankCreate::class)->name('ranks.create');
    Route::get('ranks/{rank}/edit', RankEdit::class)->name('ranks.edit');

    // Position CRUD
    Route::get('positions', PositionIndex::class)->name('positions.index');
    Route::get('positions/create', PositionCreate::class)->name('positions.create');
    Route::get('positions/{position}/edit', PositionEdit::class)->name('positions.edit');

    // Unit CRUD
    Route::get('units', UnitIndex::class)->name('units.index');
    Route::get('units/create', UnitCreate::class)->name('units.create');
    Route::get('units/{unit}/edit', UnitEdit::class)->name('units.edit');

    // Province CRUD
    Route::get('provinces', ProvinceIndex::class)->name('provinces.index');
    Route::get('provinces/create', ProvinceCreate::class)->name('provinces.create');
    Route::get('provinces/{province}/edit', ProvinceEdit::class)->name('provinces.edit');

    // City CRUD
    Route::get('cities', CityIndex::class)->name('cities.index');
    Route::get('cities/create', CityCreate::class)->name('cities.create');
    Route::get('cities/{city}/edit', CityEdit::class)->name('cities.edit');

    // District CRUD
    Route::get('districts', DistrictIndex::class)->name('districts.index');
    Route::get('districts/create', DistrictCreate::class)->name('districts.create');
    Route::get('districts/{district}/edit', DistrictEdit::class)->name('districts.edit');

    // Org Place CRUD
    Route::get('org-places', OrgPlaceIndex::class)->name('org-places.index');
    Route::get('org-places/create', OrgPlaceCreate::class)->name('org-places.create');
    Route::get('org-places/{orgPlace}/edit', OrgPlaceEdit::class)->name('org-places.edit');

    // Settings
    Route::get('settings/profile', Profile::class)->name('profile.show');
    Route::get('settings/password', Password::class)->name('password.show');
    Route::get('settings/appearance', Appearance::class)->name('appearance.show');
    Route::get('settings/delete-user', DeleteUserForm::class)->name('delete-user.show');
    Route::get('settings/organization', OrganizationSettings::class)->name('organization.show');
});

require __DIR__.'/auth.php';

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
use App\Livewire\TransportModes\Create as TransportModeCreate;
use App\Livewire\TransportModes\Edit as TransportModeEdit;
use App\Livewire\TransportModes\Index as TransportModeIndex;
use App\Livewire\TravelRoutes\Create as TravelRouteCreate;
use App\Livewire\TravelRoutes\Edit as TravelRouteEdit;
use App\Livewire\TravelRoutes\Index as TravelRouteIndex;
use App\Livewire\TravelGrades\Create as TravelGradeCreate;
use App\Livewire\TravelGrades\Edit as TravelGradeEdit;
use App\Livewire\TravelGrades\Index as TravelGradeIndex;
use App\Livewire\UserTravelGradeMaps\Index as UserTravelGradeMapIndex;
use App\Livewire\PerdiemRates\Create as PerdiemRateCreate;
use App\Livewire\PerdiemRates\Edit as PerdiemRateEdit;
use App\Livewire\PerdiemRates\Index as PerdiemRateIndex;
use App\Livewire\LodgingCaps\Create as LodgingCapCreate;
use App\Livewire\LodgingCaps\Edit as LodgingCapEdit;
use App\Livewire\LodgingCaps\Index as LodgingCapIndex;
use App\Livewire\RepresentationRates\Create as RepresentationRateCreate;
use App\Livewire\RepresentationRates\Edit as RepresentationRateEdit;
use App\Livewire\RepresentationRates\Index as RepresentationRateIndex;
use App\Livewire\ReferenceRates\Index as ReferenceRatesIndex;
use App\Livewire\AirfareRefs\Create as AirfareRefCreate;
use App\Livewire\AirfareRefs\Edit as AirfareRefEdit;
use App\Livewire\AirfareRefs\Index as AirfareRefIndex;
use App\Livewire\IntraProvinceTransportRefs\Create as IntraProvinceTransportRefCreate;
use App\Livewire\IntraProvinceTransportRefs\Edit as IntraProvinceTransportRefEdit;
use App\Livewire\IntraProvinceTransportRefs\Index as IntraProvinceTransportRefIndex;
use App\Livewire\IntraDistrictTransportRefs\Create as IntraDistrictTransportRefCreate;
use App\Livewire\IntraDistrictTransportRefs\Edit as IntraDistrictTransportRefEdit;
use App\Livewire\IntraDistrictTransportRefs\Index as IntraDistrictTransportRefIndex;
use App\Livewire\OfficialVehicleTransportRefs\Create as OfficialVehicleTransportRefCreate;
use App\Livewire\OfficialVehicleTransportRefs\Edit as OfficialVehicleTransportRefEdit;
use App\Livewire\OfficialVehicleTransportRefs\Index as OfficialVehicleTransportRefIndex;
use App\Livewire\AtCostComponents\Create as AtCostComponentCreate;
use App\Livewire\AtCostComponents\Edit as AtCostComponentEdit;
use App\Livewire\AtCostComponents\Index as AtCostComponentIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\DocNumberFormats\Index as DocNumberFormatIndex;
use App\Livewire\DocNumberFormats\Create as DocNumberFormatCreate;
use App\Livewire\DocNumberFormats\Edit as DocNumberFormatEdit;
use App\Livewire\NumberSequences\Index as NumberSequenceIndex;
use App\Livewire\DocumentNumbers\Index as DocumentNumberIndex;
use App\Livewire\NotaDinas\Index as NotaDinasIndex;
use App\Livewire\NotaDinas\Create as NotaDinasCreate;
use App\Livewire\NotaDinas\Edit as NotaDinasEdit;
use App\Livewire\NotaDinas\Show as NotaDinasShow;
use App\Livewire\Spt\Index as SptIndex;
use App\Livewire\Spt\Create as SptCreate;
use App\Livewire\Spt\Edit as SptEdit;
use App\Livewire\Spt\Show as SptShow;
use App\Livewire\Sppd\Index as SppdIndex;
use App\Livewire\Sppd\Create as SppdCreate;
use App\Livewire\Sppd\Edit as SppdEdit;
use App\Livewire\Sppd\Show as SppdShow;


Route::get('/', function () {
    return view('welcome');
})->name('home');

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

    // Transport Mode CRUD
    Route::get('transport-modes', TransportModeIndex::class)->name('transport-modes.index');
    Route::get('transport-modes/create', TransportModeCreate::class)->name('transport-modes.create');
    Route::get('transport-modes/{transportMode}/edit', TransportModeEdit::class)->name('transport-modes.edit');

    // Travel Route CRUD
    Route::get('travel-routes', TravelRouteIndex::class)->name('travel-routes.index');
    Route::get('travel-routes/create', TravelRouteCreate::class)->name('travel-routes.create');
    Route::get('travel-routes/{travelRoute}/edit', TravelRouteEdit::class)->name('travel-routes.edit');

    // Travel Grade CRUD
    Route::get('travel-grades', TravelGradeIndex::class)->name('travel-grades.index');
    Route::get('travel-grades/create', TravelGradeCreate::class)->name('travel-grades.create');
    Route::get('travel-grades/{travelGrade}/edit', TravelGradeEdit::class)->name('travel-grades.edit');

    // User Travel Grade Mapping
    Route::get('user-travel-grade-maps', UserTravelGradeMapIndex::class)->name('user-travel-grade-maps.index');

    // Perdiem Rate CRUD
    Route::get('perdiem-rates', PerdiemRateIndex::class)->name('perdiem-rates.index');
    Route::get('perdiem-rates/create', PerdiemRateCreate::class)->name('perdiem-rates.create');
    Route::get('perdiem-rates/{perdiemRate}/edit', PerdiemRateEdit::class)->name('perdiem-rates.edit');

    // Lodging Cap CRUD
    Route::get('lodging-caps', LodgingCapIndex::class)->name('lodging-caps.index');
    Route::get('lodging-caps/create', LodgingCapCreate::class)->name('lodging-caps.create');
    Route::get('lodging-caps/{lodgingCap}/edit', LodgingCapEdit::class)->name('lodging-caps.edit');

    // Reference Rates Index
    Route::get('reference-rates', ReferenceRatesIndex::class)->name('reference-rates.index');

    // Airfare Reference CRUD
    Route::get('airfare-refs', AirfareRefIndex::class)->name('airfare-refs.index');
    Route::get('airfare-refs/create', AirfareRefCreate::class)->name('airfare-refs.create');
    Route::get('airfare-refs/{airfareRef}/edit', AirfareRefEdit::class)->name('airfare-refs.edit');

    // Representation Rate CRUD
    Route::get('representation-rates', RepresentationRateIndex::class)->name('representation-rates.index');
    Route::get('representation-rates/create', RepresentationRateCreate::class)->name('representation-rates.create');
    Route::get('representation-rates/{representationRate}/edit', RepresentationRateEdit::class)->name('representation-rates.edit');

    // Intra Province Transport Reference CRUD
    Route::get('intra-province-transport-refs', IntraProvinceTransportRefIndex::class)->name('intra-province-transport-refs.index');
    Route::get('intra-province-transport-refs/create', IntraProvinceTransportRefCreate::class)->name('intra-province-transport-refs.create');
    Route::get('intra-province-transport-refs/{transportRef}/edit', IntraProvinceTransportRefEdit::class)->name('intra-province-transport-refs.edit');

    // Intra District Transport Reference CRUD
    Route::get('intra-district-transport-refs', IntraDistrictTransportRefIndex::class)->name('intra-district-transport-refs.index');
    Route::get('intra-district-transport-refs/create', IntraDistrictTransportRefCreate::class)->name('intra-district-transport-refs.create');
    Route::get('intra-district-transport-refs/{transportRef}/edit', IntraDistrictTransportRefEdit::class)->name('intra-district-transport-refs.edit');

    // Official Vehicle Transport Reference CRUD
    Route::get('official-vehicle-transport-refs', OfficialVehicleTransportRefIndex::class)->name('official-vehicle-transport-refs.index');
    Route::get('official-vehicle-transport-refs/create', OfficialVehicleTransportRefCreate::class)->name('official-vehicle-transport-refs.create');
    Route::get('official-vehicle-transport-refs/{transportRef}/edit', OfficialVehicleTransportRefEdit::class)->name('official-vehicle-transport-refs.edit');

    // At-Cost Components CRUD
    Route::get('at-cost-components', AtCostComponentIndex::class)->name('at-cost-components.index');
    Route::get('at-cost-components/create', AtCostComponentCreate::class)->name('at-cost-components.create');
    Route::get('at-cost-components/{component}/edit', AtCostComponentEdit::class)->name('at-cost-components.edit');

    // Settings
    Route::get('settings/profile', Profile::class)->name('profile.show');
    Route::get('settings/password', Password::class)->name('password.show');
    Route::get('settings/appearance', Appearance::class)->name('appearance.show');
    Route::get('settings/delete-user', DeleteUserForm::class)->name('delete-user.show');
    Route::get('settings/organization', OrganizationSettings::class)->name('organization.show');

    // Format Penomoran Dokumen CRUD
    Route::get('doc-number-formats', DocNumberFormatIndex::class)->name('doc-number-formats.index');
    Route::get('doc-number-formats/create', DocNumberFormatCreate::class)->name('doc-number-formats.create');
    Route::get('doc-number-formats/{format}/edit', DocNumberFormatEdit::class)->name('doc-number-formats.edit');

    // Number Sequence
    Route::get('number-sequences', NumberSequenceIndex::class)->name('number-sequences.index');
    // Document Numbers (Audit Trail)
    Route::get('document-numbers', DocumentNumberIndex::class)->name('document-numbers.index');

    // Nota Dinas CRUD
    Route::get('nota-dinas', NotaDinasIndex::class)->name('nota-dinas.index');
    Route::get('nota-dinas/create', NotaDinasCreate::class)->name('nota-dinas.create');
    Route::get('nota-dinas/{notaDinas}/edit', NotaDinasEdit::class)->name('nota-dinas.edit');
    Route::get('nota-dinas/{notaDinas}', NotaDinasShow::class)->name('nota-dinas.show');

    // SPT CRUD
    Route::get('spt', SptIndex::class)->name('spt.index');
    Route::get('spt/create', SptCreate::class)->name('spt.create');
    Route::get('spt/{spt}/edit', SptEdit::class)->name('spt.edit');
    Route::get('spt/{spt}', SptShow::class)->name('spt.show');

    // SPPD CRUD
    Route::get('sppd', SppdIndex::class)->name('sppd.index');
    Route::get('sppd/create', SppdCreate::class)->name('sppd.create');
    Route::get('sppd/{sppd}/edit', SppdEdit::class)->name('sppd.edit');
    Route::get('sppd/{sppd}', SppdShow::class)->name('sppd.show');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\Admin\AccessMatrixController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\AssistantController;
use App\Http\Controllers\Customer\BookingController as CustomerBookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\PetController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Staff\BookingController as StaffBookingController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/redirect-by-role', [AuthController::class, 'redirectByRole'])->middleware('auth')->name('redirect.role');

Route::prefix('customer')->name('customer.')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('pets', PetController::class);
    Route::resource('bookings', CustomerBookingController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('/bookings/{booking}/cancel', [CustomerBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/reschedule', [CustomerBookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::resource('reviews', ReviewController::class)->only(['index', 'create', 'store']);
    Route::get('/assistant', [AssistantController::class, 'index'])->name('assistant.index');
    Route::post('/assistant', [AssistantController::class, 'store'])->name('assistant.store');
});

Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index')->middleware('permission:booking.view_assigned');
    Route::get('/bookings/{booking}', [StaffBookingController::class, 'show'])->name('bookings.show')->middleware('permission:booking.view_assigned');
    Route::patch('/bookings/{booking}/status', [StaffBookingController::class, 'updateStatus'])->name('bookings.update-status')->middleware('permission:booking.update_status');
    Route::post('/bookings/{booking}/images', [StaffBookingController::class, 'uploadImage'])->name('bookings.upload-image')->middleware('permission:booking.upload_image');
    Route::post('/bookings/{booking}/notes', [StaffBookingController::class, 'addNote'])->name('bookings.add-note')->middleware('permission:booking.add_note');
    Route::get('/team', [StaffDashboardController::class, 'team'])->name('team')->middleware('permission:staff.view_team');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class);
    Route::resource('services', AdminServiceController::class);
    Route::resource('promotions', AdminPromotionController::class);
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/assign-staff', [AdminBookingController::class, 'assignStaff'])->name('bookings.assign-staff');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');

    // Permission Management
    Route::resource('roles', RoleController::class);
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

    Route::resource('permissions', PermissionController::class);

    Route::get('/user-permissions', [UserPermissionController::class, 'index'])->name('users.permissions.index');
    Route::get('/user-permissions/{user}/edit', [UserPermissionController::class, 'edit'])->name('users.permissions.edit');
    Route::patch('/user-permissions/{user}', [UserPermissionController::class, 'update'])->name('users.permissions.update');
    Route::patch('/user-permissions/{user}/manager', [UserPermissionController::class, 'updateManager'])->name('users.permissions.update-manager');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{user}', [TeamController::class, 'show'])->name('teams.show');

    Route::get('/access-matrix', [AccessMatrixController::class, 'index'])->name('access-matrix.index');
});

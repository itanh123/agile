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
use App\Http\Controllers\GuestController;
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
Route::get('/vnpay/return', [App\Http\Controllers\Customer\PaymentController::class, 'vnpayReturn'])->name('payments.vnpay.return.flat');

// RQ26 + RQ27 + RQ28: Guest routes (không cần đăng nhập)
Route::prefix('guest')->name('guest.')->group(function () {
    Route::get('/services', [GuestController::class, 'services'])->name('services');
    Route::get('/services/{service}', [GuestController::class, 'serviceDetail'])->name('service');
    Route::post('/quick-register', [GuestController::class, 'quickRegister'])->name('quick-register');
    Route::post('/quick-booking', [GuestController::class, 'quickBooking'])->name('quick-booking');
});

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

    // RQ07 + RQ08: Notification routes
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Customer\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Customer\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // RQ09: Thanh toán online
    Route::get('/bookings/{booking}/payment', [App\Http\Controllers\Customer\PaymentController::class, 'selectMethod'])->name('payments.select');
    Route::match(['get', 'post'], '/bookings/{booking}/payment', [App\Http\Controllers\Customer\PaymentController::class, 'process'])->name('payments.process');
    Route::get('/bookings/{booking}/payment/show', [App\Http\Controllers\Customer\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/bookings/{booking}/payment/confirm-transfer', [App\Http\Controllers\Customer\PaymentController::class, 'confirmTransfer'])->name('payments.confirm-transfer');
    Route::get('/payment/vnpay/return', [App\Http\Controllers\Customer\PaymentController::class, 'vnpayReturn'])->name('payments.vnpay.return');
    Route::get('/payment/momo/return', [App\Http\Controllers\Customer\PaymentController::class, 'momoReturn'])->name('payments.momo.return');

    // RQ29: Yêu cầu giao nhận
    Route::get('/pickups', [App\Http\Controllers\Customer\PickupController::class, 'index'])->name('pickups.index');
    Route::get('/pickups/{pickup}', [App\Http\Controllers\Customer\PickupController::class, 'show'])->name('pickups.show');
    Route::post('/bookings/{booking}/pickup', [App\Http\Controllers\Customer\PickupController::class, 'store'])->name('pickups.store');
    Route::patch('/pickups/{pickup}/cancel', [App\Http\Controllers\Customer\PickupController::class, 'cancel'])->name('pickups.cancel');
});

Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [StaffBookingController::class, 'index'])->name('bookings.index')->middleware('permission:booking.view_assigned');
    Route::get('/bookings/{booking}', [StaffBookingController::class, 'show'])->name('bookings.show')->middleware('permission:booking.view_assigned');
    Route::patch('/bookings/{booking}/status', [StaffBookingController::class, 'updateStatus'])->name('bookings.update-status')->middleware('permission:booking.update_status');
    Route::post('/bookings/{booking}/images', [StaffBookingController::class, 'uploadImage'])->name('bookings.upload-image')->middleware('permission:booking.upload_image');
    Route::post('/bookings/{booking}/notes', [StaffBookingController::class, 'addNote'])->name('bookings.add-note')->middleware('permission:booking.add_note');
    Route::get('/team', [StaffDashboardController::class, 'team'])->name('team')->middleware('permission:staff.view_team');

    // RQ29: Giao nhận thú cưng
    Route::get('/pickups', [App\Http\Controllers\Staff\PickupController::class, 'index'])->name('pickups.index');
    Route::get('/pickups/{pickup}', [App\Http\Controllers\Staff\PickupController::class, 'show'])->name('pickups.show');
    Route::post('/pickups/{pickup}/accept', [App\Http\Controllers\Staff\PickupController::class, 'accept'])->name('pickups.accept');
    Route::post('/pickups/{pickup}/picked-up', [App\Http\Controllers\Staff\PickupController::class, 'pickedUp'])->name('pickups.picked-up');
    Route::post('/pickups/{pickup}/delivered', [App\Http\Controllers\Staff\PickupController::class, 'delivered'])->name('pickups.delivered');
    Route::post('/pickups/{pickup}/note', [App\Http\Controllers\Staff\PickupController::class, 'addNote'])->name('pickups.add-note');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->middleware('permission:user.view');
    Route::resource('services', AdminServiceController::class)->middleware('permission:service.view');
    Route::resource('promotions', AdminPromotionController::class)->middleware('permission:promotion.view');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index')->middleware('permission:booking.view');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show')->middleware('permission:booking.view');
    Route::patch('/bookings/{booking}/assign-staff', [AdminBookingController::class, 'assignStaff'])->name('bookings.assign-staff')->middleware('permission:booking.assign_staff');
    Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status')->middleware('permission:booking.update_status');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index')->middleware('permission:report.view');
 
    // Permission Management (Admin Only ideally, but we can secure it with permission middleware too)
    Route::resource('roles', RoleController::class)->middleware('role:admin');
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('role:admin');
    Route::patch('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('role:admin');
 
    Route::resource('permissions', PermissionController::class)->middleware('role:admin');
 
    Route::get('/user-permissions', [UserPermissionController::class, 'index'])->name('users.permissions.index')->middleware('role:admin');
    Route::get('/user-permissions/{user}/edit', [UserPermissionController::class, 'edit'])->name('users.permissions.edit')->middleware('role:admin');
    Route::patch('/user-permissions/{user}', [UserPermissionController::class, 'update'])->name('users.permissions.update')->middleware('role:admin');
    Route::patch('/user-permissions/{user}/manager', [UserPermissionController::class, 'updateManager'])->name('users.permissions.update-manager')->middleware('role:admin');
 
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index')->middleware('permission:staff.view_team');
    Route::get('/teams/{user}', [TeamController::class, 'show'])->name('teams.show')->middleware('permission:staff.view_team');
 
    Route::get('/access-matrix', [AccessMatrixController::class, 'index'])->name('access-matrix.index')->middleware('role:admin');
});

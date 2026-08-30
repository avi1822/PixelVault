<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\HomeController;

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
    return view('home');
});
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('registration');
});

Route::get('/user', function () {
    return view('userpanel');
})->middleware('auth');

Route::get('/admin', function () {
    return view('adminpanel');
})->middleware(['auth',"isAdmin"]);

Route::post('user/update', [UserController::class, 'update'])->middleware('auth');
Route::post('user/updatep', [UserController::class, 'updatep'])->middleware('auth');
Route::get('user/viewone', [UserController::class, 'viewOne'])->middleware('auth');

Route::post('registration/store', [UserController::class, 'store'])->middleware('throttle:registration');
Route::post('registration/dologin', [UserController::class, 'dologin'])->middleware('throttle:login');
Route::get('registration/anydata', [UserController::class, 'anydata'])->middleware(['auth',"isAdmin"]);

Route::post('reservation/store', [ReservationController::class, 'store'])->middleware(['auth', 'throttle:reservation']);
Route::get('reservation/anydata', [ReservationController::class, 'anydata'])->middleware(['auth', 'isAdmin']);
Route::get('reservation/userdata', [ReservationController::class, 'userdata'])->middleware('auth');
Route::get('reservation/respkgdata', [ReservationController::class, 'respkgdata']);
Route::get('reservation/viewpopuler', [ReservationController::class, 'viewPopuler']);
Route::get('reservation/geteventdetails', [ReservationController::class, 'getEventDetails']);

Route::post('game/store', [GameController::class, 'store'])->middleware(['auth', 'isAdmin']);
Route::post('game/update', [GameController::class, 'update'])->middleware(['auth', 'isAdmin']);
Route::post('game/delete', [GameController::class, 'delete'])->middleware(['auth', 'isAdmin']);
Route::get('game/view', [GameController::class, 'view']);
Route::get('game/viewone', [GameController::class, 'viewone']);
Route::get('game/viewhimg', [GameController::class, 'viewhimg']);
Route::get('game/anydata', [GameController::class, 'anydata'])->middleware(['auth', 'isAdmin']);
Route::get('game/partofdata', [GameController::class, 'partofdata']);
Route::get('game/getlatest', [GameController::class, 'getLatest']);

Route::post('computer/store', [ComputerController::class, 'store'])->middleware(['auth', 'isAdmin']);
Route::post('computer/update', [ComputerController::class, 'update'])->middleware(['auth', 'isAdmin']);
Route::post('computer/status', [ComputerController::class, 'updateStatus'])->middleware(['auth', 'isAdmin']);
Route::get('computer/view', [ComputerController::class, 'view']);
Route::get('computer/viewhGame', [ComputerController::class, 'viewhGame']);
Route::get('computer/viewone', [ComputerController::class, 'viewone']);
Route::get('computer/viewoneimg', [ComputerController::class, 'viewoneimg']);
Route::get('computer/delete', [ComputerController::class, 'delete'])->middleware(['auth', 'isAdmin']);

use App\Http\Controllers\VisitorEntryController;

Route::post('package/store', [PackageController::class, 'store'])->middleware(['auth', 'isAdmin']);
Route::post('package/update', [PackageController::class, 'update'])->middleware(['auth', 'isAdmin']);
Route::post('package/delete', [PackageController::class, 'delete'])->middleware(['auth', 'isAdmin']);
Route::get('package/viewall', [PackageController::class, 'viewall']);
Route::get('package/viewone', [PackageController::class, 'viewone']);

Route::post('visitor/store', [VisitorEntryController::class, 'store'])->middleware(['auth', 'isAdmin']);
Route::get('visitor/anydata', [VisitorEntryController::class, 'anydata'])->middleware(['auth', 'isAdmin']);
Route::get('visitor/analytics', [VisitorEntryController::class, 'analytics'])->middleware(['auth', 'isAdmin']);
Route::post('visitor/delete', [VisitorEntryController::class, 'delete'])->middleware(['auth', 'isAdmin']);

use App\Http\Controllers\GamingSessionController;

Route::post('session/start-reservation', [GamingSessionController::class, 'startFromReservation'])->middleware(['auth', 'isAdmin']);
Route::post('session/start-walkin', [GamingSessionController::class, 'startWalkIn'])->middleware(['auth', 'isAdmin']);
Route::post('session/end', [GamingSessionController::class, 'endSession'])->middleware(['auth', 'isAdmin']);
Route::get('session/active', [GamingSessionController::class, 'viewActive'])->middleware(['auth', 'isAdmin']);
Route::get('session/my-active', [GamingSessionController::class, 'myActiveSession'])->middleware('auth');
Route::get('session/anydata', [GamingSessionController::class, 'anyData'])->middleware(['auth', 'isAdmin']);
use App\Http\Controllers\BillingController;

Route::get('billing/anydata', [BillingController::class, 'anyData'])->middleware(['auth', 'isAdmin']);
Route::get('billing/stats', [BillingController::class, 'summaryStats'])->middleware(['auth', 'isAdmin']);
Route::post('billing/payment', [BillingController::class, 'recordPayment'])->middleware(['auth', 'isAdmin']);
Route::get('billing/invoice/{id}', [BillingController::class, 'viewInvoice'])->middleware('auth');
use App\Http\Controllers\MembershipController;

Route::get('membership/plans', [MembershipController::class, 'getPlans']);
Route::post('membership/purchase', [MembershipController::class, 'purchasePlan'])->middleware('auth');
Route::get('membership/mymembership', [MembershipController::class, 'myMembership'])->middleware('auth');
Route::get('membership/anydata', [MembershipController::class, 'anyData'])->middleware(['auth', 'isAdmin']);
use App\Http\Controllers\InventoryController;

Route::get('inventory/products', [InventoryController::class, 'getProducts']);
Route::post('inventory/order', [InventoryController::class, 'orderProduct'])->middleware('auth');
Route::get('inventory/anydata', [InventoryController::class, 'anyData'])->middleware(['auth', 'isAdmin']);
Route::post('inventory/adjuststock', [InventoryController::class, 'adjustStock'])->middleware(['auth', 'isAdmin']);
use App\Http\Controllers\AnalyticsController;

Route::get('analytics/dashboard', [AnalyticsController::class, 'dashboard'])->middleware(['auth', 'isAdmin']);

use App\Http\Controllers\ContactMessageController;

Route::post('contact/store', [ContactMessageController::class, 'store'])->middleware('throttle:contact');
Route::get('contact/anydata', [ContactMessageController::class, 'anyData'])->middleware(['auth', 'isAdmin']);
Route::post('contact/markread', [ContactMessageController::class, 'markRead'])->middleware(['auth', 'isAdmin']);
Route::post('contact/delete', [ContactMessageController::class, 'delete'])->middleware(['auth', 'isAdmin']);
Route::get('contact/unreadcount', [ContactMessageController::class, 'unreadCount'])->middleware(['auth', 'isAdmin']);

Route::get('/logout', [LogoutController::class, 'perform'])->middleware('auth')->name('logout');

Route::get('home/view', [HomeController::class, 'view']);
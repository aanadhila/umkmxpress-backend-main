<?php

use App\Http\Controllers\APIListController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\ClusterCoverageController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\CourierWalletController;
use App\Http\Controllers\CourierWalletTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\ExpeditionController;
use App\Http\Controllers\GoogleMapsAPIController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\RecipientController;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\SenderPacketController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentItemController;
use App\Http\Controllers\SpecialCostController;
use App\Http\Controllers\TestingCourierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourrierPacketController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return to_route('login');
});

Auth::routes(['register' => false]);
Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        if (auth()->user()->hasRole('Super Admin')) {
            return to_route('dashboard.index');
        } else {
            return to_route('testing.orders.pickup');
        }
    })->name('home');
    Route::get('distance', [GoogleMapsAPIController::class, 'getDistance'])->name('getDistance');
    Route::get('getCourier', [GoogleMapsAPIController::class, 'getCourier'])->name('getCourier');

    Route::middleware('role:Super Admin|Admin|Kurir')->group(function () {
        Route::resource('wallets', CourierWalletTransactionController::class);
    });

    Route::middleware('role:Super Admin|Admin')->group(function () {
        Route::prefix('dashboard')->controller(DashboardController::class)->as('dashboard.')->group(function () {
            Route::get('/', 'index')->name('index');
        });
        Route::resource('users', UserController::class);
        Route::put('couriers/status', [CourierController::class, 'updateStatus'])->name('couriers.update.status');
        Route::put('couriers/cluster', [CourierController::class, 'updateCluster'])->name('couriers.update.cluster');
        Route::resource('couriers', CourierController::class);
        Route::put('wallets/update/status', [CourierWalletTransactionController::class, 'updateStatus'])->name('wallets.update.status');
        Route::put('expeditions/update/status', [ExpeditionController::class, 'changeStatus'])->name('expeditions.update.status');
        Route::resource('expeditions', ExpeditionController::class);
        Route::put('recipients/update/default', [RecipientController::class, 'changeDefault'])->name('recipients.update.default');
        Route::resource('recipients', RecipientController::class);
        Route::resource('shipments', ShipmentController::class);
        Route::resource('items', ShipmentItemController::class);
        Route::put('payments/methods/update/available', [PaymentMethodController::class, 'changeAvailable'])->name('methods.update.available');
        Route::resource('payments/methods', PaymentMethodController::class);
        Route::put('payments/update/status', [PaymentController::class, 'changeStatus'])->name('payments.update.status');
        
        Route::resources([
            'payments' => PaymentController::class,
            'senders' => SenderController::class,
            'sender-packets' => SenderPacketController::class,
            'costs/specials' => SpecialCostController::class,
            'costs' => CostController::class,
            'clusters' => ClusterController::class,
            'coverages' => ClusterCoverageController::class,
            'apis' => APIListController::class,
            'courrier' => CourrierPacketController::class
        ]);

        Route::prefix('dropdown')->controller(DropdownController::class)->as('dropdown.')->group(function () {
            Route::get('users', 'getUsers')->name('users');
            Route::get('recipients', 'getRecipients')->name('recipients');
            Route::get('senders', 'getSenders')->name('senders');
            Route::get('couriers', 'getCouriers')->name('couriers');
            Route::get('clusters', 'getClusters')->name('clusters');
        });
    });

    Route::prefix('dropdown')->controller(DropdownController::class)->as('dropdown.')->group(function () {
        Route::get('province', 'getProvinces')->name('provinces');
        Route::get('cities', 'getCities')->name('cities');
        Route::get('subdistricts', 'getSubdistricts')->name('subdistricts');
        Route::get('expeditions', 'getExpeditions')->name('expeditions');
        Route::get('payments/methods', 'getPaymentMethods')->name('payments.methods');
    });

    // Route Testing Kurir
    Route::middleware('Kurir')->group(function () {
        Route::prefix('testing')->controller(TestingCourierController::class)->as('testing.')->group(function () {
            Route::get('orders', 'index')->name('orders.index');
            Route::put('orders', 'pickup')->name('orders.pickup');
        });
    });
});

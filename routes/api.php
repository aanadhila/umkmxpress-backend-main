<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourierController;
use App\Http\Controllers\API\CourierWalletTransactionController;
use App\Http\Controllers\API\GetSubdistrictIdController;
use App\Http\Controllers\API\JourneyController;
use App\Http\Controllers\API\OngkirController;
use App\Http\Controllers\API\ShipmentController;
use App\Http\Controllers\API\StatusController;
use App\Http\Controllers\API\SenderController;
use App\Http\Controllers\API\RecipientController;
use App\Http\Controllers\API\WithdrawMethodController;
use App\Http\Controllers\API\ClusterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('clusters/{lat}/{lon}', [ClusterController::class,"getCluster"])->name('cluster');

Route::middleware('adsecurity')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('otp', 'requestOTP')->name('requestOTP');
        Route::post('validate', 'validateOTP');
        Route::post('register', 'register');
        Route::post('register/identity', '');
        Route::post('reset/otp', 'resetDailyOTP');
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(CourierController::class)->prefix('couriers')->group(function () {
            Route::post('store', 'store');
            Route::get('show', 'show');
            Route::middleware('role:Kurir')->group(function () {
                Route::post('update', 'update');
            });
            Route::controller(JourneyController::class)->middleware('role:Kurir')->prefix('journeys')->group(function () {
                Route::get('pickup', 'pickup');
                Route::get('delivery', 'delivery');
            });
        });

        Route::controller(CourierWalletTransactionController::class)->prefix('wallets')->group(function () {
            Route::middleware('role:Kurir')->group(function () {
                Route::get('/', 'index');
                Route::get('show/{id}', 'show');
                Route::post('store', 'store');
            });
        });

        Route::controller(WithdrawMethodController::class)->prefix('withdraws')->group(function () {
            Route::middleware('role:Kurir|Super Admin',)->group(function () {
                Route::get('/', 'index');
                Route::get('show/{id}', 'show');
            });
            Route::middleware('role:Super Admin')->group(function () {
                Route::post('store', 'store');
                Route::post('update/{id}', 'update');
            });
        });
        Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
            Route::middleware('role:Kurir|Super Admin')->group(function () {
                Route::get('/', 'index');
                Route::get('show/{id}', 'show');
                Route::post('create', 'create');
                Route::post('store', 'store');
                Route::post('update', 'update');
                Route::post('status/{id}', 'updateStatus');
                Route::get('image/{id}', 'getImageShipment');
            });
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
            // Route::middleware('role:Kurir|Super Admin')->group(function () {
            Route::get('/', 'index');
            Route::get('show/{id}', 'show');
            Route::post('store', 'store');
            Route::post('update', 'update');
            Route::post('status/{id}', 'updateStatus');
            Route::get('image/{id}', 'getImageShipment');
            // });
        });
    });

    Route::prefix('statuses')->controller(StatusController::class)->as('statuses.')->group(function () {
        Route::get('shipment', 'getShipmentStatus')->name('shipment');
    });

    Route::controller(ShipmentController::class)->prefix('shipments')->group(function () {
        Route::post('create', 'create');
    });

    Route::controller(OngkirController::class)->prefix('ongkir')->group(function () {
        Route::post('/', 'getOngkir');
    });

    Route::controller(SenderController::class)->prefix('senders')->group(function () {
        // Route::middleware('role:Kurir|Super Admin')->group(function () {
        Route::get('/', 'index');
        Route::get('show/{id}', 'show');
        Route::post('create', 'create');
        Route::post('store', 'store');
        Route::post('update', 'update');
        // });
    });

    Route::controller(RecipientController::class)->prefix('recipients')->group(function () {
        // Route::middleware('role:Kurir|Super Admin')->group(function () {
        Route::get('/', 'index');
        Route::get('show/{id}', 'show');
        Route::post('create', 'create');
        Route::post('store', 'store');
        Route::post('update', 'update');
        // });
    });
});

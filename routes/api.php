<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\Hotel\HotelBookingResourceController;
use App\Http\Controllers\Api\Hotel\HotelResourceController;
use App\Http\Controllers\Api\Hotel\HotelReviewResourceController;
use App\Http\Controllers\Api\Hotel\RoomResourceController;
use App\Http\Controllers\Api\TourBookingController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\PaymentWebhookController;
use App\Http\Controllers\SearchController;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('hotel', HotelResourceController::class) ->only(['index', 'show']);

Route::apiResource('room', RoomResourceController::class) ->only(['show']);

Route::apiResource('hotel', HotelResourceController::class)->only(['index', 'show']);
Route::apiResource('room', RoomResourceController::class)->only(['show']);
Route::apiResource('hotel', HotelResourceController::class) ->only(['index', 'show']);

Route::apiResource('room', RoomResourceController::class) ->only(['show']);

Route::get('tours/search', [SearchController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('hotel-bookings', HotelBookingResourceController::class)

    ->only(['index', 'store']);

    Route::apiResource('hotel-review', HotelReviewResourceController::class)

    ->only(['index', 'store']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::apiResource('hotel', HotelResourceController::class)->only(['index', 'show']);
Route::apiResource('room', RoomResourceController::class)->only(['show']);

//! login
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


//! tours
Route::prefix('tours')->group(function () {
    Route::get('/', [TourController::class, 'index']);
    Route::get('/recommended', [TourController::class, 'recommended']);
    Route::get('/available', [TourController::class, 'available']);
    Route::post('/compare', [TourController::class, 'compare']);
    Route::get('/{id}', [TourController::class, 'show']);
});

Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    //! favorites
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/', [FavoriteController::class, 'store']);
        Route::delete('/{tourId}', [FavoriteController::class, 'destroy']);
    });

    //! bookings
    Route::post('/bookings', [BookingController::class, 'store']);

    //! tour bookings
    Route::prefix('tour-bookings')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TourBookingController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\TourBookingController::class, 'store']);
        Route::get('/{booking}', [\App\Http\Controllers\Api\TourBookingController::class, 'show']);
    });
});

//! hotel
Route::apiResource('hotel', HotelResourceController::class) ->only(['index', 'show']);
Route::apiResource('room', RoomResourceController::class) ->only(['show']);


Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/profile', [AuthController::class, 'show']);
    Route::put('/profile', [AuthController::class, 'update']);

    Route::get('/flight-seats/{id}', [BookingController::class, 'getFlightSeat']);
    Route::post('/book-flight/{flightId}', [BookingController::class, 'bookFlight']);

    Route::post('/book-car', [BookingController::class, 'bookCar']);

    Route::post('/search-flights', [BookingController::class, 'searchFlights']);


    Route::post('search-car', [BookingController::class, 'searchCar']);

    Route::post('create-payment-intent/{bookingId}', [PaymentController::class, 'makePayment']);

    Route::get('get-user-bookings',[BookingController::class,'getUserBookings']);
});

Route::post('webhook/stripe', [PaymentWebhookController::class, 'handle']);

Route::get('all-flights', function(){
    return Flight::all();
});

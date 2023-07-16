<?php

use App\Http\Controllers\API\AdminDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\BorrowController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\RoomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->middleware('admin');
    Route::apiResource('rooms', RoomController::class);
    Route::apiResource('items', ItemController::class);
    Route::apiResource('dashboard-admin', AdminDashboardController::class)->middleware('admin');

    Route::apiResource('borrows', BorrowController::class);
    Route::post('borrows/{id}/return', [BorrowController::class, 'returnBorrow']);
    Route::get('borrows/send-reminder', [BorrowController::class, 'sendReturnReminder']);
    Route::get('borrows/submit-request/{borrow_code}', [BorrowController::class, 'submitBorrowRequest']);
    Route::post('borrows/verify-request/{borrow_code}', [BorrowController::class, 'verifySubmitBorrowRequest']);
    Route::post('borrows/{id}/reject-request', [BorrowController::class, 'rejectBorrowRequest']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\Borrower\BorrowController as BorrowerBorrowController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BorrowReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\App;

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

Route::get('/', function () {
    // App::setLocale($locale);
    return view('landing_page.index');
});
Route::get('/template', function () {
    return view('layouts.main');
});

Route::get('locale/{locale}', function ($locale) {
    // if (!in_array($locale, ['en', 'id'])) {
    //     abort(400);
    // }

    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
})->name('locale');
//Route Yang Tidak Perlu Login
Route::view('/about', 'about.index');
// Route Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact-send-to-whatsapp', [ContactController::class, 'sendToWhatsapp'])->name('contact.send-to-whatsapp');
Route::post('/contact-send-to-email', [ContactController::class, 'sendToEmail'])->name('contact.send-to-email');
Auth::routes(['verify' => TRUE]);


Route::middleware('verified')->group(function () {


    Route::middleware('admin')->group(function () {
        // route yang hanya bisa diakses oleh admin
        Route::resource('/admin/users', UserController::class)->names('admin.users');
        Route::resource('/admin/rooms', RoomController::class)->names('admin.rooms');
        Route::resource('/admin/items', ItemController::class)->names('admin.items');
        Route::resource('/admin/borrows', BorrowController::class)->names('admin.borrows');
        Route::get('/admin/borrows/{borrow_code}/submit-borrow-request', [BorrowController::class, 'submitBorrowRequest']);
        Route::put('/admin/borrows/{borrow_code}/verify-submit-borrow-request', [BorrowController::class, 'verifySubmitBorrowRequest']);
        Route::put('/admin/borrows/{id}/return', [BorrowController::class, 'returnBorrow'])->name('borrows.return');
        Route::put('/admin/borrows/{id}/reject-borrow-request', [BorrowController::class, 'rejectBorrowRequest'])->name('admin.borrows.return');
        Route::post('/admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::get('/borrow-report', [BorrowReportController::class, 'index'])->name('borrow-report.index');
        Route::post('/borrow-report', [BorrowReportController::class, 'generateReport'])->name('borrow-report.generate');
        Route::post('/borrow-report/export/', [BorrowReportController::class, 'export'])->name('borrow-report.export');
    });

    Route::middleware(['operator'])->group(function () {
        // route yang hanya bisa diakses oleh operator
        Route::resource('/operator/rooms', RoomController::class)->only(['index', 'show'])->names('operator.rooms');
        Route::resource('/operator/items', ItemController::class)->names('operator.items');
        Route::resource('/operator/borrows', BorrowController::class)->names('operator.borrows');
        Route::get('/operator/borrows/{borrow_code}/submit-borrow-request', [BorrowController::class, 'submitBorrowRequest']);
        Route::put('/operator/borrows/{borrow_code}/verify-submit-borrow-request', [BorrowController::class, 'verifySubmitBorrowRequest']);
        Route::put('/operator/borrows/{id}/return', [BorrowController::class, 'returnBorrow'])->name('operator.borrows.return');
    });

    Route::middleware('borrower')->group(function () {
        // route yang hanya bisa diakses oleh borrower
        // Route::get('/borrower/dashboard', 'BorrowerController@dashboard');
        Route::resource('/borrower/borrows', BorrowerBorrowController::class);
        Route::get('/borrower/borrows/create/search-item', [BorrowerBorrowController::class, 'searchItemView']);
        Route::post('/borrower/borrows/create/search-item', [BorrowerBorrowController::class, 'searchItem'])->name('borrower.borrow.search-item');
        Route::get('/borrower/borrows/create/{item_code}/submit-borrow-request', [BorrowerBorrowController::class, 'submitBorrowRequestView']);
        Route::get('/borrower/borrows/create/submit-borrow-request-two', [BorrowerBorrowController::class, 'submitBorrowRequestViewTwo']);
        Route::post('/borrower/borrows/create/submit-borrow-request-two', [BorrowerBorrowController::class, 'submitBorrowRequestViewTwo']);
        Route::post('/borrower/borrows/create/submit-borrow-request-page-two', [BorrowerBorrowController::class, 'submitBorrowRequestViewTwo']);
        Route::post('/borrower/borrows/create/submit-borrow-request-verifiy/', [BorrowerBorrowController::class, 'verifySubmitBorrowRequestView']);
        Route::get('/borrower/borrows/create/submit-borrow-request-verifiy/', [BorrowerBorrowController::class, 'verifySubmitBorrowRequestView']);
    });

    Route::middleware('auth')->group(function () {
        // route yang hanya bisa diakses oleh borrower
        // Route::get('/borrower/dashboard', 'BorrowerController@dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

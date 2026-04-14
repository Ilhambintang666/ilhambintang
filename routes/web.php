<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Auth\LoginController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Item;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminLoanController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\LaporanBarangController;

// ============================================
// GUEST ROUTES (Must be before auth middleware)
// ============================================

// Root URL - Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes (must be accessible without auth)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register-peminjam', [RegisterController::class, 'showPeminjamForm'])->name('register.peminjam');
Route::post('/register-peminjam', [RegisterController::class, 'registerPeminjam'])->name('register.peminjam.submit');

// Public QR code route
Route::get('/items/{id}/qrcode', function ($id) {
    $item = Item::findOrFail($id);
    return view('items.qrcode', ['item' => $item]);
})->name('items.qrcode');

Route::get('/qr', function () {
    return QrCode::size(300)->generate('Halo dari Laravel!');
});

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {

    // Dashboard - redirect based on role
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // User routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
        Route::get('/borrow', [App\Http\Controllers\UserDashboardController::class, 'borrow'])->name('borrow');
        Route::get('/my-borrowings', [App\Http\Controllers\UserDashboardController::class, 'myBorrowings'])->name('my-borrowings');
        Route::get('/profile', [App\Http\Controllers\UserDashboardController::class, 'profile'])->name('profile');
        Route::post('/borrow', [App\Http\Controllers\LoanController::class, 'borrow'])->name('borrow.submit');
        Route::post('/borrow/bulk', [App\Http\Controllers\LoanController::class, 'bulkBorrow'])->name('borrow.bulk');
        Route::post('/borrow/quantity', [App\Http\Controllers\LoanController::class, 'bulkBorrowByQuantity'])->name('borrow.quantity');
        Route::post('/loans/{loan}/return', [App\Http\Controllers\LoanController::class, 'submitReturn'])->name('return');
        Route::post('/loans/bulk-return', [App\Http\Controllers\LoanController::class, 'bulkReturn'])->name('loans.bulk-return');
        Route::post('/profile/update', [App\Http\Controllers\UserDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\UserDashboardController::class, 'updatePassword'])->name('profile.password');
    });

    // Loan return routes
    Route::post('/loans/{id}/return', [LoanController::class, 'submitReturn']);

    // Resource Routes
    Route::resource('categories', CategoryController::class);
    Route::resource('locations', LocationController::class);
    
    // Items Routes
    Route::post('items/bulk-destroy', [ItemController::class, 'bulkDestroy'])->name('items.bulk-destroy');
    Route::resource('items', ItemController::class);
    
    Route::resource('borrowings', BorrowingController::class);

    // Custom Routes untuk Borrowing
    Route::post('borrowings/{borrowing}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');
    Route::post('borrowings/bulk-return', [BorrowingController::class, 'bulkReturn'])->name('borrowings.bulk-return');
    Route::post('borrowings/bulk-return-by-borrower', [BorrowingController::class, 'bulkReturnByBorrower'])->name('borrowings.bulk-return-by-borrower');
    Route::post('borrowings/bulk-return-by-category', [BorrowingController::class, 'bulkReturnByCategory'])->name('borrowings.bulk-return-by-category');
    Route::post('borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    Route::post('borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');

    // ============================================
    // ADMIN ROUTES
    // ============================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

        // Admin resources
        Route::resource('users', App\Http\Controllers\UserController::class)->except(['create', 'store', 'show']);
        Route::resource('peminjaman', AdminLoanController::class)->only(['index']);

        // Peminjaman routes
        Route::get('/peminjaman', [AdminLoanController::class, 'index'])->name('peminjaman.index');
        Route::get('/pengembalian-pending', [AdminLoanController::class, 'returnIndex'])->name('peminjaman.returns');
        Route::post('/peminjaman/group/{id}/approve', [AdminLoanController::class, 'approveGroup'])->name('peminjaman.group.approve');
        Route::post('/peminjaman/group/{id}/reject', [AdminLoanController::class, 'rejectGroup'])->name('peminjaman.group.reject');
        Route::post('/peminjaman/{id}/confirm-return', [AdminLoanController::class, 'confirmReturn'])->name('peminjaman.confirm-return');
        
        // Return approval/rejection
        Route::post('/returns/{id}/approve', [AdminLoanController::class, 'approveReturn'])->name('returns.approve');
        Route::post('/returns/{id}/reject', [AdminLoanController::class, 'rejectReturn'])->name('returns.reject');

        // Verifikasi and return routes
        Route::put('/peminjaman/{id}/verifikasi', [PeminjamanController::class, 'verifikasiReturn'])->name('peminjaman.verifikasi');
        Route::put('/peminjaman/{id}/tolak-return', [PeminjamanController::class, 'tolakReturn'])->name('peminjaman.tolakReturn');

        // Laporan routes
        Route::get('/laporan-barang', [\App\Http\Controllers\LaporanBarangController::class, 'index'])->name('laporan.barang');
        Route::get('/laporan-barang/pdf', [\App\Http\Controllers\LaporanBarangController::class, 'pdf'])->name('laporan.barang.pdf');
        Route::get('/laporan-barang/excel', [\App\Http\Controllers\LaporanBarangController::class, 'excel'])->name('laporan.barang.excel');
    });
});

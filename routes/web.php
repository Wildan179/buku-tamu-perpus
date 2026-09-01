<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\VisitorController;
use App\Models\Book;
use App\Models\Visitor;
use App\Models\Member;
use App\Models\Borrowing;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Dashboard dengan data dinamis dari database
    Route::get('/dashboard', function () {
        $totalBuku = Book::count();
        $totalAnggota = Member::count();
        $sedangDipinjam = Borrowing::whereIn('status', ['active', 'late'])->count();
        
        $aktivitasTerbaru = Borrowing::with(['member', 'book'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalBuku', 'totalAnggota', 'sedangDipinjam', 'aktivitasTerbaru'));
    })->name('dashboard');

    Route::resource('buku', BookController::class);
    Route::resource('anggota', MemberController::class);
    Route::resource('peminjaman', BorrowingController::class);

    // Route Buku Tamu
    Route::get('/tamu', [VisitorController::class, 'index'])->name('tamu.index');
    Route::post('/tamu', [VisitorController::class, 'store'])->name('tamu.store');
    Route::delete('/tamu/{id}', [VisitorController::class, 'destroy'])->name('tamu.destroy');
});

Route::get('/', function () {
    return redirect('/login');
});
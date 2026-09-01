<?php

use App\Models\Visitor;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use Carbon\Carbon;

public function index()
{
    $totalBuku = Book::count();
    $totalAnggota = Member::count();
    $totalDipinjam = Borrowing::where('status', 'active')->count();
    
    // Logika menghitung jumlah pengunjung dari Senin sampai Minggu minggu ini
    $startOfWeek = Carbon::now()->startOfWeek();
    $weeklyVisitors = [];
    
    for ($i = 0; $i < 7; $i++) {
        $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
        // Menghitung data berdasarkan kolom 'visit_date' di tabel visitors
        $count = Visitor::whereDate('visit_date', $date)->count();
        $weeklyVisitors[] = $count;
    }

    $recentBorrowings = Borrowing::with(['member', 'book'])->latest()->take(5)->get();

    return view('admin.dashboard', compact(
        'totalBuku', 
        'totalAnggota', 
        'totalDipinjam', 
        'weeklyVisitors', 
        'recentBorrowings'
    ));
}
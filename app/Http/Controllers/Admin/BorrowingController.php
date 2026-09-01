<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Fine charged per day of late return (in Rupiah).
     */
    private const FINE_PER_DAY = 2000;

    /**
     * Display a paginated, searchable, filterable list of borrowings.
     */
    public function index(Request $request): View
    {
        // Auto-flag any active borrowing whose due date has already passed.
        Borrowing::where('status', 'active')
            ->whereDate('due_date', '<', Carbon::today())
            ->update(['status' => 'late']);

        $query = Borrowing::with(['book', 'member']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('member', fn ($m) => $m->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn ($b) => $b->where('title', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->orderByDesc('borrow_date')->paginate(10)->withQueryString();

        $books = Book::where('stock', '>', 0)->orderBy('title')->get();
        $members = Member::orderBy('name')->get();

        return view('admin.peminjaman.index', compact('borrowings', 'books', 'members'));
    }

    /**
     * Record a new borrowing and decrement the book's stock.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id'   => 'required|exists:members,id',
            'book_id'     => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date'    => 'required|date|after_or_equal:borrow_date',
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('error', 'Stok buku tidak tersedia untuk dipinjam.');
        }

        $validated['status'] = 'active';
        $validated['fine_amount'] = 0;

        Borrowing::create($validated);

        $book->decrement('stock');

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    /**
     * Process a book return: set return date, calculate fine if late,
     * update status, and restock the book.
     */
    public function update(Request $request, Borrowing $peminjaman): RedirectResponse
    {
        if ($peminjaman->return_date) {
            return redirect()
                ->route('admin.peminjaman.index')
                ->with('error', 'Peminjaman ini sudah tercatat dikembalikan.');
        }

        $request->validate([
            'return_date' => 'required|date',
        ]);

        $returnDate = Carbon::parse($request->return_date)->startOfDay();
        $dueDate = Carbon::parse($peminjaman->due_date)->startOfDay();

        $fine = 0;
        if ($returnDate->gt($dueDate)) {
            $daysLate = $dueDate->diffInDays($returnDate);
            $fine = $daysLate * self::FINE_PER_DAY;
        }

        $peminjaman->update([
            'return_date' => $returnDate,
            'status'      => 'returned',
            'fine_amount' => $fine,
        ]);

        $peminjaman->book()->increment('stock');

        $message = 'Buku berhasil dikembalikan.';
        if ($fine > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($fine, 0, ',', '.');
        }

        return redirect()->route('admin.peminjaman.index')->with('success', $message);
    }

    /**
     * Delete a borrowing record. If it was still active, restock the book.
     */
    public function destroy(Borrowing $peminjaman): RedirectResponse
    {
        if (in_array($peminjaman->status, ['active', 'late'], true)) {
            $peminjaman->book()->increment('stock');
        }

        $peminjaman->delete();

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
    /**
     * Show the form for creating a new borrowing.
     */
    public function create()
    {
        $books = \App\Models\Book::where('stock', '>', 0)->get();
        $members = \App\Models\Member::all(); 
        
        return view('admin.peminjaman.create', compact('books', 'members'));
    }
}

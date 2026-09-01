<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    /**
     * Display a paginated, searchable list of books.
     */
    public function index(Request $request): View
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books = $query->orderBy('title')->paginate(10)->withQueryString();

        $categories = Book::whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.buku.index', compact('books', 'categories'));
    }

    /**
     * Store a newly created book.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'category'     => 'nullable|string|max:100',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($validated);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku baru berhasil ditambahkan.');
    }

    /**
     * Update the specified book.
     */
    public function update(Request $request, Book $buku): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'author'       => 'required|string|max:255',
            'category'     => 'nullable|string|max:100',
            'stock'        => 'required|integer|min:0',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($buku->cover_image) {
                Storage::disk('public')->delete($buku->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $buku->update($validated);

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    /**
     * Show the form for creating a new book.
     */
    public function create(): View
    {
        return view('admin.buku.create');
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $buku): View
    {
        return view('admin.buku.edit', compact('buku'));
    }

    /**
     * Remove the specified book, unless it still has an active borrowing.
     */
    public function destroy(Book $buku): RedirectResponse
    {
        if ($buku->borrowings()->whereIn('status', ['active', 'late'])->exists()) {
            return redirect()
                ->route('admin.buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih dalam status dipinjam.');
        }

        if ($buku->cover_image) {
            Storage::disk('public')->delete($buku->cover_image);
        }

        $buku->delete();

        return redirect()
            ->route('admin.buku.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}

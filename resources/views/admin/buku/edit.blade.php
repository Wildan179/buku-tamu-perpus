@extends('layouts.admin')

@section('title', 'Edit Buku - Sistem Perpus')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="font-serif text-3xl font-bold text-gold">Edit Buku</h1>
        <p class="text-sm text-goldmuted mt-1">Perbarui informasi detail koleksi buku perpustakaan.</p>
    </div>

    {{-- Perhatikan parameter $buku->id --}}
    <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST" class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6 space-y-5">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Judul Buku</label>
            <input type="text" name="title" value="{{ old('title', $buku->title) }}" required class="w-full rounded-lg p-3 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold focus:ring-1 focus:ring-gold outline-none">
            @error('title') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Penulis</label>
            <input type="text" name="author" value="{{ old('author', $buku->author) }}" required class="w-full rounded-lg p-3 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold focus:ring-1 focus:ring-gold outline-none">
            @error('author') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Penerbit</label>
            <input type="text" name="publisher" value="{{ old('publisher', $buku->publisher) }}" required class="w-full rounded-lg p-3 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold focus:ring-1 focus:ring-gold outline-none">
            @error('publisher') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Stok</label>
            <input type="number" name="stock" value="{{ old('stock', $buku->stock) }}" required min="0" class="w-full rounded-lg p-3 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold focus:ring-1 focus:ring-gold outline-none">
            @error('stock') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gold/10">
            <a href="{{ route('admin.buku.index') }}" class="bg-inputgreen border border-gold/30 text-cream px-4 py-2 rounded-lg text-sm hover:bg-gold/10 transition">Batal</a>
            <button type="submit" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-5 py-2 rounded-lg text-sm transition">Perbarui Buku</button>
        </div>
    </form>
</div>
@endsection
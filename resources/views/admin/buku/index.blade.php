@extends('layouts.admin')

@section('title', 'Kelola Buku - Sistem Perpus')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-3xl font-bold text-gold">Kelola Buku</h1>
        <p class="text-sm text-goldmuted mt-1">Daftar seluruh koleksi buku perpustakaan.</p>
    </div>
    <a href="{{ route('admin.buku.create') }}" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-lg">
        + Tambah Buku
    </a>
</div>

{{-- Search & Category Filter --}}
<form method="GET" action="{{ route('admin.buku.index') }}" class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-4">
    <div class="flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau kategori..." class="w-full rounded-lg p-2.5 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold outline-none placeholder-goldmuted">
    </div>
    <div class="w-full md:w-48">
        <select name="category" class="w-full rounded-lg p-2.5 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold outline-none" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-gold/20 border border-gold/40 text-gold hover:bg-gold hover:text-emeraldbg px-4 py-2 rounded-lg text-sm font-semibold transition">
        Filter
    </button>
</form>

{{-- Kotak Stat Total Buku & Total Stok (Dipindah ke bawah, diperbesar, dan didesain seperti card utama) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5 flex items-center justify-between">
        <div>
            <span class="block text-xs uppercase tracking-wider text-goldmuted font-semibold">Total Judul Buku</span>
            <span class="text-2xl font-bold text-cream mt-1 block">{{ \App\Models\Book::count() }} <span class="text-xs font-normal text-goldmuted">judul</span></span>
        </div>
        <div class="w-12 h-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-gold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
    </div>

    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5 flex items-center justify-between">
        <div>
            <span class="block text-xs uppercase tracking-wider text-goldmuted font-semibold">Total Seluruh Stok</span>
            <span class="text-2xl font-bold mt-1 block">{{ \App\Models\Book::sum('stock') }} <span class="text-xs font-normal text-goldmuted">eksemplar</span></span>
        </div>
        <div class="w-12 h-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-gold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
    </div>
</div>

<div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gold/20 text-gold text-xs uppercase tracking-wider bg-inputgreen/50">
                <th class="p-4">Judul Buku</th>
                <th class="p-4">Penulis</th>
                <th class="p-4">Kategori</th>
                <th class="p-4 text-center">Stok</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gold/10 text-sm text-cream/90">
            @forelse($books as $index => $buku)
                <tr class="hover:bg-gold/5 transition">
                    <td class="p-4 font-medium text-cream">{{ $buku->title }}</td>
                    <td class="p-4 text-cream/80">{{ $buku->author }}</td>
                    <td class="p-4 text-cream/80">{{ $buku->category ?? '-' }}</td>
                    <td class="p-4 text-center text-gold font-semibold">{{ $buku->stock }}</td>
                    <td class="p-4 text-center space-x-3">
                        <a href="{{ route('admin.buku.edit', $buku->id) }}" class="text-gold hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline text-xs font-semibold" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-goldmuted italic">Tidak ada data buku ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $books->links() }}
</div>
@endsection
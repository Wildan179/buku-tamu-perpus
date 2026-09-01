@extends('layouts.admin')

@section('title', 'Tambah Peminjaman - Sistem Perpus')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="font-serif text-3xl font-bold text-gold">Catat Peminjaman Buku</h1>
        <p class="text-sm text-goldmuted mt-1">Pilih anggota, buku, dan tentukan masa pinjam.</p>
    </div>

    <form action="{{ route('admin.peminjaman.store') }}" method="POST" class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Anggota</label>
            <select name="member_id" required class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
                <option value="" disabled selected>Pilih Anggota</option>
                @foreach($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->name }}
                    </option>
                @endforeach
            </select>
            @error('member_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Buku (Tersedia)</label>
            <select name="book_id" required class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
                <option value="" disabled selected>Pilih Buku</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->title }} (Stok: {{ $book->stock }})
                    </option>
                @endforeach
            </select>
            @error('book_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Tanggal Pinjam</label>
            <input type="date" name="borrow_date" required value="{{ old('borrow_date', date('Y-m-d')) }}" style="color-scheme: dark;" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
            @error('borrow_date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Tanggal Jatuh Tempo (Kembali)</label>
            <input type="date" name="due_date" required value="{{ old('due_date', \Carbon\Carbon::today()->addDays(7)->format('Y-m-d')) }}" style="color-scheme: dark;" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
            @error('due_date') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gold/10">
            <a href="{{ route('admin.peminjaman.index') }}" class="bg-[#0d2a18] border border-gold/30 text-cream px-4 py-2 rounded-lg text-sm hover:bg-gold/10 transition">Batal</a>
            <button type="submit" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-5 py-2 rounded-lg text-sm transition">Simpan Peminjaman</button>
        </div>
    </form>
</div>
@endsection
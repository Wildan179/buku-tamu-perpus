@extends('layouts.admin')

@section('title', 'Kelola Peminjaman - Sistem Perpus')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="font-serif text-3xl font-bold text-gold">Peminjaman Buku</h1>
        <p class="text-sm text-goldmuted mt-1">Data peminjaman buku & pengembalian.</p>
    </div>
    <a href="{{ route('admin.peminjaman.create') }}" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-lg">
        + Catat Peminjaman
    </a>
</div>

{{-- Filter & Search Form --}}
<form method="GET" action="{{ route('admin.peminjaman.index') }}" class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-4">
    <div class="flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama anggota atau judul buku..." class="w-full rounded-lg p-2.5 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold outline-none placeholder-goldmuted">
    </div>
    <div class="w-full md:w-48">
        <select name="status" class="w-full rounded-lg p-2.5 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold outline-none" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Dipinjam</option>
            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Terlambat</option>
            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Dikembalikan</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="bg-gold/20 border border-gold/40 text-gold hover:bg-gold hover:text-emeraldbg px-4 py-2 rounded-lg text-sm font-semibold transition">
            Filter
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.peminjaman.index') }}" class="bg-[#0d2a18] border border-gold/20 text-cream/70 hover:text-cream px-4 py-2 rounded-lg text-sm transition flex items-center">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Data Table --}}
<div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gold/20 text-gold text-xs uppercase tracking-wider bg-inputgreen/50">
                    <th class="p-4">No</th>
                    <th class="p-4">Anggota</th>
                    <th class="p-4">Buku</th>
                    <th class="p-4">Tgl Pinjam</th>
                    <th class="p-4">Jatuh Tempo</th>
                    <th class="p-4">Tgl Kembali</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gold/10 text-sm text-cream/90">
                @forelse($borrowings as $index => $item)
                    <tr class="hover:bg-gold/5 transition">
                        <td class="p-4 text-goldmuted">{{ $borrowings->firstItem() + $index }}</td>
                        <td class="p-4 font-medium text-cream">{{ $item->member->name ?? '-' }}</td>
                        <td class="p-4 text-cream/80">{{ $item->book->title ?? '-' }}</td>
                        <td class="p-4 text-cream/80">{{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/Y') }}</td>
                        <td class="p-4 text-cream/80">{{ \Carbon\Carbon::parse($item->due_date)->format('d/m/Y') }}</td>
                        <td class="p-4 text-cream/80">
                            {{ $item->return_date ? \Carbon\Carbon::parse($item->return_date)->format('d/m/Y') : '-' }}
                        </td>
                        <td class="p-4 font-semibold {{ $item->fine_amount > 0 ? 'text-red-400' : 'text-cream/50' }}">
                            Rp {{ number_format($item->fine_amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            @if($item->status == 'active')
                                <span class="bg-gold/20 text-gold border border-gold/30 px-2.5 py-1 rounded-full text-xs font-semibold">Dipinjam</span>
                            @elseif($item->status == 'late')
                                <span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2.5 py-1 rounded-full text-xs font-semibold">Terlambat</span>
                            @else
                                <span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2.5 py-1 rounded-full text-xs font-semibold">Dikembalikan</span>
                            @endif
                        </td>
                        <td class="p-4 text-center space-x-2 whitespace-nowrap">
                            @if(!$item->return_date)
                                {{-- Form Pengembalian Buku --}}
                                <form action="{{ route('admin.peminjaman.update', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="return_date" value="{{ date('Y-m-d') }}">
                                    <button type="submit" onclick="return confirm('Proses pengembalian buku hari ini?')" class="text-gold hover:underline text-xs font-semibold">
                                        Kembalikan
                                    </button>
                                </form>
                                <span class="text-goldmuted/40">|</span>
                            @endif

                            <form action="{{ route('admin.peminjaman.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-400 hover:underline text-xs font-semibold">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-goldmuted italic">Tidak ada data peminjaman ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
</div>
@endsection 
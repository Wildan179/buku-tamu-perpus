@extends('layouts.admin')

@section('title', 'Buku Tamu - Sistem Perpus')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-3xl font-bold text-gold">Buku Tamu Perpustakaan</h1>
        <p class="text-sm text-goldmuted mt-1">Catat dan pantau daftar pengunjung harian.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Form Tambah Tamu --}}
    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6 h-fit">
        <h2 class="font-serif text-xl font-bold text-gold mb-4">Catat Kunjungan Baru</h2>
        <form action="{{ route('admin.tamu.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Nama Tamu</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none placeholder-goldmuted/50" placeholder="...">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Status Pengunjung</label>
                <select name="status" required class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
                    <option value="" disabled selected>
                        
                    </option>
                    <option value="Siswa" {{ old('status') == 'Siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="Mahasiswa" {{ old('status') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="Guru / Dosen" {{ old('status') == 'Guru / Dosen' ? 'selected' : '' }}>Guru / Dosen</option>
                    <option value="Umum" {{ old('status') == 'Umum' ? 'selected' : '' }}>Umum</option>
                </select>
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none placeholder-goldmuted/50" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Keperluan</label>
                <input type="text" name="purpose" value="{{ old('purpose') }}" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none placeholder-goldmuted/50" placeholder="...">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-wider text-gold font-semibold mb-2">Tanggal Kunjungan</label>
                <input type="date" name="visit_date" required value="{{ old('visit_date', date('Y-m-d')) }}" style="color-scheme: dark;" class="w-full rounded-lg p-3 text-sm text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
            </div>
            <button type="submit" class="w-full bg-gold hover:bg-goldmuted text-emeraldbg font-semibold py-2.5 rounded-lg text-sm transition">Simpan Tamu</button>
        </form>
    </div>

    {{-- Tabel Daftar Tamu & Filter Pencarian --}}
    <div class="lg:col-span-2 space-y-4">
        {{-- Form Filter Pencarian --}}
        <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-4">
            <form action="{{ route('admin.tamu.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-gold font-semibold mb-1">Cari Nama</label>
                    <input type="text" name="name" value="{{ request('name') }}" class="w-full rounded-lg p-2.5 text-xs text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none placeholder-goldmuted/50" placeholder="Nama tamu...">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-gold font-semibold mb-1">Tanggal Masuk</label>
                    <input type="date" name="visit_date" value="{{ request('visit_date') }}" style="color-scheme: dark;" class="w-full rounded-lg p-2.5 text-xs text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-wider text-gold font-semibold mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ request('no_hp') }}" class="w-full rounded-lg p-2.5 text-xs text-cream bg-[#0d2a18] border border-gold/30 focus:border-gold outline-none placeholder-goldmuted/50" placeholder="No HP...">
                </div>
                <div class="sm:col-span-3 flex justify-end gap-2 pt-1">
                    @if(request()->filled('name') || request()->filled('visit_date') || request()->filled('no_hp'))
                        <a href="{{ route('admin.tamu.index') }}" class="bg-transparent hover:bg-gold/10 text-gold border border-gold/30 font-semibold px-4 py-2 rounded-lg text-xs transition">Reset</a>
                    @endif
                    <button type="submit" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-4 py-2 rounded-lg text-xs transition">Filter Data</button>
                </div>
            </form>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl overflow-hidden">
            <div class="p-4 border-b border-gold/20 bg-inputgreen/50 flex justify-between items-center">
                <h2 class="font-serif text-lg font-bold text-gold">Riwayat Pengunjung</h2>
                <span class="text-xs text-goldmuted">Total: {{ $visitors->total() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gold/20 text-gold text-xs uppercase tracking-wider">
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">No. HP</th>
                            <th class="p-4">Keperluan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gold/10 text-sm text-cream/90">
                        @forelse($visitors as $visitor)
                            <tr class="hover:bg-gold/5 transition">
                                <td class="p-4 text-gold">{{ \Carbon\Carbon::parse($visitor->visit_date)->format('d/m/Y') }}</td>
                                <td class="p-4 font-medium text-cream">{{ $visitor->name }}</td>
                                <td class="p-4">
                                    <span class="bg-gold/10 border border-gold/30 text-gold px-2.5 py-1 rounded-full text-xs font-medium">{{ $visitor->status }}</span>
                                </td>
                                <td class="p-4 text-cream/80">{{ $visitor->no_hp ?? '-' }}</td>
                                <td class="p-4 text-cream/80">{{ $visitor->purpose ?? '-' }}</td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('admin.tamu.destroy', $visitor->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:underline text-xs font-semibold" onclick="return confirm('Hapus data tamu ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-goldmuted italic">Tidak ada data pengunjung yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">
                {{ $visitors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
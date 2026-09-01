@extends('layouts.admin')

@section('title', 'Data Anggota - Sistem Perpus')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="font-serif text-3xl font-bold text-gold">Data Anggota</h1>
        <p class="text-sm text-goldmuted mt-1">Daftar seluruh anggota perpustakaan yang terdaftar.</p>
    </div>
    <a href="{{ route('admin.anggota.create') }}" class="bg-gold hover:bg-goldmuted text-emeraldbg font-semibold px-5 py-2.5 rounded-lg text-sm transition shadow-lg">
        + Tambah Anggota
    </a>
</div>

@if(session('success'))
    <div class="p-4 mb-6 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Search Filter --}}
<form method="GET" action="{{ route('admin.anggota.index') }}" class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-4 mb-6 flex flex-col md:flex-row gap-4">
    <div class="flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kode anggota, atau email..." class="w-full rounded-lg p-2.5 text-sm text-cream bg-inputgreen border border-gold/20 focus:border-gold outline-none placeholder-goldmuted">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="bg-gold/20 border border-gold/40 text-gold hover:bg-gold hover:text-emeraldbg px-5 py-2.5 rounded-lg text-sm font-semibold transition">
            Cari
        </button>
        @if(request('search'))
            <a href="{{ route('admin.anggota.index') }}" class="bg-red-500/20 border border-red-500/40 text-red-400 hover:bg-red-500 hover:text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition flex items-center">
                Reset
            </a>
        @endif
    </div>
</form>

{{-- Kotak Stat Total Anggota --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5 flex items-center justify-between">
        <div>
            <span class="block text-xs uppercase tracking-wider text-goldmuted font-semibold">Total Anggota Terdaftar</span>
            <span class="text-2xl font-bold text-cream mt-1 block">{{ $members->total() ?? count($members) }} <span class="text-xs font-normal text-goldmuted">orang</span></span>
        </div>
        <div class="w-12 h-12 rounded-lg bg-gold/10 border border-gold/30 flex items-center justify-center text-gold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
    </div>
</div>

{{-- Tabel Data Anggota --}}
<div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gold/20 text-gold text-xs uppercase tracking-wider bg-inputgreen/50">
                <th class="p-4">Kode</th>
                <th class="p-4">Nama Lengkap</th>
                <th class="p-4">Email</th>
                <th class="p-4">No. HP</th>
                <th class="p-4">Alamat</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gold/10 text-sm text-cream/90">
            @forelse($members as $member)
                <tr class="hover:bg-gold/5 transition">
                    <td class="p-4 font-mono text-gold font-bold">{{ $member->member_code }}</td>
                    <td class="p-4 font-medium text-cream">{{ $member->name }}</td>
                    <td class="p-4 text-cream/80">{{ $member->email }}</td>
                    <td class="p-4 text-cream/80">{{ $member->phone ?? '-' }}</td>
                    <td class="p-4 text-xs max-w-xs truncate text-cream/80">{{ $member->address ?? '-' }}</td>
                    <td class="p-4 text-center space-x-3">
                        <a href="{{ route('admin.anggota.edit', $member->id) }}" class="text-gold hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.anggota.destroy', $member->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:underline text-xs font-semibold" onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-goldmuted italic">Tidak ada data anggota ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $members->links() }}
</div>
@endsection
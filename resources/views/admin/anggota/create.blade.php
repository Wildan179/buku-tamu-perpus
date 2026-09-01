@extends('layouts.admin')

@section('title', 'Tambah Anggota - Sistem Perpus')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-serif text-3xl font-bold text-gold">Tambah Anggota</h1>
            <p class="text-sm text-goldmuted mt-1">Isi formulir berikut untuk menambahkan anggota baru</p>
        </div>
        <a href="{{ route('admin.anggota.index') }}" class="text-goldmuted hover:text-cream text-sm flex items-center gap-1 transition-colors">
            &larr; Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6">
        <form action="{{ route('admin.anggota.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs text-goldmuted mb-1.5 uppercase font-semibold">Kode Anggota</label>
                <input type="text" name="member_code" value="{{ old('member_code') }}" required placeholder="Contoh: AGT-001" class="w-full bg-[#0d2a18] border border-gold/20 rounded-lg px-4 py-2.5 text-sm text-cream focus:outline-none focus:border-gold">
            </div>

            <div>
                <label class="block text-xs text-goldmuted mb-1.5 uppercase font-semibold">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama lengkap anggota" class="w-full bg-[#0d2a18] border border-gold/20 rounded-lg px-4 py-2.5 text-sm text-cream focus:outline-none focus:border-gold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-goldmuted mb-1.5 uppercase font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com" class="w-full bg-[#0d2a18] border border-gold/20 rounded-lg px-4 py-2.5 text-sm text-cream focus:outline-none focus:border-gold">
                </div>
                <div>
                    <label class="block text-xs text-goldmuted mb-1.5 uppercase font-semibold">No. HP (Opsional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789" class="w-full bg-[#0d2a18] border border-gold/20 rounded-lg px-4 py-2.5 text-sm text-cream focus:outline-none focus:border-gold">
                </div>
            </div>

            <div>
                <label class="block text-xs text-goldmuted mb-1.5 uppercase font-semibold">Alamat (Opsional)</label>
                <textarea name="address" rows="3" placeholder="Alamat lengkap tempat tinggal..." class="w-full bg-[#0d2a18] border border-gold/20 rounded-lg px-4 py-2.5 text-sm text-cream focus:outline-none focus:border-gold">{{ old('address') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gold/10">
                <a href="{{ route('admin.anggota.index') }}" class="px-5 py-2.5 rounded-lg text-sm text-goldmuted hover:text-cream transition-colors">
                    Batal
                </a>
                <button type="submit" class="bg-gold hover:bg-gold/80 text-cardgreen font-bold px-6 py-2.5 rounded-lg text-sm transition-all shadow-md">
                    Simpan Anggota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Dashboard - Sistem Perpus')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="font-serif text-3xl font-bold text-gold">Dashboard</h1>
        <p class="text-sm text-goldmuted mt-1">Selamat datang kembali, Admin!</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5">
            <span class="block text-[10px] uppercase tracking-wider text-goldmuted font-semibold">Total Buku</span>
            <span class="text-3xl font-bold text-cream mt-2 block">{{ $totalBuku ?? \App\Models\Book::count() }}</span>
        </div>
        <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5">
            <span class="block text-[10px] uppercase tracking-wider text-goldmuted font-semibold">Anggota Terdaftar</span>
            <span class="text-3xl font-bold text-cream mt-2 block">{{ $totalAnggota ?? \App\Models\Member::count() }}</span>
        </div>
        <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-5">
            <span class="block text-[10px] uppercase tracking-wider text-goldmuted font-semibold">Sedang Dipinjam</span>
            <span class="text-3xl font-bold text-cream mt-2 block">{{ $totalDipinjam ?? \App\Models\Borrowing::where('status', 'active')->count() }}</span>
        </div>
    </div>
                    
    {{-- Grafik Pengunjung Mingguan --}}
    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6">
        <div class="flex justify-between items-center mb-3">
            <div>
                <h2 class="font-serif text-xl font-bold text-gold">Grafik Pengunjung Mingguan</h2>
                <p class="text-xs text-goldmuted mt-0.5">Statistik jumlah tamu perpustakaan minggu ini</p>
            </div>
            <span class="text-xs bg-gold/10 text-gold border border-gold/30 px-3 py-1 rounded-full font-semibold">
                {{ \Carbon\Carbon::now()->startOfWeek()->format('d M') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('d M Y') }}
            </span>
        </div>

        {{-- Container Grafik ditinggikan ke h-96 --}}
        <div class="h-96 flex items-end justify-between gap-3 pt-2 px-2 border-b border-gold/20 pb-3">
            @php
                $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
                $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                
                $dataPengunjung = [];
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
                    $dataPengunjung[] = \App\Models\Visitor::whereDate('visit_date', $date)->count();
                }

                $maxVal = max($dataPengunjung) > 0 ? max($dataPengunjung) : 1;
            @endphp

            @foreach($hari as $index => $d)
                @php
                    $dateObj = $startOfWeek->copy()->addDays($index);
                    $val = $dataPengunjung[$index];
                    $percent = ($val / $maxVal) * 100;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                    <span class="text-[11px] text-gold font-bold opacity-0 group-hover:opacity-100 transition-opacity">{{ $val }} orang</span>
                    <div class="w-full bg-[#0d2a18] border border-gold/20 rounded-t-lg relative overflow-hidden flex items-end" style="height: 100%;">
                        <div class="w-full bg-gradient-to-t from-gold/30 to-gold rounded-t-lg transition-all duration-500 group-hover:brightness-125" style="height: {{ $percent }}%;"></div>
                    </div>
                    <div class="text-center mt-1">
                        <span class="block text-xs font-semibold text-cream">{{ $d }}</span>
                        <span class="block text-[10px] text-goldmuted">{{ $dateObj->format('d/m') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Aktivitas Peminjaman Terbaru --}}
    <div class="beveled-card bg-cardgreen border border-gold/20 rounded-xl p-6">
        <h2 class="font-serif text-xl font-bold text-gold mb-3">Aktivitas Peminjaman Terbaru</h2>
        @php
            $recentBorrowingsData = $recentBorrowings ?? \App\Models\Borrowing::with(['member', 'book'])->latest()->take(5)->get();
        @endphp
        
        @if(count($recentBorrowingsData) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gold/20 text-gold text-xs uppercase tracking-wider">
                            <th class="py-3 px-4">Peminjam</th>
                            <th class="py-3 px-4">Buku</th>
                            <th class="py-3 px-4">Tgl Pinjam</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gold/10 text-sm text-cream/90">
                        @foreach($recentBorrowingsData as $item)
                            <tr>
                                <td class="py-3 px-4">{{ $item->member->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $item->book->title ?? '-' }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->borrow_date)->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 text-center">
                                    @if($item->status === 'active')
                                        <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider">Dipinjam</span>
                                    @elseif($item->status === 'returned')
                                        <span class="bg-green-500/20 text-green-400 border border-green-500/30 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider">Selesai</span>
                                    @elseif($item->status === 'late')
                                        <span class="bg-red-500/20 text-red-400 border border-red-500/30 px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider">Terlambat</span>
                                    @else
                                        <span class="bg-gold/20 text-gold border border-gold/30 px-2 py-0.5 rounded-full text-xs">{{ $item->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-goldmuted/70 italic">Belum ada aktivitas peminjaman terbaru.</p>
        @endif
    </div>
</div>
@endsection
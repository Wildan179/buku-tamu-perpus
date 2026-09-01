<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Sistem Perpus')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emeraldbg: '#062e1a',
                        cardgreen: '#083d23',
                        inputgreen: '#042415',
                        gold: '#d4a05f',
                        goldmuted: '#b0804c',
                        cream: '#f5ead9',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        body: ['"Poppins"', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <style>
        body {
            background-color: #062e1a;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(212, 160, 95, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(212, 160, 95, 0.04) 0%, transparent 40%),
                repeating-linear-gradient(45deg, rgba(255,255,255,0.015) 0px, rgba(255,255,255,0.015) 1px, transparent 1px, transparent 12px);
            font-family: 'Poppins', sans-serif;
        }

        .beveled-card {
            box-shadow:
                inset 0 2px 8px rgba(0,0,0,0.55),
                inset 0 -2px 4px rgba(212,160,95,0.06),
                0 20px 40px -12px rgba(0,0,0,0.6);
        }

        .menu-active {
            background: linear-gradient(90deg, rgba(212,160,95,0.16) 0%, rgba(212,160,95,0.02) 100%);
            border-left: 3px solid #d4a05f;
            color: #d4a05f;
        }

        .menu-item {
            border-left: 3px solid transparent;
            transition: all 0.25s ease;
        }

        .menu-item:hover {
            background: rgba(212,160,95,0.08);
            color: #d4a05f;
        }

        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #062e1a; }
        ::-webkit-scrollbar-thumb { background: #8a6338; border-radius: 4px; }

        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="font-body text-cream">

<div class="flex min-h-screen w-full">

    {{-- SIDEBAR --}}
    <aside class="w-64 shrink-0 bg-[#062e1a] border-r border-goldmuted/40 flex flex-col justify-between">
        <div>
            <div class="px-6 py-8 border-b border-goldmuted/20 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-gold/10 border border-gold/30 flex items-center justify-center text-gold font-serif font-bold text-lg">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <h1 class="font-serif text-base text-gold tracking-wide font-semibold truncate">
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </h1>
                    <p class="text-xs text-goldmuted truncate">{{ Auth::user()->email ?? 'admin@perpus.com' }}</p>
                </div>
            </div>

            <nav class="py-6 px-3 space-y-1">
                @php
                    $menu = [
                        ['label' => 'Dashboard',     'route' => 'admin.dashboard',       'icon' => 'home'],
                        ['label' => 'Kelola Buku',   'route' => 'admin.buku.index',      'icon' => 'book'],
                        ['label' => 'Peminjaman',    'route' => 'admin.peminjaman.index','icon' => 'swap'],
                        ['label' => 'Data Anggota',  'route' => 'admin.anggota.index',   'icon' => 'users'],
                        ['label' => 'Buku Tamu',     'route' => 'admin.tamu.index',      'icon' => 'visitor'],
                    ];
                @endphp

                @foreach ($menu as $item)
                    @php
                        $isActive = Route::currentRouteName() && Str::startsWith(Route::currentRouteName(), Str::before($item['route'], '.index') ?: $item['route']);
                    @endphp
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                       class="menu-item flex items-center gap-3 px-4 py-3 rounded-md text-sm font-medium {{ $isActive ? 'menu-active' : 'text-cream/80' }}">
                        <span class="h-5 w-5 inline-flex items-center justify-center">
                            @switch($item['icon'])
                                @case('home')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5V21a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5z"/></svg>
                                    @break
                                @case('book')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                    @break
                                @case('swap')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4"/><path d="M9 15l10-10"/><path d="M14 5h5v5"/></svg>
                                    @break
                                @case('users')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 20v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 20v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    @break
                                @case('visitor')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @break
                            @endswitch
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="px-3 py-6 border-t border-goldmuted/20">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-md text-sm font-medium text-cream/80 hover:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 min-w-0 p-6 lg:p-10 space-y-6">
        @if (session('success'))
            <div class="beveled-card bg-cardgreen border-l-4 border-gold rounded-lg px-5 py-3 text-sm text-cream">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="beveled-card bg-cardgreen border-l-4 border-red-400 rounded-lg px-5 py-3 text-sm text-cream">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
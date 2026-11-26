<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#176b55">
    <link rel="icon" href="{{ $siteSettings->favicon ?? '/favicon.ico' }}">
    <link rel="apple-touch-icon" href="{{ $siteSettings->favicon ?? '/favicon.ico' }}">

    @yield('seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('structured-data')
</head>
<body class="min-h-screen flex flex-col bg-[#fbfcfa] text-[#13221d] antialiased selection:bg-[#176b55]/15 selection:text-[#0f4c3c]">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-lg focus:bg-[#176b55] focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white">
        Lewati ke konten utama
    </a>

    {{-- P11: Top Announcement Banner --}}
    @if($activeAnnouncement)
        <div
            x-data="{ open: true }"
            x-show="open"
            class="border-b border-[#c97a3d]/25 bg-[#0f4c3c] text-white"
            role="region"
            aria-label="Pengumuman Praktek"
        >
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-2.5 text-xs sm:px-6 sm:text-sm">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-[#c97a3d] px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-white">
                        Info
                    </span>
                    <p class="font-medium leading-snug">
                        {{ $activeAnnouncement->message }}
                    </p>
                    @if($activeAnnouncement->link_url)
                        <a href="{{ $activeAnnouncement->link_url }}" class="font-semibold underline decoration-white/60 underline-offset-2 hover:decoration-white">
                            {{ $activeAnnouncement->link_label ?: 'Selengkapnya' }}
                        </a>
                    @endif
                </div>
                <button
                    type="button"
                    @click="open = false"
                    aria-label="Tutup pengumuman"
                    class="shrink-0 rounded-lg p-1 text-white/80 hover:bg-white/10 hover:text-white"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Main Sticky Navigation --}}
    @php
        $hasLogo = ! empty($siteSettings->logo);
        $isLogoOnly = $hasLogo && ($siteSettings->logo_only ?? false);
    @endphp
    <header
        x-data="{ mobileOpen: false }"
        class="sticky top-0 z-40 border-b border-[#176b55]/12 bg-[#fbfcfa]/92 backdrop-blur-md"
    >
        <div @class([
            'mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 sm:px-6',
            'py-2 sm:py-2.5' => $isLogoOnly,
            'py-3.5' => ! $isLogoOnly,
        ])>
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3">
                @if($hasLogo)
                    <img
                        src="{{ $siteSettings->logo }}"
                        alt="Logo {{ $siteSettings->practice_name }}"
                        @class([
                            'w-auto shrink-0 object-contain',
                            'h-16 max-w-[250px] rounded-lg sm:h-20 sm:max-w-[360px]' => $isLogoOnly,
                            'h-10 max-w-[220px] rounded-xl sm:h-11' => ! $isLogoOnly,
                        ])
                    >
                @endif
                @if($isLogoOnly)
                    <span class="sr-only">{{ $siteSettings->practice_name }}</span>
                @else
                    <div class="leading-tight">
                        <span class="font-display block text-base font-semibold text-[#13221d] sm:text-lg">
                            {{ $siteSettings->practice_name }}
                        </span>
                        <span class="block text-xs text-[#4b615a]">
                            Binong, Curug · Kab. Tangerang
                        </span>
                    </div>
                @endif
            </a>

            <nav aria-label="Navigasi Utama" class="hidden items-center gap-1 lg:flex">
                @php
                    $navItems = [
                        ['label' => 'Beranda', 'route' => 'home', 'active' => request()->routeIs('home')],
                        ['label' => 'Layanan', 'route' => 'services.index', 'active' => request()->routeIs('services.*')],
                        ['label' => 'Tentang', 'route' => 'about', 'active' => request()->routeIs('about')],
                        ['label' => 'Lokasi & Jam', 'route' => 'location', 'active' => request()->routeIs('location')],
                        ['label' => 'Edukasi', 'route' => 'articles.index', 'active' => request()->routeIs('articles.*')],
                        ['label' => 'Kontak', 'route' => 'contact.index', 'active' => request()->routeIs('contact.*')],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="rounded-xl px-3.5 py-2 text-sm font-medium transition {{ $item['active'] ? 'bg-[#eef6f3] font-semibold text-[#0f4c3c]' : 'text-[#31453f] hover:bg-[#eef6f3]/60 hover:text-[#0f4c3c]' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2.5">
                <a
                    href="{{ $whatsappHref }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hidden items-center gap-2 rounded-xl bg-[#176b55] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f4c3c] sm:inline-flex"
                >
                    <x-icons.whatsapp class="h-4 w-4 fill-white" />
                    Hubungi Praktek
                </a>

                <button
                    type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-nav"
                    aria-label="Buka menu navigasi"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-[#176b55]/20 bg-white text-[#13221d] lg:hidden"
                >
                    <svg x-show="!mobileOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Dropdown Navigation --}}
        <div id="mobile-nav" x-show="mobileOpen" x-transition class="border-t border-[#176b55]/10 bg-white px-4 py-3 lg:hidden">
            <nav aria-label="Menu Mobile" class="grid gap-1">
                @foreach($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="rounded-xl px-3.5 py-2.5 text-sm font-medium {{ $item['active'] ? 'bg-[#eef6f3] font-semibold text-[#0f4c3c]' : 'text-[#13221d] hover:bg-[#eef6f3]/50' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="mt-16 border-t border-[#176b55]/15 bg-[#0f4c3c] pb-24 text-white/90 md:pb-12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-10 md:grid-cols-12">
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3">
                        @if($hasLogo)
                            <img
                                src="{{ $siteSettings->logo }}"
                                alt="Logo {{ $siteSettings->practice_name }}"
                                @class([
                                    'w-auto shrink-0 rounded-xl bg-white/95 object-contain',
                                    'h-16 max-w-[260px] px-3 py-2 sm:h-20 sm:max-w-[340px]' => $isLogoOnly,
                                    'h-10 max-w-[200px] p-1 sm:h-11' => ! $isLogoOnly,
                                ])
                            >
                        @endif
                        @if(! $isLogoOnly)
                            <div>
                                <p class="font-display text-lg font-semibold text-white">{{ $siteSettings->practice_name }}</p>
                                <p class="text-xs text-emerald-100/80">{{ $siteSettings->doctor_name }} · {{ $siteSettings->doctor_role }}</p>
                            </div>
                        @endif
                    </div>
                    @if($isLogoOnly)
                        <p class="mt-3 text-xs font-medium text-emerald-100/85">
                            {{ $siteSettings->doctor_name }} · {{ $siteSettings->doctor_role }}
                        </p>
                    @endif

                    <p class="mt-4 max-w-md text-sm leading-relaxed text-emerald-50/85">
                        {{ $siteSettings->tagline }}.
                    </p>

                    <div class="mt-5 space-y-2 text-sm text-emerald-50/90">
                        <p><strong>Alamat:</strong> {{ $siteSettings->address }}</p>
                        <p>
                            <strong>Telepon / WhatsApp:</strong>
                            <a href="{{ $telHref }}" class="underline decoration-white/40 underline-offset-2 hover:text-white">{{ $siteSettings->phone }}</a>
                        </p>
                        <p><strong>Jadwal Hari Ini:</strong> {{ $todaySchedule->hours_text }}</p>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-emerald-200/90">Navigasi</h2>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white hover:underline">Beranda</a></li>
                        <li><a href="{{ route('services.index') }}" class="hover:text-white hover:underline">Layanan Praktek</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white hover:underline">Tentang Praktek</a></li>
                        <li><a href="{{ route('location') }}" class="hover:text-white hover:underline">Lokasi &amp; Jam Praktek</a></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-white hover:underline">Edukasi Kesehatan</a></li>
                        <li><a href="{{ route('contact.index') }}" class="hover:text-white hover:underline">Kontak &amp; Pertanyaan</a></li>
                    </ul>
                </div>

                <div class="md:col-span-4">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-emerald-200/90">Informasi &amp; Kepatuhan</h2>
                    <p class="mt-4 text-xs leading-relaxed text-emerald-50/80">
                        {{ $siteSettings->medical_disclaimer }}
                    </p>
                    <ul class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-xs font-medium text-emerald-100">
                        <li><a href="{{ route('pages.privacy') }}" class="underline underline-offset-2 hover:text-white">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="underline underline-offset-2 hover:text-white">Syarat &amp; Ketentuan</a></li>
                        <li><a href="{{ $siteSettings->maps_url }}" target="_blank" rel="noopener noreferrer" class="underline underline-offset-2 hover:text-white">Google Maps</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col items-start justify-between gap-3 border-t border-white/15 pt-6 text-xs text-emerald-100/75 sm:flex-row sm:items-center">
                <p>&copy; {{ now('Asia/Jakarta')->year }} {{ $siteSettings->practice_name }}. Seluruh hak cipta dilindungi.</p>
                <p>Melayani Binong, Curug &amp; sekitar Aryana Karawaci.</p>
            </div>
        </div>
    </footer>

    {{-- P7: Floating WhatsApp CTA --}}
    <a
        href="{{ $whatsappHref }}"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat WhatsApp dengan {{ $siteSettings->practice_name }}"
        class="fixed bottom-20 right-4 z-40 inline-flex items-center gap-2 rounded-full bg-[#176b55] px-4 py-3 text-sm font-semibold text-white shadow-lg ring-2 ring-white transition hover:bg-[#0f4c3c] md:bottom-6 md:right-6"
    >
        <x-icons.whatsapp class="h-5 w-5 fill-current" />
        <span class="hidden sm:inline">WhatsApp Praktek</span>
    </a>

    {{-- Mobile-First Quick Action Bar --}}
    <div class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-3 border-t border-[#176b55]/15 bg-white/95 backdrop-blur-md md:hidden" role="region" aria-label="Aksi Cepat Pasien">
        <a href="{{ $telHref }}" class="flex flex-col items-center justify-center gap-0.5 py-2.5 text-xs font-semibold text-[#13221d]">
            <svg class="h-4 w-4 text-[#176b55]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
            Telepon
        </a>
        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center gap-0.5 border-x border-[#176b55]/10 bg-[#eef6f3] py-2.5 text-xs font-semibold text-[#0f4c3c]">
            <x-icons.whatsapp class="h-4 w-4 fill-[#176b55]" />
            WhatsApp
        </a>
        <a href="{{ $siteSettings->maps_url }}" target="_blank" rel="noopener noreferrer" class="flex flex-col items-center justify-center gap-0.5 py-2.5 text-xs font-semibold text-[#13221d]">
            <svg class="h-4 w-4 text-[#176b55]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
            </svg>
            Petunjuk Arah
        </a>
    </div>
</body>
</html>

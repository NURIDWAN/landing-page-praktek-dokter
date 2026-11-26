@extends('layouts.app')

@section('seo')
    <x-seo-head
        :title="$siteSettings->default_meta_title"
        :description="$siteSettings->default_meta_description"
        :ogImage="$siteSettings->hero_image"
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$clinicJsonLd" />
@endpush

@section('content')
    {{-- P1: Editorial Hero Section --}}
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16 lg:py-20">
            <div class="grid items-center gap-10 lg:grid-cols-12 lg:gap-12">
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center gap-2 rounded-full border border-[#176b55]/20 bg-white/90 px-3.5 py-1.5 text-xs font-semibold text-[#0f4c3c] shadow-xs">
                        <span class="h-2 w-2 rounded-full {{ $todaySchedule->is_open_now ? 'bg-emerald-500 animate-pulse' : ($todaySchedule->is_closed_today ? 'bg-[#c97a3d]' : 'bg-[#176b55]') }}"></span>
                        <span>{{ $siteSettings->hero_badge }}</span>
                    </div>

                    <h1 class="font-display mt-5 text-3xl font-semibold leading-[1.15] tracking-tight text-[#13221d] sm:text-4xl lg:text-[2.85rem]">
                        {{ $siteSettings->hero_title }}
                    </h1>

                    <p class="mt-5 max-w-2xl text-base leading-relaxed text-[#3d4d47] sm:text-lg">
                        {{ $siteSettings->hero_description }}
                    </p>

                    <div class="mt-7 flex flex-wrap items-center gap-3.5">
                        <a
                            href="{{ $siteSettings->maps_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-2xl bg-[#176b55] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f4c3c]"
                        >
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            {{ $siteSettings->hero_primary_cta }}
                        </a>

                        <a
                            href="{{ $whatsappHref }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-2xl border border-[#176b55]/25 bg-white px-6 py-3.5 text-sm font-semibold text-[#0f4c3c] shadow-xs transition hover:border-[#176b55] hover:bg-[#eef6f3]"
                        >
                            <x-icons.whatsapp class="h-4 w-4 fill-[#176b55]" />
                            {{ $siteSettings->hero_secondary_cta }}
                        </a>

                        <a href="{{ route('location') }}" class="px-2 py-2 text-sm font-semibold text-[#176b55] underline decoration-[#176b55]/40 underline-offset-4 hover:decoration-[#176b55]">
                            Lihat jadwal lengkap &rarr;
                        </a>
                    </div>

                    {{-- Trust Strip (Single Source of Truth) --}}
                    <dl class="mt-9 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-[#176b55]/12 bg-white/85 p-3.5 backdrop-blur-xs">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-[#4b615a]">Lokasi Praktek</dt>
                            <dd class="mt-1 text-sm font-semibold text-[#13221d]">Binong, Curug</dd>
                            <dd class="text-xs text-[#4b615a]">Kab. Tangerang</dd>
                        </div>

                        <div class="rounded-2xl border border-[#176b55]/12 bg-white/85 p-3.5 backdrop-blur-xs">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-[#4b615a]">Telepon / WhatsApp</dt>
                            <dd class="mt-1 text-sm font-semibold tabular-nums text-[#0f4c3c]">
                                <a href="{{ $telHref }}" class="hover:underline">{{ $siteSettings->phone }}</a>
                            </dd>
                            <dd class="text-xs text-[#4b615a]">Konsultasi &amp; info jadwal</dd>
                        </div>

                        <div class="rounded-2xl border border-[#176b55]/12 bg-white/85 p-3.5 backdrop-blur-xs">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-[#4b615a]">Profil Google Maps</dt>
                            @if(! empty($siteSettings->google_rating_text))
                                <dd class="mt-1 text-sm font-semibold text-[#13221d]">{{ $siteSettings->google_rating_text }}</dd>
                            @else
                                <dd class="mt-1 text-sm font-semibold text-[#13221d]">Terdaftar di Maps</dd>
                            @endif
                            <dd class="text-xs">
                                <a href="{{ $siteSettings->maps_url }}" target="_blank" rel="noopener noreferrer" class="font-medium text-[#176b55] hover:underline">
                                    Buka ulasan &amp; rute &rarr;
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Differentiation Anchor: Hero Photo + Architectural Live Clinic Status Card --}}
                <div class="lg:col-span-5">
                    <div class="relative">
                        <div class="overflow-hidden rounded-[2rem] border border-[#176b55]/15 bg-[#eef6f3] shadow-lg">
                            <img
                                src="{{ $siteSettings->hero_image }}"
                                alt="Ruang konsultasi {{ $siteSettings->practice_name }} di Binong, Curug"
                                width="1200"
                                height="675"
                                fetchpriority="high"
                                class="aspect-[4/3] w-full object-cover"
                            >
                        </div>

                        <div class="pointer-glow relative z-10 mx-4 -mt-12 rounded-2xl border border-[#176b55]/20 bg-white/95 p-5 shadow-md backdrop-blur-md sm:mx-6">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $todaySchedule->is_closed_today ? 'bg-[#fdf7f2] text-[#9a5521]' : 'bg-[#eef6f3] text-[#0f4c3c]' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $todaySchedule->is_closed_today ? 'bg-[#c97a3d]' : 'bg-[#176b55]' }}"></span>
                                        {{ $todaySchedule->badge_text }}
                                    </span>
                                    <p class="mt-2 font-display text-lg font-semibold text-[#13221d] tabular-nums">
                                        {{ $todaySchedule->hours_text }}
                                    </p>
                                    @if($todaySchedule->note)
                                        <p class="mt-1 text-xs leading-relaxed text-[#4b615a]">{{ $todaySchedule->note }}</p>
                                    @endif
                                </div>
                                <a
                                    href="{{ $whatsappHref }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="Chat WhatsApp {{ $siteSettings->phone }}"
                                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#eef6f3] text-[#176b55] transition hover:bg-[#176b55] hover:text-white"
                                >
                                    <x-icons.whatsapp class="h-4 w-4 fill-current" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- P2: Ringkasan Layanan --}}
    <section class="reveal-section mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Layanan Praktek</p>
                <h2 class="font-display mt-1.5 text-2xl font-semibold text-[#13221d] sm:text-3xl">
                    Pelayanan Medis Dasar untuk Anak, Dewasa &amp; Keluarga
                </h2>
            </div>
            <a href="{{ route('services.index') }}" class="text-sm font-semibold text-[#176b55] hover:text-[#0f4c3c] hover:underline">
                Semua layanan &rarr;
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($services->take(4) as $service)
                <article class="pointer-glow tilt-card flex flex-col rounded-3xl border border-[#176b55]/14 bg-white p-6 shadow-xs">
                    <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef6f3] text-[#176b55]">
                        <x-service-icon :name="$service->icon" class="h-6 w-6" />
                    </div>
                    <h3 class="font-display mt-5 text-lg font-semibold leading-snug text-[#13221d]">
                        {{ $service->title }}
                    </h3>
                    <p class="mt-2.5 flex-1 text-sm leading-relaxed text-[#4b615a]">
                        {{ $service->summary }}
                    </p>
                    <a href="{{ route('services.index') }}#{{ $service->slug }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#176b55] hover:text-[#0f4c3c]">
                        Detail layanan <span aria-hidden="true">&rarr;</span>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    {{-- P3 & P8: Tentang Praktek + Area Layanan Lokal --}}
    <section class="reveal-section border-y border-[#176b55]/10 bg-[#eef6f3]/55">
        <div class="mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
            <div class="grid items-center gap-10 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <div class="overflow-hidden rounded-[2rem] border border-[#176b55]/15 bg-white p-2 shadow-sm">
                        <img
                            src="{{ $siteSettings->doctor_photo }}"
                            alt="Foto {{ $siteSettings->doctor_name }} — {{ $siteSettings->practice_name }}"
                            width="720"
                            height="960"
                            loading="lazy"
                            class="aspect-[4/5] w-full rounded-[1.5rem] object-cover object-top"
                        >
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Tentang Praktek</p>
                        <h2 class="font-display mt-1.5 text-2xl font-semibold text-[#13221d] sm:text-3xl">
                            {{ $siteSettings->doctor_name }} · {{ $siteSettings->doctor_role }}
                        </h2>
                        @if(! empty($siteSettings->sip_number))
                            <p class="mt-1 text-xs font-medium text-[#4b615a]">No. SIP: {{ $siteSettings->sip_number }}</p>
                        @endif
                    </div>

                    <p class="text-base leading-relaxed text-[#31453f]">
                        {{ $siteSettings->doctor_bio }}
                    </p>

                    <div class="rounded-2xl border border-[#176b55]/15 bg-white p-5">
                        <h3 class="text-sm font-semibold text-[#0f4c3c]">Catatan untuk Pasien</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-[#4b615a]">
                            {{ $siteSettings->doctor_notes }}
                        </p>
                    </div>

                    {{-- P8: Area Layanan (SEO Lokal) --}}
                    <div class="rounded-2xl border border-[#176b55]/15 bg-white p-5">
                        <h3 class="text-sm font-semibold text-[#0f4c3c]">{{ $siteSettings->service_area_title }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-[#4b615a]">
                            {{ $siteSettings->service_area_text }}
                        </p>
                    </div>

                    <x-medical-disclaimer compact />
                </div>
            </div>
        </div>
    </section>

    {{-- P5: 3 Artikel Edukasi Terbaru --}}
    <section class="reveal-section mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Edukasi Kesehatan</p>
                <h2 class="font-display mt-1.5 text-2xl font-semibold text-[#13221d] sm:text-3xl">
                    Artikel Kesehatan Keluarga Terbaru
                </h2>
            </div>
            <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-[#176b55] hover:text-[#0f4c3c] hover:underline">
                Baca semua artikel &rarr;
            </a>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach($latestArticles as $article)
                <article class="pointer-glow tilt-card flex flex-col overflow-hidden rounded-3xl border border-[#176b55]/14 bg-white shadow-xs">
                    @if(! empty($article->cover_image_url))
                        <img
                            src="{{ $article->cover_image_url }}"
                            alt="{{ $article->title }}"
                            width="640"
                            height="360"
                            loading="lazy"
                            class="aspect-[16/9] w-full object-cover"
                        >
                    @else
                        <div class="clinical-contour-bg flex aspect-[16/9] items-end border-b border-[#176b55]/10 p-5">
                            <span class="rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-[#0f4c3c] shadow-xs">
                                {{ $article->category->name ?? 'Edukasi' }}
                            </span>
                        </div>
                    @endif

                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center justify-between gap-2 text-xs text-[#4b615a]">
                            <span class="font-semibold text-[#176b55]">{{ $article->category->name ?? 'Edukasi' }}</span>
                            @if($article->published_at)
                                <time datetime="{{ $article->published_at->toDateString() }}">
                                    {{ $article->published_at->translatedFormat('d M Y') }}
                                </time>
                            @endif
                        </div>

                        <h3 class="font-display mt-2.5 text-lg font-semibold leading-snug text-[#13221d]">
                            <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-[#176b55]">
                                {{ $article->title }}
                            </a>
                        </h3>

                        <p class="mt-2.5 flex-1 text-sm leading-relaxed text-[#4b615a]">
                            {{ $article->excerpt }}
                        </p>

                        <div class="mt-5 flex items-center justify-between border-t border-[#176b55]/10 pt-4 text-xs">
                            <span class="truncate text-[#4b615a]" title="{{ $article->source_name }}">
                                Sumber: {{ $article->source_name }}
                            </span>
                            <a href="{{ route('articles.show', $article->slug) }}" class="shrink-0 font-semibold text-[#176b55] hover:underline">
                                Baca &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- P4 & P6: Jadwal & Peta (Satu Container), dan Kontak Pertanyaan di Bawahnya --}}
    <section class="reveal-section mx-auto max-w-6xl px-4 pb-12 sm:px-6">
        <div class="space-y-10">
            {{-- Container Terpadu: Jadwal Praktek & Peta Lokasi --}}
            <div class="overflow-hidden rounded-3xl border border-[#176b55]/15 bg-white shadow-sm">
                <div class="flex flex-col lg:flex-row">
                    {{-- Kolom Kiri: Jadwal Praktek --}}
                    <div class="w-full p-6 sm:p-8 lg:w-1/2 lg:border-r lg:border-[#176b55]/10">
                        <x-clinic-hours-card :openingHours="$openingHours" :upcomingClosures="$upcomingClosures" class="border-0 !p-0 !shadow-none" />
                    </div>

                    {{-- Kolom Kanan: Peta Lokasi & Alamat Lengkap --}}
                    <div class="flex w-full flex-col border-t border-[#176b55]/10 bg-[#fafcfb] lg:w-1/2 lg:border-t-0">
                        <div class="p-6 sm:p-8">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold tracking-wider text-[#176b55] uppercase">Lokasi Praktek</p>
                                    <h3 class="font-display mt-1 text-xl font-semibold text-[#13221d]">Peta &amp; Akses Klinik</h3>
                                </div>
                                <a
                                    href="{{ $siteSettings->maps_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-[#176b55]/20 bg-white px-3 py-1.5 text-xs font-semibold text-[#0f4c3c] shadow-xs transition hover:bg-[#eef6f3]"
                                >
                                    <span>Buka di Maps</span>
                                    <svg class="h-3.5 w-3.5 text-[#176b55]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14 21 3"/>
                                    </svg>
                                </a>
                            </div>

                            <p class="mt-3 text-sm leading-relaxed text-[#3d4d47]">
                                <strong class="text-[#13221d]">Alamat:</strong> {{ $siteSettings->address }}
                            </p>
                            <p class="mt-1 text-xs text-[#4b615a]">
                                Patokan: Dekat kawasan perumahan Aryana Karawaci, Binong, Curug.
                            </p>
                        </div>

                        <div class="mt-auto min-h-[280px] flex-1 overflow-hidden border-t border-[#176b55]/10">
                            <iframe
                                title="Peta Lokasi {{ $siteSettings->practice_name }}"
                                src="{{ $siteSettings->maps_embed_url }}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="h-full min-h-[280px] w-full border-0"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Container Kontak: Form Pertanyaan di Bawah Terpusat --}}
            <div class="mx-auto max-w-3xl rounded-3xl border border-[#176b55]/15 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
                <div class="text-center">
                    <p class="text-xs font-semibold tracking-wider text-[#176b55] uppercase">Hubungi Praktek</p>
                    <h2 class="font-display mt-1.5 text-2xl font-semibold text-[#13221d] sm:text-3xl">
                        Ada Pertanyaan Jadwal atau Layanan?
                    </h2>
                    <p class="mx-auto mt-2 max-w-xl text-sm leading-relaxed text-[#4b615a]">
                        Kirim pertanyaan singkat melalui formulir di bawah ini, atau hubungi langsung via telepon / WhatsApp
                        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#176b55] underline hover:text-[#0f4c3c]">{{ $siteSettings->phone }}</a>.
                    </p>
                </div>

                <div class="mt-8">
                    <x-contact-form compact class="border-0 !p-0 !shadow-none" />
                </div>
            </div>
        </div>
    </section>
@endsection

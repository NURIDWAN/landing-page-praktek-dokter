@extends('layouts.app')

@section('seo')
    <x-seo-head
        title="Lokasi & Jam Praktek — {{ $siteSettings->practice_name }}"
        description="Alamat lengkap di Binong, Curug, Kabupaten Tangerang, jadwal jam buka per hari, info libur khusus, dan peta Google Maps {{ $siteSettings->practice_name }}."
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$clinicJsonLd" />
    <x-json-ld :data="$breadcrumbJsonLd" />
@endpush

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Lokasi &amp; Jadwal</p>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Alamat, Kontak &amp; Jam Praktek
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[#3d4d47]">
                Status hari ini: <strong class="text-[#0f4c3c]">{{ $todaySchedule->badge_text }} ({{ $todaySchedule->hours_text }})</strong>.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-12">
            <div class="space-y-6 lg:col-span-5">
                <div class="rounded-3xl border border-[#176b55]/15 bg-white p-6 shadow-xs">
                    <h2 class="font-display text-xl font-semibold text-[#13221d]">Informasi Kontak &amp; Alamat</h2>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Alamat Lengkap</dt>
                            <dd class="mt-1 leading-relaxed text-[#13221d]">{{ $siteSettings->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Telepon / WhatsApp</dt>
                            <dd class="mt-1 font-semibold tabular-nums text-[#0f4c3c]">
                                <a href="{{ $telHref }}" class="hover:underline">{{ $siteSettings->phone }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Area Layanan Sekitar</dt>
                            <dd class="mt-1 leading-relaxed text-[#4b615a]">{{ $siteSettings->service_area_text }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a
                            href="{{ $siteSettings->maps_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#176b55] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0f4c3c]"
                        >
                            Petunjuk Arah Maps
                        </a>
                        <a
                            href="{{ $whatsappHref }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-xl border border-[#176b55]/25 bg-[#eef6f3] px-4 py-2.5 text-sm font-semibold text-[#0f4c3c] hover:bg-[#d8ece4]"
                        >
                            <x-icons.whatsapp class="h-4 w-4 fill-[#0f4c3c]" />
                            Chat WhatsApp
                        </a>
                    </div>
                </div>

                <x-clinic-hours-card :openingHours="$openingHours" :upcomingClosures="$upcomingClosures" />
            </div>

            <div class="space-y-6 lg:col-span-7">
                <div class="overflow-hidden rounded-3xl border border-[#176b55]/15 bg-white shadow-sm">
                    <iframe
                        title="Google Maps {{ $siteSettings->practice_name }}"
                        src="{{ $siteSettings->maps_embed_url }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="h-[420px] w-full border-0"
                    ></iframe>
                </div>

                <x-medical-disclaimer compact />
            </div>
        </div>
    </section>
@endsection

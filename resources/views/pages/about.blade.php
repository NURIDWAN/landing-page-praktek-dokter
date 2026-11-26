@extends('layouts.app')

@section('seo')
    <x-seo-head
        title="Tentang Praktek — {{ $siteSettings->practice_name }}"
        description="Profil {{ $siteSettings->doctor_name }}, catatan kunjungan pasien, dan disclaimer medis {{ $siteSettings->practice_name }} di Binong, Curug, Kabupaten Tangerang."
        :ogImage="$siteSettings->doctor_photo"
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$breadcrumbJsonLd" />
@endpush

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Tentang Praktek</p>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Mengenal {{ $siteSettings->practice_name }}
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[#3d4d47]">
                {{ $siteSettings->tagline }}.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="grid items-start gap-10 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <div class="overflow-hidden rounded-[2rem] border border-[#176b55]/15 bg-white p-2.5 shadow-sm">
                    <img
                        src="{{ $siteSettings->doctor_photo }}"
                        alt="{{ $siteSettings->doctor_name }}"
                        width="720"
                        height="960"
                        class="aspect-[4/5] w-full rounded-[1.5rem] object-cover object-top"
                    >
                </div>

                <div class="mt-5 rounded-2xl border border-[#176b55]/15 bg-[#eef6f3]/70 p-5 text-sm">
                    <p class="font-display text-lg font-semibold text-[#13221d]">{{ $siteSettings->doctor_name }}</p>
                    <p class="text-[#176b55] font-medium">{{ $siteSettings->doctor_role }}</p>
                    @if(! empty($siteSettings->sip_number))
                        <p class="mt-2 text-xs text-[#4b615a]">Nomor SIP: {{ $siteSettings->sip_number }}</p>
                    @endif
                    <p class="mt-2 text-xs text-[#4b615a]">Lokasi: {{ $siteSettings->address }}</p>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-7">
                <div class="rounded-3xl border border-[#176b55]/14 bg-white p-6 sm:p-8">
                    <h2 class="font-display text-2xl font-semibold text-[#13221d]">Profil &amp; Pendekatan Pelayanan</h2>
                    <p class="mt-3 text-base leading-relaxed text-[#3d4d47]">
                        {{ $siteSettings->doctor_bio }}
                    </p>
                </div>

                <div class="rounded-3xl border border-[#176b55]/14 bg-white p-6 sm:p-8">
                    <h2 class="font-display text-xl font-semibold text-[#13221d]">Catatan untuk Pasien Sebelum Berkunjung</h2>
                    <p class="mt-3 text-sm leading-relaxed text-[#3d4d47]">
                        {{ $siteSettings->doctor_notes }}
                    </p>
                    <ul class="mt-4 space-y-2 text-sm text-[#3d4d47]">
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#176b55]"></span>
                            <span>Cek status jam buka atau info libur khusus terlebih dahulu pada halaman <a href="{{ route('location') }}" class="font-semibold text-[#176b55] underline">Lokasi &amp; Jam Praktek</a>.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-[#176b55]"></span>
                            <span>Sampaikan riwayat alergi obat yang diketahui sebelum dokter menuliskan resep.</span>
                        </li>
                    </ul>
                </div>

                <div class="rounded-3xl border border-[#176b55]/14 bg-white p-6 sm:p-8">
                    <h2 class="font-display text-xl font-semibold text-[#13221d]">{{ $siteSettings->service_area_title }}</h2>
                    <p class="mt-3 text-sm leading-relaxed text-[#3d4d47]">
                        {{ $siteSettings->service_area_text }}
                    </p>
                </div>

                <x-medical-disclaimer />
            </div>
        </div>
    </section>
@endsection

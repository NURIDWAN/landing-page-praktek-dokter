@extends('layouts.app')

@section('seo')
    <x-seo-head
        title="Layanan Praktek — {{ $siteSettings->practice_name }}"
        description="Daftar layanan pemeriksaan dokter umum anak, dewasa, lansia, konsultasi KB, dan surat keterangan sehat di Binong, Curug, Kabupaten Tangerang."
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$breadcrumbJsonLd" />
@endpush

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Layanan Medis Dasar</p>
            <h1 class="font-display mt-2 max-w-2xl text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Layanan Konsultasi &amp; Pemeriksaan Keluarga
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-[#3d4d47]">
                Seluruh layanan dilaksanakan langsung oleh {{ $siteSettings->doctor_name }} dengan pendekatan pemeriksaan yang tenang, jelas, dan mengutamakan edukasi pasien.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="grid gap-6 md:grid-cols-2">
            @foreach($services as $service)
                <article id="{{ $service->slug }}" class="pointer-glow rounded-3xl border border-[#176b55]/15 bg-white p-7 shadow-xs">
                    <div class="flex items-start gap-4">
                        <div class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#eef6f3] text-[#176b55]">
                            <x-service-icon :name="$service->icon" class="h-6 w-6" />
                        </div>
                        <div>
                            <h2 class="font-display text-xl font-semibold text-[#13221d]">{{ $service->title }}</h2>
                            <p class="mt-2 text-sm font-medium text-[#176b55]">{{ $service->summary }}</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-[#4b615a]">
                        {{ $service->description }}
                    </p>
                </article>
            @endforeach
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <x-medical-disclaimer />
            </div>
            <div class="flex flex-col justify-between rounded-3xl border border-[#176b55]/15 bg-[#0f4c3c] p-6 text-white lg:col-span-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Konsultasi Langsung</p>
                    <h2 class="font-display mt-1.5 text-xl font-semibold">Ingin Memastikan Ketersediaan Layanan?</h2>
                    <p class="mt-2 text-sm text-emerald-50/85">
                        Jam praktek hari ini: {{ $todaySchedule->hours_text }}.
                    </p>
                </div>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-[#0f4c3c] hover:bg-[#eef6f3]">
                        <x-icons.whatsapp class="h-4 w-4 fill-[#0f4c3c]" />
                        Chat WhatsApp
                    </a>
                    <a href="{{ route('location') }}" class="rounded-xl border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                        Lokasi &amp; Jadwal
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('seo')
    <x-seo-head
        title="Kontak &amp; Pertanyaan — {{ $siteSettings->practice_name }}"
        description="Hubungi {{ $siteSettings->practice_name }} di Binong, Curug melalui formulir pertanyaan, telepon {{ $siteSettings->phone }}, atau WhatsApp."
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$breadcrumbJsonLd" />
@endpush

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Kontak Praktek</p>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Hubungi {{ $siteSettings->practice_name }}
            </h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-[#3d4d47]">
                Gunakan formulir ini untuk menanyakan jadwal praktek, informasi layanan, atau rute menuju klinik.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        <div class="grid gap-8 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <x-contact-form />
            </div>

            <div class="space-y-6 lg:col-span-5">
                <div class="rounded-3xl border border-[#176b55]/15 bg-white p-6 shadow-xs">
                    <h2 class="font-display text-xl font-semibold text-[#13221d]">Kontak Langsung</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Telepon / WhatsApp</dt>
                            <dd class="mt-1 font-semibold tabular-nums text-[#0f4c3c]">
                                <a href="{{ $telHref }}" class="hover:underline">{{ $siteSettings->phone }}</a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Alamat</dt>
                            <dd class="mt-1 text-[#13221d]">{{ $siteSettings->address }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wider text-[#4b615a]">Jadwal Hari Ini</dt>
                            <dd class="mt-1 font-medium text-[#13221d]">{{ $todaySchedule->hours_text }}</dd>
                        </div>
                    </dl>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl bg-[#176b55] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#0f4c3c]">
                            <x-icons.whatsapp class="h-4 w-4 fill-white" />
                            Chat via WhatsApp
                        </a>
                        <a href="{{ $siteSettings->maps_url }}" target="_blank" rel="noopener noreferrer" class="rounded-xl border border-[#176b55]/25 px-4 py-2.5 text-sm font-semibold text-[#0f4c3c] hover:bg-[#eef6f3]">
                            Petunjuk Arah
                        </a>
                    </div>
                </div>

                <x-medical-disclaimer compact />
            </div>
        </div>
    </section>
@endsection

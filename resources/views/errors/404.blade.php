@extends('layouts.app')

@section('seo')
    <x-seo-head
        :title="'Halaman Tidak Ditemukan (404) — '.($siteSettings->practice_name ?? 'Praktek Dokter Siti Mudiroh')"
        description="Halaman yang Anda cari tidak tersedia atau telah dipindahkan."
    />
@endsection

@section('content')
    <section class="clinical-contour-bg py-16 sm:py-24">
        <div class="mx-auto max-w-2xl px-4 text-center sm:px-6">
            <span class="inline-flex items-center rounded-full border border-[#176b55]/20 bg-white px-3.5 py-1 text-xs font-semibold text-[#176b55]">
                Kesalahan 404
            </span>
            <h1 class="font-display mt-4 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Halaman Tidak Ditemukan
            </h1>
            <p class="mt-3 text-sm leading-relaxed text-[#4b615a] sm:text-base">
                Maaf, halaman yang Anda tuju tidak tersedia, belum diterbitkan, atau tautannya telah berubah.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#176b55] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0f4c3c]"
                >
                    Kembali ke Beranda
                </a>
                <a
                    href="{{ route('services.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-[#176b55]/25 bg-white px-5 py-2.5 text-sm font-semibold text-[#0f4c3c] transition hover:bg-[#eef6f3]"
                >
                    Lihat Layanan
                </a>
                <a
                    href="{{ route('contact.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-[#176b55]/25 bg-white px-5 py-2.5 text-sm font-semibold text-[#0f4c3c] transition hover:bg-[#eef6f3]"
                >
                    Hubungi Praktek
                </a>
            </div>
        </div>
    </section>
@endsection

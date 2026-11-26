@extends('layouts.app')

@section('seo')
    <x-seo-head
        :title="$article->meta_title ?: ($article->title.' — '.$siteSettings->practice_name)"
        :description="$article->meta_description ?: $article->excerpt"
        ogType="article"
        :ogImage="$article->cover_image_url ?? null"
        :publishedTime="$article->published_at?->toIso8601String()"
        :modifiedTime="$article->updated_at?->toIso8601String()"
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$articleJsonLd" />
@endpush

@section('content')
    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 sm:py-14">
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb" class="mb-6 text-xs font-medium text-[#4b615a]">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ route('home') }}" class="hover:text-[#176b55]">Beranda</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('articles.index') }}" class="hover:text-[#176b55]">Edukasi</a></li>
                @if($article->category)
                    <li aria-hidden="true">/</li>
                    <li>
                        <a href="{{ route('articles.index', ['kategori' => $article->category->slug]) }}" class="hover:text-[#176b55]">
                            {{ $article->category->name }}
                        </a>
                    </li>
                @endif
            </ol>
        </nav>

        <header>
            @if($article->category)
                <span class="inline-flex rounded-full bg-[#eef6f3] px-3.5 py-1 text-xs font-semibold text-[#0f4c3c]">
                    {{ $article->category->name }}
                </span>
            @endif

            <h1 class="font-display mt-4 text-3xl font-semibold leading-tight text-[#13221d] sm:text-4xl">
                {{ $article->title }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-[#176b55]/12 pb-6 text-xs text-[#4b615a]">
                <span>Ditulis / ditinjau: <strong class="text-[#13221d]">{{ $article->author_name ?? $siteSettings->doctor_name }}</strong></span>
                @if($article->published_at)
                    <span>Diterbitkan: <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->translatedFormat('d F Y') }}</time></span>
                @endif
                @if($article->updated_at)
                    <span>Diperbarui: <time datetime="{{ $article->updated_at->toDateString() }}">{{ $article->updated_at->translatedFormat('d F Y') }}</time></span>
                @endif
            </div>
        </header>

        @if(! empty($article->cover_image_url))
            <div class="mt-8 overflow-hidden rounded-3xl border border-[#176b55]/12">
                <img
                    src="{{ $article->cover_image_url }}"
                    alt="{{ $article->title }}"
                    width="960"
                    height="540"
                    class="aspect-[16/9] w-full object-cover"
                >
            </div>
        @endif

        <div class="article-prose mt-8">
            {!! $article->body !!}
        </div>

        {{-- Mandatory Reference / Source Box --}}
        <div class="mt-10 rounded-2xl border border-[#176b55]/20 bg-[#eef6f3]/70 p-5 text-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#0f4c3c]">Sumber Rujukan Medis</p>
            <p class="mt-1.5 font-medium text-[#13221d]">
                {{ $article->source_name }}
                @if(! empty($article->source_url))
                    — <a href="{{ $article->source_url }}" target="_blank" rel="noopener noreferrer" class="text-[#176b55] underline break-all">{{ $article->source_url }}</a>
                @endif
            </p>
        </div>

        <x-medical-disclaimer class="mt-6" />

        {{-- CTA Consult with Doctor --}}
        <div class="mt-8 flex flex-col items-start justify-between gap-4 rounded-3xl bg-[#0f4c3c] p-6 text-white sm:flex-row sm:items-center sm:p-8">
            <div>
                <h2 class="font-display text-xl font-semibold">Butuh Pemeriksaan atau Penjelasan Lebih Lanjut?</h2>
                <p class="mt-1 text-sm text-emerald-50/85">
                    Kunjungi {{ $siteSettings->practice_name }} atau hubungi kami untuk informasi jadwal praktek.
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-3">
                <a href="{{ route('contact.index') }}" class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-[#0f4c3c] hover:bg-[#eef6f3]">
                    Konsultasikan dengan Dokter
                </a>
                <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-5 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                    <x-icons.whatsapp class="h-4 w-4 fill-white" />
                    WhatsApp
                </a>
            </div>
        </div>

        @if($relatedArticles->isNotEmpty())
            <section class="mt-14 border-t border-[#176b55]/12 pt-10">
                <h2 class="font-display text-2xl font-semibold text-[#13221d]">Artikel Edukasi Lainnya</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-3">
                    @foreach($relatedArticles as $related)
                        <article class="rounded-2xl border border-[#176b55]/14 bg-white p-5">
                            <p class="text-xs font-semibold text-[#176b55]">{{ $related->category->name ?? 'Edukasi' }}</p>
                            <h3 class="font-display mt-2 text-base font-semibold leading-snug text-[#13221d]">
                                <a href="{{ route('articles.show', $related->slug) }}" class="hover:text-[#176b55]">
                                    {{ $related->title }}
                                </a>
                            </h3>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
@endsection

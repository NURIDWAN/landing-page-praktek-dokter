@extends('layouts.app')

@section('seo')
    <x-seo-head
        title="Edukasi Kesehatan — {{ $siteSettings->practice_name }}"
        description="Artikel edukasi kesehatan anak, kesehatan dewasa, keluarga berencana (KB), dan kesehatan perempuan dengan sumber rujukan medis tepercaya."
    />
@endsection

@push('structured-data')
    <x-json-ld :data="$breadcrumbJsonLd" />
@endpush

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-6xl px-4 py-12 sm:px-6 sm:py-16">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Edukasi Keluarga</p>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                Artikel &amp; Informasi Kesehatan
            </h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-[#3d4d47]">
                Bacaan kesehatan praktis untuk keluarga dengan pencantuman sumber rujukan resmi dan tanggal pembaruan.
            </p>

            {{-- Filter & Search --}}
            <form action="{{ route('articles.index') }}" method="GET" class="mt-7 flex flex-col gap-3 sm:flex-row sm:items-center">
                @if($activeCategory)
                    <input type="hidden" name="kategori" value="{{ $activeCategory }}">
                @endif
                <div class="relative flex-1 max-w-md">
                    <label for="article-search" class="sr-only">Cari artikel kesehatan</label>
                    <input
                        id="article-search"
                        name="q"
                        type="search"
                        value="{{ $searchQuery }}"
                        placeholder="Cari topik kesehatan (mis. demam, hipertensi, KB)..."
                        class="w-full rounded-2xl border border-[#176b55]/20 bg-white px-4 py-3 text-sm text-[#13221d] placeholder:text-[#6c7f78] focus:border-[#176b55] focus:outline-none"
                    >
                </div>
                <button type="submit" class="rounded-2xl bg-[#176b55] px-5 py-3 text-sm font-semibold text-white hover:bg-[#0f4c3c]">
                    Cari Artikel
                </button>
                @if($activeCategory || $searchQuery)
                    <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-[#176b55] hover:underline">
                        Reset filter
                    </a>
                @endif
            </form>

            {{-- Category Pills --}}
            <div class="mt-5 flex flex-wrap gap-2" role="navigation" aria-label="Filter Kategori Artikel">
                <a
                    href="{{ route('articles.index', $searchQuery ? ['q' => $searchQuery] : []) }}"
                    class="rounded-full px-4 py-1.5 text-xs font-semibold transition {{ ! $activeCategory ? 'bg-[#176b55] text-white' : 'border border-[#176b55]/20 bg-white text-[#13221d] hover:bg-[#eef6f3]' }}"
                >
                    Semua Kategori
                </a>
                @foreach($categories as $category)
                    <a
                        href="{{ route('articles.index', array_filter(['kategori' => $category->slug, 'q' => $searchQuery])) }}"
                        class="rounded-full px-4 py-1.5 text-xs font-semibold transition {{ $activeCategory === $category->slug ? 'bg-[#176b55] text-white' : 'border border-[#176b55]/20 bg-white text-[#13221d] hover:bg-[#eef6f3]' }}"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12 sm:px-6">
        @if($articles->isEmpty())
            <div class="rounded-3xl border border-[#176b55]/15 bg-white p-10 text-center">
                <p class="font-display text-xl font-semibold text-[#13221d]">Artikel belum ditemukan</p>
                <p class="mt-2 text-sm text-[#4b615a]">Coba gunakan kata kunci lain atau lihat seluruh kategori artikel edukasi.</p>
                <a href="{{ route('articles.index') }}" class="mt-5 inline-flex rounded-xl bg-[#176b55] px-4 py-2.5 text-sm font-semibold text-white">
                    Lihat Semua Artikel
                </a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($articles as $article)
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

                            <h2 class="font-display mt-2.5 text-lg font-semibold leading-snug text-[#13221d]">
                                <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-[#176b55]">
                                    {{ $article->title }}
                                </a>
                            </h2>

                            <p class="mt-2.5 flex-1 text-sm leading-relaxed text-[#4b615a]">
                                {{ $article->excerpt }}
                            </p>

                            <div class="mt-5 flex items-center justify-between border-t border-[#176b55]/10 pt-4 text-xs">
                                <span class="truncate text-[#4b615a]">{{ $article->source_name }}</span>
                                <a href="{{ route('articles.show', $article->slug) }}" class="shrink-0 font-semibold text-[#176b55] hover:underline">
                                    Baca artikel &rarr;
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $articles->links() }}
            </div>
        @endif
    </section>
@endsection

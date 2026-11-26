@extends('layouts.app')

@section('seo')
    <x-seo-head
        :title="$page->meta_title ?: ($page->title.' — '.$siteSettings->practice_name)"
        :description="$page->meta_description ?: $siteSettings->default_meta_description"
    />
@endsection

@section('content')
    <section class="clinical-contour-bg border-b border-[#176b55]/12">
        <div class="mx-auto max-w-4xl px-4 py-12 sm:px-6">
            <p class="text-xs font-semibold uppercase tracking-wider text-[#176b55]">Informasi Legal &amp; Kepatuhan</p>
            <h1 class="font-display mt-2 text-3xl font-semibold text-[#13221d] sm:text-4xl">
                {{ $page->title }}
            </h1>
            @if(! empty($page->updated_at))
                <p class="mt-2 text-xs text-[#4b615a]">
                    Terakhir diperbarui: {{ \Carbon\Carbon::parse($page->updated_at)->translatedFormat('d F Y') }}
                </p>
            @endif
        </div>
    </section>

    <section class="mx-auto max-w-4xl px-4 py-10 sm:px-6">
        <div class="rounded-3xl border border-[#176b55]/14 bg-white p-6 sm:p-10">
            <div class="article-prose">
                {!! $page->body !!}
            </div>
        </div>
    </section>
@endsection

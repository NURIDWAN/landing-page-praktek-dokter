@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'ogType' => 'website',
    'ogImage' => null,
    'publishedTime' => null,
    'modifiedTime' => null,
])

@php
    $resolvedTitle = $title ?: ($siteSettings->default_meta_title ?? 'Praktek Dokter Siti Mudiroh');
    $resolvedDescription = $description ?: ($siteSettings->default_meta_description ?? '');
    $resolvedCanonical = $canonical ?: url()->current();
    $rawImage = $ogImage ?: ($siteSettings->hero_image ?? '/images/clinic-hero.webp');
    $resolvedImage = str_starts_with($rawImage, 'http') ? $rawImage : url($rawImage);
@endphp

<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $resolvedDescription }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">
<meta name="robots" content="index, follow">

<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $resolvedDescription }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:site_name" content="{{ $siteSettings->practice_name ?? 'Praktek Dokter Siti Mudiroh' }}">
<meta property="og:image" content="{{ $resolvedImage }}">

@if($publishedTime)
    <meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if($modifiedTime)
    <meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $resolvedDescription }}">
<meta name="twitter:image" content="{{ $resolvedImage }}">

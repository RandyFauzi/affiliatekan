@php
    $seoManager = app(\App\Services\SeoManager::class);
    if (isset($pageTitle)) {
        // Strip out dynamic suffix if it was already appended in view to avoid duplicates
        $cleanTitle = str_replace(' - Affiliatekan', '', $pageTitle);
        $seoManager->setTitle($cleanTitle);
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Dynamic Meta SEO Tags -->
        <title>{{ $seoManager->getTitle() }}</title>
        <meta name="description" content="{{ $seoManager->getDescription() }}">
        <meta name="keywords" content="{{ $seoManager->getKeywords() }}">
        <meta name="robots" content="{{ $seoManager->getRobots() }}">
        <link rel="canonical" href="{{ $seoManager->getCanonical() }}">

        <!-- Open Graph Tags -->
        @foreach($seoManager->getOgTags() as $property => $content)
        <meta property="og:{{ $property }}" content="{{ $content }}">
        @endforeach

        <!-- Twitter Card Tags -->
        @foreach($seoManager->getTwitterTags() as $name => $content)
        <meta name="twitter:{{ $name }}" content="{{ $content }}">
        @endforeach

        <!-- JSON-LD Structured Data Schema Markups -->
        {!! $seoManager->renderSchemas() !!}
        @stack('head_meta')
        @stack('schema')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/Pavicon - Affilaitekan.svg') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(35,76,240,0.22),_transparent_34%),linear-gradient(135deg,_#eef3ff_0%,_#f7faff_42%,_#fffed8_100%)] font-sans text-slate-900">
        {{ $slot }}
    </body>
</html>

@php
    $seoManager = app(\App\Services\SeoManager::class);
    if (isset($pageTitle)) {
        $cleanTitle = str_replace(' - Affiliatekan', '', $pageTitle);
        $seoManager->setTitle($cleanTitle);
    }
    // Authenticated pages shouldn't be indexed by search engines
    $seoManager->setRobots('noindex, nofollow');
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
    <body class="font-sans text-slate-800">
        @php
            $authenticatedUser = auth()->user();
            $welcomeName = $authenticatedUser?->name ?? 'Partner';
            $vendorNavigationItems = [
                [
                    'label' => 'Dashboard',
                    'route' => route('vendor.dashboard.index'),
                    'active' => request()->routeIs('vendor.dashboard.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><rect x="4" y="4" width="6" height="6" rx="1.5"/><rect x="14" y="4" width="6" height="6" rx="1.5"/><rect x="4" y="14" width="6" height="6" rx="1.5"/><rect x="14" y="14" width="6" height="6" rx="1.5"/></svg>',
                ],
                [
                    'label' => 'Produk & Komisi',
                    'route' => route('vendor.products.index'),
                    'active' => request()->routeIs('vendor.products.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
                ],
                [
                    'label' => 'Payouts',
                    'route' => route('vendor.payouts.index'),
                    'active' => request()->routeIs('vendor.payouts.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/><path d="M16 14h2"/></svg>',
                ],
                [
                    'label' => 'Integration',
                    'route' => route('vendor.integration.index'),
                    'active' => request()->routeIs('vendor.integration.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M8.5 8.5l7 7"/><path d="M7 14a4 4 0 010-5.66l1.34-1.34A4 4 0 0114 7"/><path d="M17 10a4 4 0 010 5.66l-1.34 1.34A4 4 0 0110 17"/></svg>',
                ],
                [
                    'label' => 'Afiliator Terdaftar',
                    'route' => route('vendor.affiliates.index'),
                    'active' => request()->routeIs('vendor.affiliates.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
                ],
                [
                    'label' => 'Profil Toko',
                    'route' => route('profile.edit'),
                    'active' => request()->routeIs('profile.edit'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>',
                ],
            ];
        @endphp

        <div class="flex h-screen bg-slate-50 overflow-hidden">
            <aside class="w-64 bg-white border-r border-slate-100 flex-col h-full hidden md:flex">
                <div class="px-6 py-7">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/Logo - Affilaitekan.svg') }}" alt="Logo Affiliatekan" class="h-10 w-auto">
                    </div>
                </div>

                <nav class="flex-1 px-4">
                    <div class="space-y-2">
                        @foreach ($vendorNavigationItems as $vendorNavigationItem)
                            <a
                                href="{{ $vendorNavigationItem['route'] }}"
                                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $vendorNavigationItem['active'] ? 'bg-orange-50 text-brandOrange' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                            >
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $vendorNavigationItem['active'] ? 'bg-white text-brandOrange shadow-sm' : 'bg-slate-50 text-slate-400 group-hover:bg-white group-hover:text-brandOrange' }}">
                                    {!! $vendorNavigationItem['icon'] !!}
                                </span>
                                <span>{{ $vendorNavigationItem['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </nav>

                <div class="p-4">
                    <div class="rounded-2xl border border-orange-100 bg-orange-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-orange-400">Workspace</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800">Vendor aktif</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Kelola traffic, payout, dan integrasi dari satu panel yang rapi.</p>
                    </div>
                </div>

                <div class="p-4 pt-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:text-slate-800"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 overflow-y-auto p-8">
                <div class="w-full">
                    <header class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Welcome back, {{ $welcomeName }}</p>
                            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-slate-800">Vendor Dashboard</h1>
                        </div>

                        <div class="flex items-center gap-3">
                            <a
                                href="{{ route('vendor.integration.index') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-5 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg"
                            >
                                Integrasi API
                            </a>
                            <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-white px-4 py-2.5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:border-orange-200 hover:shadow-md transition-all duration-300">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 font-bold text-brandOrange group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                                    {{ strtoupper(substr($welcomeName, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800 group-hover:text-brandOrange transition-all duration-300">{{ $welcomeName }}</p>
                                    <p class="truncate text-xs uppercase tracking-[0.24em] text-slate-400">Vendor</p>
                                </div>
                            </a>
                        </div>
                    </header>

                    <div
                        x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, 100)"
                        x-show="show"
                        x-transition.opacity.duration.500ms
                    >
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <x-sweet-alert />
    </body>
</html>

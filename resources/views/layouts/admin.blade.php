@php
    $seoManager = app(\App\Services\SeoManager::class);
    if (isset($pageTitle)) {
        $cleanTitle = str_replace(' - Affiliatekan', '', $pageTitle);
        $seoManager->setTitle($cleanTitle);
    }
    // Admin pages shouldn't be indexed by search engines
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
            $welcomeName = $authenticatedUser?->name ?? 'Super Admin';
            $adminNavigationItems = [
                [
                    'label' => 'Dashboard',
                    'route' => route('admin.dashboard'),
                    'active' => request()->routeIs('admin.dashboard'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><rect x="4" y="4" width="6" height="6" rx="1.5"/><rect x="14" y="4" width="6" height="6" rx="1.5"/><rect x="4" y="14" width="6" height="6" rx="1.5"/><rect x="14" y="14" width="6" height="6" rx="1.5"/></svg>',
                ],
                [
                    'label' => 'Vendors',
                    'route' => route('admin.vendors.index'),
                    'active' => request()->routeIs('admin.vendors.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M4 20V8l8-4 8 4v12"/><path d="M9 20v-5h6v5"/><path d="M9 10h.01"/><path d="M15 10h.01"/></svg>',
                ],
                [
                    'label' => 'Affiliates',
                    'route' => route('admin.affiliates.index'),
                    'active' => request()->routeIs('admin.affiliates.*'),
                    'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
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
                        @foreach ($adminNavigationItems as $adminNavigationItem)
                            <a
                                href="{{ $adminNavigationItem['route'] }}"
                                class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg {{ $adminNavigationItem['active'] ? 'bg-orange-50 text-brandOrange' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}"
                            >
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $adminNavigationItem['active'] ? 'bg-white text-brandOrange shadow-sm' : 'bg-slate-50 text-slate-400 group-hover:bg-white group-hover:text-brandOrange' }}">
                                    {!! $adminNavigationItem['icon'] !!}
                                </span>
                                <span>{{ $adminNavigationItem['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </nav>

                <div class="p-4">
                    <div class="rounded-2xl border border-orange-100 bg-orange-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-orange-400">Control Center</p>
                        <p class="mt-2 text-sm font-semibold text-slate-800">Platform supervision aktif</p>
                        <p class="mt-1 text-sm leading-6 text-slate-500">Pantau vendor, afiliator, dan kesehatan bisnis dari satu panel admin.</p>
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
                <div class="mx-auto max-w-7xl">
                    <header class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Welcome back, {{ $welcomeName }}</p>
                            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-slate-800">Admin Dashboard</h1>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 rounded-2xl border border-slate-100 bg-white px-4 py-2.5 shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:border-orange-200 hover:shadow-md transition-all duration-300">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 font-bold text-brandOrange group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                                    {{ strtoupper(substr($welcomeName, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800 group-hover:text-brandOrange transition-all duration-300">{{ $welcomeName }}</p>
                                    <p class="truncate text-xs uppercase tracking-[0.24em] text-slate-400">Super Admin</p>
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

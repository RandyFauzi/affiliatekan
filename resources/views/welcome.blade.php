<x-layouts.marketing page-title="Affiliatekan - Platform Pelacak Afiliasi SaaS B2B Terpercaya">
    @push('head_meta')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Affiliatekan",
      "url": "https://affiliatekan-by.autogrowthid.site",
      "logo": "https://affiliatekan-by.autogrowthid.site/images/Logo - Affilaitekan.svg",
      "description": "Platform pelacak kemitraan afiliasi otomatis untuk bisnis SaaS B2B dengan keamanan kriptografis lapis kedua.",
      "sameAs": [
        "https://facebook.com/affiliatekan",
        "https://twitter.com/affiliatekan"
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "Affiliatekan",
      "operatingSystem": "All",
      "applicationCategory": "BusinessApplication",
      "description": "SaaS Platform pelacak afiliasi B2B otomatis dengan enkripsi data rekening, verifikasi API Key terproteksi hash SHA-256, dan row-level integrity signature.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "IDR"
      }
    }
    </script>
    @endpush
    {{-- Inline CSS for magnificent custom animations and keyframes --}}
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(-5px); }
            50% { transform: translateY(5px); }
        }
        .animate-float-delayed {
            animation: float-delayed 5s ease-in-out infinite;
        }
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 0.08; }
            50% { transform: scale(1.08); opacity: 0.14; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 8s ease-in-out infinite;
        }
        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 20s linear infinite;
        }

        /* Premium Scroll Reveal Transitions */
        .reveal {
            opacity: 0;
            transform: translateY(24px) scale(0.98);
            transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform, opacity;
        }
        .reveal.reveal-left {
            transform: translateX(-30px) scale(1);
        }
        .reveal.reveal-right {
            transform: translateX(30px) scale(1);
        }
        .reveal.reveal-scale {
            transform: scale(0.94);
        }
        .reveal.revealed {
            opacity: 1;
            transform: translate(0) scale(1);
        }

        /* Stagger Delays for child element animations */
        .delay-75 { transition-delay: 75ms; }
        .delay-100 { transition-delay: 100ms; }
        .delay-150 { transition-delay: 150ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }
        .delay-500 { transition-delay: 500ms; }
    </style>

    {{-- Main Container with Clean White-Orange Theme --}}
    <div x-data="{ mobileMenuOpen: false }" class="relative min-h-screen bg-slate-50/50 font-sans antialiased text-slate-800 overflow-x-hidden">
        
        {{-- Animated Background Blobs for Visual Depth --}}
        <div class="absolute top-[-100px] right-[-100px] w-[500px] h-[500px] rounded-full bg-brandOrange/5 blur-[120px] pointer-events-none z-0 animate-pulse-slow"></div>
        <div class="absolute top-[800px] left-[-200px] w-[600px] h-[600px] rounded-full bg-orange-400/5 blur-[150px] pointer-events-none z-0 animate-pulse-slow" style="animation-delay: 2s;"></div>

        {{-- Custom Radial Orange Gradient Highlight --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[650px] bg-[radial-gradient(circle_at_top,_rgba(255,107,0,0.08),_transparent_55%)] pointer-events-none z-0"></div>

        {{-- 1. HEADER / NAVBAR --}}
        <header class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-6">
            <nav class="flex items-center justify-between rounded-3xl border border-white/20 bg-white/70 px-6 py-4 shadow-sm backdrop-blur-md transition-all duration-300 hover:shadow-md" aria-label="Navigasi Utama">
                <div class="flex items-center gap-10">
                    <a href="/" class="flex items-center gap-3 focus:outline-none transition-transform hover:scale-105 duration-300" id="nav-brand-logo">
                        <img src="{{ asset('images/Logo - Affilaitekan.svg') }}" alt="Logo Resmi Affiliatekan" class="h-9 w-auto">
                    </a>
                    
                    {{-- Desktop Links --}}
                    <div class="hidden md:flex items-center gap-8">
                        <a href="#solusi" class="text-sm font-semibold text-slate-600 hover:text-brandOrange transition duration-250 relative group">
                            Solusi
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-brandOrange transition duration-250 relative group">
                            Fitur
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-brandOrange transition duration-250 relative group">
                            Cara Kerja
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#faq" class="text-sm font-semibold text-slate-600 hover:text-brandOrange transition duration-250 relative group">
                            Tanya Jawab
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-brandOrange transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </div>
                </div>

                {{-- CTA Buttons --}}
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-brandOrange transition duration-200 px-4 py-2">
                        Masuk
                    </a>
                    <a
                        href="{{ route('register') }}"
                        id="btn-register-navbar"
                        class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-5 py-3 text-sm font-extrabold text-white shadow-md shadow-orange-500/10 hover:bg-orange-600 hover:shadow-lg hover:shadow-orange-500/20 hover:-translate-y-0.5 transition-all duration-300 active:scale-[0.98]"
                    >
                        Mulai Gratis
                    </a>
                </div>

                {{-- Mobile Menu Toggle --}}
                <button 
                    type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition"
                    aria-expanded="false"
                    id="btn-mobile-menu-toggle"
                >
                    <span class="sr-only">Buka menu utama</span>
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </nav>

            {{-- Mobile Dropdown Menu --}}
            <div 
                x-show="mobileMenuOpen" 
                x-cloak
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute left-4 right-4 mt-3 rounded-3xl border border-slate-150 bg-white p-6 shadow-xl z-20 md:hidden"
            >
                <div class="flex flex-col gap-4">
                    <a href="#solusi" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 hover:text-brandOrange py-2">Solusi</a>
                    <a href="#fitur" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 hover:text-brandOrange py-2">Fitur</a>
                    <a href="#cara-kerja" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 hover:text-brandOrange py-2">Cara Kerja</a>
                    <a href="#faq" @click="mobileMenuOpen = false" class="text-base font-semibold text-slate-700 hover:text-brandOrange py-2">Tanya Jawab</a>
                    <hr class="border-slate-100 my-2">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white py-3.5 text-sm font-bold text-slate-700">Masuk</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-brandOrange py-3.5 text-sm font-extrabold text-white">Mulai Gratis</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 lg:py-24">
            
            {{-- 2. HERO SECTION --}}
            <section class="grid gap-16 lg:grid-cols-2 items-center" aria-label="Hero Pengenalan">
                <div class="space-y-6 reveal reveal-left">
                    <span class="inline-flex items-center gap-2 rounded-full border border-orange-200/60 bg-orange-50 px-4 py-2 text-xs font-extrabold uppercase tracking-widest text-brandOrange transition-transform hover:scale-105 duration-300">
                        <span class="h-2 w-2 rounded-full bg-brandOrange animate-pulse"></span>
                        SaaS Tracking Afiliasi B2B
                    </span>
                    <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl leading-[1.1] md:leading-[1.05]">
                        Mulai Hasilkan Pendapatan Tanpa Batas Bersama <span class="text-transparent bg-clip-text bg-gradient-to-r from-brandOrange via-orange-500 to-amber-500">Affiliatekan</span>
                    </h1>
                    <p class="max-w-2xl text-base sm:text-lg leading-relaxed text-slate-600">
                        Platform pelacak kemitraan afiliasi otomatis untuk bisnis SaaS B2B. Lacak klik referral secara presisi dan cairkan komisi Anda secara instan dengan proteksi tanda tangan kriptografis lapis kedua.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a
                            href="{{ route('register') }}"
                            id="btn-hero-join"
                            class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-8 py-4.5 text-base font-extrabold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-600 hover:shadow-xl hover:shadow-orange-500/30 hover:-translate-y-1 transition-all duration-300 active:scale-[0.97]"
                        >
                            Bergabung Sekarang
                        </a>
                        <a
                            href="#fitur"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-8 py-4.5 text-base font-bold text-slate-655 hover:bg-slate-50 hover:text-slate-900 hover:border-slate-300 transition-all duration-200"
                        >
                            Pelajari Fitur Kami
                        </a>
                    </div>

                    <div class="pt-6 flex items-center gap-6 text-xs text-slate-450 font-bold uppercase tracking-wider">
                        <span class="flex items-center gap-2"><svg class="h-4.5 w-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Tanpa Kartu Kredit</span>
                        <span class="flex items-center gap-2"><svg class="h-4.5 w-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> Setup 2 Menit</span>
                    </div>
                </div>

                {{-- Hero Magnificent Floating App Preview Mockup --}}
                <div class="relative lg:block animate-float reveal reveal-right delay-150" aria-hidden="true">
                    <div class="absolute -inset-2 rounded-[38px] bg-gradient-to-tr from-brandOrange/25 to-orange-400/25 blur-2xl opacity-75 animate-pulse-slow"></div>
                    <div class="relative rounded-[36px] border border-slate-150 bg-white p-7 shadow-2xl transition-all duration-500 hover:border-orange-200/50">
                        
                        {{-- Mockup Decorative Top Dots --}}
                        <div class="flex gap-1.5 absolute top-5 right-6">
                            <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                            <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                            <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                        </div>

                        {{-- Mockup Header --}}
                        <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange border border-orange-100/50">
                                    A
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-800">Afiliator Jagoan</p>
                                    <p class="text-[10px] text-brandOrange font-bold tracking-wider uppercase">Saldo Siap Tarik</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-extrabold text-emerald-600 border border-emerald-100/50">TERVERIFIKASI</span>
                        </div>

                        {{-- Mockup Metrics Grid --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50/70 p-4 border border-slate-100 hover:bg-slate-50 transition-colors">
                                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Total Klik</p>
                                <p class="mt-2 text-2xl font-black text-slate-800">12,450</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50/70 p-4 border border-slate-100 hover:bg-slate-50 transition-colors">
                                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest leading-none">Komisi Cair</p>
                                <p class="mt-2 text-2xl font-black text-brandOrange">Rp 4.5M+</p>
                            </div>
                        </div>

                        {{-- Mockup Visual Chart Element --}}
                        <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50/40 p-4">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Aktivitas Kemitraan</span>
                                <span class="text-[10px] font-extrabold text-brandOrange">Statistik Harian</span>
                            </div>
                            <div class="h-28 flex items-end gap-3 pt-4">
                                <div class="w-full bg-orange-100/60 rounded-t-lg h-12 hover:bg-brandOrange hover:scale-105 transition-all duration-300"></div>
                                <div class="w-full bg-orange-100/60 rounded-t-lg h-20 hover:bg-brandOrange hover:scale-105 transition-all duration-300" style="animation-delay: 0.1s;"></div>
                                <div class="w-full bg-orange-100/60 rounded-t-lg h-16 hover:bg-brandOrange hover:scale-105 transition-all duration-300" style="animation-delay: 0.2s;"></div>
                                <div class="w-full bg-brandOrange rounded-t-lg h-24 shadow-md shadow-orange-500/20 hover:scale-105 transition-transform duration-300"></div>
                                <div class="w-full bg-orange-100/60 rounded-t-lg h-14 hover:bg-brandOrange hover:scale-105 transition-all duration-300" style="animation-delay: 0.3s;"></div>
                                <div class="w-full bg-orange-100/60 rounded-t-lg h-22 hover:bg-brandOrange hover:scale-105 transition-all duration-300" style="animation-delay: 0.4s;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 3. KEY STATS SECTION --}}
            <section id="statistik" class="py-16 border-t border-slate-100 mt-20 reveal reveal-scale" aria-label="Statistik Utama">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="space-y-1 group hover:scale-105 transition-transform duration-300 cursor-default">
                        <p class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-brandOrange transition-colors duration-300">5.000+</p>
                        <p class="text-xs sm:text-sm font-extrabold text-slate-400 uppercase tracking-widest">Afiliator Aktif</p>
                    </div>
                    <div class="space-y-1 group hover:scale-105 transition-transform duration-300 cursor-default">
                        <p class="text-3xl sm:text-4xl font-black text-brandOrange">Rp 10M+</p>
                        <p class="text-xs sm:text-sm font-extrabold text-slate-400 uppercase tracking-widest">Komisi Cair</p>
                    </div>
                    <div class="space-y-1 group hover:scale-105 transition-transform duration-300 cursor-default">
                        <p class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-brandOrange transition-colors duration-300">99,99%</p>
                        <p class="text-xs sm:text-sm font-extrabold text-slate-400 uppercase tracking-widest">Akurasi Tracking</p>
                    </div>
                    <div class="space-y-1 group hover:scale-105 transition-transform duration-300 cursor-default">
                        <p class="text-3xl sm:text-4xl font-black text-slate-900 group-hover:text-brandOrange transition-colors duration-300">250ms</p>
                        <p class="text-xs sm:text-sm font-extrabold text-slate-400 uppercase tracking-widest">Response Time API</p>
                    </div>
                </div>
            </section>
 
            {{-- 4. DUAL ECOSYSTEM SECTION (AFFILIATE & VENDOR) --}}
            <section id="solusi" class="py-20 border-t border-slate-100 reveal" aria-label="Ekosistem Solusi Dua Arah">
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                    <span class="text-xs font-black text-brandOrange uppercase tracking-widest">Dua Solusi, Satu Ekosistem</span>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Membuka Peluang Kolaborasi Afiliator & Vendor
                    </h2>
                    <p class="text-base text-slate-500 leading-relaxed">
                        Kami menjembatani afiliator berbakat dengan pemilik produk/vendor terpercaya untuk memperluas jangkauan pasar dan melipatgandakan omzet penjualan.
                    </p>
                </div>
 
                <div class="grid gap-8 lg:grid-cols-2">
                    {{-- Left Card: For Affiliates --}}
                    <article class="group relative rounded-[32px] border border-slate-150 bg-white p-8 sm:p-10 shadow-xl overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-2xl hover:border-orange-200/50 reveal reveal-left">
                        <div class="absolute top-0 right-0 w-24 h-24 rounded-bl-full bg-orange-50/50 transition-all duration-300 group-hover:w-28 group-hover:h-28"></div>
                        <div class="relative z-10 space-y-6">
                            <span class="inline-flex rounded-xl bg-orange-50 px-3 py-1.5 text-xs font-extrabold text-brandOrange border border-orange-100/50">UNTUK AFILIATOR</span>
                            <h3 class="text-2xl font-black text-slate-900">Monetisasi Trafik Digital & Hasilkan Komisi Melimpah</h3>
                            <p class="text-sm text-slate-500 leading-relaxed">
                                Temukan berbagai produk siap promosi dari berbagai brand ternama. Bagikan link referral Anda dan pantau statistik performa komisi Anda dalam satu panel instan yang transparan.
                            </p>
                            <ul class="space-y-3 text-sm text-slate-600 font-semibold">
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-brandOrange"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Komisi Transparan Flat atau Persentase
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-brandOrange"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Pencairan Saldo Terjamin 1x24 Jam Kerja
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-orange-50 text-brandOrange"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Dashboard Pelacakan Klik & Penjualan Real-Time
                                </li>
                            </ul>
                            <div class="pt-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3.5 text-sm font-extrabold text-white shadow-md hover:bg-orange-600 active:scale-[0.98] transition">Daftar Jadi Afiliator</a>
                            </div>
                        </div>
                    </article>
 
                    {{-- Right Card: For Vendors --}}
                    <article class="group relative rounded-[32px] bg-gradient-to-tr from-brandOrange to-orange-500 p-8 sm:p-10 text-white shadow-xl overflow-hidden transition-all duration-500 hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-orange-500/20 reveal reveal-right delay-150">
                        {{-- Decorative background circles --}}
                        <div class="absolute -top-10 -right-10 h-32 w-32 rounded-full bg-white/5 pointer-events-none animate-float"></div>
                        <div class="relative z-10 space-y-6">
                            <span class="inline-flex rounded-xl bg-white/20 px-3 py-1.5 text-xs font-extrabold text-white border border-white/10">UNTUK VENDOR / MERCHANTS</span>
                            <h3 class="text-2xl font-black text-white">Lipatgandakan Penjualan Tanpa Biaya Iklan di Awal</h3>
                            <p class="text-sm text-white/80 leading-relaxed">
                                Bangun program afiliasi Anda sendiri. Rekrut ribuan afiliator yang siap memasarkan produk Anda secara sukarela. Anda hanya membayar komisi setelah penjualan benar-benar terjadi (*pay-per-sale*).
                            </p>
                            <ul class="space-y-3 text-sm text-white/95 font-semibold">
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-white"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Atur Komisi Fleksibel per Produk (Flat / Persentase)
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-white"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Integrasi SDK JavaScript 1 Baris Kode di Website Toko
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-white/20 text-white"><svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                                    Proteksi HMAC & API Key dari Manipulasi Data
                                </li>
                            </ul>
                            <div class="pt-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-extrabold text-brandOrange shadow-md hover:bg-orange-50 active:scale-[0.98] transition">Daftar Sebagai Vendor</a>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            {{-- 4B. VENDOR SYNERGY & GROWTH HUB SECTION --}}
            <section id="vendor-hub" class="py-20 border-t border-slate-100 reveal" aria-label="Peluang dan Sinergi Vendor">
                <div class="grid gap-16 lg:grid-cols-2 items-center">
                    
                    {{-- Left Column: Interactive Vendor Control Center Mockup --}}
                    <div class="relative order-2 lg:order-1" aria-hidden="true">
                        <div class="absolute -inset-2 rounded-[38px] bg-gradient-to-tr from-brandOrange/20 to-orange-400/20 blur-2xl opacity-70 animate-pulse-slow"></div>
                        <div class="relative rounded-[36px] border border-slate-150 bg-white p-8 shadow-2xl transition-all duration-500 hover:border-orange-200/50">
                            
                            {{-- Decorative Top Dots --}}
                            <div class="flex gap-1.5 absolute top-5 right-6">
                                <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                                <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                                <span class="h-2 w-2 rounded-full bg-slate-200"></span>
                            </div>

                            <div class="flex items-center gap-3 border-b border-slate-100 pb-5 mb-6">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange border border-orange-100/50">
                                    V
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-slate-800">Panel Kontrol Vendor</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Campaign Hub & Integrasi API</p>
                                </div>
                            </div>

                            {{-- Mockup Campaign Fields --}}
                            <div class="space-y-4">
                                <div class="rounded-2xl bg-slate-50/70 p-4 border border-slate-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-extrabold text-slate-500">Program Komisi Aktif</span>
                                        <span class="rounded-full bg-brandOrange/10 px-2.5 py-0.5 text-[9px] font-extrabold text-brandOrange border border-orange-200/30">PERSENTASE</span>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Nilai Default</p>
                                            <p class="text-xl font-black text-slate-800">20.00% <span class="text-xs text-slate-450 font-normal">per penjualan</span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Masa Aktif Cookie</p>
                                            <p class="text-sm font-extrabold text-slate-800">30 Hari Kalender</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Security & API Webhook Status --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-2xl bg-slate-50/70 p-4 border border-slate-100 flex flex-col justify-between">
                                        <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider leading-none">Status Webhook API</p>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                                            <span class="text-xs font-extrabold text-emerald-600">CONNECTED (200)</span>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-50/70 p-4 border border-slate-100 flex flex-col justify-between">
                                        <p class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider leading-none">Integritas Transaksi</p>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            <svg class="h-4 w-4 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                            <span class="text-xs font-extrabold text-brandOrange uppercase tracking-wide">HMAC SIGNED</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Growth Synergy Metric --}}
                                <div class="rounded-2xl bg-gradient-to-r from-orange-50 to-amber-50/30 p-4 border border-orange-100/50">
                                    <p class="text-[10px] font-extrabold text-brandOrange uppercase tracking-widest leading-none">Ekspansi Penjualan</p>
                                    <div class="flex justify-between items-center mt-3">
                                        <div>
                                            <p class="text-[9px] text-slate-400 font-bold uppercase">Afiliator Tergabung</p>
                                            <p class="text-lg font-black text-slate-800">1,420 Orang</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] text-slate-400 font-bold uppercase">Omzet Tambahan Kemitraan</p>
                                            <p class="text-lg font-black text-brandOrange">Rp 284.5M+</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Detailed Value Propositions --}}
                    <div class="space-y-6 order-1 lg:order-2">
                        <span class="inline-flex items-center gap-2 rounded-full border border-orange-200/60 bg-orange-50 px-4 py-2 text-xs font-extrabold uppercase tracking-widest text-brandOrange">
                            <span class="h-2 w-2 rounded-full bg-brandOrange animate-pulse"></span>
                            Peluang Skala Bisnis (Vendor)
                        </span>
                        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                            Buka Peluang Baru untuk Afiliator & Perluas Penjualan Anda
                        </h2>
                        <p class="text-base text-slate-600 leading-relaxed">
                            Sebagai pemilik produk (Vendor), Anda tidak lagi berjuang sendirian memasarkan produk. Buka peluang kolaborasi seluas-luasnya bagi para afiliator berbakat untuk menjadi kepanjangan tangan penjualan produk Anda.
                        </p>

                        <div class="space-y-6 pt-4">
                            {{-- Benefit 1 --}}
                            <div class="flex gap-4 items-start">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-brandOrange">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Jangkauan Pasar Tanpa Batas</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Produk Anda akan dipromosikan oleh ribuan afiliator yang sudah memiliki audiens tertarget dan terpercaya, memperluas kehadiran brand Anda di pasar B2B secara instan.</p>
                                </div>
                            </div>

                            {{-- Benefit 2 --}}
                            <div class="flex gap-4 items-start">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-brandOrange">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Pemasaran Berbasis Hasil (Pay-Per-Sale)</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Lupakan risiko anggaran iklan yang sia-sia (*ad-spend burnout*). Anda hanya membayar komisi afiliasi ketika transaksi penjualan benar-benar berhasil diverifikasi.</p>
                                </div>
                            </div>

                            {{-- Benefit 3 --}}
                            <div class="flex gap-4 items-start">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-brandOrange">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-800">Skema Cookie & Komisi Fleksibel</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed mt-1">Konfigurasikan persentase komisi secara mandiri, atur masa retensi cookie pelacakan per produk, dan tinjau performa kampanye dalam dashboard cockpit terpadu.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-7 py-4 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600 hover:shadow-xl transition-all duration-300">
                                Mulai Integrasikan Produk Anda
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 5. FEATURES SECTION --}}
            <section id="fitur" class="py-20 border-t border-slate-100 reveal" aria-label="Fitur Unggulan">
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16 reveal reveal-scale">
                    <span class="text-xs font-black text-brandOrange uppercase tracking-widest">Keunggulan Platform</span>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Fitur Kelas Dunia untuk Kemitraan Anda
                    </h2>
                    <p class="text-base text-slate-500 leading-relaxed">
                        Kami menyederhanakan pelacakan konversi afiliasi dengan teknologi mutakhir dan arsitektur pengamanan lapis kedua yang andal.
                    </p>
                </div>

                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Feature Card 1 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-75">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Pelacakan Klik Real-Time</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Setiap klik tautan referral langsung terekam oleh JavaScript SDK dalam waktu milidetik. Akurat, responsif, dan bebas manipulasi.
                        </p>
                    </article>

                    {{-- Feature Card 2 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-150">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Keamanan HMAC & Enkripsi</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Data komisi diamankan dengan tanda tangan kriptografis HMAC-SHA256 untuk mendeteksi manipulasi database. Detail bank afiliator tersimpan terenkripsi penuh.
                        </p>
                    </article>

                    {{-- Feature Card 3 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-200">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Payout Manual Terpantau</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Proses pencairan dana manual dikelola secara transparan dengan wajib melampirkan bukti transfer dan didukung notifikasi email otomatis.
                        </p>
                    </article>

                    {{-- Feature Card 4 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-75">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Workspace Terpisah</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Panel khusus bagi Vendor untuk memantau traffic & payout, serta dashboard visual bagi Afiliator untuk memonitor komisi & statistik klik mereka sendiri.
                        </p>
                    </article>

                    {{-- Feature Card 5 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-150">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Integrasi Webhook Instan</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Integrasikan transaksi platform e-commerce Anda ke API webhook conversions kami hanya menggunakan *HTTP POST request* dan verifikasi API key yang aman.
                        </p>
                    </article>

                    {{-- Feature Card 6 --}}
                    <article class="group bg-white rounded-3xl p-8 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] hover:-translate-y-2 hover:scale-[1.02] hover:border-orange-200/50 hover:shadow-[0_20px_50px_rgba(255,107,0,0.06)] duration-500 transition-all reveal reveal-scale delay-200">
                        <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange mb-6 border border-orange-100/50 group-hover:scale-110 group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-brandOrange transition-colors duration-300">Log Audit Komprehensif</h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Setiap tindakan kritis (pencairan dana, perubahan rekening, pembaruan profil) tercatat lengkap ke log audit dengan deteksi IP address & user agent.
                        </p>
                    </article>
                </div>
            </section>

            {{-- 6. HOW IT WORKS SECTION (SPLIT FOR AFFILIATE & VENDOR) --}}
            <section id="cara-kerja" class="py-20 border-t border-slate-100 reveal" aria-label="Cara Kerja">
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16 reveal reveal-scale">
                    <span class="text-xs font-black text-brandOrange uppercase tracking-widest">Sederhana & Terarah</span>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Bagaimana Alur Kerja Sistem?
                    </h2>
                    <p class="text-base text-slate-500 leading-relaxed">
                        Kami merancang alur kerja yang mudah dipahami baik untuk Anda yang ingin mempromosikan produk maupun yang ingin melacak omzet penjualan.
                    </p>
                </div>

                <div class="grid gap-12 lg:grid-cols-2">
                    
                    {{-- Affiliate Steps Column --}}
                    <div class="space-y-8 rounded-3xl bg-white border border-slate-100 p-8 shadow-sm reveal reveal-left">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50 text-brandOrange font-bold text-xs"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            <h3 class="text-lg font-extrabold text-slate-800">Alur Kerja Afiliator</h3>
                        </div>
                        
                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Daftar Akun Afiliator</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Lakukan pendaftaran gratis, lengkapi data profil, dan masukkan informasi rekening bank Anda secara aman (data terenkripsi).</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Gabung Program & Ambil Link</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Pilih program komisi dari vendor yang tersedia di dashboard, setujui ketentuan, lalu salin kode referral atau link kemitraan Anda.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Promosikan Link Afiliasi</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Sebarkan link referral Anda melalui media sosial, blog, website pribadi, atau saluran kampanye digital untuk menarik calon pembeli.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Tarik Pendapatan Komisi</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Setiap penjualan yang disetujui akan menambah saldo Anda. Ajukan penarikan saldo dan tunggu proses transfer manual dari vendor selesai.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Vendor Steps Column --}}
                    <div class="space-y-8 rounded-3xl bg-white border border-slate-100 p-8 shadow-sm reveal reveal-right delay-150">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-50 text-brandOrange font-bold text-xs"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
                            <h3 class="text-lg font-extrabold text-slate-800">Alur Kerja Vendor</h3>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">1</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Daftar Sebagai Merchant/Vendor</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Daftarkan bisnis atau toko online Anda di platform, konfigurasikan tipe komisi default (flat atau persentase nilai penjualan).</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">2</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Integrasikan Tracker SDK</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Salin snippet Javascript `tracker.js` dari menu integrasi ke website toko Anda, lalu aktifkan trigger webhook saat terjadi konversi order.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">3</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Rekrut Afiliator & Pantau Klik</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Afiliator akan mulai memasarkan produk Anda secara mandiri. Pantau performa klik referral dan nominal penjualan langsung dari dashboard cockpit.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start group">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-black text-brandOrange text-sm group-hover:bg-brandOrange group-hover:text-white transition-all duration-300">4</div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">Konfirmasi Pembayaran Payout</h4>
                                <p class="text-xs text-slate-450 leading-relaxed mt-1">Tinjau antrean permintaan pencairan komisi dari afiliator, lakukan transfer manual ke rekening tujuan mereka, lalu upload bukti transfer untuk menyelesaikan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 7. INTERACTIVE FAQ SECTION --}}
            <section id="faq" class="py-20 border-t border-slate-100 reveal" aria-label="Tanya Jawab">
                <div class="text-center max-w-3xl mx-auto space-y-4 mb-16 reveal reveal-scale">
                    <span class="text-xs font-black text-brandOrange uppercase tracking-widest">Ada Pertanyaan?</span>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                        Tanya Jawab Seputar Affiliatekan
                    </h2>
                    <p class="text-base text-slate-500 leading-relaxed">
                        Kami merangkum beberapa informasi penting agar Anda semakin yakin untuk bergabung.
                    </p>
                </div>

                {{-- Alpine.js Interactive Accordion --}}
                <div x-data="{ active: null }" class="max-w-3xl mx-auto space-y-4">
                    
                    {{-- FAQ 1 --}}
                    <div class="rounded-2xl border border-slate-150 bg-white overflow-hidden transition-all duration-300 hover:border-orange-200/50 hover:shadow-[0_12px_25px_rgba(255,107,0,0.02)] reveal reveal-scale delay-75">
                        <button 
                            type="button"
                            @click="active = (active === 1 ? null : 1)"
                            class="flex w-full items-center justify-between p-6 text-left font-bold text-slate-800 hover:text-brandOrange focus:outline-none transition-colors duration-300"
                            id="faq-btn-1"
                        >
                            <span>Bagaimana cara kerja perhitungan komisi?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-300" :class="active === 1 ? 'rotate-180 text-brandOrange' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div 
                            x-show="active === 1" 
                            x-collapse
                            x-cloak
                            class="px-6 pb-6 text-sm text-slate-500 leading-relaxed"
                        >
                            Komisi dihitung berdasarkan konfigurasi program dari vendor yang Anda ikuti. Vendor dapat menetapkan tipe komisi bernilai tetap (flat) seperti Rp 15.000 per pesanan, atau persentase (percentage) dari total nilai penjualan barang yang Anda pasarkan.
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="rounded-2xl border border-slate-150 bg-white overflow-hidden transition-all duration-300 hover:border-orange-200/50 hover:shadow-[0_12px_25px_rgba(255,107,0,0.02)] reveal reveal-scale delay-150">
                        <button 
                            type="button"
                            @click="active = (active === 2 ? null : 2)"
                            class="flex w-full items-center justify-between p-6 text-left font-bold text-slate-800 hover:text-brandOrange focus:outline-none transition-colors duration-300"
                            id="faq-btn-2"
                        >
                            <span>Apakah data informasi rekening bank saya aman?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-300" :class="active === 2 ? 'rotate-180 text-brandOrange' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div 
                            x-show="active === 2" 
                            x-collapse
                            x-cloak
                            class="px-6 pb-6 text-sm text-slate-500 leading-relaxed"
                        >
                            Sangat aman. Platform kami menggunakan sistem pengamanan *Encryption-at-Rest*. Detail nomor rekening dan nama bank Anda dienkripsi secara penuh secara otomatis menggunakan algoritma kriptografi AES-256-CBC dari framework Laravel sebelum disimpan ke dalam database utama.
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="rounded-2xl border border-slate-150 bg-white overflow-hidden transition-all duration-300 hover:border-orange-200/50 hover:shadow-[0_12px_25px_rgba(255,107,0,0.02)] reveal reveal-scale delay-200">
                        <button 
                            type="button"
                            @click="active = (active === 3 ? null : 3)"
                            class="flex w-full items-center justify-between p-6 text-left font-bold text-slate-800 hover:text-brandOrange focus:outline-none transition-colors duration-300"
                            id="faq-btn-3"
                        >
                            <span>Bagaimana cara mengintegrasikan tracking ke website toko saya?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-300" :class="active === 3 ? 'rotate-180 text-brandOrange' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div 
                            x-show="active === 3" 
                            x-collapse
                            x-cloak
                            class="px-6 pb-6 text-sm text-slate-500 leading-relaxed"
                        >
                            Bagi Vendor, Anda cukup menyalin file script pelacakan `tracker.js` yang ada di halaman integrasi ke website Anda (sebelum tag `</body>`). Kemudian buat request HTTP POST webhook dari website Anda ke endpoint API conversions kami pada saat pembeli berhasil menyelesaikan pesanan/checkout.
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="rounded-2xl border border-slate-150 bg-white overflow-hidden transition-all duration-300 hover:border-orange-200/50 hover:shadow-[0_12px_25px_rgba(255,107,0,0.02)] reveal reveal-scale delay-250">
                        <button 
                            type="button"
                            @click="active = (active === 4 ? null : 4)"
                            class="flex w-full items-center justify-between p-6 text-left font-bold text-slate-800 hover:text-brandOrange focus:outline-none transition-colors duration-300"
                            id="faq-btn-4"
                        >
                            <span>Bagaimana kolaborasi ini memperluas jangkauan dan penjualan?</span>
                            <svg class="h-5 w-5 transform transition-transform duration-300" :class="active === 4 ? 'rotate-180 text-brandOrange' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div 
                            x-show="active === 4" 
                            x-collapse
                            x-cloak
                            class="px-6 pb-6 text-sm text-slate-500 leading-relaxed"
                        >
                            Vendor membagikan program komisi berkinerja tinggi, lalu afiliator terdaftar mempromosikan produk Vendor melalui link referral khusus mereka. Sinergi ini membuka akses bagi Vendor ke pasar/audiens baru yang dimiliki afiliator, sementara afiliator mendapat kesempatan menghasilkan komisi secara terpercaya tanpa perlu membuat produk sendiri.
                        </div>
                    </div>
                </div>
            </section>

            {{-- 8. CTA SECTION --}}
            <section class="mt-20 rounded-[36px] bg-gradient-to-tr from-brandOrange to-orange-500 p-8 sm:p-12 md:p-16 text-white text-center shadow-[0_24px_50px_rgba(255,107,0,0.15)] relative overflow-hidden reveal reveal-scale" aria-label="Ajakan Bergabung">
                {{-- Decorative animated circles --}}
                <div class="absolute -top-10 -left-10 h-44 w-44 rounded-full bg-white/5 pointer-events-none animate-float"></div>
                <div class="absolute -bottom-12 -right-12 h-64 w-64 rounded-full bg-white/5 pointer-events-none animate-float-delayed"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                    <h2 class="text-3xl font-extrabold sm:text-4xl lg:text-5xl leading-tight">
                        Siap Melipatgandakan Omzet Bisnis & Pendapatan Anda?
                    </h2>
                    <p class="text-sm sm:text-base text-white/80 leading-relaxed">
                        Bergabunglah hari ini. Dapatkan pelaporan pelacakan afiliasi yang tepercaya, transparan, dan terenkripsi untuk pertumbuhan bisnis masa depan.
                    </p>
                    <div class="pt-4 flex flex-col sm:flex-row justify-center gap-4">
                        <a 
                            href="{{ route('register') }}"
                            id="btn-cta-bottom"
                            class="inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-base font-extrabold text-brandOrange shadow-lg hover:bg-orange-50 hover:shadow-orange-600/10 hover:scale-[1.03] transition duration-300 active:scale-[0.98]"
                        >
                            Mulai Registrasi Sekarang
                        </a>
                    </div>
                </div>
            </section>

        </main>

        {{-- 9. FOOTER --}}
        <footer class="bg-white border-t border-slate-150 mt-20 relative z-10" aria-label="Informasi Hak Cipta & Link Kemitraan">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                <div class="grid gap-8 md:grid-cols-4 border-b border-slate-100 pb-12 mb-12">
                    <div class="space-y-4 col-span-1 md:col-span-2">
                        <img src="{{ asset('images/Logo - Affilaitekan.svg') }}" alt="Logo Footer Affiliatekan" class="h-8 w-auto" loading="lazy">
                        <p class="text-xs sm:text-sm text-slate-450 leading-relaxed max-w-sm">
                            Platform SaaS internal pelacak konversi, afiliasi link, klik referral, dan antrean payout manual bagi vendor dan afiliator terpercaya.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest mb-4">Navigasi</h4>
                        <div class="flex flex-col gap-3 text-xs sm:text-sm text-slate-500">
                            <a href="#solusi" class="hover:text-brandOrange transition">Ekosistem Kemitraan</a>
                            <a href="#fitur" class="hover:text-brandOrange transition">Fitur Utama</a>
                            <a href="#cara-kerja" class="hover:text-brandOrange transition">Alur Kerja Platform</a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest mb-4">Halaman Hukum</h4>
                        <div class="flex flex-col gap-3 text-xs sm:text-sm text-slate-500">
                            <a href="#" class="hover:text-brandOrange transition">Ketentuan Penggunaan</a>
                            <a href="#" class="hover:text-brandOrange transition">Kebijakan Privasi</a>
                            <a href="#" class="hover:text-brandOrange transition">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Hak Cipta Dilindungi Undang-Undang.</p>
                    <p>SaaS Platform B2B Internal - Dilindungi Keamanan Kriptografi Lapis Kedua.</p>
                </div>
            </div>
        </footer>

        {{-- Scroll Reveal Observer --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const revealElements = document.querySelectorAll(".reveal");
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("revealed");
                            // Once revealed, we stop observing to keep scroll performance optimal
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.05,
                    rootMargin: "0px 0px -40px 0px"
                });
                
                revealElements.forEach(element => {
                    observer.observe(element);
                });
            });
        </script>
    </div>
</x-layouts.marketing>

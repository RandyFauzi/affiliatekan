<x-layouts.admin page-title="Affiliatekan Super Admin Dashboard">
    <section
        class="rounded-3xl px-8 py-8 text-white shadow-[0_18px_45px_rgba(255,107,0,0.24)]"
        style="background: linear-gradient(90deg, #FF6B00 0%, #FB923C 100%);"
    >
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-white/70">Platform Overview</p>
                <h2 class="mt-3 text-3xl font-extrabold tracking-tight">Selamat Datang kembali, {{ auth()->user()->name }}!</h2>
                <p class="mt-3 text-sm leading-7 text-white/85">
                    Mari pantau kesehatan platform Affiliatekan hari ini. Semua metrik global
                    vendor, afiliator, conversion, dan komisi ada di satu pusat kendali.
                </p>
            </div>

            <div class="rounded-2xl bg-white/15 px-5 py-4 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-white/70">Super Admin</p>
                <p class="mt-2 text-lg font-bold text-white">{{ auth()->user()->name }}</p>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mt-6">
        <article class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M4 20V8l8-4 8 4v12"/><path d="M9 20v-5h6v5"/>
                    </svg>
                </span>
                <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-500">live</span>
            </div>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Active Vendors</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-800">{{ number_format($adminDashboardMetrics['active_vendor_total']) }}</p>
            <p class="mt-3 text-sm text-slate-500">Total akun vendor aktif yang sedang memakai platform.</p>
        </article>

        <article class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-500">growth</span>
            </div>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Active Affiliates</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-800">{{ number_format($adminDashboardMetrics['active_affiliate_total']) }}</p>
            <p class="mt-3 text-sm text-slate-500">Jumlah afiliator aktif yang terdaftar di seluruh sistem.</p>
        </article>

        <article class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <rect x="4" y="5" width="16" height="14" rx="3"/><path d="M8 10h8"/><path d="M8 14h5"/>
                    </svg>
                </span>
                <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-500">api</span>
            </div>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">API Conversions</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-800">{{ number_format($adminDashboardMetrics['conversion_total']) }}</p>
            <p class="mt-3 text-sm text-slate-500">Semua conversion yang tercatat lewat flow integrasi sistem.</p>
        </article>

        <article class="bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path d="M5 12h14"/><path d="M12 5v14"/>
                    </svg>
                </span>
                <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-emerald-500">gross</span>
            </div>
            <p class="mt-5 text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Gross Commission</p>
            <p class="mt-3 text-4xl font-extrabold text-slate-800">Rp {{ number_format($adminDashboardMetrics['gross_commission_total'], 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-slate-500">Total komisi global dari semua conversion approved dan paid.</p>
        </article>
    </div>
</x-layouts.admin>

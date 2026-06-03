<x-layouts.vendor-dashboard page-title="Vendor Dashboard - Affiliatekan">
    <div class="flex flex-col xl:flex-row gap-8 items-start">
        
        {{-- Column 2: Middle Content (Summary Cockpit) --}}
        <div class="flex-1 space-y-8 min-w-0">
            {{-- 2x2 Metric Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- Metric 1: Total Clicks --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M15 3h6v6"/><path d="M10 14L21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                        </span>
                        <span class="rounded-xl bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider">Clicks</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Total Klik</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">{{ number_format($vendorDashboardMetrics['total_clicks']) }}</p>
                </article>

                {{-- Metric 2: Conversions --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-550 border border-emerald-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><rect x="4" y="5" width="16" height="14" rx="3"/><path d="M8 10h8"/><path d="M8 14h5"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-550 uppercase tracking-wider">Sales</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Konversi</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">{{ number_format($vendorDashboardMetrics['total_conversions']) }}</p>
                </article>

                {{-- Metric 3: Pending Payout / Debt --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </span>
                        <span class="rounded-xl bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600 uppercase tracking-wider">Debt</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Tagihan Tertunda</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">Rp {{ number_format($vendorDashboardMetrics['total_debt'], 0, ',', '.') }}</p>
                </article>

                {{-- Metric 4: Paid Payout --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><path d="M5 12l4 4L19 6"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Paid</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Sudah Dibayarkan</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">Rp {{ number_format($vendorDashboardMetrics['total_paid'], 0, ',', '.') }}</p>
                </article>
            </div>

            {{-- Table: 5 Transaksi Terakhir --}}
            <section class="bg-white rounded-[32px] border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.01)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">5 Transaksi Terakhir</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan transaksi referral teranyar di platform Anda.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">Afiliator</th>
                                <th class="px-6 py-4">Total Penjualan</th>
                                <th class="px-6 py-4">Komisi</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($recentConversions as $conversion)
                                <tr class="transition-colors duration-300 hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-bold text-brandOrange border border-orange-100/50">
                                                {{ strtoupper(substr($conversion->affiliate?->user?->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 leading-none">{{ $conversion->affiliate?->user?->name ?? 'Afiliator Terhapus' }}</p>
                                                <p class="text-[10px] text-slate-450 mt-1 font-semibold">{{ $conversion->affiliate?->referral_code ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                        Rp {{ number_format($conversion->sale_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-brandOrange">
                                        Rp {{ number_format($conversion->commission_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($conversion->status === 'approved')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600 border border-emerald-100/55">
                                                Approved
                                            </span>
                                        @elseif ($conversion->status === 'pending')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600 border border-amber-100/55">
                                                Pending
                                            </span>
                                        @elseif ($conversion->status === 'paid')
                                            <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-600 border border-blue-100/55">
                                                Paid
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-600 border border-rose-100/55">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                        {{ $conversion->created_at ? $conversion->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                        Belum ada transaksi referral yang teratribusikan ke program Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        {{-- Column 3: Right Column (Insights & Actions) --}}
        <aside class="w-80 shrink-0 space-y-6 hidden xl:block">
            {{-- Giant Hero Action Card --}}
            <div class="rounded-[32px] p-6 text-white bg-gradient-to-tr from-brandOrange to-orange-400 shadow-[0_18px_45px_rgba(255,107,0,0.22)]">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 mb-5 border border-white/10">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold tracking-tight">Tagihan Payout</h3>
                <p class="text-sm text-white/80 mt-2 leading-relaxed">
                    Periksa antrean permintaan transfer komisi afiliasi yang diajukan oleh afiliator Anda.
                </p>
                
                <a
                    href="{{ route('vendor.payouts.index') }}"
                    class="mt-6 w-full inline-flex items-center justify-center rounded-2xl bg-white px-5 py-4 text-base font-extrabold text-brandOrange shadow-sm hover:bg-orange-50 transition-all duration-300"
                >
                    Proses Tagihan
                </a>
            </div>
        </aside>

    </div>
</x-layouts.vendor-dashboard>

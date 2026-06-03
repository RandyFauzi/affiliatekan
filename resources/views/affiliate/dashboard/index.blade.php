<x-layouts.affiliate-dashboard page-title="Affiliate Dashboard - Affiliatekan">
    <div x-data="{ payoutRequestModalOpen: {{ request()->has('payout') ? 'true' : 'false' }} }" class="flex flex-col xl:flex-row gap-8 items-start">
        
        {{-- Column 2: Middle Content (Summary Cockpit) --}}
        <div class="flex-1 space-y-8 min-w-0">
            {{-- Status Notification --}}
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-250 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-600 shadow-sm flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

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
                    <p class="mt-1 text-3xl font-black text-slate-800">{{ number_format($affiliateAnalyticsSummary['total_clicks']) }}</p>
                </article>

                {{-- Metric 2: Conversions --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 border border-emerald-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5"><rect x="4" y="5" width="16" height="14" rx="3"/><path d="M8 10h8"/><path d="M8 14h5"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-550 uppercase tracking-wider">Sales</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Konversi</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">{{ number_format($affiliateAnalyticsSummary['total_conversions']) }}</p>
                </article>

                {{-- Metric 3: Available Balance --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Ready</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Saldo Tersedia</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">Rp {{ number_format($affiliateAnalyticsSummary['available_balance'], 0, ',', '.') }}</p>
                </article>

                {{-- Metric 4: Pending Balance --}}
                <article class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/50">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                        </span>
                        <span class="rounded-xl bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600 uppercase tracking-wider">Hold</span>
                    </div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Saldo Menunggu</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">Rp {{ number_format($affiliateAnalyticsSummary['pending_balance'], 0, ',', '.') }}</p>
                </article>
            </div>

            {{-- Table: 5 Transaksi Terakhir --}}
            <section class="bg-white rounded-[32px] border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.01)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">5 Transaksi Terakhir</h2>
                        <p class="text-xs text-slate-450 mt-0.5">Ringkasan konversi referral teranyar Anda.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 border-b border-slate-100">
                                <th class="px-6 py-4">Vendor</th>
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
                                                {{ strtoupper(substr($conversion->vendor?->company_name ?? 'V', 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-800">{{ $conversion->vendor?->company_name ?? 'Vendor Terhapus' }}</span>
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
                                        Belum ada data konversi atau transaksi afiliasi.
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
            {{-- Giant Hero Action Card (Redesigned Orange Premium Widget) --}}
            <div class="rounded-[32px] p-8 text-white bg-brandOrange shadow-[0_18px_45px_rgba(255,107,0,0.22)] transition-all duration-300">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 mb-6 border border-white/10">
                    <!-- Overlapping Payout Cards Icon -->
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="6" width="13" height="10" rx="2" class="opacity-60" />
                        <rect x="8" y="10" width="13" height="10" rx="2" />
                        <circle cx="14.5" cy="15" r="1" fill="currentColor" />
                    </svg>
                </div>
                
                <h3 class="text-xl font-extrabold tracking-tight">Tagihan Payout</h3>
                <p class="text-[13px] text-white/90 mt-2.5 leading-relaxed font-medium">
                    Periksa antrean permintaan transfer komisi afiliasi yang Anda ajukan dan kelola pendapatan harian Anda.
                </p>

                {{-- Earnings Calculations & Breakdown --}}
                <div class="mt-6 border-t border-white/15 pt-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-white/60"></span>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Pendapatan Hari Ini</p>
                        </div>
                        <p class="text-base font-black text-white">Rp {{ number_format($affiliateAnalyticsSummary['todays_earnings'], 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-white/60"></span>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                        </div>
                        <p class="text-base font-black text-white">Rp {{ number_format($affiliateAnalyticsSummary['this_months_earnings'], 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-white/60"></span>
                            <p class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Komisi Siap Cair</p>
                        </div>
                        <p class="text-base font-black text-white">Rp {{ number_format($affiliateAnalyticsSummary['eligible_payout_total_amount'], 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <button
                    type="button"
                    @click="payoutRequestModalOpen = true"
                    @disabled($affiliateAnalyticsSummary['eligible_payout_total_amount'] <= 0)
                    class="mt-6 w-full inline-flex items-center justify-center rounded-2xl bg-white px-5 py-4 text-sm font-extrabold text-brandOrange shadow-sm hover:bg-orange-50 active:scale-[0.98] transition-all duration-300 disabled:bg-white/50 disabled:text-orange-350 disabled:cursor-not-allowed"
                >
                    Proses Tagihan
                </button>
            </div>

            {{-- Bottom: Dynamic & Reactive Calendar UI Widget --}}
            <div 
                x-data="{ 
                    selectedDay: {{ now()->day }}, 
                    today: {{ now()->day }}, 
                    earnings: @js($dailyEarnings)
                }" 
                class="rounded-[32px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition hover:shadow-md"
            >
                <div class="flex items-center justify-between mb-5 border-b border-slate-50 pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Kalender Workspace</h4>
                    <span class="text-xs font-bold text-brandOrange">{{ $currentMonthYear }}</span>
                </div>
                
                <div class="grid grid-cols-7 gap-y-3.5 text-center text-[10px] font-bold text-slate-400">
                    <div>S</div><div>S</div><div>R</div><div>K</div><div>J</div><div>S</div><div>M</div>
                    
                    {{-- Start Offset (Blank Days before the 1st of the month) --}}
                    @for ($i = 0; $i < $startOffset; $i++)
                        <div class="h-6 w-full"></div>
                    @endfor

                    {{-- Days of the Month --}}
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        <div class="flex items-center justify-center h-6 w-full">
                            <button
                                type="button"
                                @click="selectedDay = {{ $d }}"
                                class="flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold transition duration-300 relative focus:outline-none"
                                :class="{
                                    'bg-brandOrange text-white shadow-sm font-extrabold scale-110': selectedDay === {{ $d }},
                                    'border border-orange-200 text-brandOrange font-bold': selectedDay !== {{ $d }} && today === {{ $d }},
                                    'text-slate-650 font-semibold hover:bg-slate-100': selectedDay !== {{ $d }} && today !== {{ $d }}
                                }"
                            >
                                {{ $d }}
                                {{-- Little dot under date indicating earnings are available --}}
                                @if (($dailyEarnings[$d] ?? 0) > 0)
                                    <span 
                                        class="absolute bottom-0.5 h-1 w-1 rounded-full"
                                        :class="selectedDay === {{ $d }} ? 'bg-white' : 'bg-brandOrange'"
                                    ></span>
                                @endif
                            </button>
                        </div>
                    @endfor
                </div>

                {{-- Reactive Daily Earnings Info Display Panel --}}
                <div class="mt-6 border-t border-slate-100 pt-5">
                    <div class="rounded-2xl bg-orange-50/50 p-4 border border-orange-100/35">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400">Pendapatan Harian</p>
                        <div class="mt-2.5 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-orange-100 text-brandOrange">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                </span>
                                <p class="text-xs font-bold text-slate-700">
                                    Tgl <span class="text-brandOrange font-black" x-text="selectedDay"></span> {{ now()->translatedFormat('F Y') }}
                                </p>
                            </div>
                            <p class="text-sm font-black text-slate-800">
                                Rp <span x-text="new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(earnings[selectedDay] || 0)"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Alpine.js Modal for Tarik Komisi (Payout Confirmation) --}}
        <div
            x-cloak
            x-show="payoutRequestModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4 py-8 backdrop-blur-sm"
        >
            <div
                @click.away="payoutRequestModalOpen = false"
                x-show="payoutRequestModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="w-full max-w-xl rounded-[32px] border border-slate-100 bg-white p-6 shadow-[0_24px_50px_rgba(0,0,0,0.1)]"
            >
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <span class="inline-flex items-center justify-center rounded-xl bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider mb-2">
                            Pencairan Saldo
                        </span>
                        <h3 class="text-xl font-extrabold tracking-tight text-slate-800">Konfirmasi Penarikan Komisi</h3>
                    </div>
                    <button
                        type="button"
                        @click="payoutRequestModalOpen = false"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 hover:text-slate-700 transition"
                    >
                        <span class="text-xl font-semibold">&times;</span>
                    </button>
                </div>

                <div class="mt-5 grid gap-4 grid-cols-1 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Nominal</p>
                        <p class="mt-1.5 text-lg font-black text-slate-800">Rp {{ number_format($affiliateAnalyticsSummary['eligible_payout_total_amount'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tujuan Payout</p>
                        <p class="mt-1.5 text-sm font-bold text-slate-800">{{ $authenticatedAffiliate->bank_name ?: 'Belum diatur' }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $authenticatedAffiliate->bank_account_number ?: 'Atur rekening terlebih dahulu' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('affiliate.payout-requests.store') }}" class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                    @csrf
                    
                    <button
                        type="button"
                        @click="payoutRequestModalOpen = false"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition duration-300"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        @disabled(!$authenticatedAffiliate->bank_name)
                        class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-sm font-bold text-white shadow-lg transition duration-300 hover:bg-orange-600 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none"
                    >
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layouts.affiliate-dashboard>

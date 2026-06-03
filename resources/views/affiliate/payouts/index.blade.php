<x-layouts.affiliate-dashboard page-title="Payouts & Keuangan - Affiliatekan">
    <div x-data="{ payoutRequestModalOpen: false }" class="flex flex-col xl:flex-row gap-8 items-start">
        
        {{-- Left/Middle Area: Financial Metrics, Bank Settings & Payout History --}}
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

            {{-- 4 Grid Balances Metrics --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Metric 1: Available --}}
                <article class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/30">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-500 uppercase tracking-wider">Ready</span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Saldo Tersedia</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-800">Rp {{ number_format($payoutSummary['available_balance'], 0, ',', '.') }}</p>
                </article>

                {{-- Metric 2: Pending --}}
                <article class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/30">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                        </span>
                        <span class="rounded-xl bg-amber-50 px-2 py-0.5 text-[9px] font-bold text-amber-600 uppercase tracking-wider">Hold</span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Saldo Menunggu</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-800">Rp {{ number_format($payoutSummary['pending_balance'], 0, ',', '.') }}</p>
                </article>

                {{-- Metric 3: Processing --}}
                <article class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/30">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </span>
                        <span class="rounded-xl bg-blue-50 px-2 py-0.5 text-[9px] font-bold text-blue-500 uppercase tracking-wider">Process</span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Sedang Diproses</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-800">Rp {{ number_format($payoutSummary['processing_balance'], 0, ',', '.') }}</p>
                </article>

                {{-- Metric 4: Paid --}}
                <article class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition hover:shadow-md">
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange border border-orange-100/30">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" class="h-5 w-5"><path d="M5 12l4 4L19 6"/></svg>
                        </span>
                        <span class="rounded-xl bg-emerald-50 px-2 py-0.5 text-[9px] font-bold text-emerald-500 uppercase tracking-wider">Paid</span>
                    </div>
                    <p class="mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Sudah Dicairkan</p>
                    <p class="mt-1 text-xl font-extrabold text-slate-800">Rp {{ number_format($payoutSummary['paid_balance'], 0, ',', '.') }}</p>
                </article>
            </div>

            {{-- Table: Payout History --}}
            <section class="bg-white rounded-[32px] border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.01)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan Payout</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar lengkap penarikan komisi yang Anda ajukan ke vendor partner.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 border-b border-slate-100 bg-slate-50/50">
                                <th class="px-6 py-4">Vendor</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Tanggal Pengajuan</th>
                                <th class="px-6 py-4">Tanggal Cair</th>
                                <th class="px-6 py-4 text-right">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($payouts as $payout)
                                <tr class="transition duration-200 hover:bg-slate-50/30">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-bold text-brandOrange border border-orange-100/55">
                                                {{ strtoupper(substr($payout->vendor?->company_name ?? 'V', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800">{{ $payout->vendor?->company_name ?? 'Vendor Terhapus' }}</p>
                                                <a href="{{ $payout->vendor?->website_url }}" target="_blank" class="text-[10px] text-slate-400 hover:text-brandOrange transition">
                                                    {{ $payout->vendor?->website_url }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($payout->status === 'requested')
                                            <span class="inline-flex rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-brandOrange border border-orange-100/55">
                                                Requested
                                             </span>
                                        @elseif ($payout->status === 'processing')
                                            <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600 border border-amber-100/55">
                                                Processing
                                             </span>
                                        @elseif ($payout->status === 'paid')
                                            <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600 border border-emerald-100/55">
                                                Paid
                                             </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-600 border border-rose-100/55">
                                                Rejected
                                             </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-400">
                                        {{ $payout->created_at ? $payout->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-450">
                                        {{ $payout->status === 'paid' && $payout->updated_at ? $payout->updated_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($payout->proof_of_transfer_path)
                                            <a 
                                                href="{{ Storage::url($payout->proof_of_transfer_path) }}" 
                                                target="_blank" 
                                                class="inline-flex items-center gap-1.5 rounded-xl border border-orange-100 bg-[#fff8f1] px-3.5 py-2 text-xs font-bold text-brandOrange transition duration-300 hover:-translate-y-0.5 hover:bg-orange-50 hover:shadow-sm"
                                            >
                                                <svg class="h-3.5 w-3.5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>Lihat Bukti</span>
                                            </a>
                                        @else
                                            <span class="text-xs font-medium text-slate-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                        Belum ada riwayat pengajuan penarikan dana.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Card: Update Bank Account Form --}}
            <section class="rounded-[32px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(0,0,0,0.015)]">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-800">Pengaturan Rekening Bank</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Atur rekening bank tujuan Anda untuk memperlancar transfer pencairan oleh vendor.</p>
                </div>

                <form method="POST" action="{{ route('affiliate.bank-account.update') }}" class="mt-6 space-y-5" data-confirm="Simpan pengaturan rekening bank baru?">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label for="bank_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Bank / E-Wallet</label>
                            <input 
                                id="bank_name" 
                                name="bank_name" 
                                type="text" 
                                value="{{ old('bank_name', $authenticatedAffiliate->bank_name) }}" 
                                placeholder="Contoh: BCA / Mandiri / GoPay"
                                required
                                class="mt-2.5 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                style="padding: 1.1rem 1.5rem;"
                            >
                        </div>
                        <div>
                            <label for="bank_account_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Nomor Rekening</label>
                            <input 
                                id="bank_account_number" 
                                name="bank_account_number" 
                                type="text" 
                                value="{{ old('bank_account_number', $authenticatedAffiliate->bank_account_number) }}" 
                                placeholder="Contoh: 7012345678"
                                required
                                class="mt-2.5 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                style="padding: 1.1rem 1.5rem;"
                            >
                        </div>
                        <div>
                            <label for="bank_account_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Atas Nama Pemilik</label>
                            <input 
                                id="bank_account_name" 
                                name="bank_account_name" 
                                type="text" 
                                value="{{ old('bank_account_name', $authenticatedAffiliate->bank_account_name) }}" 
                                placeholder="Nama sesuai buku tabungan"
                                required
                                class="mt-2.5 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                style="padding: 1.1rem 1.5rem;"
                            >
                        </div>
                    </div>

                    <div class="flex justify-start pt-4">
                        <button 
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-8 py-4.5 text-sm font-extrabold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] hover:bg-orange-600 transition duration-300 hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100 min-w-[240px]"
                            style="padding: 1.1rem 2rem;"
                        >
                            Simpan Rekening Bank
                        </button>
                    </div>
                </form>
            </section>
        </div>

        {{-- Right Column: tarikomisi CTA card and bank info preview --}}
        <aside class="w-80 shrink-0 space-y-6 hidden xl:block">
            {{-- Tarik Komisi CTA Card --}}
            <div class="rounded-[32px] p-6 text-white bg-gradient-to-tr from-brandOrange to-orange-400 shadow-[0_18px_45px_rgba(255,107,0,0.22)]">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20 mb-5 border border-white/10">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold tracking-tight">Tarik Komisi</h3>
                <p class="text-sm text-white/80 mt-2 leading-relaxed">
                    Ajukan pencairan saldo komisi tersedia ke rekening bank terdaftar secara instan.
                </p>
                
                <button
                    type="button"
                    @click="payoutRequestModalOpen = true"
                    @disabled($payoutSummary['eligible_payout_total_amount'] <= 0)
                    class="mt-6 w-full inline-flex items-center justify-center rounded-2xl bg-white px-5 py-4 text-base font-extrabold text-brandOrange shadow-sm hover:bg-orange-50 transition-all duration-300 disabled:bg-white/50 disabled:text-orange-350 disabled:cursor-not-allowed"
                >
                    Tarik Saldo Instan
                </button>
            </div>

            {{-- Bank account preview card --}}
            <div class="rounded-[32px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgba(0,0,0,0.01)]">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 border-b border-slate-50 pb-2">Rekening Aktif</h4>
                @if ($authenticatedAffiliate->bank_name)
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-extrabold text-brandOrange">
                                {{ strtoupper(substr($authenticatedAffiliate->bank_name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $authenticatedAffiliate->bank_name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Atas nama: {{ $authenticatedAffiliate->bank_account_name }}</p>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4 font-mono text-sm font-bold text-slate-700 text-center">
                            {{ $authenticatedAffiliate->bank_account_number }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-50 text-amber-500 mx-auto mb-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-700">Rekening Belum Diatur</p>
                        <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Silakan isi formulir rekening bank untuk dapat mencairkan dana.</p>
                    </div>
                @endif
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
                        <p class="mt-1.5 text-lg font-black text-slate-800">Rp {{ number_format($payoutSummary['eligible_payout_total_amount'], 0, ',', '.') }}</p>
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
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-extrabold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition duration-300"
                        style="padding: 0.9rem 1.8rem;"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        @disabled(!$authenticatedAffiliate->bank_name)
                        class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-7 py-3.5 text-sm font-extrabold text-white shadow-lg transition duration-300 hover:bg-orange-600 hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none"
                        style="padding: 0.9rem 1.8rem;"
                    >
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layouts.affiliate-dashboard>

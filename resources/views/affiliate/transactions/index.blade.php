<x-layouts.affiliate-dashboard page-title="Riwayat Transaksi - Affiliatekan">
    <div class="space-y-8">
        {{-- Header & Total Stats Summary --}}
        <section class="rounded-[28px] border border-orange-100 bg-white/95 px-6 py-6 shadow-[0_18px_50px_rgba(255,122,0,0.04)]">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#ff7a00]/70">Referral Transactions</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-800">Semua Transaksi Referral Anda</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Berikut adalah riwayat lengkap seluruh konversi penjualan yang Anda hasilkan melalui tautan promosi afiliasi aktif Anda.
                    </p>
                </div>

                <div class="grid gap-3 grid-cols-2">
                    <div class="rounded-2xl border border-orange-100 bg-[#fff8f1] px-5 py-4 shadow-sm min-w-[160px]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Total Transaksi</p>
                        <p class="mt-2 text-2xl font-black text-slate-800 font-mono">{{ $transactions->total() }}</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-[#fff8f1] px-5 py-4 shadow-sm min-w-[160px]">
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Total Komisi</p>
                        <p class="mt-2 text-lg font-black text-brandOrange font-mono">
                            Rp {{ number_format($transactions->sum('commission_amount'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Table: Complete Transactions History --}}
        <section class="bg-white rounded-[32px] border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.01)] overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Riwayat Transaksi</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar transaksi realtime yang tercatat oleh sistem pelacakan Affiliatekan.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 border-b border-slate-100 bg-slate-50/50">
                            <th class="px-6 py-4">Vendor</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Total Penjualan</th>
                            <th class="px-6 py-4">Komisi</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($transactions as $transaction)
                            <tr class="transition duration-200 hover:bg-slate-50/30">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-50 font-bold text-brandOrange border border-orange-100/55">
                                            {{ strtoupper(substr($transaction->vendor?->company_name ?? 'V', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-800">{{ $transaction->vendor?->company_name ?? 'Vendor Terhapus' }}</p>
                                            <a href="{{ $transaction->vendor?->website_url }}" target="_blank" class="text-[10px] text-slate-400 hover:text-brandOrange transition">
                                                {{ $transaction->vendor?->website_url }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700">
                                    {{ $transaction->product_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-700 whitespace-nowrap">
                                    Rp {{ number_format($transaction->sale_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-brandOrange whitespace-nowrap">
                                    Rp {{ number_format($transaction->commission_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($transaction->status === 'approved')
                                        <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-600 border border-emerald-100/55">
                                            Approved
                                        </span>
                                    @elseif ($transaction->status === 'pending')
                                        <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-bold text-amber-600 border border-amber-100/55">
                                            Pending
                                        </span>
                                    @elseif ($transaction->status === 'paid')
                                        <span class="inline-flex rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-600 border border-blue-100/55">
                                            Paid
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-50 px-2.5 py-1 text-[10px] font-bold text-rose-600 border border-rose-100/55">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-400 whitespace-nowrap">
                                    {{ $transaction->created_at ? $transaction->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-400">
                                    Belum ada transaksi referral yang terekam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Custom Premium Pagination --}}
            @if ($transactions->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </section>
    </div>
</x-layouts.affiliate-dashboard>

@props([
    'payoutQueueEntries' => collect(),
])

<div class="overflow-hidden rounded-[28px] border border-orange-100/70 bg-white shadow-[0_18px_50px_rgba(255,122,0,0.05)]">
    <div class="flex flex-col gap-3 px-6 py-5 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#ff7a00]/70">Payout Queue</p>
            <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Daftar Antrean Payout</h2>
            <p class="mt-1 text-sm text-slate-500">Tinjau semua request pencairan afiliator dengan tabel yang lebih bersih, ringan, dan fokus pada aksi.</p>
        </div>
        <div class="inline-flex items-center gap-2 rounded-full bg-[#fff7f0] px-4 py-2 text-sm font-semibold text-slate-500">
            <span class="h-2.5 w-2.5 rounded-full bg-[#ff7a00]"></span>
            <span>{{ $payoutQueueEntries->count() }} antrean payout</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="border-b border-slate-100 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                    <th class="px-6 py-4">Afiliator</th>
                    <th class="px-6 py-4">Rekening</th>
                    <th class="px-6 py-4">Nominal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payoutQueueEntries as $payoutQueueEntry)
                    @php
                        $isPendingPayout = in_array($payoutQueueEntry->status, ['requested', 'processing']);
                        $affiliateAccountName = $payoutQueueEntry->affiliate?->bank_account_name ?? $payoutQueueEntry->affiliate?->user?->name ?? 'Nama rekening belum tersedia';
                        $affiliateBankName = $payoutQueueEntry->affiliate?->bank_name ?? 'Bank belum diatur';
                        $affiliateAccountNumber = $payoutQueueEntry->affiliate?->bank_account_number ?? 'Nomor rekening belum diatur';
                        
                        $statusClasses = [
                            'requested' => 'bg-[#fff1e5] text-[#ff7a00]',
                            'processing' => 'bg-amber-50 text-amber-600 border border-amber-100/70',
                            'paid' => 'bg-emerald-50 text-emerald-500',
                        ][$payoutQueueEntry->status] ?? 'bg-slate-50 text-slate-500';
                    @endphp

                    <tr x-data="{ open: false }" class="border-b border-slate-100 transition-all duration-300 hover:bg-slate-50">
                        <td class="px-6 py-5 align-top">
                            <div class="flex items-center gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff1e5] font-bold text-[#ff7a00]">
                                    {{ strtoupper(substr($payoutQueueEntry->affiliate?->user?->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-800">{{ $payoutQueueEntry->affiliate?->user?->name ?? 'Afiliator tidak ditemukan' }}</span>
                                    <span class="mt-1 text-sm text-slate-500">{{ $payoutQueueEntry->affiliate?->user?->email ?? 'Email belum tersedia' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top">
                            <div class="rounded-xl border border-orange-100 bg-[#fff8f1] px-4 py-3">
                                <p class="text-sm font-semibold text-slate-800">{{ $affiliateBankName }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $affiliateAccountNumber }}</p>
                                <p class="mt-2 text-xs uppercase tracking-[0.2em] text-slate-400">{{ $affiliateAccountName }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-5 align-top text-sm font-semibold text-slate-800">
                            Rp {{ number_format((float) $payoutQueueEntry->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-5 align-top">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                {{ ucfirst($payoutQueueEntry->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 align-top text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($payoutQueueEntry->status === 'requested')
                                    <form method="POST" action="{{ route('vendor.payouts.process', $payoutQueueEntry->id) }}" data-confirm="Ubah status payout ini menjadi 'processing' untuk diproses?" data-confirm-title="Proses Payout" data-confirm-button="Ya, Proses!" class="inline">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-600 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-amber-100"
                                        >
                                            Proses
                                        </button>
                                    </form>
                                @endif

                                @if ($isPendingPayout)
                                    <button
                                        type="button"
                                        @click="open = true"
                                        class="inline-flex items-center justify-center rounded-xl bg-[#ff7a00] px-5 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#e86d00]"
                                    >
                                        Bayar Manual
                                    </button>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-500">
                                        Paid
                                    </span>
                                @endif
                            </div>

                            <div
                                x-cloak
                                x-show="open"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-40 flex items-center justify-center bg-[#381200]/30 px-4 py-8 backdrop-blur-sm"
                            >
                                <div
                                    @click.away="open = false"
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                    class="w-full max-w-2xl rounded-[28px] border border-orange-100 bg-white p-6 shadow-[0_18px_50px_rgba(255,122,0,0.12)]"
                                >
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#ff7a00]/70">Manual Transfer</p>
                                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Konfirmasi Pembayaran Payout</h3>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">Pastikan nominal dan rekening penerima sudah benar sebelum bukti transfer Anda diunggah.</p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="open = false"
                                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-orange-100 bg-[#fff8f1] text-slate-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:text-[#ff7a00]"
                                        >
                                            <span class="text-lg">&times;</span>
                                        </button>
                                    </div>

                                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                                        <div class="rounded-2xl border border-orange-100 bg-[#fff8f1] p-4">
                                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Nama Penerima</p>
                                            <p class="mt-2 text-base font-semibold text-slate-800">{{ $affiliateAccountName }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-orange-100 bg-[#fff8f1] p-4">
                                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Bank / E-Wallet</p>
                                            <p class="mt-2 text-base font-semibold text-slate-800">{{ $affiliateBankName }}</p>
                                        </div>
                                        <div class="rounded-2xl border border-orange-100 bg-[#fff8f1] p-4">
                                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Nomor Rekening</p>
                                            <p class="mt-2 text-base font-semibold text-slate-800">{{ $affiliateAccountNumber }}</p>
                                        </div>
                                    </div>

                                    <form
                                        method="POST"
                                        action="{{ route('vendor.payouts.pay-manual', $payoutQueueEntry->id) }}"
                                        enctype="multipart/form-data"
                                        data-confirm="Apakah Anda yakin ingin menyelesaikan pembayaran payout sebesar Rp {{ number_format((float) $payoutQueueEntry->amount, 0, ',', '.') }} secara manual?"
                                        data-confirm-title="Konfirmasi Pembayaran"
                                        data-confirm-button="Ya, Selesai!"
                                        class="mt-6 space-y-5"
                                    >
                                        @csrf

                                        <div class="rounded-2xl border border-dashed border-orange-200 bg-[#fff8f1] p-5">
                                            <label for="proof_of_transfer_path_{{ $payoutQueueEntry->id }}" class="block text-sm font-semibold text-slate-800">
                                                Unggah Bukti Transfer
                                            </label>
                                            <p class="mt-1 text-sm text-slate-500">Format yang diizinkan: JPEG, PNG, atau PDF dengan ukuran maksimum 2MB.</p>
                                            <input
                                                id="proof_of_transfer_path_{{ $payoutQueueEntry->id }}"
                                                name="proof_of_transfer_path"
                                                type="file"
                                                accept=".jpg,.jpeg,.png,.pdf"
                                                class="mt-4 block w-full rounded-xl border border-orange-100 bg-white px-4 py-3 text-sm text-slate-500 file:mr-4 file:rounded-full file:border-0 file:bg-[#ff7a00] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#e86d00]"
                                                required
                                            >
                                        </div>

                                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                            <button
                                                type="button"
                                                @click="open = false"
                                                class="inline-flex items-center justify-center rounded-xl border border-orange-100 bg-[#fff8f1] px-5 py-3 text-sm font-semibold text-slate-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:text-[#ff7a00]"
                                            >
                                                Batal
                                            </button>
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-xl bg-[#ff7a00] px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:bg-[#e86d00]"
                                            >
                                                Konfirmasi Pembayaran
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="mx-auto max-w-md rounded-2xl border border-slate-100 bg-slate-50 px-6 py-10">
                                <p class="text-lg font-bold text-slate-800">Belum ada antrean payout</p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">Saat afiliator mulai mengajukan pencairan, seluruh antrean akan muncul rapi di panel ini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

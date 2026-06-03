<x-layouts.admin page-title="Affiliatekan Affiliate Management">
    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-10" x-data="{ open: @js($errors->any() && old('editing_affiliate_id') === null) }">
        <div class="mx-auto max-w-7xl">
            <header class="mb-8 rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_10px_30px_rgb(15,23,42,0.04)] lg:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.4em] text-brandOrange/80">Affiliatekan</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
                            Direktori Afiliator
                        </h1>
                        <p class="mt-3 text-sm leading-7 text-slate-500 sm:text-base">
                            Lihat performa affiliate, identitas referral, dan kesiapan payout dari daftar yang lebih ringan dan mudah dibaca.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="open = true"
                        class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] transition-all duration-300 hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg"
                    >
                        Tambah Afiliator Baru
                    </button>
                </div>

                @if (session('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif
            </header>

            <div class="overflow-hidden rounded-[28px] border border-slate-100 bg-white shadow-[0_8px_30px_rgb(15,23,42,0.04)]">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse table-auto">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 bg-slate-50/50">
                                <th class="px-6 py-5">Afiliator</th>
                                <th class="px-6 py-5">Referral Code</th>
                                <th class="px-6 py-5">Rekening Bank</th>
                                <th class="px-6 py-5 text-center">Total Klik</th>
                                <th class="px-6 py-5 text-center">Total Konversi</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($affiliateDirectoryEntries as $affiliateDirectoryEntry)
                                @php
                                    $affiliateAccountName = $affiliateDirectoryEntry->bank_account_name ?? $affiliateDirectoryEntry->user?->name ?? 'Belum diatur';
                                    $affiliateBankName = $affiliateDirectoryEntry->bank_name ?? 'Belum diatur';
                                    $affiliateAccountNumber = $affiliateDirectoryEntry->bank_account_number ?? 'Nomor rekening belum ada';
                                @endphp
                                <tr x-data="{ editOpen: @js((string) old('editing_affiliate_id') === (string) $affiliateDirectoryEntry->id) }" class="border-b border-slate-100 transition-all duration-300 hover:bg-slate-50/70">
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange font-bold border border-orange-100 shadow-sm">
                                                {{ strtoupper(substr($affiliateDirectoryEntry->user?->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $affiliateDirectoryEntry->user?->name ?? 'Affiliate user missing' }}</p>
                                                <p class="mt-1 text-xs font-medium text-slate-400">{{ $affiliateDirectoryEntry->user?->email ?? 'Email missing' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <span class="inline-flex rounded-xl border border-orange-100 bg-orange-50 px-3.5 py-1.5 font-mono text-xs font-semibold text-brandOrange shadow-sm">
                                            {{ $affiliateDirectoryEntry->referral_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <div class="text-xs leading-normal">
                                            <p class="font-bold text-slate-700">{{ $affiliateBankName }}</p>
                                            <p class="text-slate-500 font-mono mt-0.5">{{ $affiliateAccountNumber }}</p>
                                            <p class="text-[10px] uppercase tracking-wider text-slate-400 mt-0.5">{{ $affiliateAccountName }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-700 font-mono border border-slate-100 min-w-[3rem]">
                                            {{ number_format($affiliateDirectoryEntry->click_logs_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-700 font-mono border border-slate-100 min-w-[3rem]">
                                            {{ number_format($affiliateDirectoryEntry->conversions_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                @click="editOpen = true"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-orange-200 hover:text-brandOrange hover:shadow-md"
                                            >
                                                Edit
                                            </button>

                                            <form method="POST" action="{{ route('admin.affiliates.destroy', $affiliateDirectoryEntry) }}" data-confirm="Hapus affiliate ini beserta user dan seluruh data terkait?" data-confirm-title="Hapus Affiliate" data-confirm-button="Ya, Hapus!" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center justify-center rounded-xl bg-rose-50 px-4 py-2.5 text-xs font-semibold text-rose-600 transition-all duration-300 hover:-translate-y-0.5 hover:bg-rose-100 hover:shadow-md"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>

                                        <div
                                            x-cloak
                                            x-show="editOpen"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 z-45 flex items-center justify-center bg-slate-950/45 px-4 py-8 backdrop-blur-sm"
                                        >
                                            <div
                                                @click.away="editOpen = false"
                                                x-show="editOpen"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                                class="w-full max-w-3xl rounded-[28px] border border-slate-100 bg-white p-6 text-left shadow-[0_24px_60px_rgb(15,23,42,0.18)]"
                                            >
                                                <div class="flex items-start justify-between gap-4">
                                                    <div>
                                                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brandOrange/70">Update Affiliate</p>
                                                        <h2 class="mt-2 text-2xl font-bold text-slate-900">Edit Affiliate</h2>
                                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                                            Update the affiliate identity, referral code, and payout destination details safely.
                                                        </p>
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click="editOpen = false"
                                                        class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md hover:text-brandOrange"
                                                    >
                                                        <span class="text-lg">&times;</span>
                                                    </button>
                                                </div>

                                                <form method="POST" action="{{ route('admin.affiliates.update', $affiliateDirectoryEntry) }}" class="mt-6 grid gap-5 md:grid-cols-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="editing_affiliate_id" value="{{ $affiliateDirectoryEntry->id }}">

                                                    <div>
                                                        <label for="edit_affiliate_name_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nama Affiliate</label>
                                                        <input id="edit_affiliate_name_{{ $affiliateDirectoryEntry->id }}" name="name" type="text" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('name') : $affiliateDirectoryEntry->user?->name }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_affiliate_email_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Email</label>
                                                        <input id="edit_affiliate_email_{{ $affiliateDirectoryEntry->id }}" name="email" type="email" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('email') : $affiliateDirectoryEntry->user?->email }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_referral_code_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Referral Code</label>
                                                        <input id="edit_referral_code_{{ $affiliateDirectoryEntry->id }}" name="referral_code" type="text" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('referral_code') : $affiliateDirectoryEntry->referral_code }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_bank_name_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Bank</label>
                                                        <input id="edit_bank_name_{{ $affiliateDirectoryEntry->id }}" name="bank_name" type="text" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('bank_name') : $affiliateDirectoryEntry->bank_name }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_bank_account_number_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nomor Rekening</label>
                                                        <input id="edit_bank_account_number_{{ $affiliateDirectoryEntry->id }}" name="bank_account_number" type="text" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('bank_account_number') : $affiliateDirectoryEntry->bank_account_number }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_bank_account_name_{{ $affiliateDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nama Pemilik Rekening</label>
                                                        <input id="edit_bank_account_name_{{ $affiliateDirectoryEntry->id }}" name="bank_account_name" type="text" value="{{ old('editing_affiliate_id') == $affiliateDirectoryEntry->id ? old('bank_account_name') : $affiliateDirectoryEntry->bank_account_name }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div class="md:col-span-2 flex flex-col gap-3 sm:flex-row sm:justify-end">
                                                        <button
                                                            type="button"
                                                            @click="editOpen = false"
                                                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-lg"
                                                        >
                                                            Batal
                                                        </button>
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(255,107,0,0.2)] transition-all duration-300 hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-200"
                                                        >
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto max-w-md rounded-2xl border border-slate-100 bg-slate-50 px-6 py-10">
                                            <p class="text-lg font-bold text-slate-800">Belum ada afiliator</p>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">Daftar affiliate aktif akan tampil sebagai partner performance list di panel ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                class="fixed inset-0 z-40 flex items-center justify-center bg-slate-950/45 px-4 py-8 backdrop-blur-sm"
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
                    class="w-full max-w-3xl rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_24px_60px_rgb(15,23,42,0.18)]"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brandOrange/70">New Affiliate</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Tambah Afiliator Baru</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Create an affiliate user account and provision its referral identity in one secure transaction.
                            </p>
                        </div>
                        <button
                            type="button"
                            @click="open = false"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-900"
                        >
                            <span class="text-lg">&times;</span>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('admin.affiliates.store') }}" class="mt-6 grid gap-5 md:grid-cols-2">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-900">Nama Affiliate</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-900">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-900">Password Awal</label>
                            <input id="password" name="password" type="password" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="referral_code" class="block text-sm font-semibold text-slate-900">Referral Code</label>
                            <input id="referral_code" name="referral_code" type="text" value="{{ old('referral_code') }}" placeholder="Kosongkan untuk generate otomatis" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="bank_name" class="block text-sm font-semibold text-slate-900">Bank</label>
                            <input id="bank_name" name="bank_name" type="text" value="{{ old('bank_name') }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="bank_account_number" class="block text-sm font-semibold text-slate-900">Nomor Rekening</label>
                            <input id="bank_account_number" name="bank_account_number" type="text" value="{{ old('bank_account_number') }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="md:col-span-2">
                            <label for="bank_account_name" class="block text-sm font-semibold text-slate-900">Nama Pemilik Rekening</label>
                            <input id="bank_account_name" name="bank_account_name" type="text" value="{{ old('bank_account_name') }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="md:col-span-2 flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                @click="open = false"
                                class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-lg"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] transition-all duration-300 hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-200"
                            >
                                Simpan Afiliator
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

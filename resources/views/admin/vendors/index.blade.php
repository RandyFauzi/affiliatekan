<x-layouts.admin page-title="Affiliatekan Vendor Management">
    <div class="min-h-screen px-4 py-8 sm:px-6 lg:px-10" x-data="{ open: @js($errors->any() && old('editing_vendor_id') === null) }">
        <div class="mx-auto max-w-7xl">
            <header class="mb-8 rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_10px_30px_rgb(15,23,42,0.04)] lg:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.4em] text-brandOrange/80">Affiliatekan</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
                            Direktori Vendor
                        </h1>
                        <p class="mt-3 text-sm leading-7 text-slate-500 sm:text-base">
                            Kelola akun vendor, lihat performa integrasi, dan pantau kesehatan bisnis tiap partner dari satu halaman yang ringkas.
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="open = true"
                        class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] transition-all duration-300 hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg"
                    >
                        Tambah Vendor Baru
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
                                <th class="px-6 py-5">Vendor</th>
                                <th class="px-6 py-5">Email</th>
                                <th class="px-6 py-5">Komisi</th>
                                <th class="px-6 py-5">API Key</th>
                                <th class="px-6 py-5 text-center">Konversi</th>
                                <th class="px-6 py-5 text-center">Payout</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vendorDirectoryEntries as $vendorDirectoryEntry)
                                <tr x-data="{ editOpen: @js((string) old('editing_vendor_id') === (string) $vendorDirectoryEntry->id) }" class="border-b border-slate-100 transition-all duration-300 hover:bg-slate-50/70">
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange font-bold border border-orange-100 shadow-sm">
                                                {{ strtoupper(substr($vendorDirectoryEntry->company_name ?? 'V', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-800 leading-tight">{{ $vendorDirectoryEntry->company_name }}</p>
                                                <p class="mt-1 text-xs font-medium text-slate-400">{{ $vendorDirectoryEntry->user?->name ?? 'Vendor user missing' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <span class="text-sm font-medium text-slate-600">
                                            {{ $vendorDirectoryEntry->user?->email ?? 'Email missing' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        @if($vendorDirectoryEntry->commission_type === 'percentage')
                                            <div class="inline-flex flex-col gap-0.5">
                                                <span class="inline-flex self-start rounded-md bg-blue-50 px-2 py-0.5 text-[9px] font-bold text-[#234cf0] uppercase tracking-wider border border-blue-100">
                                                    Bagi Hasil
                                                </span>
                                                <span class="text-xs font-bold text-slate-700 font-mono">
                                                    {{ $vendorDirectoryEntry->commission_value }}%
                                                </span>
                                            </div>
                                        @else
                                            <div class="inline-flex flex-col gap-0.5">
                                                <span class="inline-flex self-start rounded-md bg-orange-50 px-2 py-0.5 text-[9px] font-bold text-brandOrange uppercase tracking-wider border border-orange-100">
                                                    Flat
                                                </span>
                                                <span class="text-xs font-bold text-slate-700 font-mono">
                                                    Rp {{ number_format((float)$vendorDirectoryEntry->commission_value, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 align-middle whitespace-nowrap">
                                        <div class="flex items-center gap-1.5" x-data="{ copied: false }">
                                            <span class="font-mono text-[11px] text-slate-500 bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1 select-all" title="{{ $vendorDirectoryEntry->api_key }}">
                                                {{ substr($vendorDirectoryEntry->api_key, 0, 8) }}...{{ substr($vendorDirectoryEntry->api_key, -5) }}
                                            </span>
                                            <button
                                                type="button"
                                                @click="navigator.clipboard.writeText('{{ $vendorDirectoryEntry->api_key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-brandOrange hover:border-orange-200 hover:shadow-sm transition-all focus:outline-none"
                                                :title="copied ? 'Disalin!' : 'Salin API Key'"
                                            >
                                                <svg x-show="!copied" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                                </svg>
                                                <svg x-show="copied" x-cloak class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-700 font-mono border border-slate-100 min-w-[3rem]">
                                            {{ number_format($vendorDirectoryEntry->conversions_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-bold text-slate-700 font-mono border border-slate-100 min-w-[3rem]">
                                            {{ number_format($vendorDirectoryEntry->payouts_count) }}
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

                                            <form method="POST" action="{{ route('admin.vendors.destroy', $vendorDirectoryEntry) }}" data-confirm="Hapus vendor ini beserta user dan seluruh data terkait?" data-confirm-title="Hapus Vendor" data-confirm-button="Ya, Hapus!" class="inline">
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
                                                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brandOrange/70">Update Vendor</p>
                                                        <h2 class="mt-2 text-2xl font-bold text-slate-900">Edit Vendor</h2>
                                                        <p class="mt-2 text-sm leading-6 text-slate-600">
                                                            Update the vendor identity and its global commission settings from the admin panel.
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

                                                <form method="POST" action="{{ route('admin.vendors.update', $vendorDirectoryEntry) }}" class="mt-6 grid gap-5 md:grid-cols-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="editing_vendor_id" value="{{ $vendorDirectoryEntry->id }}">

                                                    <div>
                                                        <label for="edit_vendor_name_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nama Kontak</label>
                                                        <input id="edit_vendor_name_{{ $vendorDirectoryEntry->id }}" name="name" type="text" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('name') : $vendorDirectoryEntry->user?->name }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_vendor_email_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Email</label>
                                                        <input id="edit_vendor_email_{{ $vendorDirectoryEntry->id }}" name="email" type="email" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('email') : $vendorDirectoryEntry->user?->email }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_company_name_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nama Perusahaan</label>
                                                        <input id="edit_company_name_{{ $vendorDirectoryEntry->id }}" name="company_name" type="text" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('company_name') : $vendorDirectoryEntry->company_name }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_website_url_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Website URL Vendor</label>
                                                        <input id="edit_website_url_{{ $vendorDirectoryEntry->id }}" name="website_url" type="text" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('website_url') : $vendorDirectoryEntry->website_url }}" placeholder="https://domainklien.com" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_commission_type_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Tipe Komisi</label>
                                                        <select id="edit_commission_type_{{ $vendorDirectoryEntry->id }}" name="commission_type" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                            @php
                                                                $selectedVendorCommissionType = old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('commission_type') : $vendorDirectoryEntry->commission_type;
                                                            @endphp
                                                            <option value="percentage" @selected($selectedVendorCommissionType === 'percentage')>Percentage</option>
                                                            <option value="flat" @selected($selectedVendorCommissionType === 'flat')>Flat</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="edit_commission_value_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Nilai Komisi</label>
                                                        <input id="edit_commission_value_{{ $vendorDirectoryEntry->id }}" name="commission_value" type="number" step="0.01" min="0" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('commission_value') : $vendorDirectoryEntry->commission_value }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                                    </div>

                                                    <div>
                                                        <label for="edit_cookie_duration_days_{{ $vendorDirectoryEntry->id }}" class="block text-sm font-semibold text-slate-900">Cookie Duration Days</label>
                                                        <input id="edit_cookie_duration_days_{{ $vendorDirectoryEntry->id }}" name="cookie_duration_days" type="number" min="1" max="365" value="{{ old('editing_vendor_id') == $vendorDirectoryEntry->id ? old('cookie_duration_days') : $vendorDirectoryEntry->cookie_duration_days }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
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
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="mx-auto max-w-md rounded-2xl border border-slate-100 bg-slate-50 px-6 py-10">
                                            <p class="text-lg font-bold text-slate-800">Belum ada vendor</p>
                                            <p class="mt-2 text-sm leading-6 text-slate-500">Vendor baru yang dibuat dari panel admin akan muncul sebagai daftar partner di sini.</p>
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
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-brandOrange/70">New Vendor</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Tambah Vendor Baru</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                Create a vendor user account and provision its company profile in one secure transaction.
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

                    <form method="POST" action="{{ route('admin.vendors.store') }}" class="mt-6 grid gap-5 md:grid-cols-2">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-900">Nama Kontak</label>
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
                            <label for="company_name" class="block text-sm font-semibold text-slate-900">Nama Perusahaan</label>
                            <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="website_url" class="block text-sm font-semibold text-slate-900">Website URL Vendor (Contoh: https://domainklien.com)</label>
                            <input id="website_url" name="website_url" type="text" value="{{ old('website_url') }}" placeholder="https://domainklien.com" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="commission_type" class="block text-sm font-semibold text-slate-900">Tipe Komisi</label>
                            <select id="commission_type" name="commission_type" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                                <option value="percentage" @selected(old('commission_type', 'percentage') === 'percentage')>Percentage</option>
                                <option value="flat" @selected(old('commission_type') === 'flat')>Flat</option>
                            </select>
                        </div>

                        <div>
                            <label for="commission_value" class="block text-sm font-semibold text-slate-900">Nilai Komisi</label>
                            <input id="commission_value" name="commission_value" type="number" step="0.01" min="0" value="{{ old('commission_value', '10') }}" required class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="md:col-span-2">
                            <label for="cookie_duration_days" class="block text-sm font-semibold text-slate-900">Cookie Duration Days</label>
                            <input id="cookie_duration_days" name="cookie_duration_days" type="number" min="1" max="365" value="{{ old('cookie_duration_days', '30') }}" class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-orange-300 focus:ring-2 focus:ring-orange-100">
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
                                Simpan Vendor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.vendor-dashboard page-title="Afiliator Terdaftar - Affiliatekan">
    {{-- Header Card --}}
    <header class="rounded-3xl px-6 py-7 text-white shadow-[0_18px_45px_rgba(255,107,0,0.24)] md:px-8 md:py-8 mb-8"
        style="background: linear-gradient(90deg, #FF6B00 0%, #FB923C 100%);">
        <div class="max-w-3xl">
            <span class="inline-flex items-center justify-center rounded-xl bg-white/20 px-2.5 py-1 text-[10px] font-bold text-white uppercase tracking-wider shadow-sm mb-3">
                Kemitraan Aktif
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight">Afiliator Terdaftar</h1>
            <p class="mt-3 text-sm leading-7 text-white/85">
                Lihat dan kelola semua partner afiliasi yang telah mendaftar ke program Anda dan telah menyetujui Syarat & Ketentuan (T&C) yang berlaku.
            </p>
        </div>
    </header>

    {{-- Registered Affiliates Table --}}
    <section class="bg-white rounded-3xl border border-slate-100 shadow-[0_12px_35px_rgba(0,0,0,0.015)] overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Affiliate Directory</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Daftar Afiliator</h2>
                <p class="mt-1 text-sm text-slate-500">Seluruh partner afiliasi yang secara resmi terhubung ke program Anda.</p>
            </div>
            <div class="rounded-2xl bg-orange-50 px-4 py-2.5 text-sm font-bold text-brandOrange">
                Total: {{ $affiliates->count() }} Partner
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4">Afiliator</th>
                        <th class="px-6 py-4">Kode Referral</th>
                        <th class="px-6 py-4 text-center">Persetujuan T&amp;C</th>
                        <th class="px-6 py-4">Tanggal Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($affiliates as $affiliate)
                        <tr class="transition-colors duration-300 hover:bg-slate-50/50">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-orange-50 font-bold text-brandOrange border border-orange-100/50 shadow-sm">
                                        {{ strtoupper(substr($affiliate->user?->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $affiliate->user?->name ?? 'Afiliator tidak ditemukan' }}</p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $affiliate->user?->email ?? 'Email belum tersedia' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <code class="rounded-xl bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-650 border border-slate-150">
                                    {{ $affiliate->referral_code }}
                                </code>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-55 px-2.5 py-1 text-[11px] font-bold text-emerald-600 bg-emerald-50/70 border border-emerald-100/55">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Agreed (T&amp;C)</span>
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-600 font-semibold">
                                {{ $affiliate->pivot->created_at ? $affiliate->pivot->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-md rounded-2xl bg-slate-50 px-6 py-10">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 mx-auto mb-3">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-slate-800">Belum ada afiliator bergabung</p>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">
                                        Afiliator yang bergabung dengan program promosi Anda akan terdaftar dan tampil secara otomatis di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.vendor-dashboard>

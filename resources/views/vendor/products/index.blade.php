<x-layouts.vendor-dashboard pageTitle="Daftar Produk & Komisi">
    <div class="space-y-8">
        
        <!-- Welcome Alert / Description -->
        <div class="rounded-3xl border border-orange-100 bg-gradient-to-r from-orange-50/50 to-orange-100/30 p-6 md:p-8">
            <h2 class="text-xl font-bold text-slate-800">Daftar Produk & Skema Komisi</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 max-w-3xl">
                Berikut adalah produk-produk dan skema komisi rujukan yang saat ini dikonfigurasi secara live di website tenant Anda. Semua perubahan data produk atau nilai komisi yang dilakukan di website tenant akan langsung tersinkronisasi di halaman ini secara otomatis.
            </p>
        </div>

        <!-- ERROR STATE CARD -->
        @if ($error)
            <div class="rounded-3xl border border-red-100 bg-red-50 p-6 flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
                <div class="flex gap-4 items-start">
                    <div class="p-3 bg-red-100 text-red-700 rounded-2xl shrink-0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Gagal Mengambil Data Produk</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-5">
                            {{ $error }}
                        </p>
                    </div>
                </div>
                <div class="shrink-0 mt-4 md:mt-0">
                    <a href="{{ route('vendor.integration.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:shadow transition">
                        Atur Integrasi API →
                    </a>
                </div>
            </div>
        @else
            <!-- SUCCESS LIVE PRODUCTS TABLE -->
            <div class="rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Daftar Produk Live</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ditarik dari: {{ rtrim($authenticatedVendor->website_url, '/') }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Live Terkoneksi
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/50 text-xs font-bold uppercase tracking-wider text-slate-400">
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4">Tipe Produk</th>
                                <th class="px-6 py-4 text-right">Harga Produk</th>
                                <th class="px-6 py-4">Jenis Komisi</th>
                                <th class="px-6 py-4 text-right">Nilai Komisi</th>
                                <th class="px-6 py-4 text-right">Estimasi Komisi / Sale</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @foreach ($packages as $pkg)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 font-bold text-slate-800">
                                        {{ $pkg['name'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($pkg['type'] === 'package')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                                Paket Undangan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                                Subscription Vendor
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-slate-800">
                                        Rp {{ number_format($pkg['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($pkg['commission_type'] === 'percentage')
                                            <span class="text-xs font-semibold px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-100 rounded-md">
                                                Persentase (%)
                                            </span>
                                        @else
                                            <span class="text-xs font-semibold px-2 py-0.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-md">
                                                Rupiah (Flat)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">
                                        {{ $pkg['commission_type'] === 'percentage' ? $pkg['commission_value'] . '%' : 'Rp ' . number_format($pkg['commission_value'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-brandOrange">
                                        {{ $pkg['commission_display'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</x-layouts.vendor-dashboard>

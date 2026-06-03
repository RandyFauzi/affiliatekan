<x-layouts.affiliate-dashboard page-title="Direktori Program - Affiliatekan">
    @push('head_meta')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://affiliatekan-by.autogrowthid.site/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Dashboard",
          "item": "https://affiliatekan-by.autogrowthid.site/affiliate/dashboard"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Direktori Program",
          "item": "https://affiliatekan-by.autogrowthid.site/affiliate/programs"
        }
      ]
    }
    </script>
    @endpush
    <div x-data="{ 
        termsModalOpen: false, 
        selectedVendorId: null, 
        selectedVendorName: '',
        agreed: false,
        openTermsModal(vendorId, vendorName) {
            this.selectedVendorId = vendorId;
            this.selectedVendorName = vendorName;
            this.agreed = false;
            this.termsModalOpen = true;
        }
    }">
        {{-- Header Section --}}
        <header class="rounded-3xl px-6 py-7 text-white shadow-[0_18px_45px_rgba(255,107,0,0.24)] md:px-8 md:py-8 mb-8"
            style="background: linear-gradient(90deg, #FF6B00 0%, #FB923C 100%);">
            <div class="max-w-3xl">
                <span class="inline-flex items-center justify-center rounded-xl bg-white/20 px-2.5 py-1 text-[10px] font-bold text-white uppercase tracking-wider shadow-sm mb-3">
                    Katalog Kemitraan
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight">Direktori Program Afiliasi</h1>
                <p class="mt-3 text-sm leading-7 text-white/85">
                    Temukan program kemitraan terbaik, ajukan pendaftaran dengan menyetujui Syarat & Ketentuan (T&C) Vendor, dan dapatkan link promosi instan untuk mulai menghasilkan komisi.
                </p>
            </div>
        </header>

        {{-- Status Notification --}}
        @if (session('status'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-600 shadow-sm flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Directory Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($vendors as $vendor)
                @php
                    $isJoined = in_array($vendor->id, $joinedVendorIds);
                    $affiliateLink = $vendor->website_url . '?ref=' . $authenticatedAffiliate->referral_code;
                    $commissionText = $vendor->commission_type === 'flat'
                        ? 'Flat Rp ' . number_format($vendor->commission_value, 0, ',', '.')
                        : 'Persentase ' . number_format($vendor->commission_value, 2, ',', '.') . '%';
                @endphp

                <article class="rounded-3xl border {{ $isJoined ? 'border-orange-200/60 bg-gradient-to-br from-orange-50/20 to-orange-100/5' : 'border-slate-100 bg-white' }} p-6 shadow-[0_12px_35px_rgba(0,0,0,0.015)] transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center justify-center rounded-xl {{ $isJoined ? 'bg-orange-100 text-brandOrange' : 'bg-slate-100 text-slate-500' }} px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $isJoined ? 'Terdaftar' : 'Tersedia' }}
                                </span>
                                <span class="text-xs text-slate-400">Cookie: {{ $vendor->cookie_duration_days }} Hari</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">{{ $vendor->company_name }}</h3>
                            <p class="mt-2 text-sm font-semibold text-brandOrange">Komisi: {{ $commissionText }}</p>
                            <a href="{{ $vendor->website_url }}" target="_blank" class="mt-1 inline-flex items-center gap-1 text-xs text-slate-400 hover:text-brandOrange transition">
                                <span>Kunjungi Website</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>

                        @if (!$isJoined)
                            <button
                                type="button"
                                @click="openTermsModal({{ $vendor->id }}, '{{ addslashes($vendor->company_name) }}')"
                                class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-5 py-3.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(255,107,0,0.2)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none"
                            >
                                Daftar Program
                            </button>
                        @endif
                    </div>

                    {{-- If joined: display dynamic link generator/copy component --}}
                    @if ($isJoined)
                        <div class="mt-6 pt-5 border-t border-orange-100/60" x-data="copyablePanel(@js($affiliateLink), 'Link referral program berhasil disalin.')">
                            <label class="block text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Link Referral Anda</label>
                            
                            <div class="mt-2 flex items-center gap-2">
                                <div class="flex-1 rounded-2xl border border-orange-200/50 bg-white px-4 py-3.5 overflow-hidden">
                                    <p class="truncate text-sm font-semibold text-slate-700">{{ $affiliateLink }}</p>
                                </div>
                                <button
                                    type="button"
                                    @click="copyValue()"
                                    class="inline-flex h-12 items-center justify-center rounded-2xl bg-brandOrange px-5 text-sm font-bold text-white transition hover:bg-orange-600"
                                >
                                    Copy
                                </button>
                            </div>

                            <p
                                x-cloak
                                x-show="feedbackMessage"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="mt-3 inline-flex rounded-xl bg-emerald-50 px-3.5 py-2 text-xs font-bold text-emerald-600 border border-emerald-100/55"
                            >
                                <span x-text="feedbackMessage"></span>
                            </p>
                        </div>
                    @endif
                </article>
            @empty
                <div class="col-span-1 md:col-span-2 rounded-3xl bg-white border border-slate-100 px-6 py-12 text-center shadow-[0_12px_35px_rgba(0,0,0,0.01)]">
                    <div class="mx-auto max-w-md">
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-orange-50 text-brandOrange mx-auto mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tidak Ada Program</h3>
                        <p class="mt-2 text-sm text-slate-500">
                            Saat ini belum ada vendor partner yang membuka program afiliasi di platform kami.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Alpine.js Modal for Terms & Conditions --}}
        <div
            x-cloak
            x-show="termsModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 px-4 py-6 backdrop-blur-sm"
        >
            <div
                @click.away="termsModalOpen = false"
                x-show="termsModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-6 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="w-full max-w-xl rounded-[32px] border border-slate-100 bg-white p-6 shadow-[0_24px_60px_rgba(0,0,0,0.12)] md:p-8 overflow-y-auto max-h-[90vh]"
            >
                {{-- Modal Header --}}
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="inline-flex items-center justify-center rounded-xl bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider mb-2">
                            Persetujuan Syarat &amp; Ketentuan
                        </span>
                        <h3 class="text-xl font-extrabold tracking-tight text-slate-800" x-text="'Gabung Program: ' + selectedVendorName"></h3>
                    </div>
                    <button
                        type="button"
                        @click="termsModalOpen = false"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-100 bg-white text-slate-400 hover:text-slate-700 transition"
                    >
                        <span class="text-xl font-semibold">&times;</span>
                    </button>
                </div>

                {{-- Terms Content --}}
                <div class="mt-6 space-y-4 text-sm text-slate-650 leading-relaxed max-h-64 overflow-y-auto pr-2">
                    <div class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-brandOrange">1</span>
                        <p>Afiliator dilarang menggunakan metode <strong>SPAM</strong> dalam menyebarkan tautan promosi.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-brandOrange">2</span>
                        <p>Komisi hanya sah untuk transaksi yang telah <strong>dibayar lunas</strong> oleh pelanggan dan tidak direfund.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-brandOrange">3</span>
                        <p>Afiliator dilarang menggunakan nama brand Vendor (merek dagang) untuk kampanye iklan berbayar (<em>Search Ads</em>) tanpa izin tertulis.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-brandOrange">4</span>
                        <p>Pencairan komisi akan diproses selambat-lambatnya <strong>1x24 jam kerja</strong> setelah diajukan secara resmi di platform.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-brandOrange">5</span>
                        <p>Vendor berhak <strong>memberhentikan kerjasama sepihak</strong> jika ditemukan indikasi kecurangan (<em>fraud</em>) atau manipulasi.</p>
                    </div>
                </div>

                {{-- Agreement Form --}}
                <form :action="'/affiliate/programs/' + selectedVendorId + '/join'" method="POST" class="mt-6 pt-5 border-t border-slate-100">
                    @csrf
                    
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            x-model="agreed"
                            class="mt-1 h-4 w-4 rounded border-slate-350 text-brandOrange focus:ring-orange-200"
                        >
                        <span class="text-sm font-semibold text-slate-700">Saya setuju dengan syarat dan ketentuan di atas.</span>
                    </label>

                    <div class="mt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                        <button
                            type="button"
                            @click="termsModalOpen = false"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition duration-300"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="!agreed"
                            class="inline-flex items-center justify-center rounded-2xl bg-brandOrange px-6 py-3.5 text-sm font-bold text-white shadow-lg transition duration-300 hover:bg-orange-600 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none"
                        >
                            Setuju &amp; Gabung
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.affiliate-dashboard>

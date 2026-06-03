<x-layouts.vendor-dashboard page-title="Affiliatekan Vendor Integration">
    <div class="space-y-6">
        <section class="rounded-[20px] border border-slate-100 bg-white px-6 py-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-4xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#234cf0]/70">Integration Guide</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-800">Dokumentasi integrasi yang lebih bersih dan developer-friendly</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Halaman ini merangkum alur lengkap integrasi Affiliatekan, mulai dari tracking trafik affiliate di frontend hingga pencatatan transaksi lunas secara Server-to-Server dari backend vendor.
                    </p>
                </div>

                <div
                    x-data="copyablePanel(@js($authenticatedVendor->api_key), 'API Key vendor berhasil disalin.')"
                    class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 shadow-sm"
                >
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">API Key Vendor</p>
                    <p class="mt-2 max-w-sm break-all text-sm font-semibold text-slate-800">{{ $authenticatedVendor->api_key }}</p>
                    <button
                        type="button"
                        @click="copyValue()"
                        class="mt-4 inline-flex items-center justify-center rounded-xl bg-[#234cf0] px-4 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        Copy API Key
                    </button>

                    <p
                        x-cloak
                        x-show="feedbackMessage"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="mt-3 inline-flex rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-500"
                    >
                        <span x-text="feedbackMessage"></span>
                    </p>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="max-w-4xl">
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#234cf0]/70">API &amp; Webhook Settings</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Pengaturan Integrasi &amp; Webhook</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Konfigurasikan website URL Anda untuk pelacakan klik, webhook URL untuk menerima notifikasi pembayaran/konversi secara otomatis, dan durasi kedaluwarsa cookie pelacakan.
                </p>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-600">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 rounded-xl bg-rose-50 p-4 text-sm font-semibold text-rose-600">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('vendor.integration.settings.update') }}" data-confirm="Simpan perubahan konfigurasi API & Webhook Anda?" data-confirm-title="Simpan Pengaturan" data-confirm-button="Ya, Simpan!" class="mt-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="website_url" class="block text-sm font-bold text-slate-800">Website URL</label>
                        <p class="mt-1 text-xs text-slate-500">URL utama website Anda untuk pencocokan traffic klik.</p>
                        <input
                            type="url"
                            name="website_url"
                            id="website_url"
                            value="{{ old('website_url', $authenticatedVendor->website_url) }}"
                            class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#234cf0] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#234cf0] transition-all"
                            placeholder="https://tokoanda.com"
                            required
                        >
                    </div>

                    <div>
                        <label for="cookie_duration_days" class="block text-sm font-bold text-slate-800">Durasi Cookie (Hari)</label>
                        <p class="mt-1 text-xs text-slate-500">Jumlah hari masa berlaku cookie pelacakan afiliasi.</p>
                        <input
                            type="number"
                            name="cookie_duration_days"
                            id="cookie_duration_days"
                            value="{{ old('cookie_duration_days', $authenticatedVendor->cookie_duration_days ?? 30) }}"
                            class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#234cf0] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#234cf0] transition-all"
                            min="1"
                            max="365"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label for="webhook_url" class="block text-sm font-bold text-slate-800">Webhook URL</label>
                    <p class="mt-1 text-xs text-slate-500">Endpoint API di server Anda yang akan menerima data event (POST request).</p>
                    <input
                        type="url"
                        name="webhook_url"
                        id="webhook_url"
                        value="{{ old('webhook_url', $authenticatedVendor->webhook_url) }}"
                        class="mt-2 block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#234cf0] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#234cf0] transition-all"
                        placeholder="https://api.tokoanda.com/webhooks/affiliatekan"
                    >
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-[#234cf0] px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#234cf0]/70">Langkah 1</p>
                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Tangkap Trafik Afiliasi (Frontend)</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Pasang script pelacak Affiliatekan di website vendor untuk menyimpan sumber referral pengunjung ke cookie <span class="font-semibold text-slate-800">affiliatekan_ref</span>. Cookie ini nanti dibaca backend saat order masuk ke checkout atau pembayaran.
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm">
                    Script ini bersifat publik, jadi tidak perlu menyertakan API key.
                </div>
            </div>

            <div
                x-data="copyablePanel(@js($publicTrackingSnippet), 'Snippet frontend berhasil disalin.')"
                class="mt-6"
            >
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-semibold text-slate-800">Snippet HTML</p>
                    <button
                        type="button"
                        @click="copyValue()"
                        class="inline-flex items-center justify-center rounded-xl border border-[#d7df00] bg-[#f4fe00] px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        Copy
                    </button>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-950 p-4 shadow-sm">
                    <textarea
                        readonly
                        rows="4"
                        class="w-full resize-none bg-transparent font-mono text-sm leading-7 text-slate-100 outline-none"
                    >{{ $publicTrackingSnippet }}</textarea>
                </div>

                <p
                    x-cloak
                    x-show="feedbackMessage"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-4 inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-500"
                >
                    <span x-text="feedbackMessage"></span>
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-sm font-semibold text-slate-800">Apa yang dilakukan?</p>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Script membaca parameter referral dari URL, lalu menyimpannya ke cookie agar sumber affiliate tetap tersedia saat user berpindah halaman.
                    </p>
                </article>
                <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-sm font-semibold text-slate-800">Kapan dibaca backend?</p>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Saat customer checkout atau saat webhook pembayaran diproses, backend vendor membaca cookie <span class="font-mono text-slate-800">affiliatekan_ref</span> untuk mengetahui referral source.
                    </p>
                </article>
                <article class="rounded-2xl border border-slate-100 bg-slate-50 p-4 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <p class="text-sm font-semibold text-slate-800">Catatan penting</p>
                    <p class="mt-2 text-sm leading-7 text-slate-500">
                        Pastikan halaman landing dan checkout memakai domain yang konsisten agar cookie referral tetap bisa diakses selama alur pembelian.
                    </p>
                </article>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="max-w-4xl">
                <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#234cf0]/70">Langkah 2</p>
                <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-800">Catat Transaksi &amp; Komisi (Backend)</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Saat pesanan pelanggan berubah status menjadi <span class="font-semibold text-slate-800">LUNAS</span>, backend vendor mengirim data transaksi tersebut ke API Affiliatekan. Dengan model ini, komisi dicatat aman dari server vendor, bukan dari browser pembeli.
                </p>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                <div class="px-5 py-4">
                    <p class="text-sm font-semibold text-slate-800">Spesifikasi Endpoint</p>
                    <p class="mt-1 break-all font-mono text-sm text-[#234cf0]">POST /api/v1/conversions</p>
                    <p class="mt-2 text-xs text-slate-500">
                        Contoh host production:
                        <span class="font-mono text-slate-700">https://api.affiliatekan.com/v1/conversions</span>
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                                <th class="px-5 py-4">Bagian</th>
                                <th class="px-5 py-4">Nama</th>
                                <th class="px-5 py-4">Tipe</th>
                                <th class="px-5 py-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-100 transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Header</td>
                                <td class="px-5 py-4 font-mono text-slate-800">X-API-KEY</td>
                                <td class="px-5 py-4 text-sm text-slate-500">string</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Gunakan API key vendor Anda: <span class="font-mono text-slate-800">{{ $authenticatedVendor->api_key }}</span></td>
                            </tr>
                            <tr class="border-b border-slate-100 transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Body JSON</td>
                                <td class="px-5 py-4 font-mono text-slate-800">order_id</td>
                                <td class="px-5 py-4 text-sm text-slate-500">string</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Wajib. Gunakan nomor invoice atau order unik dari sistem vendor.</td>
                            </tr>
                            <tr class="border-b border-slate-100 transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Body JSON</td>
                                <td class="px-5 py-4 font-mono text-slate-800">amount</td>
                                <td class="px-5 py-4 text-sm text-slate-500">integer</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Wajib. Nilai transaksi final dalam angka bulat.</td>
                            </tr>
                            <tr class="border-b border-slate-100 transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Body JSON</td>
                                <td class="px-5 py-4 font-mono text-slate-800">ref_code</td>
                                <td class="px-5 py-4 text-sm text-slate-500">string</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Opsional. Ambil dari cookie <span class="font-mono text-slate-800">affiliatekan_ref</span> jika transaksi berasal dari affiliate.</td>
                            </tr>
                            <tr class="border-b border-slate-100 transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Body JSON</td>
                                <td class="px-5 py-4 font-mono text-slate-800">product_name</td>
                                <td class="px-5 py-4 text-sm text-slate-500">string</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Opsional. Kirimkan nama produk yang dibeli agar afiliator melihat detail produk yang terjual di dashboard mereka.</td>
                            </tr>
                            <tr class="transition-all duration-300 hover:bg-white">
                                <td class="px-5 py-4 font-semibold text-slate-800">Body JSON</td>
                                <td class="px-5 py-4 font-mono text-slate-800">commission_amount</td>
                                <td class="px-5 py-4 text-sm text-slate-500">numeric</td>
                                <td class="px-5 py-4 text-sm text-slate-500">Opsional. Nilai rupiah komisi spesifik untuk pesanan ini. Gunakan ini jika Anda ingin menghitung komisi kustom di server Anda (fleksibel per produk).</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 rounded-2xl bg-[#fff7f0] border border-orange-100/50 p-5">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-brandOrange mt-0.5">
                            <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">💡 Fleksibilitas Komisi & Harga Per Produk (Sistem Baru)</h4>
                            <p class="mt-2 text-xs leading-6 text-slate-500">
                                Sistem kami sekarang mendukung **penentuan harga & komisi dinamis per produk** yang sangat fleksibel. Jika toko e-commerce Anda memiliki aturan komisi yang berbeda untuk setiap jenis barang (misal: produk A komisi Rp 5.000, produk B komisi Rp 15.000):
                            </p>
                            <ul class="mt-3 list-inside list-disc text-xs text-slate-500 space-y-2 pl-1">
                                <li>Hitung nilai komisi final per produk secara mandiri di server backend Anda.</li>
                                <li>Kirimkan hasilnya melalui parameter <span class="font-mono bg-white border border-slate-150 rounded px-1 text-brandOrange font-semibold">commission_amount</span>.</li>
                                <li>Affiliatekan akan mencatat nominal tersebut sebagai komisi final secara aman **tanpa menimpanya** menggunakan perhitungan komisi default vendor!</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="{ activeTab: 'php-native' }" class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <button
                        type="button"
                        @click="activeTab = 'php-native'"
                        :class="activeTab === 'php-native' ? 'bg-[#234cf0] text-white shadow-sm' : 'bg-white text-slate-500'"
                        class="inline-flex rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        PHP Native (cURL)
                    </button>
                    <button
                        type="button"
                        @click="activeTab = 'laravel-http'"
                        :class="activeTab === 'laravel-http' ? 'bg-[#234cf0] text-white shadow-sm' : 'bg-white text-slate-500'"
                        class="inline-flex rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    >
                        Laravel 11 (Http Client)
                    </button>
                </div>

                <div x-show="activeTab === 'php-native'" x-transition.opacity class="mt-5">
                    <div x-data="copyablePanel(@js($nativePhpCurlExample), 'Contoh PHP Native berhasil disalin.')">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-semibold text-slate-800">Contoh Implementasi PHP Native</p>
                            <button
                                type="button"
                                @click="copyValue()"
                                class="inline-flex items-center justify-center rounded-xl border border-[#d7df00] bg-[#f4fe00] px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                            >
                                Copy
                            </button>
                        </div>

                        <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-950 p-4 shadow-sm">
                            <textarea readonly rows="19" class="w-full resize-none bg-transparent font-mono text-sm leading-7 text-slate-100 outline-none">{{ $nativePhpCurlExample }}</textarea>
                        </div>

                        <p
                            x-cloak
                            x-show="feedbackMessage"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-4 inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-500"
                        >
                            <span x-text="feedbackMessage"></span>
                        </p>
                    </div>
                </div>

                <div x-show="activeTab === 'laravel-http'" x-transition.opacity class="mt-5">
                    <div x-data="copyablePanel(@js($laravelHttpClientExample), 'Contoh Laravel Http Client berhasil disalin.')">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-semibold text-slate-800">Contoh Implementasi Laravel 11</p>
                            <button
                                type="button"
                                @click="copyValue()"
                                class="inline-flex items-center justify-center rounded-xl bg-[#234cf0] px-4 py-2.5 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                            >
                                Copy
                            </button>
                        </div>

                        <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-950 p-4 shadow-sm">
                            <textarea readonly rows="15" class="w-full resize-none bg-transparent font-mono text-sm leading-7 text-slate-100 outline-none">{{ $laravelHttpClientExample }}</textarea>
                        </div>

                        <p
                            x-cloak
                            x-show="feedbackMessage"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-4 inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-500"
                        >
                            <span x-text="feedbackMessage"></span>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layouts.vendor-dashboard>

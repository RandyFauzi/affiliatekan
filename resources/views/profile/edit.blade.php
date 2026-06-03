@php
    $layoutName = match(auth()->user()->role) {
        'admin' => 'layouts.admin',
        'vendor' => 'layouts.vendor-dashboard',
        'affiliate' => 'layouts.affiliate-dashboard',
        default => 'layouts.marketing',
    };
@endphp

<x-dynamic-component :component="$layoutName" page-title="Edit Profil - Affiliatekan">
    <div class="min-h-screen py-6 px-2 sm:px-4">
        <div class="mx-auto max-w-6xl space-y-8">
            
            {{-- Premium Header Card --}}
            <header class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_10px_30px_rgb(15,23,42,0.02)] lg:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center rounded-xl bg-orange-50 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider border border-orange-100/50 shadow-sm">
                                Pengaturan Akun
                            </span>
                            <span class="inline-flex items-center justify-center rounded-xl bg-slate-50 px-2.5 py-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider border border-slate-100 shadow-sm">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-800">
                            Profil Pengguna
                        </h1>
                        <p class="mt-2 text-sm leading-relaxed text-slate-500 max-w-2xl">
                            Kelola data diri, ganti password akun, serta perbarui informasi penunjang operasional Anda demi menjaga keamanan platform.
                        </p>
                    </div>
                    <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-3xl bg-gradient-to-tr from-brandOrange to-orange-400 text-white text-3xl font-extrabold shadow-[0_12px_30px_rgba(255,107,0,0.22)] border border-orange-300/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            @if(auth()->user()->role === 'vendor')
                {{-- Beautiful Vendor/Tenant Layout (Equal 2 Columns for Profile vs Store/Commission) --}}
                <form method="POST" action="{{ route('profile.update') }}" data-confirm="Simpan seluruh perubahan pada profil Anda?" data-confirm-title="Simpan Perubahan" data-confirm-button="Ya, Simpan!" class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start"
                    x-data='{
                        formData: {
                            name: @json(old("name", $user->name)),
                            email: @json(old("email", $user->email)),
                            password: "",
                            passwordConfirmation: "",
                            companyName: @json(old("company_name", $user->vendor->company_name)),
                            websiteUrl: @json(old("website_url", $user->vendor->website_url ?? "")),
                            commissionType: @json(old("commission_type", $user->vendor->commission_type ?? "percentage")),
                            commissionValue: @json(old("commission_value", $user->vendor->commission_value ?? 0)),
                            cookieDurationDays: @json(old("cookie_duration_days", $user->vendor->cookie_duration_days ?? 30))
                        },
                        originalData: {
                            name: @json($user->name),
                            email: @json($user->email),
                            companyName: @json($user->vendor->company_name),
                            websiteUrl: @json($user->vendor->website_url ?? ""),
                            commissionType: @json($user->vendor->commission_type ?? "percentage"),
                            commissionValue: @json($user->vendor->commission_value ?? 0),
                            cookieDurationDays: @json($user->vendor->cookie_duration_days ?? 30)
                        },
                        isDirty() {
                            return this.formData.name !== this.originalData.name ||
                                   this.formData.email !== this.originalData.email ||
                                   this.formData.password !== "" ||
                                   this.formData.passwordConfirmation !== "" ||
                                   this.formData.companyName !== this.originalData.companyName ||
                                   this.formData.websiteUrl !== this.originalData.websiteUrl ||
                                   this.formData.commissionType !== this.originalData.commissionType ||
                                   parseFloat(this.formData.commissionValue || 0) !== parseFloat(this.originalData.commissionValue) ||
                                   parseInt(this.formData.cookieDurationDays || 0) !== parseInt(this.originalData.cookieDurationDays);
                        }
                    }'
                >
                    @csrf
                    @method('PUT')

                    {{-- Left Column: User Profile & Security Details (Clean White / Slate Theme) --}}
                    <div class="space-y-8">
                        {{-- Card 1: Informasi Kredensial --}}
                        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.02)] lg:p-8">
                            <div class="border-b border-slate-100 pb-5">
                                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil &amp; Kredensial
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">Ubah nama lengkap dan email utama Anda yang terdaftar pada platform.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 tracking-wide">Nama Lengkap</label>
                                    <input 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        x-model="formData.name"
                                        value="{{ old('name', $user->name) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="Nama Lengkap Anda"
                                    >
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 tracking-wide">Email Utama</label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        x-model="formData.email"
                                        value="{{ old('email', $user->email) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="email@alamat.com"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Keamanan & Kata Sandi --}}
                        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.02)] lg:p-8">
                            <div class="border-b border-slate-100 pb-5">
                                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Kata Sandi &amp; Keamanan
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">Kosongkan kolom sandi di bawah jika Anda tidak ingin merubah password saat ini.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-700 tracking-wide">Password Baru</label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        x-model="formData.password"
                                        placeholder="Minimal 8 karakter"
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 tracking-wide">Konfirmasi Password Baru</label>
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password" 
                                        x-model="formData.passwordConfirmation"
                                        placeholder="Ketik ulang sandi baru"
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-slate-400 focus:bg-white focus:ring-4 focus:ring-slate-100"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Store Details & Commission Settings (Distinguished Warm Orange Theme) --}}
                    <div class="space-y-8">
                        {{-- Card 3: Informasi Toko --}}
                        <div class="rounded-[28px] border border-orange-200/60 bg-gradient-to-br from-orange-50/30 to-orange-100/10 p-6 shadow-[0_12px_35px_rgba(255,107,0,0.03)] lg:p-8">
                            <div class="border-b border-orange-100 pb-5">
                                <span class="inline-flex items-center justify-center rounded-xl bg-orange-100 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider shadow-sm mb-3">
                                    DATA USAHA
                                </span>
                                <h2 class="text-lg font-bold text-slate-850 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Informasi Toko &amp; Bisnis
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-600">Ubah nama brand toko serta website utama operasional bisnis Anda.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-semibold text-slate-700 tracking-wide">Nama Toko / Perusahaan</label>
                                    <input 
                                        id="company_name" 
                                        name="company_name" 
                                        type="text" 
                                        x-model="formData.companyName"
                                        value="{{ old('company_name', $user->vendor->company_name) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-orange-200/50 bg-white/80 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="Nama Perusahaan Anda"
                                    >
                                </div>

                                <div>
                                    <label for="website_url" class="block text-sm font-semibold text-slate-700 tracking-wide">Website URL Utama</label>
                                    <input 
                                        id="website_url" 
                                        name="website_url" 
                                        type="url" 
                                        x-model="formData.websiteUrl"
                                        value="{{ old('website_url', $user->vendor->website_url) }}"
                                        placeholder="https://tokoanda.com"
                                        class="mt-2 block w-full rounded-2xl border border-orange-200/50 bg-white/80 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Card 4: Pengaturan Komisi Afiliasi --}}
                        <div class="rounded-[28px] border border-orange-200/60 bg-gradient-to-br from-orange-50/30 to-orange-100/10 p-6 shadow-[0_12px_35px_rgba(255,107,0,0.03)] lg:p-8">
                            <div class="border-b border-orange-100 pb-5">
                                <span class="inline-flex items-center justify-center rounded-xl bg-orange-100 px-2.5 py-1 text-[10px] font-bold text-brandOrange uppercase tracking-wider shadow-sm mb-3">
                                    PENGATURAN KOMISI
                                </span>
                                <h2 class="text-lg font-bold text-slate-850 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pengaturan Komisi Afiliasi
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-600">Konfigurasikan persentase atau nominal komisi bagi afiliator Anda.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="commission_type" class="block text-sm font-semibold text-slate-700 tracking-wide">Tipe Komisi</label>
                                    <select 
                                        id="commission_type" 
                                        name="commission_type" 
                                        x-model="formData.commissionType"
                                        class="mt-2 block w-full rounded-2xl border border-orange-200/50 bg-white/80 px-6 py-4 text-sm font-semibold text-slate-800 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                        <option value="percentage">Persentase (%)</option>
                                        <option value="flat">Nominal Tetap (Rp)</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="commission_value" class="block text-sm font-semibold text-slate-700 tracking-wide">
                                        Nilai Komisi 
                                        <span class="text-xs font-normal text-slate-500" x-text="formData.commissionType === 'percentage' ? '(Contoh: 10 untuk 10%)' : '(Contoh: 15000 untuk Rp 15.000)'"></span>
                                    </label>
                                    <input 
                                        id="commission_value" 
                                        name="commission_value" 
                                        type="number" 
                                        step="0.01" 
                                        min="0"
                                        x-model="formData.commissionValue"
                                        value="{{ old('commission_value', $user->vendor->commission_value ?? 0) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-orange-200/50 bg-white/80 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="0"
                                    >
                                </div>

                                <div>
                                    <label for="cookie_duration_days" class="block text-sm font-semibold text-slate-700 tracking-wide">Durasi Masa Aktif Cookie (Hari)</label>
                                    <input 
                                        id="cookie_duration_days" 
                                        name="cookie_duration_days" 
                                        type="number" 
                                        min="1" 
                                        max="365"
                                        x-model="formData.cookieDurationDays"
                                        value="{{ old('cookie_duration_days', $user->vendor->cookie_duration_days ?? 30) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-orange-200/50 bg-white/80 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="30"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions Card --}}
                        <div class="rounded-[28px] border border-orange-200/60 bg-gradient-to-br from-orange-50/30 to-orange-100/10 p-6 shadow-[0_12px_35px_rgba(255,107,0,0.03)] lg:p-8 flex flex-col sm:flex-row gap-4">
                            <button 
                                type="submit" 
                                :disabled="!isDirty()"
                                :class="isDirty() 
                                    ? 'bg-brandOrange text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg focus:ring-2 focus:ring-orange-200 cursor-pointer' 
                                    : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none border-none pointer-events-none hover:translate-y-0'"
                                class="inline-flex flex-1 items-center justify-center rounded-2xl py-4 px-6 text-base font-extrabold transition-all duration-300 focus:outline-none"
                            >
                                Simpan Perubahan
                            </button>
                            
                            <a 
                                href="{{ url()->previous() ?: route('vendor.dashboard.index') }}"
                                class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white py-4 px-6 text-sm font-bold text-slate-600 transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-md"
                            >
                                Batal &amp; Kembali
                            </a>
                        </div>
                    </div>
                </form>
            @else
                {{-- Standard Layout for Non-Vendor Users (Admin/Affiliate) --}}
                <form method="POST" action="{{ route('profile.update') }}" data-confirm="Simpan seluruh perubahan pada profil Anda?" data-confirm-title="Simpan Perubahan" data-confirm-button="Ya, Simpan!" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
                    x-data='{
                        formData: {
                            name: @json(old("name", $user->name)),
                            email: @json(old("email", $user->email)),
                            bankName: @json(old("bank_name", $user->affiliate->bank_name ?? "")),
                            bankAccountNumber: @json(old("bank_account_number", $user->affiliate->bank_account_number ?? "")),
                            bankAccountName: @json(old("bank_account_name", $user->affiliate->bank_account_name ?? "")),
                            password: "",
                            passwordConfirmation: ""
                        },
                        originalData: {
                            name: @json($user->name),
                            email: @json($user->email),
                            bankName: @json($user->affiliate->bank_name ?? ""),
                            bankAccountNumber: @json($user->affiliate->bank_account_number ?? ""),
                            bankAccountName: @json($user->affiliate->bank_account_name ?? "")
                        },
                        isDirty() {
                            return this.formData.name !== this.originalData.name ||
                                   this.formData.email !== this.originalData.email ||
                                   this.formData.bankName !== this.originalData.bankName ||
                                   this.formData.bankAccountNumber !== this.originalData.bankAccountNumber ||
                                   this.formData.bankAccountName !== this.originalData.bankAccountName ||
                                   this.formData.password !== "" ||
                                   this.formData.passwordConfirmation !== "";
                        }
                    }'
                >
                    @csrf
                    @method('PUT')

                    {{-- Left Column: Core Credentials & Role Details --}}
                    <div class="lg:col-span-7 space-y-8">
                        
                        {{-- Card 1: Informasi Kredensial --}}
                        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.03)] lg:p-8">
                            <div class="border-b border-slate-100 pb-5">
                                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Informasi Kredensial
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">Ubah nama lengkap dan email utama Anda yang terdaftar pada platform.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 tracking-wide">Nama Lengkap</label>
                                    <input 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        x-model="formData.name"
                                        value="{{ old('name', $user->name) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="Nama Lengkap Anda"
                                    >
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-slate-700 tracking-wide">Email Utama</label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        x-model="formData.email"
                                        value="{{ old('email', $user->email) }}"
                                        required 
                                        class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                        placeholder="email@alamat.com"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Informasi Spesifik Peran (Hanya jika Affiliate) --}}
                        @if($user->role === 'affiliate' && $user->affiliate)
                            <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.03)] lg:p-8">
                                <div class="border-b border-slate-100 pb-5">
                                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Informasi Rekening Bank
                                    </h2>
                                    <p class="mt-1.5 text-xs leading-relaxed text-slate-500">Detail bank ini akan digunakan oleh vendor untuk mentransfer komisi pencairan Anda.</p>
                                </div>

                                <div class="mt-6 space-y-6">
                                    <div>
                                        <label for="bank_name" class="block text-sm font-semibold text-slate-700 tracking-wide">Nama Bank / E-Wallet</label>
                                        <input 
                                            id="bank_name" 
                                            name="bank_name" 
                                            type="text" 
                                            x-model="formData.bankName"
                                            value="{{ old('bank_name', $user->affiliate->bank_name) }}"
                                            placeholder="Contoh: BCA / Mandiri / GoPay"
                                            class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                            style="padding: 1rem 1.5rem;"
                                        >
                                    </div>

                                    <div>
                                        <label for="bank_account_number" class="block text-sm font-semibold text-slate-700 tracking-wide">Nomor Rekening / Nomor E-Wallet</label>
                                        <input 
                                            id="bank_account_number" 
                                            name="bank_account_number" 
                                            type="text" 
                                            x-model="formData.bankAccountNumber"
                                            value="{{ old('bank_account_number', $user->affiliate->bank_account_number) }}"
                                            placeholder="Contoh: 8012345678"
                                            class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                            style="padding: 1rem 1.5rem;"
                                        >
                                    </div>

                                    <div>
                                        <label for="bank_account_name" class="block text-sm font-semibold text-slate-700 tracking-wide">Nama Pemilik Rekening</label>
                                        <input 
                                            id="bank_account_name" 
                                            name="bank_account_name" 
                                            type="text" 
                                            x-model="formData.bankAccountName"
                                            value="{{ old('bank_account_name', $user->affiliate->bank_account_name) }}"
                                            placeholder="Nama Lengkap Pemilik Rekening"
                                            class="mt-2 block w-full rounded-2xl border border-slate-200 bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                            style="padding: 1rem 1.5rem;"
                                        >
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    {{-- Right Column: Kata Sandi & Keamanan --}}
                    <div class="lg:col-span-5 space-y-8">
                        
                        {{-- Card 3: Keamanan & Kata Sandi --}}
                        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.03)] lg:p-8">
                            <div class="border-b border-slate-100 pb-5">
                                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-brandOrange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Kata Sandi &amp; Keamanan
                                </h2>
                                <p class="mt-1.5 text-xs leading-relaxed text-slate-500">Kosongkan kolom sandi di bawah jika Anda tidak ingin merubah password saat ini.</p>
                            </div>
                            
                            <div class="mt-6 space-y-6">
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-slate-700 tracking-wide">Password Baru</label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password" 
                                        x-model="formData.password"
                                        placeholder="Minimal 8 karakter"
                                        class="mt-2 block w-full rounded-2xl border border-brandOrange bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 tracking-wide">Konfirmasi Password Baru</label>
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password" 
                                        x-model="formData.passwordConfirmation"
                                        placeholder="Ketik ulang sandi baru"
                                        class="mt-2 block w-full rounded-2xl border border-brandOrange bg-slate-50/70 px-6 py-4 text-sm font-semibold text-slate-800 placeholder:text-slate-400 outline-none transition duration-300 focus:border-brandOrange focus:bg-white focus:ring-4 focus:ring-orange-100/50"
                                        style="padding: 1rem 1.5rem;"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions Card --}}
                        <div class="rounded-[28px] border border-slate-100 bg-white p-6 shadow-[0_12px_35px_rgb(15,23,42,0.03)] lg:p-8 flex flex-col gap-4">
                            <button 
                                type="submit" 
                                :disabled="!isDirty()"
                                :class="isDirty() 
                                    ? 'bg-brandOrange text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] hover:-translate-y-1 hover:bg-orange-600 hover:shadow-lg focus:ring-2 focus:ring-orange-200 cursor-pointer' 
                                    : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none border-none pointer-events-none hover:translate-y-0'"
                                class="inline-flex w-full items-center justify-center rounded-2xl py-4 px-6 text-base font-extrabold transition-all duration-300 focus:outline-none"
                            >
                                Simpan Perubahan Profil
                            </button>
                            
                            <a 
                                href="{{ url()->previous() ?: route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'affiliate.dashboard.index') }}"
                                class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white py-4 px-6 text-sm font-bold text-slate-600 transition-all duration-300 hover:-translate-y-1 hover:bg-slate-50 hover:shadow-md"
                            >
                                Batal &amp; Kembali
                            </a>
                        </div>

                    </div>
                </form>
            @endif
            
        </div>
    </div>
</x-dynamic-component>

<x-layouts.marketing page-title="Daftar Affiliatekan">
    <main class="min-h-screen bg-[linear-gradient(135deg,_#ff6b00_0%,_#ff8a1f_52%,_#ffb15c_100%)]">
        <div class="grid min-h-screen lg:grid-cols-[1.08fr_0.92fr]">
            <section class="hidden items-center px-10 text-white lg:flex xl:px-16">
                <div class="max-w-xl">
                    <div class="affiliatekan-animate-left flex items-center gap-4">
                        <img src="{{ asset('images/Logo - Affilaitekan.svg') }}" alt="Logo Affiliatekan" class="h-12 w-auto bg-white/10 p-2 rounded-2xl backdrop-blur-sm">
                    </div>

                    <h1 class="affiliatekan-animate-left affiliatekan-stagger-1 mt-14 text-5xl font-extrabold leading-tight tracking-tight xl:text-6xl">
                        Join, Grow!
                    </h1>
                    <p class="affiliatekan-animate-left affiliatekan-stagger-2 mt-5 text-lg font-semibold text-white/90">
                        Buat akun vendor atau afiliator dan mulai jalankan program afiliasi.
                    </p>
                    <p class="affiliatekan-animate-left affiliatekan-stagger-3 mt-8 max-w-md text-sm leading-7 text-white/65">
                        Vendor mendapat API key otomatis, sementara afiliator langsung mendapatkan kode referral unik setelah registrasi berhasil.
                    </p>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
                <div class="affiliatekan-animate-card w-full max-w-md rounded-[34px] bg-white px-8 py-10 shadow-[0_24px_70px_rgba(124,45,18,0.22)] sm:px-10">
                    <div class="text-center">
                        <div class="affiliatekan-float mx-auto mb-7 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange lg:hidden">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" class="h-6 w-6">
                                <path d="M7 6.5v11l10-5.5-10-5.5Z"/>
                                <path d="M4.5 5.5a4 4 0 0 1 4-4h7a4 4 0 0 1 4 4v13a4 4 0 0 1-4 4h-7a4 4 0 0 1-4-4v-13Z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Create Account</h2>
                        <p class="mt-3 text-sm text-slate-500">Daftar sebagai vendor atau afiliator Affiliatekan.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-7 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('register.store') }}"
                        x-data="{ selectedRole: @js(old('role', 'affiliate')) }"
                        class="mt-8 space-y-4"
                    >
                        @csrf

                        <div class="grid grid-cols-2 gap-3 rounded-full bg-slate-50 p-1">
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="affiliate" x-model="selectedRole" class="sr-only">
                                <span class="block rounded-full px-4 py-2.5 text-center text-sm font-bold transition" :class="selectedRole === 'affiliate' ? 'bg-brandOrange text-white shadow-sm' : 'text-slate-500'">
                                    Affiliate
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="vendor" x-model="selectedRole" class="sr-only">
                                <span class="block rounded-full px-4 py-2.5 text-center text-sm font-bold transition" :class="selectedRole === 'vendor' ? 'bg-brandOrange text-white shadow-sm' : 'text-slate-500'">
                                    Vendor
                                </span>
                            </label>
                        </div>

                        <div>
                            <label for="name" class="sr-only">Nama</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Nama lengkap"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div>
                            <label for="email" class="sr-only">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="Email"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div x-show="selectedRole === 'vendor'" x-transition.opacity.duration.200ms>
                            <label for="company_name" class="sr-only">Nama perusahaan</label>
                            <input
                                id="company_name"
                                name="company_name"
                                type="text"
                                value="{{ old('company_name') }}"
                                placeholder="Nama perusahaan vendor"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div x-show="selectedRole === 'vendor'" x-transition.opacity.duration.200ms>
                            <label for="website_url" class="sr-only">Website URL</label>
                            <input
                                id="website_url"
                                name="website_url"
                                type="url"
                                value="{{ old('website_url') }}"
                                placeholder="Website URL vendor"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div>
                            <label for="password" class="sr-only">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                placeholder="Password minimal 8 karakter"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="sr-only">Konfirmasi password</label>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                placeholder="Konfirmasi password"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <button
                            type="submit"
                            class="affiliatekan-shine inline-flex w-full items-center justify-center rounded-full bg-brandOrange px-6 py-3 text-sm font-bold text-white shadow-[0_12px_26px_rgba(255,107,0,0.26)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100"
                        >
                            Daftar
                        </button>
                    </form>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-brandOrange transition hover:text-orange-600">
                            Login sekarang
                        </a>
                    </p>
                </div>
            </section>
        </div>
    </main>
</x-layouts.marketing>

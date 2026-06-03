<x-layouts.marketing page-title="Login Affiliatekan">
    <main class="min-h-screen bg-[linear-gradient(135deg,_#ff6b00_0%,_#ff8a1f_52%,_#ffb15c_100%)]">
        <div class="grid min-h-screen lg:grid-cols-[1.08fr_0.92fr]">
            <section class="hidden items-center px-10 text-white lg:flex xl:px-16">
                <div class="max-w-xl">
                    <div class="affiliatekan-animate-left flex items-center gap-4">
                        <img src="{{ asset('images/Logo - Affilaitekan.svg') }}" alt="Logo Affiliatekan" class="h-12 w-auto bg-white/10 p-2 rounded-2xl backdrop-blur-sm">
                    </div>

                    <h1 class="affiliatekan-animate-left affiliatekan-stagger-1 mt-14 text-5xl font-extrabold leading-tight tracking-tight xl:text-6xl">
                        Hey, Hello!
                    </h1>
                    <p class="affiliatekan-animate-left affiliatekan-stagger-2 mt-5 text-lg font-semibold text-white/90">
                        Masuk ke sistem afiliasi yang rapi untuk vendor dan afiliator.
                    </p>
                    <p class="affiliatekan-animate-left affiliatekan-stagger-3 mt-8 max-w-md text-sm leading-7 text-white/65">
                        Pantau klik, konversi, komisi, payout, dan integrasi API dari satu dashboard internal yang dibuat untuk kerja harian.
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
                        <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Welcome Back</h2>
                        <p class="mt-3 text-sm text-slate-500">Masuk untuk melanjutkan ke dashboard Affiliatekan.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mt-7 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mt-7 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="sr-only">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="Email"
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
                                placeholder="Password"
                                class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                            >
                        </div>

                        <div class="flex items-center justify-between gap-4 text-sm">
                            <label class="inline-flex items-center gap-2 text-slate-500">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-4 w-4 rounded border-slate-300 text-brandOrange focus:ring-orange-200"
                                >
                                <span>Ingat saya</span>
                            </label>
                            <a href="{{ route('password.otp.request') }}" class="font-medium text-slate-400 transition hover:text-brandOrange">
                                Forgot Password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="affiliatekan-shine inline-flex w-full items-center justify-center rounded-full bg-brandOrange px-6 py-3 text-sm font-bold text-white shadow-[0_12px_26px_rgba(255,107,0,0.26)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100"
                        >
                            Login
                        </button>
                    </form>

                    <p class="mt-8 text-center text-sm text-slate-500">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-brandOrange transition hover:text-orange-600">
                            Daftar sekarang
                        </a>
                    </p>
                </div>
            </section>
        </div>
    </main>
</x-layouts.marketing>

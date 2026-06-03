<x-layouts.marketing page-title="Password Baru Affiliatekan">
    <main class="min-h-screen bg-[linear-gradient(135deg,_#ff6b00_0%,_#ff8a1f_52%,_#ffb15c_100%)]">
        <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="affiliatekan-animate-card w-full max-w-md rounded-[34px] bg-white px-8 py-10 shadow-[0_24px_70px_rgba(124,45,18,0.22)] sm:px-10">
                <div class="text-center">
                    <div class="affiliatekan-float mx-auto mb-7 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-brandOrange">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" class="h-6 w-6">
                            <path d="M7 6.5v11l10-5.5-10-5.5Z"/>
                            <path d="M4.5 5.5a4 4 0 0 1 4-4h7a4 4 0 0 1 4 4v13a4 4 0 0 1-4 4h-7a4 4 0 0 1-4-4v-13Z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Password Baru</h2>
                    <p class="mt-3 text-sm text-slate-500">Buat password baru untuk mengamankan akun Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mt-7 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.otp.reset') }}" class="mt-8 space-y-4">
                    @csrf

                    <div>
                        <label for="password" class="sr-only">Password baru</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autofocus
                            placeholder="Password baru"
                            class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="sr-only">Konfirmasi password baru</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            placeholder="Konfirmasi password baru"
                            class="block w-full rounded-full border border-slate-200 bg-white px-5 py-3 text-sm text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-orange-200 hover:shadow-[0_8px_20px_rgba(255,107,0,0.08)] focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                        >
                    </div>

                    <button
                        type="submit"
                        class="affiliatekan-shine inline-flex w-full items-center justify-center rounded-full bg-brandOrange px-6 py-3 text-sm font-bold text-white shadow-[0_12px_26px_rgba(255,107,0,0.26)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100"
                    >
                        Simpan Password Baru
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-layouts.marketing>

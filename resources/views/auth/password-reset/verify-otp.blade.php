<x-layouts.marketing page-title="Verifikasi OTP Affiliatekan">
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
                    <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Masukkan OTP</h2>
                    <p class="mt-3 text-sm text-slate-500">Kode 6 digit sudah dikirim ke {{ $maskedEmail }}.</p>
                </div>

                @if (session('status'))
                    <div class="mt-7 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-600">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-7 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('password.otp.verify') }}"
                    x-data="{
                        digits: ['', '', '', '', '', ''],
                        moveToNextOtpBox(index, event) {
                            this.digits[index] = event.target.value.replace(/[^0-9]/g, '').slice(-1);

                            if (this.digits[index] && index < 5) {
                                this.$refs['otp' + (index + 1)].focus();
                            }
                        },
                        moveToPreviousOtpBox(index, event) {
                            if (!this.digits[index] && index > 0) {
                                this.$refs['otp' + (index - 1)].focus();
                            }
                        },
                        pasteOtpDigits(event) {
                            const pastedOtp = (event.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, 6);

                            if (pastedOtp.length !== 6) {
                                return;
                            }

                            event.preventDefault();
                            this.digits = pastedOtp.split('');
                            this.$nextTick(() => this.$refs.otp5.focus());
                        }
                    }"
                    class="mt-8"
                >
                    @csrf

                    <div class="grid grid-cols-6 gap-2 sm:gap-3" @paste="pasteOtpDigits($event)">
                        @for ($otpDigitIndex = 0; $otpDigitIndex < 6; $otpDigitIndex++)
                            <input
                                name="otp_digits[]"
                                type="text"
                                inputmode="numeric"
                                maxlength="1"
                                autocomplete="one-time-code"
                                x-model="digits[{{ $otpDigitIndex }}]"
                                x-ref="otp{{ $otpDigitIndex }}"
                                @input="moveToNextOtpBox({{ $otpDigitIndex }}, $event)"
                                @keydown.backspace="moveToPreviousOtpBox({{ $otpDigitIndex }}, $event)"
                                class="h-14 rounded-2xl border border-slate-200 bg-white text-center text-xl font-extrabold text-slate-900 outline-none transition-all duration-300 hover:border-orange-200 focus:border-brandOrange focus:ring-4 focus:ring-orange-100"
                                required
                            >
                        @endfor
                    </div>

                    <button
                        type="submit"
                        class="affiliatekan-shine mt-6 inline-flex w-full items-center justify-center rounded-full bg-brandOrange px-6 py-3 text-sm font-bold text-white shadow-[0_12px_26px_rgba(255,107,0,0.26)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-orange-100"
                    >
                        Verifikasi OTP
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('password.otp.send') }}"
                    x-data="{ secondsLeft: 60 }"
                    x-init="setInterval(() => { if (secondsLeft > 0) secondsLeft-- }, 1000)"
                    class="mt-5 text-center"
                >
                    @csrf
                    <input type="hidden" name="email" value="{{ session('password_reset_email') }}">
                    <button
                        type="submit"
                        :disabled="secondsLeft > 0"
                        class="text-sm font-bold text-brandOrange transition hover:text-orange-600 disabled:cursor-not-allowed disabled:text-slate-300"
                    >
                        <span x-show="secondsLeft > 0">Kirim ulang dalam <span x-text="secondsLeft"></span> detik</span>
                        <span x-show="secondsLeft <= 0">Kirim Ulang OTP</span>
                    </button>
                </form>
            </div>
        </section>
    </main>
</x-layouts.marketing>

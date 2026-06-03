<x-layouts.vendor-dashboard page-title="Affiliatekan Vendor Payout Queue">
    <div class="space-y-6">
        <section class="rounded-[28px] border border-orange-100/80 bg-white/95 px-6 py-6 shadow-[0_18px_50px_rgba(255,122,0,0.06)]">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-[#ff7a00]/70">Payout Overview</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-800">Antrean payout untuk {{ $authenticatedVendor->company_name }}</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-500">
                        Tinjau setiap permintaan pencairan, cek rekening tujuan, lalu selesaikan pembayaran manual dengan bukti transfer yang terdokumentasi rapi.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl border border-orange-100 bg-[#fff7f0] px-5 py-4 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Vendor</p>
                        <p class="mt-2 text-lg font-bold text-slate-800">{{ $authenticatedVendor->company_name }}</p>
                    </div>
                    <div class="rounded-2xl border border-orange-100 bg-[#fff7f0] px-5 py-4 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Pending Request</p>
                        <p class="mt-2 text-lg font-bold text-slate-800">{{ $payoutQueueEntries->where('status', 'requested')->count() }} payout</p>
                    </div>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-xl bg-[#fff1e5] px-4 py-3 text-sm font-semibold text-[#ff7a00]">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-500">
                    {{ $errors->first() }}
                </div>
            @endif
        </section>

        <x-vendor.payout-queue-table :payout-queue-entries="$payoutQueueEntries" />
    </div>
</x-layouts.vendor-dashboard>

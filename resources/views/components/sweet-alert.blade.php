<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Define premium SwalCustom helper on window
        window.SwalCustom = {
            // Elegant Success Alert
            success: function(title, text, confirmButtonText = 'Selesai') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'success',
                    iconColor: '#FF6B00',
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        popup: 'rounded-[28px] border border-slate-100 bg-white p-7 shadow-[0_20px_50px_rgba(15,23,42,0.15)] font-sans',
                        title: 'text-2xl font-extrabold text-slate-800 tracking-tight pt-2',
                        htmlContainer: 'text-sm text-slate-500 leading-relaxed font-medium mt-2',
                        confirmButton: 'inline-flex items-center justify-center rounded-2xl bg-brandOrange px-8 py-4 text-base font-extrabold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-200 min-w-[160px]'
                    },
                    buttonsStyling: false
                });
            },
            
            // Elegant Error Alert
            error: function(title, text, confirmButtonText = 'Tutup') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'error',
                    iconColor: '#ef4444',
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        popup: 'rounded-[28px] border border-slate-100 bg-white p-7 shadow-[0_20px_50px_rgba(15,23,42,0.15)] font-sans',
                        title: 'text-2xl font-extrabold text-slate-800 tracking-tight pt-2',
                        htmlContainer: 'text-sm text-slate-500 leading-relaxed font-medium mt-2',
                        confirmButton: 'inline-flex items-center justify-center rounded-2xl bg-rose-600 px-8 py-4 text-base font-extrabold text-white shadow-[0_12px_30px_rgba(244,63,94,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-rose-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-rose-200 min-w-[160px]'
                    },
                    buttonsStyling: false
                });
            },

            // Elegant Warning Alert
            warning: function(title, text, confirmButtonText = 'Mengerti') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        popup: 'rounded-[28px] border border-slate-100 bg-white p-7 shadow-[0_20px_50px_rgba(15,23,42,0.15)] font-sans',
                        title: 'text-2xl font-extrabold text-slate-800 tracking-tight pt-2',
                        htmlContainer: 'text-sm text-slate-500 leading-relaxed font-medium mt-2',
                        confirmButton: 'inline-flex items-center justify-center rounded-2xl bg-amber-500 px-8 py-4 text-base font-extrabold text-white shadow-[0_12px_30px_rgba(245,158,11,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-amber-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-200 min-w-[160px]'
                    },
                    buttonsStyling: false
                });
            },

            // Elegant Info Alert
            info: function(title, text, confirmButtonText = 'Oke') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'info',
                    iconColor: '#234cf0',
                    confirmButtonText: confirmButtonText,
                    customClass: {
                        popup: 'rounded-[28px] border border-slate-100 bg-white p-7 shadow-[0_20px_50px_rgba(15,23,42,0.15)] font-sans',
                        title: 'text-2xl font-extrabold text-slate-800 tracking-tight pt-2',
                        htmlContainer: 'text-sm text-slate-500 leading-relaxed font-medium mt-2',
                        confirmButton: 'inline-flex items-center justify-center rounded-2xl bg-[#234cf0] px-8 py-4 text-base font-extrabold text-white shadow-[0_12px_30px_rgba(35,76,240,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-200 min-w-[160px]'
                    },
                    buttonsStyling: false
                });
            },

            // Reusable Confirm Dialog
            confirm: function(title, text, onConfirm, onCancel = null, confirmButtonText = 'Ya, Hapus!', cancelButtonText = 'Batal') {
                return Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    iconColor: '#FF6B00',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText,
                    customClass: {
                        popup: 'rounded-[28px] border border-slate-100 bg-white p-8 shadow-[0_24px_60px_rgba(15,23,42,0.16)] font-sans',
                        title: 'text-2xl font-extrabold text-slate-900 tracking-tight pt-2',
                        htmlContainer: 'text-sm text-slate-500 leading-relaxed font-medium mt-2 mb-4',
                        confirmButton: 'inline-flex items-center justify-center rounded-2xl bg-brandOrange px-8 py-4 text-base font-extrabold text-white shadow-[0_12px_30px_rgba(255,107,0,0.22)] transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-600 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-200 min-w-[160px] mx-2',
                        cancelButton: 'inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-8 py-4 text-base font-bold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:bg-slate-50 hover:shadow-md focus:outline-none min-w-[160px] mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (onConfirm) onConfirm();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        if (onCancel) onCancel();
                    }
                });
            }
        };

        // Auto trigger flash session messages
        @if(session('status') || session('success'))
            window.SwalCustom.success('Berhasil!', "{{ session('status') ?: session('success') }}");
        @endif

        @if(session('error'))
            window.SwalCustom.error('Error!', "{{ session('error') }}");
        @endif

        @if(session('warning'))
            window.SwalCustom.warning('Peringatan!', "{{ session('warning') }}");
        @endif

        @if(session('info'))
            window.SwalCustom.info('Informasi', "{{ session('info') }}");
        @endif

        // Auto trigger validation errors
        @if($errors->any())
            window.SwalCustom.error('Validasi Gagal!', "{{ $errors->first() }}");
        @endif

        // Global Event Interceptor for Confirm Forms using data-confirm attribute
        document.body.addEventListener('submit', function (event) {
            const form = event.target;
            if (form.hasAttribute('data-confirm')) {
                if (form.dataset.confirmed === 'true') {
                    return; // Let it submit naturally
                }
                event.preventDefault(); // Halt submit
                const message = form.getAttribute('data-confirm');
                const title = form.getAttribute('data-confirm-title') || 'Apakah Anda yakin?';
                const confirmButtonText = form.getAttribute('data-confirm-button') || 'Ya, Hapus!';
                const cancelButtonText = form.getAttribute('data-confirm-cancel') || 'Batal';

                window.SwalCustom.confirm(
                    title,
                    message,
                    function () {
                        form.dataset.confirmed = 'true';
                        form.submit(); // Trigger natural submit
                    },
                    null,
                    confirmButtonText,
                    cancelButtonText
                );
            }
        });
    });
</script>

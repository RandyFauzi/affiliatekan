<div align="center">
  <img src="https://i.imgur.com/vb72GlY.png" width="300" alt="Laravel Logo">
  <br><br>
  <h1>Affiliatekan</h1>
  <p><b>Sistem Manajemen Afiliasi B2B (SaaS) Berbasis Open-Source</b></p>

  <p>
    <img src="https://img.shields.io/badge/Laravel-10.10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10">
    <img src="https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1">
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.4.19-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
    <img src="https://img.shields.io/badge/Alpine.js-3.15.12-8BC0D0?style=for-the-badge&logo=alpinejs&logoColor=white" alt="Alpine JS">
  </p>
</div>

---

> **Affiliatekan** adalah aplikasi internal *Affiliate Tracking SaaS B2B* yang dirancang dengan arsitektur modern untuk memfasilitasi manajemen afiliasi secara transparan dan efisien.

## 🎯 Mengapa Affiliatekan?

Aplikasi ini menjembatani dua aktor utama dalam ekosistem pemasaran digital:

* 🏢 **Vendor:** Mengelola integrasi *tracking*, melihat kunci API, menyalin *snippet* `tracker.js`, dan memproses alur kerja pembayaran komisi (*payout*) secara manual.
* 🤝 **Affiliate:** Memantau performa *referral* secara *real-time* melalui *dashboard* interaktif, melihat jumlah klik, konversi, saldo komisi yang disetujui, dan saldo yang masih tertunda.

---

## 🏗️ Arsitektur & Teknologi

Kami menggunakan pendekatan teknologi yang ringan, cepat, dan terarah tanpa harus menggunakan implementasi *TALL stack* penuh.

| Lapisan Sistem | Teknologi | Kegunaan Utama |
| :--- | :--- | :--- |
| **Backend & API** | Laravel `^10.10`, PHP `^8.1` | Fondasi utama yang dilengkapi dengan *Service Layer* tangguh. |
| **Frontend Logic** | Alpine.js `^3.15.12`, Vanilla JS | Menyajikan interaktivitas yang gesit tanpa membebani DOM. |
| **Styling & UI** | Tailwind CSS `^3.4.19`, Vite `^5` | Desain *Glassmorphism* premium (Primary Blue `#234cf0` & Accent Yellow `#f4fe00`). |

---

## 🛡️ Standar Keamanan (Security Boundaries)

Sistem ini dirancang siap untuk skala *production* dengan fokus pada integritas data:

* ✅ **Pencegahan Race Condition:** Memanfaatkan *Service Layer* (`CommissionCalculator`) yang dilengkapi `DB::transaction()`, mekanisme penguncian baris `lockForUpdate()`, dan *unique key* ganda untuk memastikan keamanan *webhook*.
* ✅ **Pencegahan IDOR:** Melindungi alur *payout* dengan dua lapis validasi ketat: otorisasi *tenant-aware* di `FormRequest` dan pengecekan di tingkat *controller*.
* ✅ **API Gatekeeper:** Menggunakan *middleware* khusus `VendorApiKey` untuk memvalidasi setiap *request* yang masuk melalui header `X-API-KEY`.
* ✅ **Model Security:** Melindungi sistem dari kerentanan *mass assignment* dengan menerapkan atribut `$fillable` secara eksplisit pada semua model.

---

## 🔌 Panduan Integrasi SDK (Untuk Vendor)

<details>
<summary><b>Klik di sini untuk melihat cara memasang tracker ke website Anda</b></summary>
<br>

**1. Pasang Script Pelacak**
Sisipkan *snippet* berikut ke dalam tag `<head>` website Anda untuk menangkap identitas *referral* pengguna secara otomatis:

```html
<script src="[https://domain-affiliatekan.com/tracker.js](https://domain-affiliatekan.com/tracker.js)"></script>
2. Kirim Data Konversi
Saat pengguna berhasil melakukan transaksi, panggil fungsi SDK di bawah ini dengan memasukkan Order ID dan Nominal Transaksi yang relevan:

JavaScript
window.AffiliatekanTracker.trackConversion('ORDER-123', 500000);
💡 Catatan: Skrip tracker ini didesain efisien. Skrip akan otomatis membatalkan pengiriman data ke API jika tidak menemukan cookie affiliatekan_ref di browser pengguna.

🗺️ Roadmap Pengembangan
[ ] Menghubungkan layanan pencatatan klik server-side (TrackingService) agar terintegrasi penuh secara end-to-end dengan database click_logs.

[ ] Mengimplementasikan masa berlaku cookie dinamis yang dapat diatur dari kolom vendors.cookie_duration_days.

[ ] Menambahkan fitur outbound webhook callback bagi vendor menggunakan kolom webhook_url yang sudah terpasang pada skema.

📄 Lisensi
Perangkat lunak ini bersifat open-source dan didistribusikan di bawah Lisensi MIT.

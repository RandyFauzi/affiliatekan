<p align="center">
  <h1 align="center">🚀 Affiliatekan</h1>
</p>

<p align="center">
  <strong>Sistem Manajemen Afiliasi B2B (SaaS) Berbasis Open-Source</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.10-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10">
  <img src="https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.4.19-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3.15.12-8BC0D0?style=for-the-badge&logo=alpinejs&logoColor=white" alt="Alpine JS">
</p>

---

## 📖 Tentang Proyek

**Affiliatekan** adalah aplikasi internal *Affiliate Tracking SaaS B2B* yang dirancang dengan arsitektur modern untuk memfasilitasi manajemen afiliasi secara transparan dan efisien. Sistem ini menjembatani dua aktor utama dalam ekosistem pemasaran:
* **Vendor:** Dapat mengelola integrasi tracking, melihat API key, menyalin snippet `tracker.js`, dan memproses alur kerja payout (pembayaran komisi) secara manual.
* **Affiliate:** Diberdayakan dengan dashboard interaktif untuk memantau performa referral secara real-time, melihat jumlah klik, konversi, saldo komisi yang disetujui, serta saldo yang masih tertunda.

## ✨ Fitur & Kemampuan Inti

Proyek ini dibangun dengan fokus pada tiga *capability* arsitektural utama:
1.  **Tracking Identity Capture:** Melacak identitas referral pengguna menggunakan Vanilla JavaScript SDK mandiri melalui file `public/tracker.js`.
2.  **Webhook Conversion Ingestion:** Memproses data konversi masuk secara aman melalui endpoint API `POST /api/v1/conversions`.
3.  **Manual Payout Workflow:** Alur kerja antrean pembayaran komisi yang dikelola langsung dari dashboard vendor.

## 🛠 Tech Stack & UI/UX

Aplikasi ini tidak menggunakan implementasi TALL stack penuh, melainkan pendekatan yang lebih ringan dan spesifik:
* **Backend & API:** Laravel `^10.10` dengan PHP `^8.1`.
* **Frontend Interactivity:** Alpine.js `^3.15.12` dan Vanilla JS.
* **Styling & Bundling:** Tailwind CSS `^3.4.19` di-bundle menggunakan Vite `^5`.
* **UI Guidelines:** Mengusung desain *Glassmorphism* (elemen tembus pandang, shadow lembut, dan sudut membulat) dengan identitas warna utama Primary Blue (`#234cf0`) dan Accent Yellow (`#f4fe00`).

## 🛡️ Arsitektur & Keamanan (Security Boundaries)

Proyek ini menerapkan standar rekayasa perangkat lunak dan keamanan tingkat produksi:
* **Pencegahan Race Condition:** Menggunakan pola *Service Layer* (`CommissionCalculator`) yang dilengkapi `DB::transaction()`, mekanisme penguncian baris `lockForUpdate()`, dan *unique key* ganda untuk memastikan *idempotency* data saat konversi masuk melalui Webhook.
* **Pencegahan IDOR (Insecure Direct Object Reference):** Alur payout diamankan dengan dua lapis validasi; otorisasi *tenant-aware* pada `FormRequest` dan pengecekan level controller.
* **API Gatekeeper:** Melindungi endpoint *webhook* menggunakan middleware kustom `VendorApiKey` untuk memvalidasi header `X-API-KEY`.
* **Model Security:** Seluruh model domain memberlakukan atribut `$fillable` secara eksplisit untuk mencegah kerentanan *mass assignment*.

## 🚀 Panduan Integrasi SDK (Untuk Vendor)

Vendor dapat merekam klik dan konversi menggunakan skrip pelacak sisi klien (*client-side SDK*).

1. **Pasang Script Pelacak**
Sisipkan skrip berikut pada tag `<head>` website vendor:
```html
<script src="[https://domain-affiliatekan.com/tracker.js](https://domain-affiliatekan.com/tracker.js)"></script>

**window.AffiliatekanTracker.trackConversion('ORDER-123', 500000);**

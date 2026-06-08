# Konteks Proyek Affiliatekan

## 1. Ringkasan Proyek & Tech Stack

### Tujuan aplikasi
Affiliatekan adalah aplikasi internal **Affiliate Tracking SaaS B2B** yang memfasilitasi dua aktor utama:

- **Vendor**: mengelola integrasi tracking, melihat API key, menyalin snippet `tracker.js`, dan memproses payout manual untuk affiliate.
- **Affiliate**: memantau performa referral melalui dashboard yang menampilkan klik, konversi, saldo komisi approved, dan saldo pending.

Secara arsitektural, aplikasi ini berfokus pada tiga capability inti:

- **Tracking identity capture** melalui `public/tracker.js`
- **Webhook conversion ingestion** melalui endpoint API `POST /api/v1/conversions`
- **Manual payout workflow** melalui dashboard vendor

### Tech stack aktual dari source code
Stack yang benar-benar terdeteksi pada kode saat ini:

- **Backend Framework**: Laravel `^10.10`
- **PHP**: `^8.1`
- **Authentication**: Laravel session auth (`web`) + Sanctum tersedia untuk route default `/api/user`
- **Frontend Styling**: Tailwind CSS `^3.4.19`
- **Frontend Interactivity**: Alpine.js `^3.15.12`
- **Bundler / Asset Pipeline**: Vite `^5`
- **HTTP Client Utility**: Axios tersedia via `resources/js/bootstrap.js`
- **Vanilla JavaScript SDK**: `public/tracker.js`

### Klarifikasi penting terhadap asumsi awal
Ada beberapa perbedaan antara ekspektasi konseptual dan implementasi aktual:

- **Bukan Laravel 11**. Proyek ini saat ini berjalan di **Laravel 10**, terlihat dari `composer.json`.
- Istilah **TALL stack** hanya cocok sebagian. Yang benar untuk source ini adalah:
  - **Tailwind CSS**
  - **Alpine.js**
  - **Laravel**
  - **Tanpa Livewire**
- Integrasi SDK memang menggunakan **Vanilla JavaScript** melalui `public/tracker.js`.

Kesimpulan yang paling akurat: proyek ini adalah **Laravel 10 + Tailwind + Alpine.js + Vanilla JS SDK**, bukan implementasi TALL penuh.

## 2. Arsitektur & Pola Desain

### Gaya arsitektur umum
Kodebase ini mengikuti pola Laravel yang cukup terjaga:

- **Route** bertugas sebagai entry point
- **Controller** menangani orkestrasi request/response
- **Service layer** menaruh business logic yang layak dipisahkan dari controller
- **Model Eloquent** menangani persistence dan relasi
- **Middleware** dipakai untuk autentikasi kunci API vendor
- **FormRequest** dipakai untuk otorisasi + validasi alur payout manual

Pendekatan ini lebih maintainable dibanding menaruh semua logika dalam controller karena:

- controller tetap tipis dan fokus pada alur HTTP
- business rule lebih mudah diuji dan dipakai ulang
- validasi dan security boundary menjadi lebih eksplisit

### Service Pattern di `app/Services`

#### `App\Services\CommissionCalculator`
Service ini adalah inti business logic untuk pemrosesan konversi. Tanggung jawabnya:

- memvalidasi `vendor_order_id`
- memvalidasi `sale_amount`
- memuat affiliate berdasarkan `referral_code`
- mengunci baris vendor dengan `lockForUpdate()`
- menghitung komisi berdasarkan `commission_type`
- melakukan `updateOrCreate()` conversion berdasarkan kombinasi unik `vendor_id + vendor_order_id`

Ini adalah contoh pemisahan logic yang baik karena `WebhookController` tidak perlu memahami:

- aturan komisi flat vs percentage
- pencegahan duplikasi order
- race condition saat request masuk bersamaan

#### `App\Services\TrackingService`
Service ini sudah tersedia sebagai fondasi tracking klik. Tanggung jawabnya:

- membuat referral code dengan prefix `aff_`
- mencatat klik ke tabel `click_logs`
- mencegah spam klik duplikat berdasarkan kombinasi:
  - `affiliate_id`
  - `vendor_id`
  - `ip_address`
  - jendela waktu 5 menit

Namun secara aktual, service ini **belum terhubung ke route atau controller mana pun**. Artinya:

- logika anti-spam klik sudah ada
- tetapi flow click logging server-side belum benar-benar aktif pada aplikasi saat ini

### Strategi controller tetap tipis

#### Controller yang sudah cukup tipis
- `Api\WebhookController`
- `Vendor\IntegrationController`
- `Vendor\PayoutController`
- `Affiliate\DashboardController`

Controller-controller tersebut mayoritas:

- mengambil context user/vendor/affiliate
- memanggil service atau query Eloquent sederhana
- mengembalikan view atau JSON response

#### Alasan ini baik
Pendekatan ini lebih efisien daripada fat controller karena:

- perubahan business rule tidak memaksa perubahan layer HTTP
- query dan aturan domain tidak tercampur dengan render response
- onboarding developer baru lebih cepat karena boundary setiap file cukup jelas

### Validasi request

#### FormRequest kustom
`App\Http\Requests\StoreManualPayoutRequest` dipakai untuk alur payout manual.

Perannya:

- **authorization layer**: memastikan vendor hanya boleh mengakses payout miliknya sendiri
- **validation layer**: memastikan file bukti transfer wajib ada dan hanya menerima `jpeg`, `png`, `pdf` dengan maksimum 2 MB

Ini adalah implementasi yang baik karena otorisasi objek dilakukan sebelum controller memproses file upload.

#### Validasi inline
Masih ada validasi inline memakai `$request->validate()` pada:

- `AuthenticatedSessionController::store()`
- `WebhookController::store()`

Artinya strategi validasi proyek saat ini adalah **campuran**:

- **FormRequest** untuk flow yang berisiko terhadap akses data lintas tenant
- **inline validation** untuk flow yang masih sederhana

### Middleware kustom

#### `App\Http\Middleware\VendorApiKey`
Middleware ini menjadi gatekeeper untuk endpoint webhook conversion.

Tanggung jawabnya:

- membaca header `X-API-KEY`
- mencari vendor berdasarkan `api_key`
- menolak request dengan JSON `401 Unauthorized` bila key kosong atau tidak valid
- menyimpan `vendor_id` ke request attributes agar dapat dipakai controller/service berikutnya

Ini menjaga controller tetap bersih karena lookup vendor berdasarkan API key tidak dilakukan berulang di setiap action API.

## 3. Struktur Aplikasi

### Route utama

#### Web routes
File: `routes/web.php`

Route yang aktif:

- `GET /`
  - redirect ke dashboard sesuai role jika user sudah login
  - kalau belum login menampilkan landing page `welcome`
- `GET /login`
- `POST /login`
- `POST /logout`
- `GET /affiliate/dashboard`
- `GET /vendor/integration`
- `GET /vendor/payouts`
- `POST /vendor/payouts/{payoutId}/pay-manual`

#### API routes
File: `routes/api.php`

Route yang aktif:

- `POST /api/v1/conversions`
  - dilindungi middleware `vendor.api.key`

### Middleware groups

#### `web`
Menggunakan middleware standar Laravel:

- cookie encryption
- session
- CSRF
- route bindings

#### `api`
Menggunakan:

- `ThrottleRequests:api`
- route bindings

Rate limit `api` didefinisikan di `App\Providers\RouteServiceProvider`:

- `60 request / menit`
- key by:
  - `user()->id` jika ada
  - fallback ke `ip()`

## 4. Skema Database & Model Eloquent

## Prinsip model security
Seluruh model domain utama menggunakan **`$fillable` eksplisit**, bukan `$guarded = []`.

Ini adalah keputusan yang tepat karena:

- mencegah mass assignment tak disengaja
- membuat field yang boleh ditulis lebih eksplisit
- aman untuk flow `create()`, `update()`, dan `updateOrCreate()`

### Tabel `users`
Migration: `2014_10_12_000000_create_users_table.php`

Kolom penting:

- `id`
- `name`
- `email`
- `email_verified_at`
- `password`
- `role` enum: `admin`, `vendor`, `affiliate`
- `remember_token`
- `created_at`, `updated_at`

Model: `App\Models\User`

Relasi:

- `hasOne(Vendor::class)`
- `hasOne(Affiliate::class)`

Catatan:

- role digunakan untuk redirect dashboard setelah login
- tidak ada policy class terpisah; kontrol akses berbasis role dilakukan di controller/flow saat ini

### Tabel `vendors`
Migration: `2026_05_28_100001_create_vendors_table.php`

Kolom penting:

- `id`
- `user_id`
- `company_name`
- `api_key`
- `webhook_url`
- `commission_type` enum: `flat`, `percentage`
- `commission_value`
- `cookie_duration_days`
- timestamps

Model: `App\Models\Vendor`

Relasi:

- `belongsTo(User::class)`
- `hasMany(ClickLog::class)`
- `hasMany(Conversion::class)`
- `hasMany(Payout::class)`

Perilaku model:

- `booted()->creating()` mengisi `api_key` otomatis jika kosong
- format API key: prefix `vnd_` + random 40 karakter

Catatan penting:

- `cookie_duration_days` sudah ada di schema, tetapi **belum dipakai** oleh `tracker.js`
- `webhook_url` juga sudah ada, tetapi **belum dipakai** dalam flow runtime saat ini

### Tabel `affiliates`
Migration: `2026_05_28_100002_create_affiliates_table.php`

Kolom penting:

- `id`
- `user_id`
- `referral_code`
- `bank_name`
- `bank_account_number`
- `bank_account_name`
- timestamps

Model: `App\Models\Affiliate`

Relasi:

- `belongsTo(User::class)`
- `hasMany(ClickLog::class)`
- `hasMany(Conversion::class)`
- `hasMany(Payout::class)`

Catatan:

- referral code adalah identity utama affiliate di flow tracking
- generator referral code disiapkan oleh `TrackingService` dengan prefix `aff_`

### Tabel `click_logs`
Migration: `2026_05_28_100003_create_click_logs_table.php`

Kolom penting:

- `id`
- `affiliate_id`
- `vendor_id`
- `ip_address`
- `user_agent`
- `referer_url`
- `clicked_at`

Index:

- index pada `affiliate_id, clicked_at`

Model: `App\Models\ClickLog`

Relasi:

- `belongsTo(Affiliate::class)`
- `belongsTo(Vendor::class)`

Catatan implementasi:

- model mengizinkan field `referer_url` di `$fillable`
- tetapi `TrackingService::recordClick()` saat ini hanya menyimpan:
  - `affiliate_id`
  - `vendor_id`
  - `ip_address`
  - `user_agent`
  - `clicked_at`
- `referer_url` **belum benar-benar diisi**

### Tabel `conversions`
Migration: `2026_05_28_100004_create_conversions_table.php`

Kolom penting:

- `id`
- `vendor_id`
- `affiliate_id`
- `vendor_order_id`
- `sale_amount`
- `commission_amount`
- `status` enum: `pending`, `approved`, `rejected`, `paid`
- timestamps

Constraint:

- unique composite: `vendor_id + vendor_order_id`

Model: `App\Models\Conversion`

Relasi:

- `belongsTo(Vendor::class)`
- `belongsTo(Affiliate::class)`

Catatan:

- unique key ini adalah lapisan penting untuk idempotency webhook
- `sale_amount` dan `commission_amount` di-cast ke `decimal:2`

### Tabel `payouts`
Migration: `2026_05_28_100005_create_payouts_table.php`

Kolom penting:

- `id`
- `affiliate_id`
- `vendor_id`
- `amount`
- `status` enum: `requested`, `processing`, `paid`
- `proof_of_transfer_path`
- `notes`
- timestamps

Model: `App\Models\Payout`

Relasi:

- `belongsTo(Affiliate::class)`
- `belongsTo(Vendor::class)`

Catatan:

- UI saat ini mengutamakan status `requested` untuk antrean
- controller manual payout langsung mengubah status ke `paid`
- status `processing` sudah tersedia di schema tetapi belum dipakai di flow controller saat ini

## 5. Relasi Antar Model

Relasi domain inti adalah:

- `User` -> `Vendor`: one-to-one
- `User` -> `Affiliate`: one-to-one
- `Vendor` -> `ClickLog`: one-to-many
- `Vendor` -> `Conversion`: one-to-many
- `Vendor` -> `Payout`: one-to-many
- `Affiliate` -> `ClickLog`: one-to-many
- `Affiliate` -> `Conversion`: one-to-many
- `Affiliate` -> `Payout`: one-to-many
- `ClickLog` -> `Vendor`: many-to-one
- `ClickLog` -> `Affiliate`: many-to-one
- `Conversion` -> `Vendor`: many-to-one
- `Conversion` -> `Affiliate`: many-to-one
- `Payout` -> `Vendor`: many-to-one
- `Payout` -> `Affiliate`: many-to-one

Interpretasi bisnisnya:

- satu vendor memiliki banyak transaksi konversi dan payout
- satu affiliate memiliki banyak klik, conversion, dan payout
- conversion selalu milik tepat satu vendor dan satu affiliate

## 6. Alur Kerja Inti

## 6.1 Flow Tracking

### Flow aktual di `public/tracker.js`
SDK frontend berjalan sebagai IIFE dan mem-publish global `window.AffiliatekanTracker`.

Konstanta penting:

- cookie name: `affiliatekan_ref`
- query parameter: `ref`
- default API endpoint: `https://api.affiliatekan.com/v1/conversions`

### Langkah flow
1. Script memanggil `captureClick()` saat file dimuat.
2. `captureClick()` membaca query parameter `?ref=` dari URL aktif.
3. Jika parameter tidak ada, function berhenti tanpa melakukan apa pun.
4. Jika ada, nilai referral disimpan ke cookie `affiliatekan_ref`.
5. Cookie ditulis dengan:
   - `expires=<30 hari>`
   - `path=/`
   - `SameSite=None`
   - `Secure`
6. Script mengekspor object:
   - `captureClick()`
   - `trackConversion(orderId, amount)`

### Klarifikasi keamanan dan domain cookie
Implementasi saat ini **mengarah ke cross-site compatibility**, tetapi tidak sepenuhnya cross-domain sharing. Alasannya:

- cookie memakai `SameSite=None; Secure`
- tetapi **tidak menetapkan atribut `domain=`**

Konsekuensinya:

- cookie scoped ke host yang menulis cookie
- cookie tidak otomatis dibagikan ke sibling subdomain lain
- istilah "lintas domain" perlu dibaca sebagai **siap untuk konteks HTTPS cross-site**, bukan shared cookie antar banyak domain secara penuh

### Flow konversi dari SDK
Saat `AffiliatekanTracker.trackConversion(orderId, amount)` dipanggil:

1. script membaca konfigurasi dari:
   - `window.AffiliatekanTrackerConfig`
   - atau atribut `data-api-endpoint`
   - atau atribut `data-api-key`
2. script membaca cookie `affiliatekan_ref`
3. jika cookie referral tidak ada, function mengembalikan `Promise.resolve(false)`
4. jika API key kosong, function juga berhenti
5. jika lengkap, SDK mengirim `POST` ke endpoint API conversion
6. header yang dikirim:
   - `Content-Type: application/json`
   - `Accept: application/json`
   - `X-API-KEY: <vendor api key>`
7. payload JSON:
   - `affiliate_code`
   - `vendor_order_id`
   - `sale_amount`

### Rate limiting yang benar-benar ada
Rate limiting yang ada pada kode saat ini adalah:

- **Laravel API throttle** `60 request/menit`
- diterapkan pada group `api`
- otomatis melindungi `POST /api/v1/conversions`

### Catatan gap penting
Ada dua lapisan tracking yang berbeda:

- **client-side referral capture** di `tracker.js`
- **server-side click logging** di `TrackingService`

Namun saat ini:

- `tracker.js` **tidak mengirim event klik ke backend**
- `TrackingService::recordClick()` **belum dihubungkan ke route mana pun**

Artinya:

- cookie referral benar-benar berfungsi
- conversion posting benar-benar berfungsi
- click logging ke tabel `click_logs` **belum aktif end-to-end**

## 6.2 Flow Webhook & Konversi

### Entry point
Route:

- `POST /api/v1/conversions`

Middleware:

- `vendor.api.key`

Controller:

- `App\Http\Controllers\Api\WebhookController::store()`

### Langkah flow
1. Request masuk ke route API versi `v1`.
2. Middleware `VendorApiKey` membaca header `X-API-KEY`.
3. Jika header kosong atau API key tidak cocok dengan vendor mana pun:
   - response JSON `401`
   - flow berhenti
4. Jika valid:
   - middleware menaruh `vendor_id` pada request attributes
5. Controller memvalidasi payload:
   - `affiliate_code` wajib string
   - `vendor_order_id` wajib string, max 255
   - `sale_amount` wajib numeric
6. Controller meneruskan data ke `CommissionCalculator::processConversion()`.
7. Service menjalankan `DB::transaction(...)`.
8. Di dalam transaksi:
   - affiliate dicari berdasarkan `referral_code`
   - vendor dikunci memakai `lockForUpdate()`
   - nilai sale dinormalisasi ke 2 digit desimal
   - komisi dihitung sesuai `commission_type`
   - conversion disimpan via `updateOrCreate()`
9. Response JSON sukses dikembalikan dengan status `201`.

### Pencegahan race condition
Pencegahan race condition dilakukan lewat kombinasi:

- `DB::transaction()`
- `lockForUpdate()` pada vendor
- unique key `vendor_id + vendor_order_id`
- `updateOrCreate()` berdasarkan dua field unik tersebut

Nilai desain ini sangat baik karena:

- request paralel untuk order yang sama tidak mudah membuat duplikasi conversion
- pembacaan konfigurasi komisi vendor terjadi dalam scope transaksi
- idempotency lebih kuat dibanding insert biasa

### Aturan perhitungan komisi

#### Jika `commission_type = flat`
Komisi = `commission_value`

#### Jika `commission_type = percentage`
Komisi = `(sale_amount * commission_value) / 100`

#### Jika tipe tidak dikenali
Service melempar `InvalidArgumentException`

### Status awal conversion
Setiap conversion yang diproses service akan disimpan dengan status awal:

- `pending`

Tidak ada flow approval/rejection terpisah di kode yang tersedia saat ini.

## 6.3 Flow Payout

### Entry point
Route:

- `POST /vendor/payouts/{payoutId}/pay-manual`

Controller:

- `Vendor\PayoutController::payManual()`

Validation:

- `StoreManualPayoutRequest`

### Cara sistem mencegah IDOR
Pencegahan IDOR diimplementasikan dengan dua lapis:

#### Lapis 1: `FormRequest::authorize()`
`StoreManualPayoutRequest`:

- mengambil `vendor_id` dari user yang sedang login
- mengambil `payoutId` dari route parameter
- mengecek keberadaan payout dengan kondisi:
  - primary key cocok
  - `vendor_id` payout cocok dengan vendor login

Jika tidak cocok, request tidak diotorisasi.

#### Lapis 2: query ulang di controller
Controller tetap melakukan query:

- `where('vendor_id', $authenticatedVendor->id)->findOrFail($payoutId)`

Ini adalah defensive programming yang baik karena:

- walaupun FormRequest sudah memfilter akses
- controller tetap tidak percaya sepenuhnya pada input route

### Langkah flow payout manual
1. Vendor membuka halaman `/vendor/payouts`.
2. Controller memuat semua payout milik vendor login.
3. Data diurutkan agar status `requested` tampil paling atas.
4. UI menampilkan tombol `Bayar Manual` hanya untuk payout berstatus `requested`.
5. Vendor membuka modal Alpine.js.
6. Vendor mengunggah bukti transfer.
7. FormRequest memvalidasi file.
8. Controller menyimpan file ke storage disk `public` pada folder `payout-proofs`.
9. Controller meng-update payout:
   - `proof_of_transfer_path`
   - `status = paid`
10. User di-redirect kembali dengan flash message sukses.

### Kelebihan desain ini
Pendekatan ini lebih maintainable daripada update langsung tanpa FormRequest karena:

- otorisasi tenant-aware dilakukan lebih awal
- upload validation tidak bercampur dengan business logic
- controller tetap fokus pada persistence akhir

## 7. Dashboard & Query Bisnis

### Affiliate dashboard
Controller: `Affiliate\DashboardController`

Metrik yang dihitung:

- `total_clicks` = jumlah click logs affiliate
- `total_conversions` = jumlah conversion berstatus `approved` atau `pending`
- `available_balance` = sum `commission_amount` untuk status `approved`
- `pending_balance` = sum `commission_amount` untuk status `pending`

Catatan:

- status `paid` tidak ikut `available_balance`
- ini berarti secara bisnis, payout settlement belum direkonsiliasi ke saldo affiliate di dashboard

### Vendor payout dashboard
Controller: `Vendor\PayoutController::index()`

Perilaku query:

- eager load `affiliate.user`
- filter berdasarkan `vendor_id` milik vendor login
- `requested` didorong ke urutan teratas dengan `orderByRaw(...)`
- lalu diurutkan `latest()`

## 8. Frontend & UI Guidelines

### Asset pipeline
Aplikasi menggunakan **Vite** melalui:

- `resources/css/app.css`
- `resources/js/app.js`

Konfigurasi ada di `vite.config.js` dengan plugin `laravel-vite-plugin`.

### Tailwind usage
Tailwind dipakai secara utility-first langsung di Blade. Belum ada layer component CSS kustom besar. `app.css` hanya memuat:

- `@tailwind base`
- `@tailwind components`
- `@tailwind utilities`
- rule `[x-cloak]`

### Font
Font utama:

- **Manrope**

Dikonfigurasi di `tailwind.config.js` dan di-load via Bunny Fonts.

### Palet warna identitas
Warna dominan yang konsisten di view:

- **Primary Blue**: `#234cf0`
- **Accent Yellow**: `#f4fe00`

Warna pendukung:

- background gradient biru muda ke kuning muda
- slate untuk teks utama
- emerald untuk feedback sukses
- rose untuk feedback error

### Gaya UI yang wajib diikuti
Gaya visual aktual proyek sangat jelas:

- **Glassmorphism**
  - `bg-white/30`
  - `border border-white/20`
  - `backdrop-blur-md`
- **Soft shadows**
  - `shadow-lg`
  - kadang `hover:shadow-xl`
- **Rounded premium cards**
  - radius besar seperti `rounded-[28px]`, `rounded-[32px]`, `rounded-[36px]`
- **Bright CTA**
  - tombol biru solid untuk aksi utama
  - tombol kuning untuk aksi sekunder/highlight

Panduan implementasi UI lanjutan:

- pertahankan layout yang airy, tidak padat
- gunakan panel semi-transparan ketimbang blok flat polos
- pakai blur dan border putih tipis untuk depth
- pertahankan identitas biru-kuning sebagai bahasa visual utama

### Background system
Layout marketing dan dashboard memakai background gradient:

- radial highlight biru di area atas
- linear gradient dari biru sangat muda ke kuning sangat muda

Ini adalah bagian identitas visual sistem, bukan dekorasi opsional.

### Penggunaan Alpine.js
Alpine dipakai ringan dan tepat sasaran, bukan untuk SPA state besar.

#### `copyablePanel(...)`
Didaftarkan di `resources/js/app.js`.

Dipakai untuk:

- copy API key vendor
- copy snippet `tracker.js`
- copy referral code affiliate

Behavior:

- menyalin ke clipboard
- menampilkan feedback message sementara
- fallback error message jika clipboard API gagal

#### Modal payout manual
Di `resources/views/components/vendor/payout-queue-table.blade.php`, Alpine dipakai dengan:

- `x-data="{ open: false }"`
- `x-show`
- `x-transition`
- `@click.away`

Peran Alpine di sini:

- membuka dan menutup modal popup
- mengelola transisi visual
- menjaga interaktivitas tetap sederhana tanpa framework frontend berat

### Catatan penting
Tidak ada Livewire, Vue, atau React pada flow UI ini. Interaktivitas memang sengaja dibuat ringan agar:

- deployment tetap sederhana
- surface area bug frontend lebih kecil
- Blade tetap menjadi rendering layer utama

## 9. Daftar Variabel Global & Nomenklatur Penting

### Header API
- `X-API-KEY`

### Cookie tracking
- `affiliatekan_ref`

### Query parameter referral
- `ref`

### Prefix referral code
- `aff_`

### Prefix API key vendor
- `vnd_`

### Default endpoint SDK
- `https://api.affiliatekan.com/v1/conversions`

### Nama helper global frontend
- `window.AffiliatekanTracker`
- `window.AffiliatekanTrackerConfig`
- `window.copyablePanel`

## 10. Security Boundary yang Sudah Ada

### Sudah ada
- explicit `$fillable` pada model
- auth session untuk dashboard internal
- `VendorApiKey` middleware untuk API vendor
- `FormRequest::authorize()` untuk mencegah IDOR payout
- file upload dibatasi mime type dan size
- rate limiting default Laravel pada route API
- transaction + row lock + unique key untuk conversion idempotency

### Belum ada / belum terlihat di kode ini
- policy/gate class spesifik per model
- hashing atau rotation workflow khusus untuk `api_key`
- audit log admin/vendor actions
- approval workflow terpisah untuk conversion dan payout
- server-side endpoint untuk click ingest

## 11. Gap dan Catatan Penting untuk AI/Developer Berikutnya

### 1. Tracking klik belum end-to-end
`TrackingService` sudah ada, tetapi belum digunakan oleh route/controller mana pun. Jadi tabel `click_logs` belum pasti terisi dari flow nyata.

### 2. Cookie vendor duration belum dipakai
Kolom `vendors.cookie_duration_days` belum dihubungkan ke `tracker.js`, yang saat ini hardcoded `30` hari.

### 3. `referer_url` belum diisi
Schema dan model mendukung `referer_url`, tetapi service pencatatan klik belum menulis field itu.

### 4. Laravel version berbeda dari asumsi
Jika ada dokumentasi eksternal yang menyebut Laravel 11, itu perlu diperbarui. Kode aktual adalah Laravel 10.

### 5. Demo account belum terlihat disediakan oleh seeder
View login menampilkan:

- `vendor@test.com / password`
- `aff@test.com / password`

Tetapi `DatabaseSeeder` masih kosong. Jadi akun demo ini kemungkinan:

- dibuat manual di database
- atau belum dibuat sama sekali

AI/developer berikutnya jangan mengasumsikan akun demo pasti tersedia tanpa verifikasi database.

### 6. Status `processing` payout belum digunakan
Enum status ada di database, tetapi controller payout manual saat ini melompat langsung dari `requested` ke `paid`.

### 7. `webhook_url` vendor belum dipakai
Kolom ada di schema, tetapi belum ada flow outbound webhook atau callback yang memakainya.

## 12. Ringkasan Arsitektural

Secara keseluruhan, Affiliatekan saat ini adalah fondasi yang cukup bersih untuk sistem affiliate internal:

- backend Laravel masih sederhana dan mudah dipahami
- service layer sudah mulai dipisah dengan baik
- dashboard vendor dan affiliate sudah usable
- alur conversion sudah paling matang secara domain dan concurrency
- alur click tracking masih setengah jalan
- alur payout sudah aman dari IDOR dasar

Keputusan arsitektural yang paling kuat di codebase ini adalah pemisahan logika komisi ke service serta penggunaan transaksi dan row locking untuk webhook conversion. Itu jauh lebih maintainable dan aman dibanding pendekatan controller-heavy biasa.

Sebaliknya, area yang masih perlu dilengkapi adalah penyambungan click logging ke flow nyata, pemanfaatan `cookie_duration_days`, dan pelengkapan lifecycle payout/conversion agar lebih production-grade.

## 13. Konfigurasi SMTP Email & Sandi Aplikasi (Gmail)

Untuk keperluan pengiriman email (seperti reset password), sistem menggunakan Gmail dengan konfigurasi sebagai berikut:
- **Email/Username**: `autogrowthid@gmail.com`
- **Sandi Aplikasi (App Password)**: `fpll yhqb ubdn smee` (ditulis rapat di `.env` sebagai `fpllyhqbubdnsmee`)
- **SMTP Host**: `smtp.gmail.com`
- **SMTP Port**: `465`
- **Encryption**: `ssl`


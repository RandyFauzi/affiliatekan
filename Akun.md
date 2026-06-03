# Daftar Akun Affiliatekan

Catatan penting:

- Password yang tersimpan di database Laravel berbentuk **hash**, jadi **tidak bisa dibaca kembali** dalam bentuk plaintext.
- File ini hanya menuliskan password yang memang **diketahui dari proses setup / demo account**, bukan hasil dekripsi database.
- Jika ada akun dengan password tidak diketahui, solusinya adalah **reset password**, bukan membaca hash lama.

## Akun Diketahui

| ID | Nama | Role | Email | Password |
| --- | --- | --- | --- | --- |
| 1 | Toko Onfix | vendor | `vendor@test.com` | `password` |
| 2 | Afiliator Jago | affiliate | `aff@test.com` | `password` |
| 3 | Super Admin | admin | `admin@affiliatekan.com` | `password123` |

## Akun Ada di Database, Password Tidak Diketahui

| ID | Nama | Role | Email | Password |
| --- | --- | --- | --- | --- |
| 4 | Budi | vendor | `budi@tokoikan.com` | Tidak diketahui dari database |
| 5 | Jago Jualan | affiliate | `jago@affiliate.com` | Tidak diketahui dari database |

## Ringkasan

- Total akun di database: `5`
- Akun vendor: `2`
- Akun affiliate: `2`
- Akun admin: `1`

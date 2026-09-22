# TrendUp — Laravel (struktur)

Project statis HTML/CSS "TrendUp" sudah dikonversi ke **struktur Laravel**: routing +
Blade views. Belum ada database/backend (auth, cart tersimpan di server, CRUD admin, dll) —
sesuai permintaan, ini baru tahap "struktur".

## Apa yang berubah

| Sebelumnya (statis)                          | Sekarang (Laravel)                                   |
|-----------------------------------------------|-------------------------------------------------------|
| `trendup_landingpage/beranda.html`             | `resources/views/site/beranda.blade.php` → route `/` |
| `trendup_shop/shop.html`                       | `resources/views/site/shop.blade.php` → `/shop`      |
| `trendup_cart/cart.html`                       | `resources/views/site/cart.blade.php` → `/cart`      |
| `trendup_checkout/checkout.html`               | `resources/views/site/checkout.blade.php` → `/checkout` |
| `trendup_login/login.html`                     | `resources/views/site/login.blade.php` → `/login`    |
| `trendup.register/register.html`               | `resources/views/site/register.blade.php` → `/register` |
| `trendup_wishlist/wishlist.html`                | `resources/views/site/wishlist.blade.php` → `/wishlist` |
| `trendup_tracking/tracking.html`                | `resources/views/site/tracking.blade.php` → `/tracking` |
| `trendup.rolex/rolex.html`                      | `resources/views/site/rolex.blade.php` → `/produk-detail?id=...` |
| `dashboard_admin/dashboard.html`                | `resources/views/admin/dashboard.blade.php` → `/admin/dashboard` |
| `dashboard_admin/produk.html`                   | `resources/views/admin/produk.blade.php` → `/admin/produk` |
| `dashboard_admin/kategori.html`                 | `resources/views/admin/kategori.blade.php` → `/admin/kategori` |
| `dashboard_admin/pesanan.html`                  | `resources/views/admin/pesanan.blade.php` → `/admin/pesanan` |
| `dashboard_admin/pelanggan.html`                | `resources/views/admin/pelanggan.blade.php` → `/admin/pelanggan` |
| `dashboard_admin/laporan.html`                  | `resources/views/admin/laporan.blade.php` → `/admin/laporan` |
| `dashboard_admin/pengaturan.html`               | `resources/views/admin/pengaturan.blade.php` → `/admin/pengaturan` |

- Semua CSS masing-masing halaman dipindah ke `public/css/` (admin di `public/css/admin/`)
  dan dipanggil lewat `{{ asset('css/...') }}`.
- Semua link antar halaman (`href="...html"`) diganti jadi `{{ route('nama.route') }}`,
  jadi kalau nanti path/nama route berubah, cukup diubah di `routes/web.php`.
- Semua `<img src="../gambar/...">` dan data produk JS diarahkan ke `asset('images/...')`
  / `/images/...` — lihat `public/images/README.md`, **gambar asli perlu Anda salin manual**
  karena file `repomix-output.xml` yang di-upload cuma berisi teks/kode, bukan file gambar.
- Controller (`app/Http/Controllers/Site/*`, `app/Http/Controllers/Admin/*`) saat ini
  cuma `return view(...)` — belum ada logic apa pun. Tinggal isi sendiri saat butuh backend.
- JS asli (cart di localStorage, filter produk, dsb) **tidak diubah** — masih jalan persis
  seperti versi HTML lama karena itu murni client-side.

## Menjalankan project

Project ini dibuat manual (bukan lewat `composer create-project`), jadi folder
`vendor/` **belum ada** — environment saya tidak punya akses ke Packagist untuk
`composer install`. Langkah di komputer Anda:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Lalu buka `http://127.0.0.1:8000`.

## Struktur route (`routes/web.php`)

```
GET  /                    home
GET  /shop                shop
GET  /produk-detail        product.detail   (pakai query ?id=... sama seperti versi HTML asli)
GET  /cart                 cart
GET  /checkout              checkout
GET  /wishlist              wishlist
GET  /tracking               tracking
GET  /login                  login
GET  /register                register

GET  /admin/dashboard        admin.dashboard
GET  /admin/produk           admin.produk
GET  /admin/kategori         admin.kategori
GET  /admin/pesanan          admin.pesanan
GET  /admin/pelanggan        admin.pelanggan
GET  /admin/laporan          admin.laporan
GET  /admin/pengaturan       admin.pengaturan
```

## Yang belum ada (langkah lanjut kalau mau full fungsional)

- Database & migration (produk, kategori, pesanan, pelanggan, user).
- Auth beneran (saat ini `/login` & `/register` cuma tampilan, submit form tidak
  memproses apa-apa ke server).
- Cart & checkout yang tersambung ke database (sekarang cart masih di-handle JS/localStorage
  seperti versi HTML asli).
- Middleware `auth`/role admin untuk melindungi rute `/admin/*` (sekarang masih terbuka
  bebas, tidak ada proteksi).
- CRUD sungguhan di panel admin (produk, kategori, pesanan, pelanggan, pengaturan, laporan).

Kalau nanti Anda mau lanjut ke salah satu bagian di atas, tinggal minta — saya bisa bikin
migration + model + controller CRUD-nya bertahap.

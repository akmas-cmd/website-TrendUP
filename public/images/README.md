# Folder gambar

File gambar asli (`.png`/`.jpg`) tidak ikut ter-export di file `repomix-output.xml` yang
Anda upload (repomix hanya menyertakan isi teks/kode, bukan file binary seperti gambar).

Semua `<img>` dan data produk di Blade view sudah diarahkan ke folder ini lewat
`asset('images/nama-file.png')`, jadi Anda tinggal:

1. Salin ulang semua file gambar asli dari folder `gambar/` project lama ke sini
   (`public/images/`), termasuk: `trend.png`, `hero.png`, `model.png`, `london.png`,
   `rolex.png`, `richard.png`, `jddseiko.png`, `P-6000.png`, `fredperry.png`,
   `ralph_lauren.png`.
2. Untuk gambar produk di `resources/views/site/shop.blade.php` (data JS `produk-xxx.jpg`),
   tambahkan file dengan nama yang sama persis, atau ganti path-nya sesuai gambar yang Anda
   punya.

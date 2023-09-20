# Nama Proyek Laravel

Proyek ini adalah sebuah sistem backend dari aplikasi message board menggunakan Laravel sebagai framework yang digunakan dalam proses pengembangan.

## Pesifikasi

- PHP versi 8.1.17
- Composer - [Instalasi](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- Node.js dan npm - [Instalasi](https://nodejs.org/)

## Instalasi

1. Clone repositori ini:

```bash
git clone https://github.com/MRienald/lokadata-test-laravel.git
```

2. Pindah ke direktori proyek:

```bash
cd lokadata-test-laravel
```

3. Instal dependensi dengan Composer:

```bash
composer install
```

4. Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database:

```bash
cp .env.example .env
```

5. Generate kunci aplikasi:

```bash
php artisan key:generate
```

6. Jalankan migrasi untuk membuat skema database:

```bash
php artisan migrate
```

7. (Opsional) Jalankan seeders untuk memasukkan data awal:

```bash
php artisan db:seed
```

8. Compile aset dengan npm:

```bash
npm install && npm run dev
```

9. Jalankan server Laravel:

```bash
php artisan serve
```

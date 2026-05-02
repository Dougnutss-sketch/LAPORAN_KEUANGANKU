# Aplikasi Web Manajemen Keuangan Pribadi

Aplikasi sederhana untuk mencatat pemasukan dan pengeluaran menggunakan PHP dan MySQL.

## Struktur Folder

```
LAPORAN KEUANGANKU/
├── assets/
│   ├── app.js
│   └── styles.css
├── config/
│   ├── auth.php
│   └── db.php
├── pages/
│   ├── transaction_form.php
│   └── transaction_list.php
├── database.sql
├── index.php
├── login.php
├── logout.php
└── README.md
```

## Fitur

- Tambah pemasukan dan pengeluaran
- Tanggal transaksi
- Jumlah dan keterangan
- Tabel daftar transaksi
- Edit dan hapus transaksi
- Filter transaksi berdasarkan tanggal
- Total pemasukan, total pengeluaran, saldo akhir
- Login sederhana
- Validasi input
- Notifikasi sukses / gagal
- Tampilan responsif

## Setup MySQL

1. Buka phpMyAdmin atau MySQL CLI.
2. Jalankan `database.sql` untuk membuat database dan tabel:
   - `CREATE DATABASE IF NOT EXISTS keuangan_db ...`
3. Pastikan database sudah tersedia.

## Cara Menjalankan di XAMPP / Laragon

1. Letakkan folder `LAPORAN KEUANGANKU` di dalam direktori web server:
   - XAMPP: `C:\xampp\htdocs\`
   - Laragon: `C:\laragon\www\`
2. Buka browser dan akses:
   - `http://localhost/LAPORAN KEUANGANKU`
3. Login menggunakan:
   - Username: `admin`
   - Password: `admin123`
4. Gunakan form untuk menambah atau mengedit transaksi.

## Konfigurasi Database

- Edit file `config/db.php` jika username, password, atau host MySQL berbeda.

## Catatan

- Data transaksi tersimpan di tabel MySQL `keuangan_db.transaksi`.
- Login menggunakan kredensial sederhana yang dikodekan di `config/auth.php`.
- Untuk memperkuat keamanan, ganti login sederhana dengan tabel `users` dan hashing password.

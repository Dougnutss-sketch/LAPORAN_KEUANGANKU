-- Script SQL untuk membuat database dan tabel keuangan
CREATE DATABASE IF NOT EXISTS keuangan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE keuangan_db;

-- Tabel users untuk menyimpan data user/akun
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel kategori untuk jenis transaksi
CREATE TABLE IF NOT EXISTS kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(50) NOT NULL UNIQUE,
  tipe ENUM('pemasukan', 'pengeluaran') NOT NULL,
  warna VARCHAR(7) DEFAULT '#3498db',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel transaksi dengan referensi kategori (tanpa enforced user constraint untuk compatibility)
CREATE TABLE IF NOT EXISTS transaksi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  kategori_id INT DEFAULT NULL,
  tipe ENUM('pemasukan', 'pengeluaran') NOT NULL,
  tanggal DATE NOT NULL,
  jumlah DECIMAL(15,2) NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel budget untuk rencana anggaran per kategori
CREATE TABLE IF NOT EXISTS budget (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  kategori_id INT,
  bulan INT NOT NULL,
  tahun INT NOT NULL,
  nominal DECIMAL(15,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert kategori default
INSERT INTO kategori (nama_kategori, tipe, warna) VALUES
('Gaji', 'pemasukan', '#27ae60'),
('Bonus', 'pemasukan', '#2ecc71'),
('Investasi', 'pemasukan', '#f39c12'),
('Belanja Rutin', 'pengeluaran', '#e74c3c'),
('Transportasi', 'pengeluaran', '#3498db'),
('Makanan', 'pengeluaran', '#e67e22'),
('Hiburan', 'pengeluaran', '#9b59b6'),
('Utilitas', 'pengeluaran', '#34495e');

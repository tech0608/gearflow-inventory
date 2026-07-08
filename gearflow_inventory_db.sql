-- --------------------------------------------------------
-- Database Dump: GearFlow Inventory System
-- Proyek Tugas Web - Universitas Teknologi Bandung
-- Disusun oleh: Luthfy Arief (23552011045)
-- Live Domain: https://www.garasifyy.site
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- 1. Tabel `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabel `penggunas` (RBAC Users)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `penggunas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staf') NOT NULL DEFAULT 'staf',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penggunas_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `penggunas` (`id`, `nama_pengguna`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator Bengkel', 'admin', 'admin@garasifyy.site', '$2y$12$eOa/hTjZJbJzJ0B7W0QY.uFwP5G1q7D2e8M3k6L9vX4n1C5r8T9uO', 'admin', NOW(), NOW()),
(2, 'Staf Operasional', 'staf', 'staf@garasifyy.site', '$2y$12$eOa/hTjZJbJzJ0B7W0QY.uFwP5G1q7D2e8M3k6L9vX4n1C5r8T9uO', 'staf', NOW(), NOW());
-- Note: Password untuk kedua akun adalah: password

-- --------------------------------------------------------
-- 3. Tabel `pemasoks`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pemasoks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pemasok` varchar(255) NOT NULL,
  `kontak` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pemasoks` (`id`, `nama_pemasok`, `kontak`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 'PT Astra Honda Motor (Parts)', '021-1234567', 'Jl. Laksda Yos Sudarso - Sunter 1, Jakarta Utara', NOW(), NOW()),
(2, 'CV Dirgantara Motor Mandiri', '0812-3456-7890', 'Jl. Soekarno-Hatta No. 245, Bandung', NOW(), NOW()),
(3, 'UD Sukses Ban & Oli Nusantara', '0857-9876-5432', 'Jl. Jenderal Sudirman No. 88, Bandung', NOW(), NOW());

-- --------------------------------------------------------
-- 4. Tabel `barangs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `barangs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(100) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `kategori` enum('Suku Cadang','Alat','Bahan') NOT NULL DEFAULT 'Suku Cadang',
  `stok` int(11) NOT NULL DEFAULT 0,
  `stok_minimum` int(11) NOT NULL DEFAULT 5,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barangs_kode_barang_unique` (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barangs` (`id`, `kode_barang`, `nama_barang`, `kategori`, `stok`, `stok_minimum`, `harga_satuan`, `created_at`, `updated_at`) VALUES
(1, 'OLI-MPX1-08', 'Oli Honda MPX-1 0.8L (10W-30)', 'Bahan', 45, 10, 58000.00, NOW(), NOW()),
(2, 'OLI-YAM-SLG', 'Oli Yamalube Silver 0.8L', 'Bahan', 30, 10, 55000.00, NOW(), NOW()),
(3, 'BRG-NGK-CR6', 'Busi NGK CR6HSA (Standard)', 'Suku Cadang', 80, 15, 18000.00, NOW(), NOW()),
(4, 'KAN-YAM-MIO', 'Kampas Rem Depan Yamaha Mio / Beat (Nissin)', 'Suku Cadang', 25, 5, 45000.00, NOW(), NOW()),
(5, 'BAN-IRC-8090', 'Ban Luar IRC 80/90-14 Tubeless (NR82)', 'Suku Cadang', 12, 4, 210000.00, NOW(), NOW()),
(6, 'ALT-KNC-T10', 'Kunci T Ukuran 10mm (Tekiro Chrome)', 'Alat', 6, 2, 35000.00, NOW(), NOW());

-- --------------------------------------------------------
-- 5. Tabel `barang_masuks`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `barang_masuks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `pemasok_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_masuks_barang_id_foreign` (`barang_id`),
  KEY `barang_masuks_pemasok_id_foreign` (`pemasok_id`),
  CONSTRAINT `barang_masuks_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuks_pemasok_id_foreign` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasoks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barang_masuks` (`id`, `barang_id`, `pemasok_id`, `jumlah_masuk`, `tanggal_masuk`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 50, CURDATE() - INTERVAL 5 DAY, NOW(), NOW()),
(2, 3, 2, 100, CURDATE() - INTERVAL 4 DAY, NOW(), NOW()),
(3, 5, 3, 15, CURDATE() - INTERVAL 2 DAY, NOW(), NOW());

-- --------------------------------------------------------
-- 6. Tabel `barang_keluars`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `barang_keluars` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `barang_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barang_keluars_barang_id_foreign` (`barang_id`),
  CONSTRAINT `barang_keluars_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `barang_keluars` (`id`, `barang_id`, `jumlah_keluar`, `tujuan`, `tanggal_keluar`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'Servis Rutin Motor Vario 125 - B 4567 TXY', CURDATE() - INTERVAL 3 DAY, NOW(), NOW()),
(2, 3, 20, 'Ganti Busi Borongan Armada Ojek Online', CURDATE() - INTERVAL 1 DAY, NOW(), NOW()),
(3, 5, 3, 'Ganti Ban Belakang Beat - D 1122 AAA', CURDATE(), NOW(), NOW());

-- --------------------------------------------------------
-- 7. Tabel `activity_logs`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `activity_logs` (`id`, `action`, `description`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 'CREATE', 'Menambahkan barang baru: OLI-MPX1-08 - Oli Honda MPX-1 0.8L', '127.0.0.1', NOW(), NOW()),
(2, 'LOGIN', 'Pengguna admin berhasil masuk ke dalam sistem', '127.0.0.1', NOW(), NOW()),
(3, 'CREATE', 'Transaksi barang keluar: OLI-MPX1-08 sebanyak 5 unit', '127.0.0.1', NOW(), NOW());

COMMIT;

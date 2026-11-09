-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for laravel
DROP DATABASE IF EXISTS `laravel`;
CREATE DATABASE IF NOT EXISTS `laravel` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `laravel`;

-- Dumping structure for table laravel.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table laravel.kriterias
DROP TABLE IF EXISTS `kriterias`;
CREATE TABLE IF NOT EXISTS `kriterias` (
  `id_kriteria` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` enum('benefit','cost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'benefit',
  `prioritas` int unsigned NOT NULL,
  `bobot_roc` decimal(8,6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kriteria`),
  UNIQUE KEY `kriterias_kode_unique` (`kode`),
  UNIQUE KEY `kriterias_prioritas_unique` (`prioritas`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.kriterias: ~0 rows (approximately)
REPLACE INTO `kriterias` (`id_kriteria`, `kode`, `nama`, `jenis`, `prioritas`, `bobot_roc`, `created_at`, `updated_at`) VALUES
	(1, 'C1', 'Nilai Akademik', 'benefit', 1, 0.456667, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(2, 'C2', 'Kehadiran', 'benefit', 2, 0.256667, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(3, 'C3', 'Sikap', 'benefit', 3, 0.156667, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(4, 'C4', 'Prestasi Non Akademik', 'benefit', 4, 0.090000, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(5, 'C5', 'Keaktifan Organisasi', 'benefit', 5, 0.040000, '2026-11-07 21:06:52', '2026-11-07 21:06:52');

-- Dumping structure for table laravel.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.migrations: ~0 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2026_11_08_033423_add_username_and_role_to_users_table', 1),
	(6, '2026_11_08_033436_create_periodes_table', 1),
	(7, '2026_11_08_033500_create_siswas_table', 1),
	(8, '2026_11_08_033501_create_kriterias_table', 1),
	(9, '2026_11_08_033502_create_penilaians_table', 1),
	(10, '2026_11_08_033503_create_perhitungans_table', 1),
	(11, '2026_11_08_033504_create_rankings_table', 1);

-- Dumping structure for table laravel.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table laravel.penilaians
DROP TABLE IF EXISTS `penilaians`;
CREATE TABLE IF NOT EXISTS `penilaians` (
  `id_penilaian` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `id_siswa` bigint unsigned NOT NULL,
  `id_kriteria` bigint unsigned NOT NULL,
  `nilai` decimal(6,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_penilaian`),
  UNIQUE KEY `penilaians_periode_id_id_siswa_id_kriteria_unique` (`periode_id`,`id_siswa`,`id_kriteria`),
  KEY `penilaians_id_siswa_foreign` (`id_siswa`),
  KEY `penilaians_id_kriteria_foreign` (`id_kriteria`),
  CONSTRAINT `penilaians_id_kriteria_foreign` FOREIGN KEY (`id_kriteria`) REFERENCES `kriterias` (`id_kriteria`) ON DELETE CASCADE,
  CONSTRAINT `penilaians_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswas` (`id_siswa`) ON DELETE CASCADE,
  CONSTRAINT `penilaians_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.penilaians: ~0 rows (approximately)
REPLACE INTO `penilaians` (`id_penilaian`, `periode_id`, `id_siswa`, `id_kriteria`, `nilai`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 95.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(2, 1, 1, 2, 98.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(3, 1, 1, 3, 94.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(4, 1, 1, 4, 92.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(5, 1, 1, 5, 90.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(6, 1, 2, 1, 94.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(7, 1, 2, 2, 97.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(8, 1, 2, 3, 93.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(9, 1, 2, 4, 91.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(10, 1, 2, 5, 89.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(11, 1, 3, 1, 93.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(12, 1, 3, 2, 96.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(13, 1, 3, 3, 92.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(14, 1, 3, 4, 90.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(15, 1, 3, 5, 88.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(16, 1, 4, 1, 92.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(17, 1, 4, 2, 95.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(18, 1, 4, 3, 91.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(19, 1, 4, 4, 89.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(20, 1, 4, 5, 87.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(21, 1, 5, 1, 91.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(22, 1, 5, 2, 94.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(23, 1, 5, 3, 90.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(24, 1, 5, 4, 88.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(25, 1, 5, 5, 86.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(26, 1, 6, 1, 90.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(27, 1, 6, 2, 93.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(28, 1, 6, 3, 89.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(29, 1, 6, 4, 87.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(30, 1, 6, 5, 85.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(31, 1, 7, 1, 89.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(32, 1, 7, 2, 92.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(33, 1, 7, 3, 88.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(34, 1, 7, 4, 86.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(35, 1, 7, 5, 84.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(36, 1, 8, 1, 88.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(37, 1, 8, 2, 91.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(38, 1, 8, 3, 87.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(39, 1, 8, 4, 85.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(40, 1, 8, 5, 83.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(41, 1, 9, 1, 87.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(42, 1, 9, 2, 90.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(43, 1, 9, 3, 86.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(44, 1, 9, 4, 84.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(45, 1, 9, 5, 82.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(46, 1, 10, 1, 86.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(47, 1, 10, 2, 89.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(48, 1, 10, 3, 85.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(49, 1, 10, 4, 83.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(50, 1, 10, 5, 81.00, '2026-11-07 21:06:52', '2026-11-07 21:06:52');

-- Dumping structure for table laravel.perhitungans
DROP TABLE IF EXISTS `perhitungans`;
CREATE TABLE IF NOT EXISTS `perhitungans` (
  `id_perhitungan` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `id_siswa` bigint unsigned NOT NULL,
  `id_kriteria` bigint unsigned NOT NULL,
  `nilai_normalisasi` decimal(10,6) NOT NULL,
  `nilai_terbobot` decimal(10,6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_perhitungan`),
  KEY `perhitungans_periode_id_foreign` (`periode_id`),
  KEY `perhitungans_id_siswa_foreign` (`id_siswa`),
  KEY `perhitungans_id_kriteria_foreign` (`id_kriteria`),
  CONSTRAINT `perhitungans_id_kriteria_foreign` FOREIGN KEY (`id_kriteria`) REFERENCES `kriterias` (`id_kriteria`) ON DELETE CASCADE,
  CONSTRAINT `perhitungans_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswas` (`id_siswa`) ON DELETE CASCADE,
  CONSTRAINT `perhitungans_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=551 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.perhitungans: ~0 rows (approximately)

-- Dumping structure for table laravel.periodes
DROP TABLE IF EXISTS `periodes`;
CREATE TABLE IF NOT EXISTS `periodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_berprestasi` int NOT NULL DEFAULT '2',
  `aktif` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.periodes: ~0 rows (approximately)
REPLACE INTO `periodes` (`id`, `nama`, `tahun_ajaran`, `semester`, `jumlah_berprestasi`, `aktif`, `created_at`, `updated_at`) VALUES
	(1, '2026/2027 - Ganjil', '2026/2027', 'Ganjil', 3, 1, '2026-11-07 21:06:52', '2026-11-08 01:56:49');

-- Dumping structure for table laravel.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table laravel.rankings
DROP TABLE IF EXISTS `rankings`;
CREATE TABLE IF NOT EXISTS `rankings` (
  `id_ranking` bigint unsigned NOT NULL AUTO_INCREMENT,
  `periode_id` bigint unsigned NOT NULL,
  `id_siswa` bigint unsigned NOT NULL,
  `nilai_akhir` decimal(10,6) NOT NULL,
  `peringkat` int unsigned NOT NULL,
  `keterangan` enum('Berprestasi','Cadangan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_ranking`),
  UNIQUE KEY `rankings_periode_id_id_siswa_unique` (`periode_id`,`id_siswa`),
  KEY `rankings_id_siswa_foreign` (`id_siswa`),
  CONSTRAINT `rankings_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswas` (`id_siswa`) ON DELETE CASCADE,
  CONSTRAINT `rankings_periode_id_foreign` FOREIGN KEY (`periode_id`) REFERENCES `periodes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.rankings: ~0 rows (approximately)

-- Dumping structure for table laravel.siswas
DROP TABLE IF EXISTS `siswas`;
CREATE TABLE IF NOT EXISTS `siswas` (
  `id_siswa` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_siswa`),
  UNIQUE KEY `siswas_nis_unique` (`nis`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.siswas: ~0 rows (approximately)
REPLACE INTO `siswas` (`id_siswa`, `nis`, `nama`, `kelas`, `jenis_kelamin`, `created_at`, `updated_at`) VALUES
	(1, '001', 'Intan Permata Sari Wilujeng', 'XI IPA 1', 'P', '2026-11-07 21:06:52', '2026-11-07 21:33:20'),
	(2, '002', 'Gina Lestari', 'XI IPA 1', 'P', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(3, '003', 'Aisyah Nur Fadilah', 'XI IPA 1', 'P', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(4, '004', 'Elsa Maharani', 'XI IPA 1', 'P', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(5, '005', 'Citra Ayu Lestari', 'XI IPA 1', 'P', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(6, '006', 'Ahmad Rizki', 'XI IPA 1', 'L', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(7, '007', 'Hendra Pratama', 'XI IPA 1', 'L', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(8, '008', 'Budi Santoso', 'XI IPA 1', 'L', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(9, '009', 'Farhan Maulana', 'XI IPA 1', 'L', '2026-11-07 21:06:52', '2026-11-07 21:06:52'),
	(10, '010', 'Dimas Aditya', 'XI IPA 1', 'L', '2026-11-07 21:06:52', '2026-11-07 21:06:52');

-- Dumping structure for table laravel.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laravel.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `username`, `role`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'admin', 'admin', 'Administrator', 'admin@smkdwijabhakti.sch.id', NULL, '$2y$10$aq8GYomh4of0eNf3LUc4tu8TYFlTp6rpCH4ZgseGjXeSV8w0zqsTK', NULL, '2026-11-07 21:06:52', '2026-11-07 21:08:45');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

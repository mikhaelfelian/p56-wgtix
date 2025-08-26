-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table db_p56_masukin.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.migrations: ~22 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
	(1, '20240101043322', 'App\\Database\\Migrations\\CreatePengaturanTable', 'default', 'App', 1748540062, 1),
	(2, '20240101055512', 'App\\Database\\Migrations\\CreatePengaturanThemeTable', 'default', 'App', 1748540062, 1),
	(3, '20250221135553', 'App\\Database\\Migrations\\CreateTblSessions', 'default', 'App', 1748540062, 1),
	(4, '20250526125512', 'App\\Database\\Migrations\\CreateTblPengaturanApi', 'default', 'App', 1748540062, 1),
	(5, '20250530002715', 'App\\Database\\Migrations\\CreateTblIonModules', 'default', 'App', 1748540062, 1),
	(6, '20181211100537', 'IonAuth\\Database\\Migrations\\Migration_Install_ion_auth', 'default', 'IonAuth', 1748540562, 2),
	(7, '20240101043322', 'App\\Database\\Migrations\\Migration_20240101043322_create_tbl_pengaturan', 'default', 'App', 1754327743, 3),
	(8, '20250122135553', 'App\\Database\\Migrations\\CreateTblMKategori', 'default', 'App', 1754327743, 3),
	(9, '20250530173000', 'App\\Database\\Migrations\\Migration_20250530173000_create_tbl_ion_modules', 'default', 'App', 1754327743, 3),
	(10, '20250806173827', 'App\\Database\\Migrations\\CreateTblMPlatform', 'default', 'App', 1754476969, 4),
	(11, '20250806183606', 'App\\Database\\Migrations\\CreateTblMUkuran', 'default', 'App', 1754565055, 5),
	(13, '2025-08-07-124716', 'App\\Database\\Migrations\\CreateTblMJadwal20250807124716', 'default', 'App', 1754571034, 6),
	(14, '20250807192526', 'App\\Database\\Migrations\\CreateTblMJadwal20250807192526', 'default', 'App', 1754571186, 7),
	(15, '2025-08-07-125631', 'App\\Database\\Migrations\\CreateTblPeserta20250807125631', 'default', 'App', 1754571572, 8),
	(16, '2025-08-07-125636', 'App\\Database\\Migrations\\CreateTblKelompokPeserta20250807125636', 'default', 'App', 1754571572, 8),
	(17, '2025-08-07-125718', 'App\\Database\\Migrations\\CreateTblPendaftaran20250807125718', 'default', 'App', 1754571572, 8),
	(18, '2025-08-07-172534', 'App\\Database\\Migrations\\AddQrCodeToTblPeserta20250807172534', 'default', 'App', 1754587571, 9),
	(19, '2025-08-07-234640', 'App\\Database\\Migrations\\CreateTblKategoriRacepack20250807234640', 'default', 'App', 1754610996, 10),
	(20, '2025-08-08-001855', 'App\\Database\\Migrations\\CreateTblRacepack20250808001855', 'default', 'App', 1754612404, 11),
	(21, '2025-08-08-001902', 'App\\Database\\Migrations\\CreateTblStockRacepack20250808001902', 'default', 'App', 1754612405, 11),
	(22, '2025-08-08-041430', 'App\\Database\\Migrations\\AddIdPlatformToTblPeserta', 'default', 'App', 1754626580, 12),
	(23, '2025-08-08-055800', 'App\\Database\\Migrations\\AddTripayColumnsToTblPeserta20250808055800', 'default', 'App', 1754633086, 13),
	(24, '20250807192526', 'App\\Database\\Migrations\\CreateTblMEvent20250807192526', 'default', 'App', 1756201379, 14),
	(25, '20250807192527', 'App\\Database\\Migrations\\CreateTblMEventHarga20250807193526', 'default', 'App', 1756201379, 14),
	(26, '20250807192528', 'App\\Database\\Migrations\\CreateTblMEventGaleri20250807194526', 'default', 'App', 1756203477, 15);

-- Dumping structure for table db_p56_masukin.tbl_ion_groups
DROP TABLE IF EXISTS `tbl_ion_groups`;
CREATE TABLE IF NOT EXISTS `tbl_ion_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_groups: ~4 rows (approximately)
DELETE FROM `tbl_ion_groups`;
INSERT INTO `tbl_ion_groups` (`id`, `name`, `description`) VALUES
	(1, 'root', 'Root - Highest Level Access'),
	(2, 'superadmin', 'Super Administrator'),
	(3, 'manager', 'Manager'),
	(4, 'supervisor', 'Supervisor');

-- Dumping structure for table db_p56_masukin.tbl_ion_login_attempts
DROP TABLE IF EXISTS `tbl_ion_login_attempts`;
CREATE TABLE IF NOT EXISTS `tbl_ion_login_attempts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) DEFAULT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_login_attempts: ~0 rows (approximately)
DELETE FROM `tbl_ion_login_attempts`;

-- Dumping structure for table db_p56_masukin.tbl_ion_modules
DROP TABLE IF EXISTS `tbl_ion_modules`;
CREATE TABLE IF NOT EXISTS `tbl_ion_modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT 'Untuk modul dengan sub-menu',
  `name` varchar(100) NOT NULL COMMENT 'Nama modul (ex: Barang, Kategori)',
  `route` varchar(100) NOT NULL COMMENT 'ex: Master/Item',
  `icon` varchar(100) DEFAULT NULL,
  `is_menu` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'tampil di sidebar',
  `is_active` enum('0','1') NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `default_permissions` text DEFAULT NULL COMMENT 'JSON: {"create":true,"read_all":true}',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_modules: ~0 rows (approximately)
DELETE FROM `tbl_ion_modules`;

-- Dumping structure for table db_p56_masukin.tbl_ion_users
DROP TABLE IF EXISTS `tbl_ion_users`;
CREATE TABLE IF NOT EXISTS `tbl_ion_users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile` varchar(160) DEFAULT NULL,
  `tipe` enum('1','2') NOT NULL DEFAULT '1' COMMENT '1 => karyawan, 2 => anggota',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `activation_selector` (`activation_selector`),
  UNIQUE KEY `forgotten_password_selector` (`forgotten_password_selector`),
  UNIQUE KEY `remember_selector` (`remember_selector`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_users: ~4 rows (approximately)
DELETE FROM `tbl_ion_users`;
INSERT INTO `tbl_ion_users` (`id`, `ip_address`, `username`, `password`, `email`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `profile`, `tipe`) VALUES
	(1, '127.0.0.1', 'root', '$2y$10$gQoEoZYp8Rz2iK9m.c1nZOQ3mJy53.Bb89WoV4m9/RxUTRVpY2FGW', 'root@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1268889823, 1, 'Root', 'User', 'ADMIN', '', '', '1'),
	(2, '127.0.0.1', 'superadmin', '$2y$10$gQoEoZYp8Rz2iK9m.c1nZOQ3mJy53.Bb89WoV4m9/RxUTRVpY2FGW', 'superadmin@admin.com', NULL, '', NULL, NULL, NULL, '15f78a6e9530db7feae415365885f15d6557f51c', '$2y$10$5m1/T7/r6/e7HlD5/kyT4OONX7lOAk7990kpj4wt6SY4XyCRGdG8u', 1268889823, 1756223315, 1, 'Superdamin', 'Admin', 'ADMIN', '', 'public/assets/theme/admin-lte-3/dist/img/user2-160x160.jpg', '1'),
	(3, '127.0.0.1', 'manager', '$2y$10$YpTvAzjvC5BEr1tdFOg3wOoZPgk90zfHHOoNOsG7f.J8qWWHVnkZe', 'manager@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1268889823, 1, 'Manager', 'User', 'ADMIN', '', NULL, '1'),
	(4, '127.0.0.1', 'supervisor', '$2y$10$YpTvAzjvC5BEr1tdFOg3wOoZPgk90zfHHOoNOsG7f.J8qWWHVnkZe', 'supervisor@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1268889823, 1, 'Supervisor', 'User', 'ADMIN', '', NULL, '1');

-- Dumping structure for table db_p56_masukin.tbl_ion_users_groups
DROP TABLE IF EXISTS `tbl_ion_users_groups`;
CREATE TABLE IF NOT EXISTS `tbl_ion_users_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  `permission` text DEFAULT NULL COMMENT 'For permission access using json',
  PRIMARY KEY (`id`),
  KEY `tbl_ion_users_groups_user_id_foreign` (`user_id`),
  KEY `tbl_ion_users_groups_group_id_foreign` (`group_id`),
  CONSTRAINT `tbl_ion_users_groups_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `tbl_ion_groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `tbl_ion_users_groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `tbl_ion_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_users_groups: ~4 rows (approximately)
DELETE FROM `tbl_ion_users_groups`;
INSERT INTO `tbl_ion_users_groups` (`id`, `user_id`, `group_id`, `permission`) VALUES
	(1, 1, 1, NULL),
	(2, 2, 2, NULL),
	(3, 3, 3, NULL),
	(4, 4, 4, NULL);

-- Dumping structure for table db_p56_masukin.tbl_kategori_racepack
DROP TABLE IF EXISTS `tbl_kategori_racepack`;
CREATE TABLE IF NOT EXISTS `tbl_kategori_racepack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Aktif, 0: Tidak Aktif',
  `id_user` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_kategori_racepack: ~0 rows (approximately)
DELETE FROM `tbl_kategori_racepack`;
INSERT INTO `tbl_kategori_racepack` (`id`, `nama_kategori`, `deskripsi`, `status`, `id_user`, `created_at`, `updated_at`) VALUES
	(1, 'Racepack Lomba Lari', '- Kaus\r\n- Tumbler', 1, 2, '2025-08-08 07:22:51', '2025-08-08 07:23:16');

-- Dumping structure for table db_p56_masukin.tbl_kelompok_peserta
DROP TABLE IF EXISTS `tbl_kelompok_peserta`;
CREATE TABLE IF NOT EXISTS `tbl_kelompok_peserta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID user yang membuat kelompok',
  `kode_kelompok` varchar(20) NOT NULL COMMENT 'Kode kelompok (KLP001, KLP002, dst)',
  `nama_kelompok` varchar(100) NOT NULL COMMENT 'Nama kelompok peserta',
  `deskripsi` text DEFAULT NULL COMMENT 'Deskripsi kelompok',
  `kapasitas` int(11) DEFAULT 0 COMMENT 'Kapasitas maksimal anggota',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status: 0=tidak aktif, 1=aktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode_kelompok` (`kode_kelompok`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_kelompok_peserta: ~1 rows (approximately)
DELETE FROM `tbl_kelompok_peserta`;
INSERT INTO `tbl_kelompok_peserta` (`id`, `id_user`, `kode_kelompok`, `nama_kelompok`, `deskripsi`, `kapasitas`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 'VET', 'DEWASA', 'dsadsad', 120, 1, '2025-08-07 23:34:07', '2025-08-08 13:36:20'),
	(2, 2, 'ANK', 'ANAK', NULL, 120, 1, '2025-08-08 13:36:33', '2025-08-08 13:36:33');

-- Dumping structure for table db_p56_masukin.tbl_m_event
DROP TABLE IF EXISTS `tbl_m_event`;
CREATE TABLE IF NOT EXISTS `tbl_m_event` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID user yang membuat event',
  `id_kategori` int(11) unsigned NOT NULL DEFAULT 0 COMMENT 'ID kategori event',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL COMMENT 'Kode event (opsional)',
  `event` varchar(100) NOT NULL COMMENT 'Nama event',
  `foto` varchar(160) DEFAULT NULL COMMENT 'Foto event',
  `tgl_masuk` date NOT NULL COMMENT 'Tanggal mulai event',
  `tgl_keluar` date NOT NULL COMMENT 'Tanggal selesai event',
  `wkt_masuk` time NOT NULL COMMENT 'Waktu mulai event',
  `wkt_keluar` time NOT NULL COMMENT 'Waktu selesai event',
  `lokasi` varchar(200) DEFAULT NULL COMMENT 'Lokasi event',
  `jml` int(11) DEFAULT 0 COMMENT 'Kapasitas peserta',
  `latitude` decimal(10,8) DEFAULT NULL COMMENT 'Latitude lokasi event',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT 'Longitude lokasi event',
  `keterangan` text DEFAULT NULL COMMENT 'Keterangan event',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Status: 0=tidak aktif, 1=aktif',
  `status_hps` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Status Hapus: 0=aktif, 1=terhapus',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `id_kategori` (`id_kategori`),
  KEY `kode` (`kode`),
  KEY `status` (`status`),
  KEY `tgl_masuk` (`tgl_masuk`),
  KEY `tgl_keluar` (`tgl_keluar`),
  KEY `wkt_masuk` (`wkt_masuk`),
  KEY `wkt_keluar` (`wkt_keluar`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `tbl_m_event_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_m_kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event: ~1 rows (approximately)
DELETE FROM `tbl_m_event`;
INSERT INTO `tbl_m_event` (`id`, `id_user`, `id_kategori`, `created_at`, `updated_at`, `deleted_at`, `kode`, `event`, `foto`, `tgl_masuk`, `tgl_keluar`, `wkt_masuk`, `wkt_keluar`, `lokasi`, `jml`, `latitude`, `longitude`, `keterangan`, `status`, `status_hps`) VALUES
	(12, 2, 1, '2025-08-26 18:48:21', '2025-08-26 18:48:21', NULL, 'saSAs', 'COBA', '1756208901_c2dc958b5fb975e10167.png', '2025-08-26', '2025-09-06', '09:00:00', '21:59:00', 'dsdasdsad', 166, NULL, NULL, 'sadsadsad', '1', '');

-- Dumping structure for table db_p56_masukin.tbl_m_event_galeri
DROP TABLE IF EXISTS `tbl_m_event_galeri`;
CREATE TABLE IF NOT EXISTS `tbl_m_event_galeri` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(11) unsigned NOT NULL COMMENT 'Relasi ke tbl_m_events',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `file` varchar(200) NOT NULL COMMENT 'Nama file atau URL media',
  `deskripsi` text DEFAULT NULL COMMENT 'Deskripsi media',
  `is_cover` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=cover utama event, 0=bukan cover',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=non-aktif, 1=aktif',
  PRIMARY KEY (`id`),
  KEY `id_event` (`id_event`),
  KEY `is_cover` (`is_cover`),
  CONSTRAINT `tbl_m_event_galeri_id_event_foreign` FOREIGN KEY (`id_event`) REFERENCES `tbl_m_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event_galeri: ~4 rows (approximately)
DELETE FROM `tbl_m_event_galeri`;
INSERT INTO `tbl_m_event_galeri` (`id`, `id_event`, `created_at`, `updated_at`, `deleted_at`, `file`, `deskripsi`, `is_cover`, `status`) VALUES
	(1, 12, '2025-08-26 22:55:53', '2025-08-26 22:55:59', '2025-08-26 22:55:59', '1756223753_c223a5cdcc3f53cc5300.jpg', '', 0, 1),
	(2, 12, '2025-08-26 22:55:53', '2025-08-26 22:56:04', '2025-08-26 22:56:04', '1756223753_7e1e18b5b0226cef9bca.png', '', 0, 1),
	(3, 12, '2025-08-26 22:58:12', '2025-08-26 23:01:51', NULL, '1756223892_43b6d53deb298c30e0d5.jpg', 'babi', 1, 1),
	(4, 12, '2025-08-26 22:58:12', '2025-08-26 23:01:44', NULL, '1756223892_dffbfc0e3e7b2c8389e4.png', 'Pemuja Syaiton', 0, 1);

-- Dumping structure for table db_p56_masukin.tbl_m_event_harga
DROP TABLE IF EXISTS `tbl_m_event_harga`;
CREATE TABLE IF NOT EXISTS `tbl_m_event_harga` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(11) unsigned NOT NULL COMMENT 'Relasi ke tbl_m_events',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `keterangan` varchar(160) DEFAULT NULL,
  `harga` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Nominal harga',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=non-aktif, 1=aktif',
  PRIMARY KEY (`id`),
  KEY `id_event` (`id_event`),
  CONSTRAINT `tbl_m_event_harga_id_event_foreign` FOREIGN KEY (`id_event`) REFERENCES `tbl_m_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event_harga: ~1 rows (approximately)
DELETE FROM `tbl_m_event_harga`;
INSERT INTO `tbl_m_event_harga` (`id`, `id_event`, `created_at`, `updated_at`, `deleted_at`, `keterangan`, `harga`, `status`) VALUES
	(1, 12, '2025-08-26 21:59:05', '2025-08-26 23:03:13', NULL, 'Fun Run 5K', 5000.00, '1');

-- Dumping structure for table db_p56_masukin.tbl_m_jadwal
DROP TABLE IF EXISTS `tbl_m_jadwal`;
CREATE TABLE IF NOT EXISTS `tbl_m_jadwal` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID user yang membuat jadwal',
  `kode` varchar(20) DEFAULT NULL COMMENT 'Kode jadwal (opsional)',
  `nama_jadwal` varchar(100) NOT NULL COMMENT 'Nama jadwal',
  `tanggal_mulai` date NOT NULL COMMENT 'Tanggal mulai jadwal',
  `tanggal_selesai` date NOT NULL COMMENT 'Tanggal selesai jadwal',
  `waktu_mulai` time NOT NULL COMMENT 'Waktu mulai jadwal',
  `waktu_selesai` time NOT NULL COMMENT 'Waktu selesai jadwal',
  `lokasi` varchar(200) DEFAULT NULL COMMENT 'Lokasi jadwal',
  `kapasitas` int(11) DEFAULT 0 COMMENT 'Kapasitas peserta',
  `keterangan` text DEFAULT NULL COMMENT 'Keterangan jadwal',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status: 0=tidak aktif, 1=aktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode` (`kode`),
  KEY `status` (`status`),
  KEY `tanggal_mulai` (`tanggal_mulai`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_jadwal: ~0 rows (approximately)
DELETE FROM `tbl_m_jadwal`;

-- Dumping structure for table db_p56_masukin.tbl_m_kategori
DROP TABLE IF EXISTS `tbl_m_kategori`;
CREATE TABLE IF NOT EXISTS `tbl_m_kategori` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL COMMENT 'Kode singkat kategori (opsional, contoh: 5KUM, 10KP)',
  `kategori` varchar(100) NOT NULL COMMENT 'Nama kategori (misal: 5K Umum, 10K Pelajar)',
  `keterangan` text DEFAULT NULL COMMENT 'Penjelasan kategori (opsional)',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Status aktif/tidak aktif untuk toggle saat pendaftaran',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode` (`kode`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan data master kategori events';

-- Dumping data for table db_p56_masukin.tbl_m_kategori: ~1 rows (approximately)
DELETE FROM `tbl_m_kategori`;
INSERT INTO `tbl_m_kategori` (`id`, `id_user`, `created_at`, `updated_at`, `kode`, `kategori`, `keterangan`, `status`) VALUES
	(1, 1, '2025-08-26 17:56:30', '2025-08-26 17:56:49', 'DFT', 'DEFAULT', NULL, '1');

-- Dumping structure for table db_p56_masukin.tbl_m_platform
DROP TABLE IF EXISTS `tbl_m_platform`;
CREATE TABLE IF NOT EXISTS `tbl_m_platform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` varchar(100) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `nama_rekening` varchar(111) NOT NULL,
  `nomor_rekening` varchar(111) NOT NULL,
  `deskripsi` text NOT NULL,
  `gateway_kode` varchar(111) DEFAULT '-',
  `gateway_instruksi` enum('0','1') DEFAULT '0',
  `logo` varchar(111) NOT NULL,
  `hasil` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `status_gateway` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan data master platform pembayaran dan gateway';

-- Dumping data for table db_p56_masukin.tbl_m_platform: ~5 rows (approximately)
DELETE FROM `tbl_m_platform`;
INSERT INTO `tbl_m_platform` (`id`, `id_user`, `id_kategori`, `nama`, `jenis`, `kategori`, `nama_rekening`, `nomor_rekening`, `deskripsi`, `gateway_kode`, `gateway_instruksi`, `logo`, `hasil`, `status`, `status_gateway`) VALUES
	(1, 0, 0, 'Tunai', '', '', 'Mikhael Felian Waskito', '816666666', '-', '-', '0', '', '', 1, '0'),
	(3, 1, 1, 'BCA Virtual Account', 'tripay', 'virtual_account', 'Tripay', '085741220427', 'Biaya Rp 5.500', 'BCAVA', '1', 'Bank_Central_Asia.png', '', 1, '1'),
	(4, 1, 1, 'BRI Virtual Account', 'tripay', 'virtual_account', 'Tripay', '085741220427', 'Biaya Rp 2.125', 'BRIVA', '1', 'briva.png', '', 1, '1'),
	(5, 1, 1, 'QRIS by ShopeePay', 'tripay', 'ewallet', 'Tripay', '085741220427', 'Biaya Rp 188 + 0.17%', 'QRIS', '1', 'qris-all-shoppee.png', '', 1, '1'),
	(6, 1, 1, 'Mandiri Virtual Account', 'tripay', 'virtual_account', 'Tripay', '085741220427', 'Biaya Rp 2.125', 'MANDIRIVA', '1', 'mandiriva.png', '', 1, '1'),
	(7, 1, 1, 'Alfamart', 'tripay', 'mini_market', 'Tripay', '085741220427', 'Biaya Rp. 3.500', 'ALFAMART', '1', 'alfamart.png', '', 1, '1');

-- Dumping structure for table db_p56_masukin.tbl_m_ukuran
DROP TABLE IF EXISTS `tbl_m_ukuran`;
CREATE TABLE IF NOT EXISTS `tbl_m_ukuran` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `kode` varchar(20) DEFAULT NULL COMMENT 'Kode singkat ukuran (opsional, contoh: S, M, L, XL)',
  `ukuran` varchar(50) NOT NULL COMMENT 'Nama ukuran (misal: Small, Medium, Large, Extra Large)',
  `deskripsi` varchar(100) DEFAULT NULL COMMENT 'Deskripsi ukuran (opsional)',
  `keterangan` text DEFAULT NULL COMMENT 'Penjelasan ukuran (opsional)',
  `harga` decimal(10,2) DEFAULT 0.00 COMMENT 'Harga tambahan untuk ukuran tertentu',
  `stok` int(11) unsigned DEFAULT 0 COMMENT 'Stok tersedia untuk ukuran ini',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'Status aktif/tidak aktif untuk toggle saat pendaftaran',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode` (`kode`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan data master ukuran produk/racepack dalam sistem POS';

-- Dumping data for table db_p56_masukin.tbl_m_ukuran: ~0 rows (approximately)
DELETE FROM `tbl_m_ukuran`;
INSERT INTO `tbl_m_ukuran` (`id`, `id_user`, `created_at`, `updated_at`, `kode`, `ukuran`, `deskripsi`, `keterangan`, `harga`, `stok`, `status`) VALUES
	(1, 2, '2025-08-08 14:17:36', '2025-08-08 14:17:36', 'ITM-001', 'XXL', 'asdf', 'hh', 6000.00, 4, '1');

-- Dumping structure for table db_p56_masukin.tbl_pendaftaran
DROP TABLE IF EXISTS `tbl_pendaftaran`;
CREATE TABLE IF NOT EXISTS `tbl_pendaftaran` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID user yang membuat pendaftaran',
  `kode_pendaftaran` varchar(20) NOT NULL COMMENT 'Kode pendaftaran (REG001, REG002, dst)',
  `id_peserta` int(11) unsigned NOT NULL COMMENT 'ID peserta yang mendaftar',
  `id_jadwal` int(11) unsigned NOT NULL COMMENT 'ID jadwal yang dipilih',
  `tanggal_pendaftaran` date NOT NULL COMMENT 'Tanggal pendaftaran',
  `status_pendaftaran` enum('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Status pendaftaran',
  `catatan` text DEFAULT NULL COMMENT 'Catatan pendaftaran',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status: 0=tidak aktif, 1=aktif',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode_pendaftaran` (`kode_pendaftaran`),
  KEY `id_peserta` (`id_peserta`),
  KEY `id_jadwal` (`id_jadwal`),
  KEY `status_pendaftaran` (`status_pendaftaran`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_pendaftaran: ~0 rows (approximately)
DELETE FROM `tbl_pendaftaran`;

-- Dumping structure for table db_p56_masukin.tbl_pengaturan
DROP TABLE IF EXISTS `tbl_pengaturan`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `judul` varchar(100) DEFAULT NULL,
  `judul_app` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `pagination_limit` int(11) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `logo_header` varchar(255) DEFAULT NULL,
  `ppn` int(2) DEFAULT NULL,
  `limit` decimal(10,2) DEFAULT NULL COMMENT 'Limit pengaturan',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_pengaturan: ~0 rows (approximately)
DELETE FROM `tbl_pengaturan`;
INSERT INTO `tbl_pengaturan` (`id`, `updated_at`, `judul`, `judul_app`, `alamat`, `deskripsi`, `kota`, `url`, `theme`, `pagination_limit`, `favicon`, `logo`, `logo_header`, `ppn`, `limit`) VALUES
	(1, '2025-08-04 17:08:34', 'KOPMENSA POS', 'MASUKIN AJA', 'Jl. Raya No. 1', 'sistem informasi manajemen penjualan', 'semarang', 'http://localhost/p54-kopmensa', 'quirk', 10, '', 'public/file/app/logo_only.png', 'public/file/app/logo_header.png', 11, NULL);

-- Dumping structure for table db_p56_masukin.tbl_pengaturan_api
DROP TABLE IF EXISTS `tbl_pengaturan_api`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_api` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `id_pengaturan` int(11) unsigned DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `pub_key` text DEFAULT NULL,
  `priv_key` text DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_pengaturan` (`id_pengaturan`),
  CONSTRAINT `FK_pengaturan_api` FOREIGN KEY (`id_pengaturan`) REFERENCES `tbl_pengaturan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan pengaturan API';

-- Dumping data for table db_p56_masukin.tbl_pengaturan_api: ~2 rows (approximately)
DELETE FROM `tbl_pengaturan_api`;
INSERT INTO `tbl_pengaturan_api` (`id`, `created_at`, `updated_at`, `id_pengaturan`, `nama`, `pub_key`, `priv_key`, `status`) VALUES
	(1, '2025-05-29 17:37:38', NULL, 1, 'Recaptcha 3', '6LfGMLMqAAAAAFiFBRqO_VRv_R9aihfNzGp7SBb1', '6LfGMLMqAAAAAPk13LbbBWB7v1aBdrjnn3CCD6nB', '1'),
	(2, '2025-05-29 17:37:38', NULL, 1, 'Chat GPT', NULL, NULL, '1');

-- Dumping structure for table db_p56_masukin.tbl_pengaturan_theme
DROP TABLE IF EXISTS `tbl_pengaturan_theme`;
CREATE TABLE IF NOT EXISTS `tbl_pengaturan_theme` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_pengaturan` int(11) unsigned DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `path` varchar(160) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_pengaturan` (`id_pengaturan`),
  CONSTRAINT `FK_pengaturan_theme` FOREIGN KEY (`id_pengaturan`) REFERENCES `tbl_pengaturan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_pengaturan_theme: ~2 rows (approximately)
DELETE FROM `tbl_pengaturan_theme`;
INSERT INTO `tbl_pengaturan_theme` (`id`, `id_pengaturan`, `nama`, `path`, `status`) VALUES
	(1, 1, 'Quirk Admin Theme', 'quirk', 0),
	(2, 1, 'Admin LTE 3', 'admin-lte-3', 1);

-- Dumping structure for table db_p56_masukin.tbl_peserta
DROP TABLE IF EXISTS `tbl_peserta`;
CREATE TABLE IF NOT EXISTS `tbl_peserta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'ID user yang membuat data peserta',
  `id_kategori` int(11) unsigned NOT NULL,
  `id_platform` int(11) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `kode_peserta` varchar(20) NOT NULL COMMENT 'Kode peserta (PES001, PES002, dst)',
  `nama_lengkap` varchar(100) NOT NULL COMMENT 'Nama lengkap peserta',
  `tempat_lahir` varchar(50) DEFAULT NULL COMMENT 'Tempat lahir',
  `tanggal_lahir` date DEFAULT NULL COMMENT 'Tanggal lahir',
  `jenis_kelamin` enum('L','P') NOT NULL COMMENT 'Jenis kelamin: L=Laki-laki, P=Perempuan',
  `alamat` text DEFAULT NULL COMMENT 'Alamat lengkap',
  `no_hp` varchar(15) DEFAULT NULL COMMENT 'Nomor handphone',
  `email` varchar(100) DEFAULT NULL COMMENT 'Email peserta',
  `id_kelompok` int(11) unsigned DEFAULT NULL COMMENT 'ID kelompok peserta',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status: 0=tidak aktif, 1=aktif',
  `qr_code` text DEFAULT NULL COMMENT 'QR Code as base64 string (max 200KB)',
  `tripay_reference` varchar(100) DEFAULT NULL,
  `tripay_pay_url` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  KEY `kode_peserta` (`kode_peserta`),
  KEY `id_kelompok` (`id_kelompok`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_peserta: ~11 rows (approximately)
DELETE FROM `tbl_peserta`;
INSERT INTO `tbl_peserta` (`id`, `id_user`, `id_kategori`, `id_platform`, `created_at`, `updated_at`, `kode_peserta`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_hp`, `email`, `id_kelompok`, `status`, `qr_code`, `tripay_reference`, `tripay_pay_url`) VALUES
	(2, 2, 4, 3, '2025-08-08 13:12:41', '2025-08-08 13:12:42', '38267293', 'Mikhael Felian Waskito', 'Semarang', '1992-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABT0lEQVRoge3ZQa7DIAwEUEscjKv7YEg0wbExLKqsIjEaK+pP4bHKQKBf+ssSwq+hile5G7Tcl1XuIkSA0VxcNanbCEIUqOOxbyOeG+8iBINzRNtbCMGgTX2Lw//0EB4K57wXqVcc7LMsXYQIULxsxucrdRECwKUsEb4A5CIEgBqJGH+vqW9Xvq+EGNDe4TJf6ZrOZzkUhKdDU+Kv8Uc1j4kv/IQAcD752K2P/vy1dkIEGKWRAnkW+O4rvRZCBKhxIEtL/rPYy2hvsbMnPBzGIaykQZ6FSAohCFxf5jVv39oSCsLTYU8RyFNfIiyEEDCXRiJGEHILIQDU/PBl7t3Ej2UqsQMgPBz6up5vrDNC0TshCLRmi0CMiLAQYsLIgi0GzX9E3/8jTggBzbZ0Oh9dhBjQKuA949cfXer+ViA8E4qXT/oIhY2wLkIA+LIIP4Y/ylyLWbds70YAAAAASUVORK5CYII=', 'DEV-T43038269482ZIVSB', 'https://tripay.co.id/checkout/DEV-T43038269482ZIVSB'),
	(3, 2, 4, 4, '2025-08-08 13:37:53', '2025-08-08 13:37:54', '56087723', 'Mikhael Waskito', 'Semarang', '1992-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, KOta Semarang', '085741220427', 'mikhaelfelian@gmail.com', 2, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABRUlEQVRoge3Y3QrDIAwF4EAfzFf3wQrZasyPDsauBh5OKMzFz6vG2Fb0xxDCf8MuHteT6NdzWdQpQgQY6cvVLW1bQYgC+7jt24o58ClCMDgHIu1eM4Rw0La+lcP36iE8FOa+t3IoRRFThAhQPGzH16tMEQLAJawivAHUIASAPSpi/L63vvWAGLdxAhACQL1na7eIrZ+lQYgCLadu+5VLu+QJQIgB7V/akdF4eJfsFIRHQ/WOnlUgs8Grd/pRFITHw2dyRPMqUPXvqZ4XQgxYdn8vL+KtHPKEONBu/rrjszpuQhyocf/9PI+uIDKPfUIAWKP7O1mXfKBrSggC8/7HfDzTrc2e8HjofX3pBLIUhSohCLS0kZiMYiHEhJ6e4/KOTogGt34f5zwhCtStEHT/6NL2U4HwTCgeH0VhK2yKEAD+GIR/hi8Eb9Gq1uMPGgAAAABJRU5ErkJggg==', 'DEV-T43038269494AEYIH', 'https://tripay.co.id/checkout/DEV-T43038269494AEYIH'),
	(4, 2, 4, 5, '2025-08-08 13:39:06', '2025-08-08 13:39:07', '45750574', 'Mike', 'Semarang', '1992-02-19', 'L', 'Jl. Mugas Dlm XI / 1', '085741220427', 'mikhaelfelian@gmail.com', 2, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABQUlEQVRoge3YXQrEIAwE4EAP5tVzMMGl5qfRhaVPCw4TSunq51PHtF0ZL0sI/w1Voq57QK/7sKpThAgwh69QXdq2ghAF6rzt2wq/iClCMOgXIq2vI4Rw0La+xeF3eggPhc++tziUUOQUIQKUKNvx9ShThABwqZKIrQgBoGYixFUrK/wgxICx11v5Jrt/xuN9lI8zQgBoKZC49ge7PGdCBFjnLQJzpP7MTkF4NiyJGJGFbPnZFQiRYLPzDIIlQiXGCVGgRhxGmWzlIU8IBWfp1+vb6HsoCE+HfvPXjzOvK5s94dmwVi6q/aANQhCo9eZLvKp3z4KWZk94PCydfuS8LKEYgxAE2vBzkedZhJgw390sDt2PaACEQDA+u73fx1NdCVGgpyDgvePXP13a/lQgPBNKVDb4CIWtsBFCAPiyCP8MP5sEnO5b0yULAAAAAElFTkSuQmCC', 'DEV-T43038269496S9MIU', 'https://tripay.co.id/checkout/DEV-T43038269496S9MIU'),
	(5, 1, 4, 5, '2025-08-08 13:51:17', '2025-08-08 13:51:17', '58522402', 'Irfan Rizky', 'KOTA SEMARANG', '1992-05-16', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'cf.esensia@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABQElEQVRoge3Y0QqDMAwF0EA/zF/PhxW6zXjT1InsaeDlBpliTvdimlZt/Bgm+G/ohmifG94+R0RNCTLAvN2gum2nEYIs0PfHfhpxXCAlSAZvLgTJYEz9KIf76hF8KJzz3mzr79T+25aUIAM0RMz4epSUIAFcIioCDaCGIAH0rIj9vEW/Rxs4DkEa+PV+5rU0+uwUggTQsYx7bOU6ygS/ggRwzvXcp0eZ5ObdogEIPh7GedRXsdLy8w8EGWC72rtF3o56MUEaOI7VO0bUfuBlVRBkgDnR7ao6uiAPzCfvuZ6jOgz3BQlgDW/LuIEdnCAH9PXhe305W5u94ONhmxUx62ItijEESaBnRRiW9zaLRZAVWpaGzQ/naACCRDBt5LGquyALzGbv6AFj/eiynVcFwWdCQ3wVRYyIlCAB/DEE/wxfEA399l+P/zIAAAAASUVORK5CYII=', NULL, NULL),
	(6, 1, 4, 5, '2025-08-08 13:52:52', '2025-08-08 13:52:52', '84960167', 'Maudy Ayunda', NULL, '1992-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', NULL, 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABRElEQVRoge3YTYrDMAwFYIEP5qv7YAZNJ/p1m0VXhTyeCCW1vsymyoszol+WEP4aLokauuKw6i1CBJjLr5Mtcl6XLUIMuHIirhPn42gRQsLbE0I0aBNxfU4lBIQV9tfDfOqxcvtUIHwklKiYizpaixAAvtWKGJjnOiEAtF9+ahsEu+8t76XCnvDp8HMicijWqBYhAtxtb96D/zUp+/+wGSEEgNaflu6xnBelJUSAw290b+Y4XNfNSAJCAKh94xYjUF/VixABtlvfIt+aZjMbCAGgNSWWJTbvXmfYEz4a6vlatm6zXwlBYJbPQua91nWEANB//MiAqXXT92EhRICR6+vcuOVQ6CbEgRnz2c+HfI4GIRi0E5+R+I/Lx26PEAL2MGijMZUQBGZzjXj5bmFvf4YQAUrUqBeyesJvXyEEgF8W4Y/hH4jSqv2V1LtbAAAAAElFTkSuQmCC', NULL, NULL),
	(7, 1, 4, 5, '2025-08-08 13:56:47', '2025-08-08 13:56:48', '87022874', 'Mikhael Felian Waskito', 'Semarang', '1992-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 2, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABPklEQVRoge3YQQ6DMAwEQEt5WL6ehyG5JY43BnHoqRKrtVCVJlMuLA7U/McywX/DYVnNRx5RdUmQAWL6OzjMrr/DkiAHHEjEHCzeLkuClPBxIMgGIxHzs7sgIdzNfm7m3S8zj7uC4CuhZWUu9lGWBAngrUa2gX6dFySAceW7lyAUi6+CHLAf84AtG/tZcyxIAmsi0PjzBNbQAATfD6O15/u3+/4FrCAPPNb0jkPc+tkJBAkgInCxbasoQQaYtzjSEYthLQMiSACxpZ/rsVL+bsFpBAmgl+f0UVR0BctJQQ6IGmjz4G0NBAnguvjz+vuxPrG9R1Ls/q+r4Dth24k4q27s2N4FWWBc9tGKKkmJaAiSwRqQgef3h1AIUsFoBlHdBUngDkK7v5/hNIIM0LLypre2nuM6ciFIAX8swT/DDwlfH8VKURkPAAAAAElFTkSuQmCC', 'DEV-T4303826949984BUK', 'https://tripay.co.id/checkout/DEV-T4303826949984BUK'),
	(8, 1, 4, 5, '2025-08-08 14:15:25', '2025-08-08 14:15:27', '73283565', 'Mikhael Felian Waskito', 'Semarang', '1990-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABTUlEQVRoge3YWwoCMQwF0EAX1q1nYQNRp3lVFPwSerllEEnP+NM0TRX7cQjhv6FKjGF2yTTTcQfHNkWIAIcv+2v+ts8xI5JThBjQUyCz4C0yCAGhZ8R1P0aIDHXt+FSEcLCKvUQ6ZJp8ORUIj4QSI/KinjZFCAD7UGlbf2xThABQ26af0aT7p3hDNwlRoOxX8Lm3cmuKEACuJn1V+tf3SArdI4QgcGy730f+gPjBTng69JUXD1vv1q/q7AgBoGVRt1IW9T7fJgSAmod57HWVdhePwkAIAHP95/4HasUts4fwbOjhtvWLy3YqEJ4O+9DVxLUar1LZQ3g69MWXKvbWejeNc4AQAY6WEVnypVUFIcSBK6yjtv5bpkwjRIPaLmQar2axJ0SCqwAsaP269qEAEB4Js9hr2/orF/xVQgwYM5kXksXAKkIIAH8chH+GDydV9ju8nCQKAAAAAElFTkSuQmCC', 'DEV-T43038269505S5ZUA', 'https://tripay.co.id/checkout/DEV-T43038269505S5ZUA'),
	(9, 1, 4, 6, '2025-08-08 15:04:26', '2025-08-08 15:04:28', '89400859', 'Mikhael Felian Waskito', 'Semarang', '2025-08-08', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 2, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABSUlEQVRoge3YQQrDMAwEQEEepq/rYQa1RFrZLjnkVLBYU0qwJr1kK9sRfzmE8N/QBONywyfGWiLsAGv6ezFE9vuqRNgDWiXivkh+bSXCljDTQdgbroSwI5zN/l7M1beZx1WB8EgoGGsWKhEoETaAP8PQBnSfJ2wA48mr70GANZnNnvB4iMW8ElGhsGj2IzsF4fHwmwicv+tCrpxXHNQIO0A8+Zpzn0t6WsIeEHOCTmBRx936sGEnPBJWj8+Bg3ipGIQd4EAcJHdtUbSavDlhA2jYpCuK6ltGdK4KhGdDRwTyjvUbF+qEfWBywXqOXBjWAcIGMB++YKt+zT/9mhTCDhB93Zbl3ZdQ+CDsA+Oxz4pvSYloEDaDFZCwOn7ezhA2gvu/P/qByHY4IzwaOooms1L9IH6GsANEMXLheAejeJ8qhF3gy0H4Z/gB/9n3oimRfRsAAAAASUVORK5CYII=', 'DEV-T43038269516QP6WN', 'https://tripay.co.id/checkout/DEV-T43038269516QP6WN'),
	(10, 1, 4, 5, '2025-08-08 15:38:52', '2025-08-08 15:38:54', '19404596', 'Mikhael Felian Waskito', 'zdf', '1992-02-15', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABPUlEQVRoge3ZTYrDMAwFYIEP5qvrYAbN1LJ+3FlMV4U8ngilsT+toioWFfswhPDbUCVimC2Ze1n3bd8iRIDjPHbdO3O9Nl6f49oixICnBHZRpMpU3yIEg14UfuUKISQ8hRBdnxAPVrNP9d9bgfCRUCJGWc1ff2wRAsC/oa0cMggBoGZFtC/5ej8XIQzMEbwN4nN54mn8hCBQ6sg2Yz6bdjV+QgSY87fdg3iWSTQAQgToZNSyK23VQYgDd+TPvWBsEWLAtx5v2eklDneDEAdKawPndp2KIESCFmdzkerx1wohBOzxS3p1WPR7Qgyo+fTvri85mUkfxAmfDOMQp1EOs1WHxjmOEAP6ci+BqpTIIMSD1ke0fMm//yNOiAJjSut5hBjQwzOmXRkSSYQIUCISSlWErWoAhE+HHwbhl+EPi1OhJt22lloAAAAASUVORK5CYII=', 'DEV-T43038269533C4YQQ', 'https://tripay.co.id/checkout/DEV-T43038269533C4YQQ'),
	(11, 1, 4, 5, '2025-08-08 15:41:31', '2025-08-08 15:41:33', '87952740', 'Mikhael Felian Waskito', 'Semarang', '2025-08-08', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABPElEQVRoge3YQa7DIAwEUEscjKv7YEhugxkwX79SV12MxouK4Ec2MSapxZdhgr+GbogW3ubksB7PuKYEGWBbj90TjrXC25US5ICOingPnnKwYjEW5IOWvPYAQUa4dv+8DEFGeJp9Je1KCTJAQ7ST990PkBIkgH/CDW2+XfOCBNDLi1ugDfTMjueEL9UjyADz4zuQNxzy+1KQAWYV1CRqoc8ayXsIcsCIqzS8nO2B/2AEGaChHLbF1nc0fkESWPp6LtoHu5exIAGMMvepOgSZ4Ank/f4+EySATy2Ul7i+D/Y5v7u+IAGs4dj6hopwQSK4Hn55/v/2AEEGiKGX13Pf63APQQ6Y097uMUjeSZAMroP9Puez9wvyQZuq70+0WFyQA2bsTd934x+nBwgyQEOgOgL5GOdXkAB+GYI/hi/v6/9dEg5hOwAAAABJRU5ErkJggg==', 'DEV-T43038269538VQ9EN', 'https://tripay.co.id/checkout/DEV-T43038269538VQ9EN'),
	(12, 1, 4, 1, '2025-08-08 15:44:36', '2025-08-08 15:44:36', '73244602', 'Mikhael Felian Waskito', 'Semarang', '2025-08-08', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'cf.esensia@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABSUlEQVRoge3Y0QrDMAgFUCEf5q/7YQW3xmgsfVifCrtcGWOrJ3uZNabiD0MI34YmGcP9EJ2XbX7tKUIEONbfbjOjx5k438clRYgBVwnMoihVSyNFCAajKOJVVwgh4SqE7PqEeHA3+1K/dgXCv4SSMba1uvszRQgA72GtHCoIAaBVRfTSaDu8zsZPCAA9x3Of52+NT8c6pUnO74QYUNvIpnk+07bVGyEKjLtfffWAID5t/QwhAryN6up7nebkTggALc7f15veG48gBIGt2cv9iYusUxwhALQ6ih07s5q9E2LBau2Z0ZzWJfd5QgDY40tk7Orw2uEJIaDVvz92OdTsVi2fEAHWE5fZ4CWbQS8Kd0IQGJd3CVybfawgxINRHftzNgNCPGjZ8tUv6wgxYDX7/oqKkFxEiABbU7e2q1tO7tUACP8dPgzCl+EHFDfkpjpAxjYAAAAASUVORK5CYII=', NULL, NULL),
	(13, 1, 4, 1, '2025-08-08 15:48:27', '2025-08-08 15:48:27', '95618027', 'Mikhael Felian Waskito', 'KOTA SEMARANG', '2025-08-08', 'L', 'Perum Mutiara Pandanaran Blok D11, Mangunharjo, Tembalang, Kota Semarang', '085741220427', 'mikhaelfelian@gmail.com', 1, 1, 'iVBORw0KGgoAAAANSUhEUgAAAUAAAAFAAQMAAAD3XjfpAAAABlBMVEUEAgT8/vxJvsdeAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABSElEQVRoge3YUYrDMAwEUIEP5qvrYAaV2J6J0l1ovwoWI0Ih0XN/OlGcWnxZJvhr6IZqEcP6vOzzNLcEK8C2f3afnT6uxvXZHi3BGnBHYIaCiktXS7AYXKFYB68IloQ7CJj6gvXgPeypPj0VBI+Ehmq3dd79aAkWgH/LUxxYggWgMxE5GukJ3+fgFywCkYKrvzIw9luaYf8uWABGehVjv3MTh0OwAmQWnimIsS9iAAgeD9etH2yyjy9AKAQrwDXv4+39LM/+ECwF2Q9OesPm7p9/XQWPhLz7zXYWjItCsBZcZ4YxYPdu3TDvBQvAXA4bIy0SrAKdv37DgMee3XlFsAZsSATi0FM6HPs4wRrQ7RmB57BfKwTrQabD80P+fWcveD7Ehr3j/WytcMEqcFUOBRNhWCRYARqKMwA7uKvGPQAET4dfluCP4QtqiwiRjfNtxwAAAABJRU5ErkJggg==', NULL, NULL);

-- Dumping structure for table db_p56_masukin.tbl_racepack
DROP TABLE IF EXISTS `tbl_racepack`;
CREATE TABLE IF NOT EXISTS `tbl_racepack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_racepack` varchar(50) NOT NULL,
  `nama_racepack` varchar(255) NOT NULL,
  `id_kategori` int(11) unsigned DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gambar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `id_user` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_racepack` (`kode_racepack`),
  KEY `tbl_racepack_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `tbl_racepack_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `tbl_kategori_racepack` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_racepack: ~2 rows (approximately)
DELETE FROM `tbl_racepack`;
INSERT INTO `tbl_racepack` (`id`, `kode_racepack`, `nama_racepack`, `id_kategori`, `deskripsi`, `harga`, `gambar`, `status`, `id_user`, `created_at`, `updated_at`) VALUES
	(1, '4324', 'Kaos', 1, 'dsads', 50000.00, NULL, 1, 2, '2025-08-08 07:23:44', '2025-08-08 08:56:15'),
	(2, '65767', 'Tumbler', 1, 'asdsd', 25000.00, NULL, 1, 2, '2025-08-08 07:24:37', '2025-08-08 07:24:37');

-- Dumping structure for table db_p56_masukin.tbl_sessions
DROP TABLE IF EXISTS `tbl_sessions`;
CREATE TABLE IF NOT EXISTS `tbl_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan session data';

-- Dumping data for table db_p56_masukin.tbl_sessions: ~12 rows (approximately)
DELETE FROM `tbl_sessions`;
INSERT INTO `tbl_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
	('masukinaja_session:3bb4cc892b91f9e3550447379b28f525', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232323935353b6d6173756b696e616a615f746f6b656e7c733a33323a226136323433383133623166383864343061303430303564333836323235666432223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('masukinaja_session:4501bb4c3054e574051bbc59b6c9c518', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232313635353b6d6173756b696e616a615f746f6b656e7c733a33323a223562346133616536316465333830363734356231303765366463626438663832223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b),
	('masukinaja_session:4a4a628eda6a11098c25244a81ef8486', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232303339353b6d6173756b696e616a615f746f6b656e7c733a33323a223331353833633562306661633535316237656436383738353661386461666535223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('masukinaja_session:5e52f37aba8fe285d23b38cb7865b61a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363231393739323b6d6173756b696e616a615f746f6b656e7c733a33323a226336303866306364623633323131343635656265383736303562333032653934223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('masukinaja_session:81dcd1beea92dcaee13fc540de980646', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363231373633353b6d6173756b696e616a615f746f6b656e7c733a33323a223337626632613263343463376262326664666438613536313532353336623366223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b),
	('masukinaja_session:83a77e00442ab4f6961801b823ef7cc8', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363231383233373b6d6173756b696e616a615f746f6b656e7c733a33323a226331323631343331313361383365663466376361353237353933316337393535223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('masukinaja_session:c09cbe08ce554866a4645d7b21e6ac35', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232303939393b6d6173756b696e616a615f746f6b656e7c733a33323a223331353833633562306661633535316237656436383738353661386461666535223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('masukinaja_session:ca1706d0ce1287d900856d5b65dc3eee', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232323935353b6d6173756b696e616a615f746f6b656e7c733a33323a226136323433383133623166383864343061303430303564333836323235666432223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b),
	('masukinaja_session:e6155e1a04bcf6442e66a1036af3fb9c', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232323332323b6d6173756b696e616a615f746f6b656e7c733a33323a226464623932623864316366383863643636346535303531336361653730343438223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b),
	('masukinaja_session:f244b2902b57b95129f3dd2561051f76', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363231393139313b6d6173756b696e616a615f746f6b656e7c733a33323a223265393035303361666333366135366466373135396630343035636433616361223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323034323839223b6c6173745f636865636b7c693a313735363231373633353b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:1127cb09f93f6a1f16cd29f2d0ee3ecc', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232333331363b77677469785f746f6b656e7c733a33323a223463363661393838363566656630383731656232316634353137653139386566223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:2fed6069e31791435c860f0c7187be50', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232333931373b77677469785f746f6b656e7c733a33323a223463363661393838363566656630383731656232316634353137653139386566223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:5a5a143400aab73e0763f2b514e0e963', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363331393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:6e981e78de8b1ed7b486258bfdb2e69e', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232353638303b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:78124908662a856afdf0d7b8e65c47a5', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232343538303b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:7f1541f91772277ab9ec545152963735', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363934393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:b9e00395ec60d85d21139a184139e490', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363934393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b);

-- Dumping structure for table db_p56_masukin.tbl_stock_racepack
DROP TABLE IF EXISTS `tbl_stock_racepack`;
CREATE TABLE IF NOT EXISTS `tbl_stock_racepack` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_racepack` int(11) unsigned NOT NULL,
  `id_ukuran` int(11) unsigned NOT NULL,
  `stok_masuk` int(11) NOT NULL DEFAULT 0,
  `stok_keluar` int(11) NOT NULL DEFAULT 0,
  `stok_tersedia` int(11) NOT NULL DEFAULT 0,
  `minimal_stok` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `id_user` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tbl_stock_racepack_id_racepack_foreign` (`id_racepack`),
  KEY `tbl_stock_racepack_id_ukuran_foreign` (`id_ukuran`),
  CONSTRAINT `tbl_stock_racepack_id_racepack_foreign` FOREIGN KEY (`id_racepack`) REFERENCES `tbl_racepack` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_stock_racepack_id_ukuran_foreign` FOREIGN KEY (`id_ukuran`) REFERENCES `tbl_m_ukuran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_stock_racepack: ~0 rows (approximately)
DELETE FROM `tbl_stock_racepack`;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

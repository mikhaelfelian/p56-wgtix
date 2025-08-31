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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.migrations: ~28 rows (approximately)
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
	(26, '20250807192528', 'App\\Database\\Migrations\\CreateTblMEventGaleri20250807194526', 'default', 'App', 1756203477, 15),
	(27, '20250831114456', 'App\\Database\\Migrations\\CreateTblPostsCategory', 'default', 'App', 1756641158, 16),
	(28, '20250831114457', 'App\\Database\\Migrations\\CreateTblPosts', 'default', 'App', 1756641158, 16),
	(29, '20250831114458', 'App\\Database\\Migrations\\CreateTblPostsGaleri', 'default', 'App', 1756641158, 16);

-- Dumping structure for table db_p56_masukin.tbl_ion_groups
DROP TABLE IF EXISTS `tbl_ion_groups`;
CREATE TABLE IF NOT EXISTS `tbl_ion_groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_ion_groups: ~5 rows (approximately)
DELETE FROM `tbl_ion_groups`;
INSERT INTO `tbl_ion_groups` (`id`, `name`, `description`) VALUES
	(1, 'root', 'Root - Highest Level Access'),
	(2, 'superadmin', 'Super Administrator'),
	(3, 'manager', 'Manager'),
	(4, 'supervisor', 'Supervisor'),
	(5, 'user', 'Pengguna');

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
	(2, '127.0.0.1', 'superadmin', '$2y$10$gQoEoZYp8Rz2iK9m.c1nZOQ3mJy53.Bb89WoV4m9/RxUTRVpY2FGW', 'superadmin@admin.com', NULL, '', NULL, NULL, NULL, NULL, NULL, 1268889823, 1756647443, 1, 'Superdamin', 'Admin', 'ADMIN', '', 'public/assets/theme/admin-lte-3/dist/img/user2-160x160.jpg', '1'),
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

-- Dumping data for table db_p56_masukin.tbl_ion_users_groups: ~6 rows (approximately)
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

-- Dumping data for table db_p56_masukin.tbl_kategori_racepack: ~1 rows (approximately)
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

-- Dumping data for table db_p56_masukin.tbl_kelompok_peserta: ~2 rows (approximately)
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
  `latitude` varchar(50) DEFAULT NULL COMMENT 'Latitude lokasi event',
  `longitude` varchar(50) DEFAULT NULL COMMENT 'Longitude lokasi event',
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event: ~50 rows (approximately)
DELETE FROM `tbl_m_event`;
INSERT INTO `tbl_m_event` (`id`, `id_user`, `id_kategori`, `created_at`, `updated_at`, `deleted_at`, `kode`, `event`, `foto`, `tgl_masuk`, `tgl_keluar`, `wkt_masuk`, `wkt_keluar`, `lokasi`, `jml`, `latitude`, `longitude`, `keterangan`, `status`, `status_hps`) VALUES
	(13, 2, 6, '2025-08-31 00:57:27', '2025-08-31 16:58:53', NULL, 'EVT013', 'Semarang Fun Run 5K', '1756580919_549b21820042bb0c34f3.jpg', '2025-09-01', '2025-09-01', '06:00:00', '09:00:00', 'Semarang', 1500, '-6.963031', '110.391078', '<p style="text-align: justify;"><strong>Fun Run: Lari Santai untuk Semua</strong><br>Fun run adalah kegiatan lari santai yang bisa diikuti siapa saja tanpa memandang usia maupun kemampuan. Tidak ada target waktu, tidak ada persaingan ketat, yang ada hanya keseruan berlari bersama di jalur yang sudah disiapkan. Anak sekolah, mahasiswa, pekerja kantoran, bahkan orang tua bisa ikut meramaikan acara ini tanpa harus memikirkan kecepatan atau menjadi juara. Konsepnya memang dirancang agar peserta bisa menikmati setiap langkah, melihat pemandangan di sepanjang rute, dan merasakan kebersamaan dengan ratusan bahkan ribuan orang lain yang memiliki tujuan sama: bergerak bersama demi kesehatan dan kesenangan.<br>Meski konsepnya santai, manfaat fun run untuk kesehatan tidak bisa dianggap remeh. Berlari ringan selama 20&ndash;30 menit saja sudah cukup membantu melancarkan peredaran darah, menjaga kesehatan jantung, serta meningkatkan daya tahan tubuh. Selain itu, aktivitas fisik seperti ini merangsang pelepasan hormon endorfin yang bisa mengurangi stres dan membuat suasana hati menjadi lebih baik. Banyak peserta yang mengaku merasa lebih segar dan berenergi setelah mengikuti fun run, bahkan ada yang menjadikannya kegiatan rutin setiap bulan karena efek positifnya terhadap tubuh dan pikiran.<br>Hal lain yang membuat fun run menarik adalah kemudahan aksesnya. Untuk mengikuti acara ini, peserta tidak perlu peralatan mahal atau perlengkapan rumit. Cukup mengenakan sepatu lari yang nyaman, pakaian olahraga yang ringan, serta semangat untuk bergerak, semua orang sudah bisa ikut berlari. Rute yang disiapkan penyelenggara pun biasanya ramah pemula, melintasi area yang aman dan menyenangkan seperti taman kota, jalan raya yang ditutup sementara, atau kawasan wisata yang indah. Hal ini menjadikan fun run tidak hanya sebagai olahraga, tapi juga kesempatan untuk rekreasi sambil berolahraga.<br>Selain faktor kesehatan dan kemudahan, suasana kebersamaan menjadi daya tarik utama fun run. Banyak peserta datang bersama teman, keluarga, bahkan rekan kerja. Selama berlari, mereka saling menyemangati, bercanda, dan menikmati momen bersama. Tidak jarang, acara ini dilengkapi dengan panggung hiburan, bazar makanan, dan kegiatan sosial seperti penggalangan dana untuk amal. Kombinasi antara olahraga, hiburan, dan aksi sosial membuat fun run menjadi pengalaman yang lengkap dan berkesan bagi setiap pesertanya.<br>Bagi pemula, fun run juga bisa menjadi titik awal untuk memulai gaya hidup sehat. Tidak perlu langsung berlari jauh atau cepat, cukup mulai dengan berjalan cepat lalu perlahan-lahan meningkatkan kecepatan. Penting untuk melakukan pemanasan sebelum mulai berlari agar otot tidak kaget, serta membawa cukup minum agar tubuh tetap terhidrasi. Dengan cara ini, risiko cedera bisa diminimalkan dan tubuh akan beradaptasi dengan baik terhadap aktivitas fisik yang baru.<br>Secara keseluruhan, fun run adalah olahraga sederhana dengan segudang manfaat. Ia tidak hanya membuat tubuh lebih sehat, tetapi juga membantu mengurangi stres, mempererat hubungan sosial, dan memberikan pengalaman seru yang jarang ditemui dalam olahraga lain. Dengan konsep yang inklusif dan menyenangkan, fun run telah menjadi tren positif di banyak kota. Sekali mencoba, banyak orang yang kemudian ketagihan untuk ikut lagi karena selain menyehatkan tubuh, kegiatan ini juga menyehatkan pikiran dan hati.</p>\r\n<p>&nbsp;</p>', '1', '0'),
	(14, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT014', 'Jogja 5K City Run', NULL, '2025-09-02', '2025-09-02', '06:00:00', '09:00:00', 'Yogyakarta', 1200, NULL, NULL, 'Rute Malioboro–Alun-Alun.', '1', '0'),
	(15, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT015', 'Surabaya 10K Challenge', NULL, '2025-09-03', '2025-09-03', '06:00:00', '10:00:00', 'Surabaya', 2000, NULL, NULL, 'Tantangan 10K untuk semua level.', '1', '0'),
	(16, 2, 4, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT016', 'Bandung Half Marathon 21K', NULL, '2025-09-04', '2025-09-04', '06:00:00', '10:00:00', 'Bandung', 2500, NULL, NULL, 'Rute elevasi ringan, pemandangan kota.', '1', '0'),
	(17, 2, 5, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT017', 'Jakarta Marathon 42K', NULL, '2025-09-05', '2025-09-05', '05:00:00', '11:00:00', 'Jakarta', 3000, NULL, NULL, 'Marathon jalan raya Jakarta.', '1', '0'),
	(18, 2, 7, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT018', 'Bromo Trail Run 10K', NULL, '2025-09-06', '2025-09-06', '06:00:00', '10:00:00', 'Probolinggo', 800, NULL, NULL, 'Trail 10K di kawasan Bromo.', '1', '0'),
	(19, 2, 8, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT019', 'Merapi Trail Run 21K', NULL, '2025-09-07', '2025-09-07', '06:00:00', '11:00:00', 'Sleman', 900, NULL, NULL, 'Trail menanjak rute Merapi.', '1', '0'),
	(20, 2, 11, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT020', 'Java Cycling 50K - Semarang', NULL, '2025-09-08', '2025-09-08', '06:00:00', '09:30:00', 'Semarang', 1000, NULL, NULL, 'Gowes santai 50K.', '1', '0'),
	(21, 2, 12, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT021', 'MTB XC 25K - Ungaran', NULL, '2025-09-09', '2025-09-09', '06:00:00', '09:30:00', 'Ungaran', 600, NULL, NULL, 'XC hutan pinus Ungaran.', '1', '0'),
	(22, 2, 9, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT022', 'Duathlon Sprint Semarang', NULL, '2025-09-10', '2025-09-10', '06:00:00', '10:00:00', 'Semarang', 400, NULL, NULL, 'Lari–sepeda–lari.', '1', '0'),
	(23, 2, 10, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT023', 'Triathlon Sprint Karimunjawa', NULL, '2025-09-11', '2025-09-11', '06:00:00', '10:30:00', 'Jepara', 300, NULL, NULL, 'Renang laut + sepeda + lari.', '1', '0'),
	(24, 2, 6, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT024', 'Color Fun Run Solo', NULL, '2025-09-12', '2025-09-12', '06:00:00', '09:00:00', 'Surakarta', 1800, NULL, NULL, 'Fun run bertema warna.', '1', '0'),
	(25, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT025', 'Makassar 5K Waterfront', NULL, '2025-09-13', '2025-09-13', '06:00:00', '09:00:00', 'Makassar', 900, NULL, NULL, 'Rute pantai LOSARI.', '1', '0'),
	(26, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT026', 'Medan 10K Heritage', NULL, '2025-09-14', '2025-09-14', '06:00:00', '10:00:00', 'Medan', 1100, NULL, NULL, 'Lewat area heritage Medan.', '1', '0'),
	(27, 2, 4, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT027', 'Bali Half Marathon 21K', NULL, '2025-09-15', '2025-09-15', '05:30:00', '10:00:00', 'Denpasar', 2200, NULL, NULL, 'Rute pesisir Bali.', '1', '0'),
	(28, 2, 5, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT028', 'Borobudur Marathon 42K', NULL, '2025-09-16', '2025-09-16', '05:00:00', '11:00:00', 'Magelang', 3200, NULL, NULL, 'Ikonik Borobudur.', '1', '0'),
	(29, 2, 7, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT029', 'Tawangmangu Trail 10K', NULL, '2025-09-17', '2025-09-17', '06:00:00', '10:00:00', 'Karanganyar', 700, NULL, NULL, 'Trail sejuk Tawangmangu.', '1', '0'),
	(30, 2, 8, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT030', 'Malang Trail 21K', NULL, '2025-09-18', '2025-09-18', '06:00:00', '11:00:00', 'Malang', 800, NULL, NULL, 'Gunung & kebun teh.', '1', '0'),
	(31, 2, 11, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT031', 'Cycling 50K - Jogja Roundtrip', NULL, '2025-09-19', '2025-09-19', '06:00:00', '09:30:00', 'Yogyakarta', 900, NULL, NULL, 'Jelajah ringroad & desa.', '1', '0'),
	(32, 2, 12, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT032', 'MTB XC 25K - Batu', NULL, '2025-09-20', '2025-09-20', '06:00:00', '09:30:00', 'Batu', 650, NULL, NULL, 'XC kebun apel Batu.', '1', '0'),
	(33, 2, 6, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT033', 'Neon Night Run 5K - Semarang', NULL, '2025-09-21', '2025-09-21', '19:00:00', '21:30:00', 'Semarang', 1700, NULL, NULL, 'Fun run malam lampu neon.', '1', '0'),
	(34, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT034', 'Palembang 5K Riverside', NULL, '2025-09-22', '2025-09-22', '06:00:00', '09:00:00', 'Palembang', 800, NULL, NULL, 'Rute Jembatan Ampera.', '1', '0'),
	(35, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT035', 'Pontianak 10K Equator Run', NULL, '2025-09-23', '2025-09-23', '06:00:00', '10:00:00', 'Pontianak', 900, NULL, NULL, 'Lari dekat Tugu Khatulistiwa.', '1', '0'),
	(36, 2, 4, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT036', 'Manado Half Marathon 21K', NULL, '2025-09-24', '2025-09-24', '06:00:00', '10:00:00', 'Manado', 1200, NULL, NULL, 'Rute pesisir Sulawesi Utara.', '1', '0'),
	(37, 2, 5, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT037', 'Riau Marathon 42K', NULL, '2025-09-25', '2025-09-25', '05:00:00', '11:00:00', 'Pekanbaru', 1400, NULL, NULL, 'Marathon datar cepat.', '1', '0'),
	(38, 2, 7, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT038', 'Dieng Trail 10K', NULL, '2025-09-26', '2025-09-26', '06:00:00', '10:00:00', 'Wonosobo', 750, NULL, NULL, 'Dataran tinggi Dieng.', '1', '0'),
	(39, 2, 8, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT039', 'Sindoro Sumbing Trail 21K', NULL, '2025-09-27', '2025-09-27', '06:00:00', '11:30:00', 'Temanggung', 700, NULL, NULL, 'Trail pegunungan kembar.', '1', '0'),
	(40, 2, 11, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT040', 'Cycling 50K - Solo Raya', NULL, '2025-09-28', '2025-09-28', '06:00:00', '09:30:00', 'Surakarta', 850, NULL, NULL, 'Jelajah urban & desa.', '1', '0'),
	(41, 2, 12, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT041', 'MTB XC 25K - Semarang Barat', NULL, '2025-09-29', '2025-09-29', '06:00:00', '09:30:00', 'Semarang', 600, NULL, NULL, 'XC bukit Semarang.', '1', '0'),
	(42, 2, 6, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT042', 'Family Color Run Kudus', NULL, '2025-09-30', '2025-09-30', '06:00:00', '09:00:00', 'Kudus', 1200, NULL, NULL, 'Fun run keluarga.', '1', '0'),
	(43, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT043', 'Bekasi 5K Industrial Run', NULL, '2025-10-01', '2025-10-01', '06:00:00', '09:00:00', 'Bekasi', 1000, NULL, NULL, 'Rute kawasan industri.', '1', '0'),
	(44, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT044', 'Depok 10K City Race', NULL, '2025-10-02', '2025-10-02', '06:00:00', '10:00:00', 'Depok', 1100, NULL, NULL, 'Rute kota Depok.', '1', '0'),
	(45, 2, 4, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT045', 'Cirebon Half Marathon 21K', NULL, '2025-10-03', '2025-10-03', '06:00:00', '10:00:00', 'Cirebon', 900, NULL, NULL, 'Rute budaya Cirebon.', '1', '0'),
	(46, 2, 5, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT046', 'Semarang Marathon 42K', NULL, '2025-10-04', '2025-10-04', '05:00:00', '11:00:00', 'Semarang', 2200, NULL, NULL, 'Marathon kota Semarang.', '1', '0'),
	(47, 2, 7, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT047', 'Lawu Trail 10K', NULL, '2025-10-05', '2025-10-05', '06:00:00', '10:00:00', 'Karanganyar', 650, NULL, NULL, 'Trail kaki Gunung Lawu.', '1', '0'),
	(48, 2, 8, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT048', 'Salak Trail 21K', NULL, '2025-10-06', '2025-10-06', '06:00:00', '11:00:00', 'Bogor', 800, NULL, NULL, 'Trail Gunung Salak.', '1', '0'),
	(49, 2, 11, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT049', 'Cycling 50K - Bandung Loop', NULL, '2025-10-07', '2025-10-07', '06:00:00', '09:30:00', 'Bandung', 1000, NULL, NULL, 'Loop kota Bandung.', '1', '0'),
	(50, 2, 12, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT050', 'MTB XC 25K - Lembang', NULL, '2025-10-08', '2025-10-08', '06:00:00', '09:30:00', 'Lembang', 700, NULL, NULL, 'XC dataran tinggi Lembang.', '1', '0'),
	(51, 2, 6, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT051', 'Car Free Day Fun Run - Jakarta', NULL, '2025-10-09', '2025-10-09', '06:00:00', '09:00:00', 'Jakarta', 2500, NULL, NULL, 'Fun run CFD.', '1', '0'),
	(52, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT052', 'Tangerang 5K Run', NULL, '2025-10-10', '2025-10-10', '06:00:00', '09:00:00', 'Tangerang', 900, NULL, NULL, 'Rute kota Tangerang.', '1', '0'),
	(53, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT053', 'Balikpapan 10K Coastal', NULL, '2025-10-11', '2025-10-11', '06:00:00', '10:00:00', 'Balikpapan', 800, NULL, NULL, 'Rute pesisir.', '1', '0'),
	(54, 2, 4, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT054', 'Batam Half Marathon 21K', NULL, '2025-10-12', '2025-10-12', '06:00:00', '10:00:00', 'Batam', 1000, NULL, NULL, 'Lintas jembatan Barelang.', '1', '0'),
	(55, 2, 5, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT055', 'NTB Marathon 42K', NULL, '2025-10-13', '2025-10-13', '05:00:00', '11:00:00', 'Mataram', 1200, NULL, NULL, 'Rute pantai & kota.', '1', '0'),
	(56, 2, 7, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT056', 'Rinjani Trail 10K', NULL, '2025-10-14', '2025-10-14', '06:00:00', '10:00:00', 'Lombok', 600, NULL, NULL, 'Trail kaki Rinjani.', '1', '0'),
	(57, 2, 8, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT057', 'Batutegi Trail 21K', NULL, '2025-10-15', '2025-10-15', '06:00:00', '11:00:00', 'Lampung', 650, NULL, NULL, 'Trail waduk Batutegi.', '1', '0'),
	(58, 2, 11, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT058', 'Cycling 50K - Banyuwangi', NULL, '2025-10-16', '2025-10-16', '06:00:00', '09:30:00', 'Banyuwangi', 700, NULL, NULL, 'Gowes sunrise.', '1', '0'),
	(59, 2, 12, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT059', 'MTB XC 25K - Semeru Foothills', NULL, '2025-10-17', '2025-10-17', '06:00:00', '09:30:00', 'Lumajang', 600, NULL, NULL, 'XC kaki Semeru.', '1', '0'),
	(60, 2, 6, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT060', 'Family Fun Run - Magelang', NULL, '2025-10-18', '2025-10-18', '06:00:00', '09:00:00', 'Magelang', 1300, NULL, NULL, 'Fun run keluarga.', '1', '0'),
	(61, 2, 2, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT061', 'Cikarang 5K Industrial', NULL, '2025-10-19', '2025-10-19', '06:00:00', '09:00:00', 'Cikarang', 800, NULL, NULL, 'Rute kawasan industri.', '1', '0'),
	(62, 2, 3, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'EVT062', 'Banjarmasin 10K Riverside', NULL, '2025-10-20', '2025-10-20', '06:00:00', '10:00:00', 'Banjarmasin', 850, NULL, NULL, 'Rute tepi sungai.', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event_galeri: ~5 rows (approximately)
DELETE FROM `tbl_m_event_galeri`;
INSERT INTO `tbl_m_event_galeri` (`id`, `id_event`, `created_at`, `updated_at`, `deleted_at`, `file`, `deskripsi`, `is_cover`, `status`) VALUES
	(1, 13, '2025-08-26 22:55:53', '2025-08-26 22:55:59', '2025-08-26 22:55:59', '1756223753_c223a5cdcc3f53cc5300.jpg', '', 0, 1),
	(2, 13, '2025-08-26 22:55:53', '2025-08-26 22:56:04', '2025-08-26 22:56:04', '1756223753_7e1e18b5b0226cef9bca.png', '', 0, 1),
	(3, 13, '2025-08-26 22:58:12', '2025-08-31 17:46:59', NULL, '1756223892_43b6d53deb298c30e0d5.jpg', 'Testing image 1', 1, 1),
	(4, 13, '2025-08-26 22:58:12', '2025-08-31 17:46:56', NULL, '1756223892_dffbfc0e3e7b2c8389e4.png', 'Testing image 2', 0, 1),
	(5, 13, '2025-08-28 01:23:17', '2025-08-31 17:46:52', NULL, '1756318997_e54921a19b754c5a6613.jpg', 'Testing image 3', 0, 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_m_event_harga: ~100 rows (approximately)
DELETE FROM `tbl_m_event_harga`;
INSERT INTO `tbl_m_event_harga` (`id`, `id_event`, `created_at`, `updated_at`, `deleted_at`, `keterangan`, `harga`, `status`) VALUES
	(3, 13, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 75000.00, '1'),
	(4, 13, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 100000.00, '1'),
	(5, 14, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(6, 14, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(7, 15, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(8, 15, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(9, 16, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 150000.00, '1'),
	(10, 16, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 200000.00, '1'),
	(11, 17, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 250000.00, '1'),
	(12, 17, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 350000.00, '1'),
	(13, 18, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 100000.00, '1'),
	(14, 18, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 130000.00, '1'),
	(15, 19, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 170000.00, '1'),
	(16, 19, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 220000.00, '1'),
	(17, 20, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(18, 20, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 110000.00, '1'),
	(19, 21, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(20, 21, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(21, 22, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 180000.00, '1'),
	(22, 22, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 230000.00, '1'),
	(23, 23, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 350000.00, '1'),
	(24, 23, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 450000.00, '1'),
	(25, 24, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(26, 24, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 100000.00, '1'),
	(27, 25, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(28, 25, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(29, 26, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(30, 26, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(31, 27, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 150000.00, '1'),
	(32, 27, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 200000.00, '1'),
	(33, 28, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 250000.00, '1'),
	(34, 28, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 350000.00, '1'),
	(35, 29, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 100000.00, '1'),
	(36, 29, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 130000.00, '1'),
	(37, 30, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 170000.00, '1'),
	(38, 30, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 220000.00, '1'),
	(39, 31, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(40, 31, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 110000.00, '1'),
	(41, 32, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(42, 32, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(43, 33, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(44, 33, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(45, 34, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(46, 34, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(47, 35, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(48, 35, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(49, 36, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 150000.00, '1'),
	(50, 36, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 200000.00, '1'),
	(51, 37, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 240000.00, '1'),
	(52, 37, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 330000.00, '1'),
	(53, 38, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 100000.00, '1'),
	(54, 38, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 130000.00, '1'),
	(55, 39, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 170000.00, '1'),
	(56, 39, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 220000.00, '1'),
	(57, 40, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(58, 40, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 110000.00, '1'),
	(59, 41, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(60, 41, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(61, 42, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(62, 42, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 100000.00, '1'),
	(63, 43, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(64, 43, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(65, 44, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(66, 44, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(67, 45, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 150000.00, '1'),
	(68, 45, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 200000.00, '1'),
	(69, 46, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 240000.00, '1'),
	(70, 46, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 330000.00, '1'),
	(71, 47, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 100000.00, '1'),
	(72, 47, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 130000.00, '1'),
	(73, 48, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 170000.00, '1'),
	(74, 48, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 220000.00, '1'),
	(75, 49, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(76, 49, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 110000.00, '1'),
	(77, 50, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(78, 50, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(79, 51, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 85000.00, '1'),
	(80, 51, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 115000.00, '1'),
	(81, 52, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(82, 52, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(83, 53, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(84, 53, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(85, 54, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 150000.00, '1'),
	(86, 54, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 200000.00, '1'),
	(87, 55, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 240000.00, '1'),
	(88, 55, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 330000.00, '1'),
	(89, 56, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 100000.00, '1'),
	(90, 56, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 130000.00, '1'),
	(91, 57, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 170000.00, '1'),
	(92, 57, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 220000.00, '1'),
	(93, 58, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(94, 58, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 110000.00, '1'),
	(95, 59, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(96, 59, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1'),
	(97, 60, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 80000.00, '1'),
	(98, 60, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 100000.00, '1'),
	(99, 61, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 70000.00, '1'),
	(100, 61, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 90000.00, '1'),
	(101, 62, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Early Bird', 90000.00, '1'),
	(102, 62, '2025-08-31 00:57:27', '2025-08-31 00:57:27', NULL, 'Regular', 120000.00, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk menyimpan data master kategori events';

-- Dumping data for table db_p56_masukin.tbl_m_kategori: ~11 rows (approximately)
DELETE FROM `tbl_m_kategori`;
INSERT INTO `tbl_m_kategori` (`id`, `id_user`, `created_at`, `updated_at`, `kode`, `kategori`, `keterangan`, `status`) VALUES
	(2, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', '5KUM', '5K Umum', 'Lari 5K kategori umum', '1'),
	(3, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', '10KUM', '10K Umum', 'Lari 10K kategori umum', '1'),
	(4, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', '21KHM', 'Half Marathon 21K', 'Lari 21K', '1'),
	(5, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', '42KFM', 'Full Marathon 42K', 'Lari 42K', '1'),
	(6, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'FRUN', 'Fun Run Family 3K–5K', 'Lari santai keluarga', '1'),
	(7, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'TR10', 'Trail Run 10K', 'Lari trail 10K', '1'),
	(8, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'TR21', 'Trail Run 21K', 'Lari trail 21K', '1'),
	(9, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'DUA', 'Duathlon Sprint', 'Lari + Sepeda (sprint)', '1'),
	(10, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'TRI', 'Triathlon Sprint', 'Renang + Sepeda + Lari', '1'),
	(11, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'CYC', 'Cycling 50K', 'Gowes 50K', '1'),
	(12, 1, '2025-08-31 00:57:27', '2025-08-31 00:57:27', 'MTB', 'MTB XC 25K', 'Cross-country MTB 25K', '1');

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

-- Dumping data for table db_p56_masukin.tbl_m_platform: ~6 rows (approximately)
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

-- Dumping data for table db_p56_masukin.tbl_m_ukuran: ~1 rows (approximately)
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

-- Dumping data for table db_p56_masukin.tbl_pengaturan: ~1 rows (approximately)
DELETE FROM `tbl_pengaturan`;
INSERT INTO `tbl_pengaturan` (`id`, `updated_at`, `judul`, `judul_app`, `alamat`, `deskripsi`, `kota`, `url`, `theme`, `pagination_limit`, `favicon`, `logo`, `logo_header`, `ppn`, `limit`) VALUES
	(1, '2025-08-31 13:51:32', 'WGTIX.COM', 'WGtix | Tiket Event Indonesia', ' Komplek Puri Niaga Center Blok DD 5 No. 12, Semarang', 'WGTIX adalah platform layanan manajemen tiket untuk event, wisata, dan berbagai kegiatan lainnya, memudahkan penjualan, pemesanan, dan pengelolaan tiket secara praktis dan efisien', 'semarang', 'https://localhost/p54-kopmensa', 'quirk', 8, '', 'public/file/app/logo_only.png', 'public/file/app/logo_header.png', 11, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table db_p56_masukin.tbl_peserta: ~0 rows (approximately)
DELETE FROM `tbl_peserta`;

-- Dumping structure for table db_p56_masukin.tbl_posts
DROP TABLE IF EXISTS `tbl_posts`;
CREATE TABLE IF NOT EXISTS `tbl_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned NOT NULL COMMENT 'Penulis/author',
  `id_category` int(11) unsigned DEFAULT NULL COMMENT 'Kategori utama (opsional)',
  `judul` varchar(240) NOT NULL,
  `slug` varchar(260) NOT NULL COMMENT 'slug-unik-artikel',
  `excerpt` text DEFAULT NULL COMMENT 'Ringkasan',
  `konten` longtext DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL COMMENT 'path gambar cover',
  `status` enum('draft','scheduled','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(320) DEFAULT NULL,
  `meta_keywords` varchar(320) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `tbl_posts_id_category_foreign` (`id_category`),
  KEY `status_published_at` (`status`,`published_at`),
  CONSTRAINT `tbl_posts_id_category_foreign` FOREIGN KEY (`id_category`) REFERENCES `tbl_posts_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk posting/artikel';

-- Dumping data for table db_p56_masukin.tbl_posts: ~0 rows (approximately)
DELETE FROM `tbl_posts`;

-- Dumping structure for table db_p56_masukin.tbl_posts_category
DROP TABLE IF EXISTS `tbl_posts_category`;
CREATE TABLE IF NOT EXISTS `tbl_posts_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(160) NOT NULL COMMENT 'Nama kategori',
  `slug` varchar(180) NOT NULL COMMENT 'slug-unik-kategori',
  `deskripsi` text DEFAULT NULL,
  `ikon` varchar(120) DEFAULT NULL COMMENT 'class/icon path opsional',
  `urutan` int(5) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 aktif, 0 nonaktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk master kategori posting';

-- Dumping data for table db_p56_masukin.tbl_posts_category: ~0 rows (approximately)
DELETE FROM `tbl_posts_category`;

-- Dumping structure for table db_p56_masukin.tbl_posts_galeri
DROP TABLE IF EXISTS `tbl_posts_galeri`;
CREATE TABLE IF NOT EXISTS `tbl_posts_galeri` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_post` int(11) unsigned NOT NULL,
  `path` varchar(255) NOT NULL COMMENT 'path file gambar',
  `caption` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 gambar utama',
  `urutan` int(5) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_post_urutan` (`id_post`,`urutan`),
  CONSTRAINT `tbl_posts_galeri_id_post_foreign` FOREIGN KEY (`id_post`) REFERENCES `tbl_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table untuk galeri gambar per posting';

-- Dumping data for table db_p56_masukin.tbl_posts_galeri: ~0 rows (approximately)
DELETE FROM `tbl_posts_galeri`;

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

-- Dumping data for table db_p56_masukin.tbl_sessions: ~85 rows (approximately)
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
	('wgtix_session:019e54c922943b5bec90edbca940faae', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634303137313b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a35353a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f362f66756e2d72756e2d66616d696c792d336b356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:02ce656850f7f76d85b1702c2dbdc728', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631373238313b77677469785f746f6b656e7c733a33323a223339636237353939663930356335623737316233333533306636393666333632223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:033e2ff6d70612844f0f49b8c02d9d0e', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634333435383b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:06841aa544b9d6856e72e5dc2491387d', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537383930393b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a34313a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f3f706167655f6576656e74733d32223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:0c6971e32934ca54567580d8974cb21c', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331383734313b77677469785f746f6b656e7c733a33323a226661393364336262336463336264633762313863343066636532376530363862223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:1127cb09f93f6a1f16cd29f2d0ee3ecc', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232333331363b77677469785f746f6b656e7c733a33323a223463363661393838363566656630383731656232316634353137653139386566223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:149fd6c7785ace6bad563e20294d6259', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363336343331313b77677469785f746f6b656e7c733a33323a226233626630303931383937333661353137633937393861396336316466613366223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:15739c3699418f995bbec92d9746ba1a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634343135343b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:194810322e50600984465ce5e94924e9', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633393335353b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:1c80c3a9c5d37bf8ec4d48098b0bf738', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537363336323b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:2727e50487dfeaa900ae80bb77923b9b', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363334343337303b77677469785f746f6b656e7c733a33323a226666303663376664323834653462626432663634636364346162393165323034223b),
	('wgtix_session:28992cb9c9bf33956427b44aeb4fec49', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636343732313b77677469785f746f6b656e7c733a33323a226334336564353437363037376333366231616535376339366130376439663136223b5f63695f70726576696f75735f75726c7c733a33373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f6c6f67696e223b746f617374727c613a323a7b733a343a2274797065223b733a353a226572726f72223b733a373a226d657373616765223b733a34383a2272654341505443484120766572696669636174696f6e206661696c65642e20506c656173652074727920616761696e2e223b7d5f5f63695f766172737c613a313a7b733a363a22746f61737472223b733a333a226f6c64223b7d),
	('wgtix_session:2b32cc97579ce82ee9ccfe8a298913a5', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631363034343b77677469785f746f6b656e7c733a33323a223931363163633533323061613831386637333436303732653133663764643866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:2fed6069e31791435c860f0c7187be50', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232333931373b77677469785f746f6b656e7c733a33323a223463363661393838363566656630383731656232316634353137653139386566223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:39386eba614c819531a8ac0cef67660a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634313235313b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:437e7fb7f686c78a343c1b7978efcfee', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363538303839353b77677469785f746f6b656e7c733a33323a223131316633613630366530656432353864316230373838373135633332343565223b5f63695f70726576696f75735f75726c7c733a34313a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f3f706167655f6576656e74733d32223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:48183542e974d4cd4bae7c4a67cec88a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631373839363b77677469785f746f6b656e7c733a33323a223339636237353939663930356335623737316233333533306636393666333632223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:4a3bcf76d418783e139aa14a0d2817dd', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636323038383b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b),
	('wgtix_session:4b115642c92f63a08f8e46c5c7fbf626', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363635353639323b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a33373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f6c6f67696e223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:53d73d9823c334ec0610b1fe625bf3cf', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631353336383b77677469785f746f6b656e7c733a33323a223931363163633533323061613831386637333436303732653133663764643866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:55e02350a756536b916034b1e47aa543', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634383137313b77677469785f746f6b656e7c733a33323a223238333931626633313530656138373262316237623630343564303833366364223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:59d5d7ee88b557e9a4c4683a74ccca63', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633353036313b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:5a5a143400aab73e0763f2b514e0e963', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363331393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:5b3eda3ba967d7bd6433de9e19e3ac1d', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363632333331303b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:5bd75977c788fb0bfe653adc84a5f5be', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363632303538373b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:661fd27f6e174cc6e932531861754f29', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363336343331313b77677469785f746f6b656e7c733a33323a226233626630303931383937333661353137633937393861396336316466613366223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:66f27a378a412ee613f1eccebe59952f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331303133333b77677469785f746f6b656e7c733a33323a223264393766613234363366373833366663336261353130636264386563383565223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b),
	('wgtix_session:672e353cfd0eb05483da9939485a9393', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537383136353b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:68c2888cd59509dd1bd267ab645d7fa4', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363236373933383b77677469785f746f6b656e7c733a33323a223636623830636364323231343339643966666263333936313264653930363866223b),
	('wgtix_session:68d1ae4e2594851c1f1c0f9737b64e85', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537323334383b77677469785f746f6b656e7c733a33323a223435373833323061653562376138336337346133613161333661633766656662223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:6995359951e8e20056e0714eecb07679', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363335343638303b77677469785f746f6b656e7c733a33323a226531633231333564626236306465316331623638623435623439313132393131223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:69971465bc45489dbb3d40066884910f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537353736313b77677469785f746f6b656e7c733a33323a223738653037646233616338333337643539366634623631616636386135633030223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:6e6728cf7bbdeeda02212f511da4dc29', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634323031353b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:6e981e78de8b1ed7b486258bfdb2e69e', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232353638303b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:70d768e48c55db38a192c35faaa3e225', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363635353431363b77677469785f746f6b656e7c733a33323a223238333931626633313530656138373262316237623630343564303833366364223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b),
	('wgtix_session:73da89f9b57225f0db8875b976aa10da', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636333539333b77677469785f746f6b656e7c733a33323a223064323565656661633936353962613331663464356132373761393937316138223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b5f63695f6f6c645f696e7075747c613a323a7b733a333a22676574223b613a303a7b7d733a343a22706f7374223b613a31323a7b733a31303a2266697273745f6e616d65223b733a373a224d696b6861656c223b733a393a226c6173745f6e616d65223b733a373a225761736b69746f223b733a383a22757365726e616d65223b733a31333a226d696b6861656c66656c69616e223b733a353a22656d61696c223b733a32363a22706f6a6f6b7365647568636f6666656540676d61696c2e636f6d223b733a383a2270617373776f7264223b733a393a2261646d696e31323334223b733a31363a2270617373776f72645f636f6e6669726d223b733a393a2261646d696e31323334223b733a373a22636f6d70616e79223b733a33363a22505420434152414b4159415341202f20476976696e672045787072657373204167656e74223b733a353a2270686f6e65223b733a31323a22303835373431323230343237223b733a373a2270726f66696c65223b733a343a2261616161223b733a343a2274697065223b733a313a2232223b733a353a227465726d73223b733a313a2231223b733a32303a22672d7265636170746368612d726573706f6e7365223b733a313537323a2230634146635765413452347157644c4b58786e3976496133725f5548344d5666517936636c416f4f576a696147506b5345566162376a2d616f763235344c2d655268662d5a62767941775666424b574532346e4d6b4a6d7472374d57537a5162306251336e526d45377848346e56386c72504165586a4965504d3856496230465361336b4434363741644a6143794f4869426637617a3479387437594c6e427a656b4d4e46427270734a6d4f5f786a2d50416b654751756978635a654b6e6a65596e713433676566743273654c442d504d6a7762314f4957706a45564e31555a657662442d74304a486d58465033513674546d56325a6b38494a6f4f78326c7837674d4354724978654953624b707256346446482d536f49523966734f6d485834652d677734306c435476594a4830574e6f644a684b2d6d7630435a734859495838717950796463544a57726b56366e77326a6b6b676b44634a697752465f566763374e4a516564514d736e624570723752744a65554a41784d305970376c6b367a613447716954564d6f484d544e5448634c413745316457747850775f326435786f576974496d5963374947764a52385a3443756f72347a75786b5434497a635675375043305f4e6e617872647261424a564a7a6a55545974722d4e77517470477256465135536133515a435939314f64754868624f7974557062374a58626e6a453474717777566b4d6635477151356a49585f33784a346d6a7041315a6f5f573653746f4570506e585a5f6d5a414849633461354f7a4a4332323041486d654a5164544c4b76374f506c326c4a56415570485a7a353636383734354445467737593665335f327970767435524f384676755f6247476c7a727174326a476170625a59514e4c4b346d6c59717444355351703677376152575a456a394e666678316c537456314e614d354b724d7545317937726456525f3075636c356d3374586f5053684167754f785a44746e51302d4975384e7734676b436d566759354f586e4f57496e3359674e795470524e4e64472d6e35707a384362592d5f5873394b6532617a316342734578494746526445676a39723661426c74307359444a336a4956634c6f5a4f2d4a455577553867763772474d734e484d3946524d4c5f756b6b394945344844697a644b66576a4857337a36747669576f4248706145643661716855583436435635317a38536f5439677677626a7162654969436938306730474c3434356959792d34734142684d6174624a4a38535646414464595358307a6236546465466a753876443858364f786c67363973384133565046654378563968544a6675746f504a5a633174416e50796875594c637a33434f504573673541593948794a6c5f424e565930716e7a4a32626577693943667644676d736872785670697267677257363531485f2d476e4f4c353476384141794853634d31687a596975546e39616b612d5245596b594c7a6470383074447a6438654e78675f2d395652686f3044694e52707a694e41353758527554646d4c6b514a674b544974516c4175484e5951385835787548634a716a6f4e387444746b4b5751396561315f71445a7930765a777748563078334e4b5f4e4b545139335649426f3142374b5065664f324b6c514f576569554b343776497256565f764b47326b5f4e453268534c576b71446c79696b6c47776965675836675779703546623152427a516a53455079436d586d3678363277506f5071316739543344495247634c64467a4a6b30676f364a70583479666b37546579712d594a526b3243426846594a3875466b6833355966306e394f722d7530554d59754d582d70466e5f6c3739503343644235595141535374526237307166676f316e494a31655468493254567531747044794d585f306f79336b684c426b6d50313772536b474750686e6f4430443255643564574442624e3977614d4e587979626650723754623265565169775a37654f337a4a4d7152584a4e5258526e6a4763642d544f6d35672d325937314a556244337935576a5a5a3874487065417047786c6d346d774e426c344a4c535f36776d7468734b753268526d6c354764776a576977466c4d505a536c395449514e62634d612d6274765272507a496e6b647a4732336545416d4f5f42384e7a4e537172544451223b7d7d5f5f63695f766172737c613a323a7b733a31333a225f63695f6f6c645f696e707574223b733a333a226f6c64223b733a363a22746f61737472223b733a333a226f6c64223b7d746f617374727c613a323a7b733a343a2274797065223b733a353a226572726f72223b733a373a226d657373616765223b733a33363a225265676973747261736920676167616c2e2053696c616b616e20636f6261206c6167692e223b7d),
	('wgtix_session:757b5beee7686d583e3ff69ba6ebddd2', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631383533363b77677469785f746f6b656e7c733a33323a223339636237353939663930356335623737316233333533306636393666333632223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:76839b32f569ee478edac8e32f2a8a33', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537313038323b77677469785f746f6b656e7c733a33323a223435373833323061653562376138336337346133613161333661633766656662223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:78124908662a856afdf0d7b8e65c47a5', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232343538303b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:7b0f663949e4171542f59512e35b8d1a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363335383037393b77677469785f746f6b656e7c733a33323a226233626630303931383937333661353137633937393861396336316466613366223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:7c178a8bd174358b6c467e3723b6e322', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363335343033383b77677469785f746f6b656e7c733a33323a226531633231333564626236306465316331623638623435623439313132393131223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:7d895791ad60313eeb0ad455490652b4', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633373832383b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:7f1541f91772277ab9ec545152963735', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363934393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:7f99411a88f6f3b03a43b3efbd76f3ad', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363239333837373b77677469785f746f6b656e7c733a33323a223264393766613234363366373833366663336261353130636264386563383565223b5f63695f70726576696f75735f75726c7c733a34363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f61646d696e2f6576656e742d70726963696e67223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:850dd44d73bcfaf7cbf654f1ac871c3f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634373338373b77677469785f746f6b656e7c733a33323a223736613431396661323132656338313666386164346162353239373830646336223b5f63695f70726576696f75735f75726c7c733a33373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f6c6f67696e223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:86360eaf42e4bfc80db3682c7c0d5900', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363239333236363b77677469785f746f6b656e7c733a33323a223636623830636364323231343339643966666263333936313264653930363866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:8854d3437be5e8c2428b3f977922d83e', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636333933323b77677469785f746f6b656e7c733a33323a223238333931626633313530656138373262316237623630343564303833366364223b5f63695f70726576696f75735f75726c7c733a35383a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74733f6d696e5f70726963653d30266d61785f70726963653d3233223b),
	('wgtix_session:88d7a273b7d2f387884ef63bdc2c3410', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363632323532313b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:8f9d85e5938dad8334ec9bb489d9f6d3', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631393231313b77677469785f746f6b656e7c733a33323a223339636237353939663930356335623737316233333533306636393666333632223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:96b983a793f5a65e24ef5d598908c66a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633383731373b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a33333a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e7473223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:96f6a7ff7d08351e9b4efe88391eb0b6', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631343638323b77677469785f746f6b656e7c733a33323a223230316361623036336137323637626238336139613938316636383838316438223b5f63695f70726576696f75735f75726c7c733a34313a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f3f706167655f6576656e74733d32223b),
	('wgtix_session:9c10a75eaf0014489f2578d947156103', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363334373431333b77677469785f746f6b656e7c733a33323a223161643265343133626466623938333733663465333366653132306437633734223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:a0b61bbaa7e7530f773d0186af3025cb', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633343333333b77677469785f746f6b656e7c733a33323a223363386230356638386662386566366636663433326434386338393165333063223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:a17eea3ed3b0db0fd967886961248cc7', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363335333234343b77677469785f746f6b656e7c733a33323a223232356464333664303236333966623065336635353536316365323137313531223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:a3265ad8caac323d1d099376345a9efc', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537373032333b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:a82b17e1981a79a466aceea59f593857', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363432363331353b77677469785f746f6b656e7c733a33323a223037383266303432656562633165333437633762393432333562633162333866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:a866dd7f92f1082ab445172b59731fde', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633333039393b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:aaa089eaae83f44b303cc9ca9793f235', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331343437303b77677469785f746f6b656e7c733a33323a226561666238346237313538653966316566653531653063393565383665383164223b5f63695f70726576696f75735f75726c7c733a33393a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f61646d696e2f6576656e7473223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:ae4fa7352c6287972602212eea2c059a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363334363733393b77677469785f746f6b656e7c733a33323a223161643265343133626466623938333733663465333366653132306437633734223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b),
	('wgtix_session:ae79a7dfe5b1faa3da8657999b907a3a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634373434333b77677469785f746f6b656e7c733a33323a226435636662316233323730383739333961326434333232303131343331636638223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536363134373532223b6c6173745f636865636b7c693a313735363634373434333b),
	('wgtix_session:b10cf1c96b774ea37bda8d6a149bb40d', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363432343538363b77677469785f746f6b656e7c733a33323a223037383266303432656562633165333437633762393432333562633162333866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:b12916b511007b0d3fa4cfc336929675', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331323833373b77677469785f746f6b656e7c733a33323a223264393766613234363366373833366663336261353130636264386563383565223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b),
	('wgtix_session:b182f0d0525f087af24bedac8e303df3', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363635343739393b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b),
	('wgtix_session:b6a91f946be0b90718160a641f3631f6', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331383034383b77677469785f746f6b656e7c733a33323a223761653363323437666266366637653164316538323734633535353431396334223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:b7871c0f472f11467c2a2ea168635931', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634383533393b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:b7ac52b63130443679d0c45ab0d936dd', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633373139393b77677469785f746f6b656e7c733a33323a223638666463323738346361643263333435616436636138353738373430393132223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:b9e00395ec60d85d21139a184139e490', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363232363934393b77677469785f746f6b656e7c733a33323a226162666637396463393633373633396632643436313063353738323061326163223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323137363335223b6c6173745f636865636b7c693a313735363232333331353b),
	('wgtix_session:bb88b8fbce30d0928b5ace2554ca05bf', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537313639363b77677469785f746f6b656e7c733a33323a223435373833323061653562376138336337346133613161333661633766656662223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:bdfe07c5beb69768bd5f3f9d56d2c913', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537323936383b77677469785f746f6b656e7c733a33323a223237333361663139326435386636643061626530336466366230346361343563223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:beed6930eb9e267602d0ef940dc3ff32', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363635343631323b77677469785f746f6b656e7c733a33323a223238333931626633313530656138373262316237623630343564303833366364223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:bf2fdea2c10ae946290f25045663eeb8', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331383734313b77677469785f746f6b656e7c733a33323a223336383731353163303939623031363030336335663331386535353461383061223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:c1a44649c67f6faf41cb22675a3326df', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537303230323b77677469785f746f6b656e7c733a33323a226131643036343336373236373933336266393761396361306662303636666635223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:c4f8652aa50fd49354f64a07d7ec0b90', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363432353238323b77677469785f746f6b656e7c733a33323a223037383266303432656562633165333437633762393432333562633162333866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:c68ae3970c75cfe882dc02e4afc7d0e2', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634343833393b77677469785f746f6b656e7c733a33323a223736613431396661323132656338313666386164346162353239373830646336223b5f63695f70726576696f75735f75726c7c733a33373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f6c6f67696e223b),
	('wgtix_session:c755527ec08b26cfbf63698ddf0ddcbb', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636323831343b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b),
	('wgtix_session:cbb961b08a5801ac0995dd597f68b6df', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636333539333b77677469785f746f6b656e7c733a33323a223262626137613733313864356430323035323763643838613432663137646264223b5f63695f70726576696f75735f75726c7c733a34303a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f7265676973746572223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:cf374ebc5941af8787d9da0d77830067', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363538303231383b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:d4e96f4beec1deefb5a29ab403066706', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631343735323b77677469785f746f6b656e7c733a33323a223931363163633533323061613831386637333436303732653133663764643866223b5f63695f70726576696f75735f75726c7c733a33333a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f61646d696e2f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:df387fe12e7af0c4298fe990412a7342', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631363637313b77677469785f746f6b656e7c733a33323a226135633938313038633061653837393462623365373566646465636264316435223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:e448fa5f0dd89e5e2823a9f7bd4e5ca9', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363632313231373b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:e6947c77e3c49b49d1007e13806b1122', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537393539303b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:e6a95e3951926f2a61587c4071b7852f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363239313531333b77677469785f746f6b656e7c733a33323a223636623830636364323231343339643966666263333936313264653930363866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:e89f1398d068a32a7823640eddb62f38', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363430323035303b77677469785f746f6b656e7c733a33323a223037383266303432656562633165333437633762393432333562633162333866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:e8ccfc98e375634f1c01081890c47cb3', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363239333237363b77677469785f746f6b656e7c733a33323a223264393766613234363366373833366663336261353130636264386563383565223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b),
	('wgtix_session:e90be2a7037e072053ce3fad44f8576d', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363636343732313b77677469785f746f6b656e7c733a33323a223732646266616664343565643361343233313232363236333266666364643036223b5f63695f70726576696f75735f75726c7c733a33373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f617574682f6c6f67696e223b746f617374727c613a323a7b733a343a2274797065223b733a373a2273756363657373223b733a373a226d657373616765223b733a33353a225265676973747261736920626572686173696c212053696c616b616e206c6f67696e2e223b7d5f5f63695f766172737c613a313a7b733a363a22746f61737472223b733a333a226f6c64223b7d),
	('wgtix_session:ea4b7c6d635fbb1ee992b9442eea754a', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331333436393b77677469785f746f6b656e7c733a33323a223264393766613234363366373833366663336261353130636264386563383565223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:f001d462eb6b01ced2cbabe1f0c1098f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363537303334333b77677469785f746f6b656e7c733a33323a223435373833323061653562376138336337346133613161333661633766656662223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:f0781f80127a569d29037ed1c68cffad', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363633333732383b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:f180c27ba37eb7c5dba8f75626f538ee', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363331373233303b77677469785f746f6b656e7c733a33323a226361353533653263313362373734666163653037366238383737313464643738223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323233333135223b6c6173745f636865636b7c693a313735363239333237363b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:f4f651028aadf79c066ead9ed2417261', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363634393231363b77677469785f746f6b656e7c733a33323a223835323562343963353335343266343830633164613434383738343337396235223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b),
	('wgtix_session:f625533e9b7de35714c3fbdf48f75fd5', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363335323433373b77677469785f746f6b656e7c733a33323a223038323665343030653262633633343139326439623164343833643431373439223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536323933323736223b6c6173745f636865636b7c693a313735363334363733393b5f5f63695f766172737c613a303a7b7d),
	('wgtix_session:f8a8c3cd9d75359579da36ee27782958', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363631393930323b77677469785f746f6b656e7c733a33323a223339636237353939663930356335623737316233333533306636393666333632223b5f63695f70726576696f75735f75726c7c733a35363a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f31332d73656d6172616e672d66756e2d72756e2d356b223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:fb2126ba1262faf65a2d81b0de322b64', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363632313931323b77677469785f746f6b656e7c733a33323a223638393239363737663065363933633538663537326139313834646262303565223b5f63695f70726576696f75735f75726c7c733a35333a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f6576656e74732f63617465676f72792f332d31306b2d756d756d223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536353730333433223b6c6173745f636865636b7c693a313735363631343735323b),
	('wgtix_session:fc893c4b2adc498bd2f7447d76438178', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363538303839353b77677469785f746f6b656e7c733a33323a223139626564353361383665636362376338656331613961363039666434626136223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b6964656e746974797c733a31303a22737570657261646d696e223b757365726e616d657c733a31303a22737570657261646d696e223b656d61696c7c733a32303a22737570657261646d696e4061646d696e2e636f6d223b757365725f69647c733a313a2232223b6f6c645f6c6173745f6c6f67696e7c733a31303a2231373536333436373339223b6c6173745f636865636b7c693a313735363537303334333b),
	('wgtix_session:fd1fcddaaab79dcbeddf871a93ab131f', '::1', 4294967295, _binary 0x5f5f63695f6c6173745f726567656e65726174657c693a313735363432363331353b77677469785f746f6b656e7c733a33323a223037383266303432656562633165333437633762393432333562633162333866223b5f63695f70726576696f75735f75726c7c733a32373a22687474703a2f2f6c6f63616c686f73742f7035362d77677469782f223b);

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

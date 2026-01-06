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


-- Dumping database structure for ci4crud-inventory
CREATE DATABASE IF NOT EXISTS `ci4crud-inventory` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ci4crud-inventory`;

-- Dumping structure for table ci4crud-inventory.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kategori` int DEFAULT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi` text,
  `stok` int NOT NULL,
  `harga` decimal(65,0) NOT NULL,
  `gambar` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ci4crud-inventory.barang: ~5 rows (approximately)
INSERT INTO `barang` (`id`, `id_kategori`, `nama_barang`, `deskripsi`, `stok`, `harga`, `gambar`, `created_at`, `updated_at`) VALUES
	(8, 1, 'Noir M1 Nex', 'Mouse Gaming Wireless', 41, 270000, '1767521446_728a23e6d1c633751cfb.webp', '2026-01-04 17:10:46', '2026-01-05 15:27:57'),
	(9, 1, 'Redragon K630W RGB', 'Mechanical Keyboard', 70, 350000, '1767521834_43a3d1292f8ea3a933bb.webp', '2026-01-04 17:17:14', '2026-01-05 15:30:24'),
	(10, 4, 'Xiaomi G24i 2026', 'Monitor Gaming 200Hz', 9, 1650000, '1767527552_e6b0f58224d81e0c9110.jpg', '2026-01-04 18:52:32', '2026-01-05 15:31:22'),
	(11, 1, 'Rexus Asta GX150', 'Gamepad Gaming Rexus', 10, 280000, '1767527925_95a624df6d4e8653a8a3.jpg', '2026-01-04 18:58:45', '2026-01-05 16:56:27'),
	(14, 7, 'Asus Vivobook 1E1404FA', 'FHD321', 15, 6500000, '1767622664_f2bd5fb08a16960767f8.webp', '2026-01-05 21:17:44', '2026-01-05 21:20:43');

-- Dumping structure for table ci4crud-inventory.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `keterangan` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ci4crud-inventory.kategori: ~4 rows (approximately)
INSERT INTO `kategori` (`id`, `nama`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 'Gaming Gear', 'Perlengkapan gaming', NULL, '2026-01-05 10:20:00'),
	(3, 'Perkabelan', 'Semua jenis kabel', '2026-01-05 10:16:07', '2026-01-05 10:17:29'),
	(4, 'PC', 'Personal Computer', '2026-01-05 10:19:27', '2026-01-05 10:19:27'),
	(7, 'Laptop', 'Semua jenis laptop', '2026-01-05 21:11:30', '2026-01-05 21:11:30');

-- Dumping structure for table ci4crud-inventory.transaksi_stok
CREATE TABLE IF NOT EXISTS `transaksi_stok` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `jumlah` decimal(65,0) NOT NULL DEFAULT '0',
  `keterangan` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_barang` (`id_barang`),
  CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ci4crud-inventory.transaksi_stok: ~5 rows (approximately)
INSERT INTO `transaksi_stok` (`id`, `id_barang`, `jenis`, `jumlah`, `keterangan`, `created_at`, `updated_at`) VALUES
	(2, 8, 'keluar', 5, 'Perawatan', '2026-01-05 15:27:57', '2026-01-05 15:27:57'),
	(3, 9, 'keluar', 5, 'Perawatan', '2026-01-05 15:30:24', '2026-01-05 15:30:24'),
	(4, 10, 'masuk', 5, 'Stok baru', '2026-01-05 15:31:22', '2026-01-05 15:31:22'),
	(5, 11, 'keluar', 8, 'Pembelian', '2026-01-05 16:56:27', '2026-01-05 16:56:27'),
	(13, 14, 'masuk', 15, 'Tambah stok', '2026-01-05 21:20:43', '2026-01-05 21:20:43');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

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


-- Dumping database structure for umkm_pakaian
CREATE DATABASE IF NOT EXISTS `umkm_pakaian` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `umkm_pakaian`;

-- Dumping structure for table umkm_pakaian.akun
CREATE TABLE IF NOT EXISTS `akun` (
  `id_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tipe_akun` enum('CUSTOMER','KASIR','ADMIN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_akun` enum('AKTIF','NONAKTIF') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_akun`),
  UNIQUE KEY `akun_username_unique` (`username`),
  UNIQUE KEY `akun_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.akun: ~2 rows (approximately)
INSERT INTO `akun` (`id_akun`, `nama`, `username`, `password_hash`, `email`, `no_telp`, `alamat`, `tipe_akun`, `status_akun`, `created_at`, `updated_at`) gVALUES
	('ADM001', 'Admin Utama', 'admin', '$2y$12$Hldp7sm9Pn2ye/oUVHls8e3q9VYy/mTD1euwdwRNgkgil5RppTUYW', 'admin@umkm.test', '081234567801', 'Surabaya', 'ADMIN', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('CUST001', 'Customer Demo', 'customer', '$2y$12$x3WffDDwcgTC1gdGzrTD.OIjwVqNovB22xO53H81OQV3Lf.Wl./MO', 'customer@umkm.test', '081234567803', 'Surabaya', 'CUSTOMER', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('KSR001', 'Kasir Utama', 'kasir', '$2y$12$vCmXAemxMXRd2clqXszkfedCXrRT3oq8bmDu1AOiCqr84rCvX3IPK', 'kasir@umkm.test', '081234567802', 'Surabaya', 'KASIR', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05');

-- Dumping structure for table umkm_pakaian.bahan_baku
CREATE TABLE IF NOT EXISTS `bahan_baku` (
  `id_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stok_minimum` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_bahan`),
  UNIQUE KEY `bahan_baku_nama_bahan_warna_unique` (`nama_bahan`,`warna`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.bahan_baku: ~21 rows (approximately)
INSERT INTO `bahan_baku` (`id_bahan`, `nama_bahan`, `warna`, `satuan`, `stok`, `stok_minimum`, `created_at`, `updated_at`) VALUES
	('BHN001', 'Cotton Combed', 'hitam', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN002', 'Rib', 'hitam', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN003', 'Cotton Combed', 'putih', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN004', 'Rib', 'putih', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN005', 'Cotton Combed', 'navy', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN006', 'Rib', 'navy', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN007', 'Cotton Combed', 'abu', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN008', 'Rib', 'abu', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN009', 'Cotton Combed', 'merah', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN010', 'Rib', 'merah', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN011', 'French Terry', 'hitam', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN012', 'French Terry', 'navy', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN013', 'French Terry', 'abu', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN014', 'Poplin', 'putih', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN015', 'Poplin', 'navy', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN016', 'Twill', 'hitam', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN017', 'Twill', 'khaki', 'meter', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN018', 'Kancing', 'netral', 'pcs', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN019', 'Zipper', 'netral', 'pcs', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN020', 'Tali Hoodie', 'netral', 'pcs', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('BHN021', 'Label', 'netral', 'pcs', 100.00, 20.00, '2026-09-20 08:13:05', '2026-09-20 08:13:05');

-- Dumping structure for table umkm_pakaian.detail_pembelian
CREATE TABLE IF NOT EXISTS `detail_pembelian` (
  `id_detail_pembelian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pembelian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `harga_satuan` decimal(14,2) NOT NULL,
  PRIMARY KEY (`id_detail_pembelian`),
  KEY `detail_pembelian_id_pembelian_foreign` (`id_pembelian`),
  KEY `detail_pembelian_id_bahan_foreign` (`id_bahan`),
  CONSTRAINT `detail_pembelian_id_bahan_foreign` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `detail_pembelian_id_pembelian_foreign` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian_bahan` (`id_pembelian`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.detail_pembelian: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.detail_penjualan
CREATE TABLE IF NOT EXISTS `detail_penjualan` (
  `id_detail_penjualan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_penjualan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_varian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int unsigned NOT NULL,
  `harga_satuan` decimal(14,2) NOT NULL,
  PRIMARY KEY (`id_detail_penjualan`),
  KEY `detail_penjualan_id_penjualan_foreign` (`id_penjualan`),
  KEY `detail_penjualan_id_varian_foreign` (`id_varian`),
  CONSTRAINT `detail_penjualan_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `detail_penjualan_id_varian_foreign` FOREIGN KEY (`id_varian`) REFERENCES `varian_produk` (`id_varian`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.detail_penjualan: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.kategori_produk
CREATE TABLE IF NOT EXISTS `kategori_produk` (
  `id_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori`),
  UNIQUE KEY `kategori_produk_nama_kategori_unique` (`nama_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.kategori_produk: ~5 rows (approximately)
INSERT INTO `kategori_produk` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
	('KAT001', 'Kaos', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('KAT002', 'Hoodie', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('KAT003', 'Sweater', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('KAT004', 'Kemeja', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('KAT005', 'Celana', '2026-09-20 08:13:05', '2026-09-20 08:13:05');

-- Dumping structure for table umkm_pakaian.komposisi_produk
CREATE TABLE IF NOT EXISTS `komposisi_produk` (
  `id_varian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_bahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_per_unit` decimal(12,3) NOT NULL,
  PRIMARY KEY (`id_varian`,`id_bahan`),
  KEY `komposisi_produk_id_bahan_foreign` (`id_bahan`),
  CONSTRAINT `komposisi_produk_id_bahan_foreign` FOREIGN KEY (`id_bahan`) REFERENCES `bahan_baku` (`id_bahan`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `komposisi_produk_id_varian_foreign` FOREIGN KEY (`id_varian`) REFERENCES `varian_produk` (`id_varian`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.komposisi_produk: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2026_09_20_150001_create_akun_table', 1),
	(2, '2026_09_20_150002_create_kategori_produk_table', 1),
	(3, '2026_09_20_150003_create_produk_table', 1),
	(4, '2026_09_20_150004_create_varian_produk_table', 1),
	(5, '2026_09_20_150005_create_supplier_table', 1),
	(6, '2026_09_20_150006_create_bahan_baku_table', 1),
	(7, '2026_09_20_150007_create_pembelian_bahan_table', 1),
	(8, '2026_09_20_150008_create_detail_pembelian_table', 1),
	(9, '2026_09_20_150009_create_komposisi_produk_table', 1),
	(10, '2026_09_20_150010_create_produksi_table', 1),
	(11, '2026_09_20_150011_create_penjualan_table', 1),
	(12, '2026_09_20_150012_create_penjualan_member_table', 1),
	(13, '2026_09_20_150013_create_detail_penjualan_table', 1),
	(14, '2026_09_20_150014_create_pengeluaran_table', 1),
	(15, '2026_09_20_150015_create_promo_table', 1),
	(16, '2026_09_20_150016_create_promo_produk_table', 1);

-- Dumping structure for table umkm_pakaian.pembelian_bahan
CREATE TABLE IF NOT EXISTS `pembelian_bahan` (
  `id_pembelian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pembelian` datetime NOT NULL,
  `status_pembelian` enum('DIPESAN','DITERIMA','BATAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DIPESAN',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `pembelian_bahan_id_supplier_foreign` (`id_supplier`),
  KEY `pembelian_bahan_id_akun_foreign` (`id_akun`),
  CONSTRAINT `pembelian_bahan_id_akun_foreign` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pembelian_bahan_id_supplier_foreign` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.pembelian_bahan: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.pengeluaran
CREATE TABLE IF NOT EXISTS `pengeluaran` (
  `id_pengeluaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengeluaran` datetime NOT NULL,
  `jenis_pengeluaran` enum('LISTRIK','INTERNET','SEWA','ONGKIR','PERAWATAN','LAINNYA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(14,2) NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pengeluaran`),
  KEY `pengeluaran_id_akun_foreign` (`id_akun`),
  CONSTRAINT `pengeluaran_id_akun_foreign` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.pengeluaran: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.penjualan
CREATE TABLE IF NOT EXISTS `penjualan` (
  `id_penjualan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_penjualan` datetime NOT NULL,
  `kanal_penjualan` enum('ONLINE','OFFLINE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `metode_pembayaran` enum('QR','TUNAI') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pembayaran` enum('MENUNGGU','BERHASIL','GAGAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'MENUNGGU',
  `referensi_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal_bayar` decimal(14,2) NOT NULL DEFAULT '0.00',
  `status_penjualan` enum('BARU','DIPROSES','DIKIRIM','SELESAI','BATAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BARU',
  `alamat_pengiriman` text COLLATE utf8mb4_unicode_ci,
  `poin_didapat` int unsigned NOT NULL DEFAULT '0',
  `poin_digunakan` int unsigned NOT NULL DEFAULT '0',
  `diskon_poin` decimal(14,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_penjualan`),
  KEY `penjualan_id_akun_foreign` (`id_akun`),
  CONSTRAINT `penjualan_id_akun_foreign` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.penjualan: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.penjualan_member
CREATE TABLE IF NOT EXISTS `penjualan_member` (
  `id_penjualan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_akun_customer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_penjualan`),
  KEY `penjualan_member_id_akun_customer_foreign` (`id_akun_customer`),
  CONSTRAINT `penjualan_member_id_akun_customer_foreign` FOREIGN KEY (`id_akun_customer`) REFERENCES `akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `penjualan_member_id_penjualan_foreign` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.penjualan_member: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_jual` decimal(14,2) NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `gambar_produk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_produk` enum('AKTIF','NONAKTIF') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_produk`),
  KEY `produk_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `produk_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_produk` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.produk: ~9 rows (approximately)
INSERT INTO `produk` (`id_produk`, `id_kategori`, `nama_produk`, `harga_jual`, `deskripsi`, `gambar_produk`, `status_produk`, `created_at`, `updated_at`) VALUES
	('PRD001', 'KAT001', 'Basic T-Shirt', 119000.00, 'Kaos basic nyaman untuk penggunaan sehari-hari.', 'images/products/basic-tshirt.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD002', 'KAT001', 'Oversized T-Shirt', 149000.00, 'Kaos oversized dengan potongan modern.', 'images/products/oversized-tshirt.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD003', 'KAT001', 'Pocket T-Shirt', 139000.00, 'Kaos casual dengan detail pocket.', 'images/products/pocket-tshirt.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD004', 'KAT002', 'Pullover Hoodie', 259000.00, 'Hoodie pullover untuk gaya casual.', 'images/products/hoodie.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD005', 'KAT003', 'Crewneck Sweatshirt', 229000.00, 'Sweatshirt crewneck berbahan nyaman.', 'images/products/crewneck.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD006', 'KAT004', 'Casual Shirt', 189000.00, 'Kemeja casual untuk aktivitas sehari-hari.', 'images/products/casual-shirt.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD007', 'KAT004', 'Overshirt', 219000.00, 'Overshirt casual dengan material twill.', 'images/products/overshirt.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD008', 'KAT005', 'Cargo Pants', 229000.00, 'Celana cargo dengan desain fungsional.', 'images/products/cargo-pants.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD009', 'KAT005', 'Straight Pants', 209000.00, 'Celana straight fit untuk gaya minimalis.', 'images/products/straight-pants.jpg', 'AKTIF', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('PRD010', 'KAT001', 'Long Sleeve T-Shirt', 169000.00, 'Kaos lengan panjang nyaman untuk penggunaan sehari-hari.', 'images/products/long-sleeve.jpg', 'AKTIF', '2026-09-20 09:01:05', '2026-09-20 09:01:05');

-- Dumping structure for table umkm_pakaian.produksi
CREATE TABLE IF NOT EXISTS `produksi` (
  `id_produksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_varian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_akun` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_produksi` int unsigned NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `estimasi_selesai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `status_produksi` enum('DRAFT','MENUNGGU_BAHAN','DIPROSES','SELESAI','BATAL') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_produksi`),
  KEY `produksi_id_varian_foreign` (`id_varian`),
  KEY `produksi_id_akun_foreign` (`id_akun`),
  CONSTRAINT `produksi_id_akun_foreign` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `produksi_id_varian_foreign` FOREIGN KEY (`id_varian`) REFERENCES `varian_produk` (`id_varian`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.produksi: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.promo
CREATE TABLE IF NOT EXISTS `promo` (
  `id_promo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_promo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persen_diskon` decimal(5,2) NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_promo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.promo: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.promo_produk
CREATE TABLE IF NOT EXISTS `promo_produk` (
  `id_promo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_promo`,`id_produk`),
  KEY `promo_produk_id_produk_foreign` (`id_produk`),
  CONSTRAINT `promo_produk_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `promo_produk_id_promo_foreign` FOREIGN KEY (`id_promo`) REFERENCES `promo` (`id_promo`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.promo_produk: ~0 rows (approximately)

-- Dumping structure for table umkm_pakaian.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.supplier: ~3 rows (approximately)
INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `no_telp`, `alamat`, `created_at`, `updated_at`) VALUES
	('SUP001', 'Surabaya Textile', '081111111111', 'Surabaya', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('SUP002', 'Nusantara Fabric', '082222222222', 'Sidoarjo', '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('SUP003', 'Jaya Garment Supply', '083333333333', 'Gresik', '2026-09-20 08:13:05', '2026-09-20 08:13:05');

-- Dumping structure for table umkm_pakaian.varian_produk
CREATE TABLE IF NOT EXISTS `varian_produk` (
  `id_varian` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ukuran` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warna` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stok` int unsigned NOT NULL DEFAULT '0',
  `stok_minimum` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_varian`),
  UNIQUE KEY `varian_produk_id_produk_ukuran_warna_unique` (`id_produk`,`ukuran`,`warna`),
  CONSTRAINT `varian_produk_id_produk_foreign` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table umkm_pakaian.varian_produk: ~72 rows (approximately)
INSERT INTO `varian_produk` (`id_varian`, `id_produk`, `ukuran`, `warna`, `stok`, `stok_minimum`, `created_at`, `updated_at`) VALUES
	('VAR001', 'PRD001', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR002', 'PRD001', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR003', 'PRD001', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR004', 'PRD001', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR005', 'PRD001', 'S', 'putih', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR006', 'PRD001', 'M', 'putih', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR007', 'PRD001', 'L', 'putih', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR008', 'PRD001', 'XL', 'putih', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR009', 'PRD002', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR010', 'PRD002', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR011', 'PRD002', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR012', 'PRD002', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR013', 'PRD002', 'S', 'navy', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR014', 'PRD002', 'M', 'navy', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR015', 'PRD002', 'L', 'navy', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR016', 'PRD002', 'XL', 'navy', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR017', 'PRD003', 'S', 'putih', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR018', 'PRD003', 'M', 'putih', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR019', 'PRD003', 'L', 'putih', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR020', 'PRD003', 'XL', 'putih', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR021', 'PRD003', 'S', 'abu', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR022', 'PRD003', 'M', 'abu', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR023', 'PRD003', 'L', 'abu', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR024', 'PRD003', 'XL', 'abu', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR025', 'PRD004', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR026', 'PRD004', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR027', 'PRD004', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR028', 'PRD004', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR029', 'PRD004', 'S', 'abu', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR030', 'PRD004', 'M', 'abu', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR031', 'PRD004', 'L', 'abu', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR032', 'PRD004', 'XL', 'abu', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR033', 'PRD005', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR034', 'PRD005', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR035', 'PRD005', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR036', 'PRD005', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR037', 'PRD005', 'S', 'navy', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR038', 'PRD005', 'M', 'navy', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR039', 'PRD005', 'L', 'navy', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR040', 'PRD005', 'XL', 'navy', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR041', 'PRD006', 'S', 'putih', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR042', 'PRD006', 'M', 'putih', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR043', 'PRD006', 'L', 'putih', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR044', 'PRD006', 'XL', 'putih', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR045', 'PRD006', 'S', 'navy', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR046', 'PRD006', 'M', 'navy', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR047', 'PRD006', 'L', 'navy', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR048', 'PRD006', 'XL', 'navy', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR049', 'PRD007', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR050', 'PRD007', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR051', 'PRD007', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR052', 'PRD007', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR053', 'PRD007', 'S', 'khaki', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR054', 'PRD007', 'M', 'khaki', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR055', 'PRD007', 'L', 'khaki', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR056', 'PRD007', 'XL', 'khaki', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR057', 'PRD008', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR058', 'PRD008', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR059', 'PRD008', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR060', 'PRD008', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR061', 'PRD008', 'S', 'khaki', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR062', 'PRD008', 'M', 'khaki', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR063', 'PRD008', 'L', 'khaki', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR064', 'PRD008', 'XL', 'khaki', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR065', 'PRD009', 'S', 'hitam', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR066', 'PRD009', 'M', 'hitam', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR067', 'PRD009', 'L', 'hitam', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR068', 'PRD009', 'XL', 'hitam', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR069', 'PRD009', 'S', 'khaki', 12, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR070', 'PRD009', 'M', 'khaki', 10, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR071', 'PRD009', 'L', 'khaki', 7, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR072', 'PRD009', 'XL', 'khaki', 4, 5, '2026-09-20 08:13:05', '2026-09-20 08:13:05'),
	('VAR073', 'PRD010', 'S', 'hitam', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR074', 'PRD010', 'M', 'hitam', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR075', 'PRD010', 'L', 'hitam', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR076', 'PRD010', 'XL', 'hitam', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR077', 'PRD010', 'S', 'merah', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR078', 'PRD010', 'M', 'merah', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR079', 'PRD010', 'L', 'merah', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05'),
	('VAR080', 'PRD010', 'XL', 'merah', 0, 5, '2026-09-20 09:01:05', '2026-09-20 09:01:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

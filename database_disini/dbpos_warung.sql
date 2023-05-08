-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2023 at 01:23 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbpos_warung`
--

-- --------------------------------------------------------

--
-- Table structure for table `backups`
--

CREATE TABLE `backups` (
  `id_backup` int(11) UNSIGNED NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `id_bank` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `pemasukan` int(11) DEFAULT NULL,
  `pengeluaran` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_penjualan` int(11) UNSIGNED DEFAULT NULL,
  `noref_nokartu` varchar(255) DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `id_bank_akun` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `bank_akun`
--

CREATE TABLE `bank_akun` (
  `id_bank_akun` int(11) NOT NULL,
  `nama_bank` varchar(255) DEFAULT NULL,
  `bank_nama` varchar(255) DEFAULT NULL,
  `no_rekening` varchar(255) DEFAULT NULL,
  `utama` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_akun`
--

INSERT INTO `bank_akun` (`id_bank_akun`, `nama_bank`, `bank_nama`, `no_rekening`, `utama`, `created_at`, `updated_at`) VALUES
(1, 'BANK 1, Purwokerto', 'John Doe', '123456789', 1, NULL, '2023-01-09 18:35:21');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` char(36) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `id_kategori` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `id_kontak` int(11) UNSIGNED DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `nama_barang` varchar(255) NOT NULL DEFAULT '',
  `merk` varchar(255) NOT NULL,
  `harga_beli` int(11) NOT NULL DEFAULT '0',
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_persen` int(11) NOT NULL DEFAULT '0',
  `harga_jual` int(11) NOT NULL DEFAULT '0',
  `harga_pelanggan` int(11) NOT NULL DEFAULT '0',
  `harga_sales` int(11) NOT NULL DEFAULT '0',
  `range_harga` varchar(255) DEFAULT NULL,
  `satuan_barang` varchar(255) NOT NULL,
  `deskripsi` text,
  `stok` int(11) NOT NULL DEFAULT '0',
  `stok_min` int(11) NOT NULL DEFAULT '0',
  `stok_gudang` int(11) NOT NULL DEFAULT '0',
  `expired` datetime DEFAULT NULL,
  `active` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `barang_item`
--

CREATE TABLE `barang_item` (
  `id_barang_item` int(11) NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '',
  `kode_barang` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `biaya`
--

CREATE TABLE `biaya` (
  `id_biaya` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `nominal` int(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(255) DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cashflow`
--

CREATE TABLE `cashflow` (
  `id_cashflow` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `pemasukan` int(11) NOT NULL DEFAULT '0',
  `pengeluaran` int(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(255) DEFAULT NULL,
  `id_penjualan` int(11) UNSIGNED DEFAULT NULL,
  `id_pembelian` int(11) UNSIGNED DEFAULT NULL,
  `id_biaya` int(11) UNSIGNED DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hutang`
--

CREATE TABLE `hutang` (
  `id_hutang` int(11) UNSIGNED NOT NULL,
  `id_pembelian` int(11) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `jumlah_hutang` int(11) DEFAULT NULL,
  `jumlah_bayar` int(11) DEFAULT NULL,
  `sisa_hutang` int(11) DEFAULT NULL,
  `status_hutang` int(11) NOT NULL DEFAULT '0',
  `id_login` int(11) NOT NULL DEFAULT '0',
  `id_toko` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `hutang_bayar`
--

CREATE TABLE `hutang_bayar` (
  `id_hutang_bayar` int(11) UNSIGNED NOT NULL,
  `id_hutang` int(11) UNSIGNED NOT NULL,
  `nominal` int(11) DEFAULT NULL,
  `id_cashflow` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `id_login` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Kopi', NULL, NULL),
(2, 'Beras', '2022-08-06 15:52:25', '2022-08-06 15:52:25'),
(3, 'Sembako', '2022-08-06 15:54:04', '2022-08-06 15:54:04'),
(4, 'Rokok', '2023-01-01 20:37:40', '2023-01-01 20:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) UNSIGNED NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `id_kontak` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `harga_beli` int(11) NOT NULL DEFAULT '0',
  `harga_jual` int(11) NOT NULL DEFAULT '0',
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_persen` int(11) NOT NULL DEFAULT '0',
  `stok` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `satuan` varchar(255) DEFAULT NULL,
  `hpp` int(11) NOT NULL DEFAULT '0',
  `jumlah` int(11) NOT NULL DEFAULT '0',
  `total_laba` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(11) UNSIGNED NOT NULL,
  `tipe` varchar(255) DEFAULT NULL,
  `grup` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `perusahaan` varchar(255) DEFAULT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nikktp` varchar(16) DEFAULT NULL,
  `npwp` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `kontak`
--

INSERT INTO `kontak` (`id_kontak`, `tipe`, `grup`, `nama`, `perusahaan`, `alamat`, `telepon`, `email`, `nikktp`, `npwp`, `created_at`, `updated_at`) VALUES
(1, 'Pelanggan', NULL, 'Umum', NULL, 'Purwokerto', '628123456789', 'member@gmail.com', '12314121', NULL, NULL, '2022-06-21 12:59:50'),
(2, 'Vendor', NULL, 'Aksara', 'PT Aksara Karya Utama', 'Purwokerto', '628123456789', 'aksaradelana@gmail.com', '123456789', NULL, '2022-05-09 14:17:28', '2022-06-14 11:02:54'),
(3, 'Pelanggan', NULL, 'Customer', NULL, 'Purwokerto', '62812345678', 'customer@gmail.com', NULL, NULL, '2022-06-14 16:30:12', '2023-02-26 06:47:20'),
(4, 'Pelanggan', NULL, 'Aksara', NULL, 'Purwokerto', '6282133445566', 'Aksara@gmail.com', NULL, NULL, '2023-03-06 11:40:42', '2023-03-06 11:40:42'),
(5, 'Pelanggan', NULL, 'Latisya', NULL, 'pwt', '62812345678', 'Latisya@gmail.com', NULL, NULL, '2023-03-06 13:16:52', '2023-03-06 13:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `nama` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL DEFAULT '',
  `id_kontak` int(11) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '0',
  `active` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id_login`, `email`, `nama`, `username`, `password`, `id_kontak`, `token`, `role`, `active`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', 'Administrator', 'admin', '$2y$10$INo6IRmOXz4YqFhdxnzRjuqgKGaOuOlhBpxClF5V8xd1KPnmhq.9G', 0, NULL, 1, 1, NULL, '2022-05-23 08:26:38'),
(2, 'kasir@gmail.com', 'Kasir', 'kasir', '$2y$10$iTLV.WtmYghGjOSnIHJr8eTF0xFzCJTHOemBLK0tCvlpkeBgOWHk2', 0, NULL, 2, 1, '2022-05-11 09:38:59', '2022-05-26 18:25:53'),
(3, 'manager@gmail.com', 'Manager', 'manager', '$2y$10$dKFJw/2EF/ki.lT5dBz6auWJnVqtBeYHbWPUpNaXq28mTAzR328Hq', 0, NULL, 3, 1, '2023-02-25 14:31:06', '2023-02-25 14:31:06'),
(4, 'sales@gmail.com', 'Sales', 'sales', '$2y$10$4fagP5P0VulbcRe7Tcyq..WEEszuMgkb2MHOzEe4JjPWSGoywjNxC', 0, NULL, 4, 1, '2023-02-25 14:32:03', '2023-02-25 14:32:03'),
(5, 'gudang@gmail.com', 'Gudang', 'gudang', '$2y$10$9fZGZFOqt8bAclgOIvCkcuFUAiL.PSzhrIcWT3n7GmhSg9h6B5y22', 0, NULL, 5, 1, '2023-02-25 14:32:32', '2023-02-25 14:32:32'),
(6, 'test@gmail.com', 'Test', 'test', '$2y$10$Gtgl/u3UO.0/GVI.pp/PkOHcJN5dOrQ/rEsvRr41P6ho6sCPLlr1C', 0, NULL, 2, 1, '2023-03-17 20:51:38', '2023-03-17 20:51:38');

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `id_log_login` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL,
  `loggedin_at` datetime DEFAULT NULL,
  `loggedout_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id_log` int(11) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id_media` int(11) UNSIGNED NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `active` int(11) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) UNSIGNED NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `id_kontak` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `harga_beli` int(11) NOT NULL DEFAULT '0',
  `harga_jual` int(11) NOT NULL DEFAULT '0',
  `stok` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  `satuan` varchar(255) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `pajak`
--

CREATE TABLE `pajak` (
  `id_pajak` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `PPN` char(10) DEFAULT '0',
  `jenis` varchar(255) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_penjualan` int(11) UNSIGNED DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `id_kontak` int(11) UNSIGNED DEFAULT NULL,
  `id_login` int(11) NOT NULL DEFAULT '0',
  `id_toko` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT '0',
  `subtotal` int(11) NOT NULL DEFAULT '0',
  `biaya` int(11) NOT NULL DEFAULT '0',
  `total` int(11) NOT NULL DEFAULT '0',
  `status_bayar` int(11) NOT NULL DEFAULT '0',
  `pembayaran` int(11) DEFAULT NULL,
  `catatan` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_item`
--

CREATE TABLE `pembelian_item` (
  `id_itempembelian` int(11) UNSIGNED NOT NULL,
  `id_pembelian` int(11) UNSIGNED DEFAULT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `harga_beli` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) UNSIGNED NOT NULL,
  `faktur` varchar(255) DEFAULT NULL,
  `id_kontak` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `jumlah` int(11) NOT NULL DEFAULT '0',
  `PPN` char(10) NOT NULL DEFAULT '0',
  `hpp` int(11) NOT NULL DEFAULT '0',
  `subtotal` int(11) NOT NULL DEFAULT '0',
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_persen` int(11) NOT NULL DEFAULT '0',
  `pajak` int(11) NOT NULL DEFAULT '0',
  `total` int(11) NOT NULL DEFAULT '0',
  `bayar` int(11) NOT NULL DEFAULT '0',
  `kembali` int(11) NOT NULL DEFAULT '0',
  `total_laba` int(11) NOT NULL DEFAULT '0',
  `periode` varchar(255) DEFAULT NULL,
  `id_login` int(11) NOT NULL DEFAULT '0',
  `id_toko` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_item`
--

CREATE TABLE `penjualan_item` (
  `id_itempenjualan` int(11) UNSIGNED NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `id_penjualan` int(11) UNSIGNED DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `harga_jual` int(11) DEFAULT NULL,
  `diskon` int(11) NOT NULL DEFAULT '0',
  `diskon_persen` int(11) NOT NULL DEFAULT '0',
  `qty` int(11) DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `piutang`
--

CREATE TABLE `piutang` (
  `id_piutang` int(11) UNSIGNED NOT NULL,
  `id_penjualan` int(11) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `jumlah_piutang` int(11) DEFAULT NULL,
  `jumlah_bayar` int(11) DEFAULT NULL,
  `sisa_piutang` int(11) DEFAULT NULL,
  `status_piutang` int(11) NOT NULL DEFAULT '0',
  `keterangan` varchar(255) DEFAULT NULL,
  `id_login` int(11) NOT NULL DEFAULT '0',
  `id_toko` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `piutang_bayar`
--

CREATE TABLE `piutang_bayar` (
  `id_piutang_bayar` int(11) UNSIGNED NOT NULL,
  `id_piutang` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `nominal` int(11) DEFAULT NULL,
  `id_cashflow` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `id_login` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `satuan`
--

CREATE TABLE `satuan` (
  `id_satuan` int(11) UNSIGNED NOT NULL,
  `nama_satuan` varchar(255) NOT NULL DEFAULT '',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `satuan`
--

INSERT INTO `satuan` (`id_satuan`, `nama_satuan`, `created_at`, `updated_at`) VALUES
(1, 'pcs', NULL, NULL),
(2, 'buah', NULL, NULL),
(3, 'butir', NULL, NULL),
(4, 'bungkus', '2023-01-01 20:38:07', '2023-01-01 20:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_setting` varchar(100) NOT NULL,
  `variable_setting` varchar(255) NOT NULL,
  `value_setting` text NOT NULL,
  `deskripsi_setting` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group_setting`, `variable_setting`, `value_setting`, `deskripsi_setting`, `updated_at`) VALUES
(1, 'general', 'app_name', 'AksaPOS Toko/Warung', 'App Name', '2022-05-09 06:16:12'),
(2, 'general', 'app_version', '1', 'App Version', '2022-05-26 18:22:10'),
(3, 'general', 'app_release', '2023-01-04 08:24:55', 'App Release', '2023-01-04 08:24:55'),
(4, 'general', 'app_developer', 'PT GLOBAL ITSHOP PURWOKERTO (ITShop Purwokerto: Tokopedia, Shopee, Bukalapak, Blibli)', 'App Developer', '2022-05-11 17:05:03'),
(5, 'image', 'img_favicon', 'assets/images/favicon.png', 'Favicon', '2022-11-12 13:14:59'),
(6, 'image', 'img_logo', 'assets/images/logo.png', 'Logo', '2022-11-13 09:47:27'),
(7, 'image', 'img_logo_resize', 'assets/images/res_logo.png', 'Logo Resized', '2022-11-13 09:47:28'),
(8, 'image', 'img_background', 'assets/images/background.jpg', 'Background', '2022-03-10 13:17:10'),
(9, 'app', 'snackbars_position', 'bottom', 'Posisi Snackbars Notification', '2022-12-28 07:22:22'),
(10, 'app', 'limit_loginlog', '2000', 'Limit Login Log (default 2000)', '2023-01-04 08:24:55'),
(11, 'app', 'enable_frontend', 'true', 'Tampilkan Home atau Halaman Login', '2023-02-24 18:22:33'),
(12, 'app', 'cashierpay_position', 'left', 'Posisi Tombol Pay Kasir', '2023-02-26 13:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `stok`
--

CREATE TABLE `stok` (
  `id_stok` int(11) NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `jenis` varchar(255) DEFAULT NULL,
  `jumlah` varchar(255) DEFAULT NULL,
  `nilai` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stok_opname`
--

CREATE TABLE `stok_opname` (
  `id_stok_opname` int(11) NOT NULL,
  `id_barang` char(36) NOT NULL DEFAULT '0',
  `stok` int(11) DEFAULT NULL,
  `stok_nyata` int(11) DEFAULT NULL,
  `selisih` varchar(255) DEFAULT NULL,
  `nilai` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `id_toko` int(11) DEFAULT NULL,
  `id_login` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `telp` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nama_pemilik` varchar(255) NOT NULL,
  `NIB` char(15) DEFAULT NULL,
  `PPN` char(10) DEFAULT '0',
  `kode_barang` varchar(255) DEFAULT NULL,
  `kode_jual` varchar(255) DEFAULT NULL,
  `kode_jual_tahun` int(11) DEFAULT '0',
  `kode_beli` varchar(255) DEFAULT NULL,
  `kode_kas` varchar(255) DEFAULT NULL,
  `kode_bank` varchar(255) DEFAULT NULL,
  `kode_pajak` varchar(255) DEFAULT NULL,
  `kode_biaya` varchar(255) DEFAULT NULL,
  `paper_size` char(10) DEFAULT '0',
  `footer_nota` varchar(255) DEFAULT NULL,
  `printer_usb` int(11) DEFAULT '0',
  `printer_bluetooth` int(11) DEFAULT '0',
  `scan_keranjang` int(11) DEFAULT '0',
  `id_bank_akun` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `toko`
--

INSERT INTO `toko` (`id_toko`, `nama_toko`, `alamat_toko`, `telp`, `email`, `nama_pemilik`, `NIB`, `PPN`, `kode_barang`, `kode_jual`, `kode_jual_tahun`, `kode_beli`, `kode_kas`, `kode_bank`, `kode_pajak`, `kode_biaya`, `paper_size`, `footer_nota`, `printer_usb`, `printer_bluetooth`, `scan_keranjang`, `id_bank_akun`, `updated_at`) VALUES
(1, 'Warung Nita (RN)', 'Dukuhwaluh Gg. Manggis RT 1 RW 7', '08123456789', 'warungnita@gmail.com', 'Hari Wicaksono', '0123012345678', '11', 'BRG-', 'PJ/', 1, 'PI/', 'KAS/', 'BANK/', 'PPN/', 'BYA/', '58', 'Terima kasih sudah berbelanja, Barang yang sudah dibeli tidak dapat dikembalikan', 1, 1, 1, 1, '2023-04-02 11:46:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backups`
--
ALTER TABLE `backups`
  ADD PRIMARY KEY (`id_backup`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id_bank`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `id_toko` (`id_toko`),
  ADD KEY `id_bank_akun` (`id_bank_akun`),
  ADD KEY `id_penjualan` (`id_penjualan`);

--
-- Indexes for table `bank_akun`
--
ALTER TABLE `bank_akun`
  ADD PRIMARY KEY (`id_bank_akun`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `barang_item`
--
ALTER TABLE `barang_item`
  ADD PRIMARY KEY (`id_barang_item`),
  ADD KEY `barang_item_ibfk_1` (`id_barang`);

--
-- Indexes for table `biaya`
--
ALTER TABLE `biaya`
  ADD PRIMARY KEY (`id_biaya`),
  ADD KEY `id_toko` (`id_toko`),
  ADD KEY `id_login` (`id_login`);

--
-- Indexes for table `cashflow`
--
ALTER TABLE `cashflow`
  ADD PRIMARY KEY (`id_cashflow`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_pembelian` (`id_pembelian`),
  ADD KEY `id_biaya` (`id_biaya`),
  ADD KEY `id_toko` (`id_toko`),
  ADD KEY `id_login` (`id_login`);

--
-- Indexes for table `hutang`
--
ALTER TABLE `hutang`
  ADD PRIMARY KEY (`id_hutang`),
  ADD KEY `id_pembelian` (`id_pembelian`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `id_toko` (`id_toko`);

--
-- Indexes for table `hutang_bayar`
--
ALTER TABLE `hutang_bayar`
  ADD PRIMARY KEY (`id_hutang_bayar`),
  ADD KEY `id_hutang` (`id_hutang`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_kontak` (`id_kontak`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id_log_login`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `orders_ibfk_1` (`id_barang`),
  ADD KEY `orders_ibfk_2` (`id_kontak`);

--
-- Indexes for table `pajak`
--
ALTER TABLE `pajak`
  ADD PRIMARY KEY (`id_pajak`),
  ADD KEY `id_penjualan` (`id_penjualan`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`);

--
-- Indexes for table `pembelian_item`
--
ALTER TABLE `pembelian_item`
  ADD PRIMARY KEY (`id_itempembelian`),
  ADD KEY `id_pembelian` (`id_pembelian`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `penjualan_item`
--
ALTER TABLE `penjualan_item`
  ADD PRIMARY KEY (`id_itempenjualan`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `penjualan_item_ibfk_1` (`id_penjualan`);

--
-- Indexes for table `piutang`
--
ALTER TABLE `piutang`
  ADD PRIMARY KEY (`id_piutang`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_login` (`id_login`),
  ADD KEY `id_toko` (`id_toko`);

--
-- Indexes for table `piutang_bayar`
--
ALTER TABLE `piutang_bayar`
  ADD PRIMARY KEY (`id_piutang_bayar`),
  ADD KEY `id_piutang` (`id_piutang`);

--
-- Indexes for table `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id_satuan`),
  ADD KEY `nama_satuan` (`nama_satuan`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok`
--
ALTER TABLE `stok`
  ADD PRIMARY KEY (`id_stok`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `stok_opname`
--
ALTER TABLE `stok_opname`
  ADD PRIMARY KEY (`id_stok_opname`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`),
  ADD KEY `id_bank_akun` (`id_bank_akun`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backups`
--
ALTER TABLE `backups`
  MODIFY `id_backup` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `id_bank` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_akun`
--
ALTER TABLE `bank_akun`
  MODIFY `id_bank_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang_item`
--
ALTER TABLE `barang_item`
  MODIFY `id_barang_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biaya`
--
ALTER TABLE `biaya`
  MODIFY `id_biaya` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashflow`
--
ALTER TABLE `cashflow`
  MODIFY `id_cashflow` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hutang`
--
ALTER TABLE `hutang`
  MODIFY `id_hutang` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hutang_bayar`
--
ALTER TABLE `hutang_bayar`
  MODIFY `id_hutang_bayar` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id_kontak` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `id_log_login` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pajak`
--
ALTER TABLE `pajak`
  MODIFY `id_pajak` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembelian_item`
--
ALTER TABLE `pembelian_item`
  MODIFY `id_itempembelian` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penjualan_item`
--
ALTER TABLE `penjualan_item`
  MODIFY `id_itempenjualan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piutang`
--
ALTER TABLE `piutang`
  MODIFY `id_piutang` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `piutang_bayar`
--
ALTER TABLE `piutang_bayar`
  MODIFY `id_piutang_bayar` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id_satuan` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stok`
--
ALTER TABLE `stok`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_opname`
--
ALTER TABLE `stok_opname`
  MODIFY `id_stok_opname` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `toko`
--
ALTER TABLE `toko`
  MODIFY `id_toko` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank`
--
ALTER TABLE `bank`
  ADD CONSTRAINT `bank_ibfk_1` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`),
  ADD CONSTRAINT `bank_ibfk_2` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`),
  ADD CONSTRAINT `bank_ibfk_3` FOREIGN KEY (`id_bank_akun`) REFERENCES `bank_akun` (`id_bank_akun`),
  ADD CONSTRAINT `bank_ibfk_4` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barang_item`
--
ALTER TABLE `barang_item`
  ADD CONSTRAINT `barang_item_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `biaya`
--
ALTER TABLE `biaya`
  ADD CONSTRAINT `biaya_ibfk_1` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`),
  ADD CONSTRAINT `biaya_ibfk_2` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`);

--
-- Constraints for table `cashflow`
--
ALTER TABLE `cashflow`
  ADD CONSTRAINT `cashflow_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cashflow_ibfk_2` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cashflow_ibfk_3` FOREIGN KEY (`id_biaya`) REFERENCES `biaya` (`id_biaya`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cashflow_ibfk_4` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`),
  ADD CONSTRAINT `cashflow_ibfk_5` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`);

--
-- Constraints for table `hutang`
--
ALTER TABLE `hutang`
  ADD CONSTRAINT `hutang_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`),
  ADD CONSTRAINT `hutang_ibfk_2` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`),
  ADD CONSTRAINT `hutang_ibfk_3` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`);

--
-- Constraints for table `hutang_bayar`
--
ALTER TABLE `hutang_bayar`
  ADD CONSTRAINT `hutang_bayar_ibfk_1` FOREIGN KEY (`id_hutang`) REFERENCES `hutang` (`id_hutang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`id_kontak`) REFERENCES `kontak` (`id_kontak`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pajak`
--
ALTER TABLE `pajak`
  ADD CONSTRAINT `pajak_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembelian_item`
--
ALTER TABLE `pembelian_item`
  ADD CONSTRAINT `pembelian_item_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembelian_item_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `penjualan_item`
--
ALTER TABLE `penjualan_item`
  ADD CONSTRAINT `penjualan_item_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penjualan_item_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `piutang`
--
ALTER TABLE `piutang`
  ADD CONSTRAINT `piutang_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`),
  ADD CONSTRAINT `piutang_ibfk_2` FOREIGN KEY (`id_login`) REFERENCES `login` (`id_login`),
  ADD CONSTRAINT `piutang_ibfk_3` FOREIGN KEY (`id_toko`) REFERENCES `toko` (`id_toko`);

--
-- Constraints for table `piutang_bayar`
--
ALTER TABLE `piutang_bayar`
  ADD CONSTRAINT `piutang_bayar_ibfk_1` FOREIGN KEY (`id_piutang`) REFERENCES `piutang` (`id_piutang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok`
--
ALTER TABLE `stok`
  ADD CONSTRAINT `stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stok_opname`
--
ALTER TABLE `stok_opname`
  ADD CONSTRAINT `stok_opname_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `toko`
--
ALTER TABLE `toko`
  ADD CONSTRAINT `toko_ibfk_1` FOREIGN KEY (`id_bank_akun`) REFERENCES `bank_akun` (`id_bank_akun`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

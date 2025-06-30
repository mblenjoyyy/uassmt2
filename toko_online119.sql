-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 04:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_online119`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `tanggal_dibuat`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin@tokoolahraga.com', '2025-06-30 13:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `produk_olahraga`
--

CREATE TABLE `produk_olahraga` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal_ditambahkan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_diupdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk_olahraga`
--

INSERT INTO `produk_olahraga` (`id`, `nama_produk`, `kategori`, `merk`, `harga`, `stok`, `deskripsi`, `gambar`, `tanggal_ditambahkan`, `tanggal_diupdate`) VALUES
(1, 'Sepatu Lari Nike Air Max', 'Sepatu', 'Nike', 1250000.00, 15, 'Sepatu lari dengan teknologi Air Max untuk kenyamanan maksimal', 'nike_airmax.jpg', '2025-06-30 13:39:24', '2025-06-30 13:39:24'),
(2, 'Bola Sepak Adidas', 'Bola', 'Adidas', 350000.00, 25, 'Bola sepak resmi dengan kualitas premium', 'adidas_ball.jpg', '2025-06-30 13:39:24', '2025-06-30 13:39:24'),
(3, 'Raket Badminton Yonex', 'Raket', 'Yonex', 850000.00, 12, 'Raket badminton profesional untuk pemain intermediate hingga advanced', 'yonex_racket.jpg', '2025-06-30 13:39:24', '2025-06-30 13:39:24'),
(4, 'Jersey Futsal Specs', 'Pakaian', 'Specs', 275000.00, 30, 'Jersey futsal dengan bahan yang nyaman dan menyerap keringat', 'specs_jersey.jpg', '2025-06-30 13:39:24', '2025-06-30 13:39:24'),
(5, 'Tas Olahraga Adidas', 'Aksesoris', 'Adidas', 450000.00, 20, 'Tas olahraga multifungsi dengan berbagai kompartemen', 'adidas_bag.jpg', '2025-06-30 13:39:24', '2025-06-30 13:39:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `produk_olahraga`
--
ALTER TABLE `produk_olahraga`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk_olahraga`
--
ALTER TABLE `produk_olahraga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

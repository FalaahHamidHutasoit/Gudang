-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Okt 2026 pada 13.33
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudang2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('gudang','gerai') NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `locations`
--

INSERT INTO `locations` (`id`, `name`, `type`, `address`, `created_at`) VALUES
(1, 'Gudang Utama (G1)', 'gudang', 'Kawasan Industri Block A No. 12', '2026-10-03 17:55:33'),
(2, 'Gudang Cadangan (G2)', 'gudang', 'Jl. Logistik Nusantara No. 45', '2026-10-03 17:55:33'),
(3, 'Gerai Penjualan Utama', 'gerai', 'Jl. Raya Pemuda No. 88 (Ruko Central)', '2026-10-03 17:55:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `po_items`
--

CREATE TABLE `po_items` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `po_items`
--

INSERT INTO `po_items` (`id`, `po_id`, `product_id`, `qty`, `unit_cost`) VALUES
(1, 1, 1, 100, 30000.00),
(2, 2, 3, 100, 15000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `unit` varchar(20) DEFAULT 'Pcs',
  `min_stock` int(11) DEFAULT 10,
  `price` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `unit`, `min_stock`, `price`, `created_at`) VALUES
(1, 'BRG-001', 'Minyak Goreng Sawit 2L', 'Pouch', 15, 34000.00, '2026-10-03 17:55:33'),
(2, 'BRG-002', 'Beras Premium Pandan Wangi 5kg', 'Karung', 10, 72000.00, '2026-10-03 17:55:33'),
(3, 'BRG-003', 'Gula Pasir Kristal Putih 1kg', 'Pack', 20, 16500.00, '2026-10-03 17:55:33'),
(4, 'BRG-004', 'Susu UHT Full Cream 1L', 'Karton', 12, 19500.00, '2026-10-03 17:55:33'),
(5, 'BRG-005', 'Mie Instan Goreng (Karton isi 40)', 'Kardus', 8, 112000.00, '2026-10-03 17:55:33'),
(6, 'BRG-006', 'Deterjen Bubuk Anti Noda 1kg', 'Bag', 15, 24500.00, '2026-10-03 17:55:33'),
(7, 'BRG-007', 'Air Mineral 600ml (Karton isi 24)', 'Kardus', 10, 48000.00, '2026-10-03 17:55:33'),
(8, 'BRG-008', 'Kopi Bubuk Robusta 200g', 'Pack', 15, 22000.00, '2026-10-03 17:55:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `po_number` varchar(50) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `target_location_id` int(11) NOT NULL,
  `status` enum('pending','received','cancelled') DEFAULT 'pending',
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `supplier_name`, `target_location_id`, `status`, `total_amount`, `created_at`) VALUES
(1, 'PO-20261001-001', 'PT Semesta Distribusi', 1, 'received', 3000000.00, '2026-10-03 17:55:33'),
(2, 'PO-20261003-002', 'CV Pangan Utama', 1, 'pending', 1500000.00, '2026-10-03 17:55:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `location_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`id`, `invoice_number`, `location_id`, `total_amount`, `created_at`) VALUES
(1, 'INV-20261003-1001', 3, 107000.00, '2026-10-03 17:55:33'),
(2, 'INV-20261003-1002', 3, 160000.00, '2026-10-03 17:55:33'),
(3, 'INV-20261003-1478', 3, 34000.00, '2026-10-03 11:00:20'),
(4, 'INV-20261003-1034', 3, 144500.00, '2026-10-03 11:45:26'),
(5, 'INV-20261003-1676', 3, 158500.00, '2026-10-03 11:48:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `qty`, `price`, `subtotal`) VALUES
(1, 1, 1, 2, 34000.00, 68000.00),
(2, 1, 4, 2, 19500.00, 39000.00),
(3, 2, 2, 2, 72000.00, 144000.00),
(4, 2, 3, 1, 16000.00, 16000.00),
(5, 3, 1, 1, 34000.00, 34000.00),
(6, 4, 7, 1, 48000.00, 48000.00),
(7, 4, 2, 1, 72000.00, 72000.00),
(8, 4, 6, 1, 24500.00, 24500.00),
(9, 5, 2, 1, 72000.00, 72000.00),
(10, 5, 7, 1, 48000.00, 48000.00),
(11, 5, 3, 1, 16500.00, 16500.00),
(12, 5, 8, 1, 22000.00, 22000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stocks`
--

INSERT INTO `stocks` (`id`, `location_id`, `product_id`, `qty`) VALUES
(1, 1, 1, 150),
(2, 1, 2, 90),
(3, 1, 3, 200),
(4, 1, 4, 80),
(5, 1, 5, 50),
(6, 1, 6, 100),
(7, 1, 7, 120),
(8, 1, 8, 90),
(9, 2, 1, 60),
(10, 2, 2, 40),
(11, 2, 3, 100),
(12, 2, 4, 30),
(13, 2, 5, 25),
(14, 2, 6, 50),
(15, 2, 7, 60),
(16, 2, 8, 40),
(17, 3, 1, 4),
(18, 3, 2, 16),
(19, 3, 3, 7),
(20, 3, 4, 15),
(21, 3, 5, 3),
(22, 3, 6, 21),
(23, 3, 7, 23),
(24, 3, 8, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` int(11) NOT NULL,
  `transfer_code` varchar(50) NOT NULL,
  `from_location_id` int(11) NOT NULL,
  `to_location_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stock_transfers`
--

INSERT INTO `stock_transfers` (`id`, `transfer_code`, `from_location_id`, `to_location_id`, `product_id`, `qty`, `created_at`) VALUES
(1, 'TRX-20261002-101', 1, 3, 1, 20, '2026-10-03 17:55:33'),
(2, 'TRX-20261002-102', 2, 3, 4, 10, '2026-10-03 17:55:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('admin','kasir','gudang','pembelian') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`, `created_at`) VALUES
(1, 'admin', 'password123', 'Pak Anton (Owner/Admin)', 'admin', '2026-10-03 18:34:05'),
(2, 'kasir', 'password123', 'Siti Rahma (Kasir Gerai)', 'kasir', '2026-10-03 18:34:05'),
(3, 'gudang', 'password123', 'Budi Santoso (Staf Gudang)', 'gudang', '2026-10-03 18:34:05'),
(4, 'pembelian', 'password123', 'Andi Wijaya (Tim Purchasing)', 'pembelian', '2026-10-03 18:34:05');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `po_items`
--
ALTER TABLE `po_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Indeks untuk tabel `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `po_number` (`po_number`),
  ADD KEY `target_location_id` (`target_location_id`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `location_id` (`location_id`);

--
-- Indeks untuk tabel `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `location_id` (`location_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfer_code` (`transfer_code`),
  ADD KEY `from_location_id` (`from_location_id`),
  ADD KEY `to_location_id` (`to_location_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `po_items`
--
ALTER TABLE `po_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `po_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`target_location_id`) REFERENCES `locations` (`id`);

--
-- Ketidakleluasaan untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Ketidakleluasaan untuk tabel `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_ibfk_1` FOREIGN KEY (`from_location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_2` FOREIGN KEY (`to_location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `stock_transfers_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

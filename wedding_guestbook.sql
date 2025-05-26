-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 07:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wedding_guestbook`
--

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` varchar(36) NOT NULL,
  `name` text NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `checkin_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `name`, `status`, `checkin_time`) VALUES
('05bacdcf-e69d-4cf7-9ad5-8ed8323d5e40', 'Sadri Ramdani – Balikpapan', NULL, NULL),
('068bbf22-7393-41bb-bb25-cf9a62c86fe3', 'Rusminah (Mama Dani) – Batu Kajang', NULL, NULL),
('0f8ad07e-9271-41ff-8431-d6184dc2ff33', 'Pak Amin – PAMA PSR', 'hadir', '2025-05-21 21:42:20'),
('100ca62c-a735-45d0-8832-68e5d69c7a88', 'Pak Dimas – PAMA Berau', NULL, NULL),
('13f697e4-db0f-4b3c-8311-c7a67e0b3ad3', 'Toko Jerry – Batu Kajang', NULL, NULL),
('161bb981-73d9-4ffa-b8d4-6068b62d3d13', 'Trisna – BCI Bogor', NULL, NULL),
('19712c8a-aa24-46e8-af28-b8abd61eb28a', 'H. Ucok – Gang PLN', NULL, NULL),
('1b26d65a-a242-410c-b523-96925ab0ee8c', 'Bunda Sri Wibi – Balikpapan', NULL, NULL),
('1c116f87-5712-4fef-9bb6-935730944bc6', 'Koh Aming – Balikpapan', NULL, NULL),
('23ae2021-e6b0-43f6-90d5-e16f3dc6cfd5', 'Bunda Deden – BCI Bogor', NULL, NULL),
('2439ddde-537b-4322-a5ac-3375a8cf9cf5', 'Bunda Rafsi – Balikpapan', NULL, NULL),
('28702b9e-9aec-43f8-b578-22cad9ade4d6', 'Susanto – BTS Balikpapan', NULL, NULL),
('2a28a262-8dd2-4181-b674-1c4d75255756', 'Endang  – Sundari Catering', NULL, NULL),
('2a57c12a-e7b3-435a-9cbe-fe3695d2d104', 'Wisnu – BCI Bogor', NULL, NULL),
('2b7275e5-19c8-413f-802d-4fc44b7e07a8', 'Kepala Bank Muamalat – Balikpapan ', NULL, NULL),
('34915699-53df-4956-bddb-911f08c1c36c', 'Ustadzah Putri – Istiqomah Balikpapan', NULL, NULL),
('35fd3cf5-e35a-4739-b7c1-b270da9671a5', 'Arif Ripani – CIMB Niaga Balikpapan', NULL, NULL),
('39f71897-7c40-4679-8ca2-7541ea573082', 'Erwan – Batu Kajang', NULL, NULL),
('3dea8357-44d4-4752-8a91-06b4c3b4bcf0', 'Slamet – BCI Bogor', NULL, NULL),
('3f967c81-f4cb-4e7a-90d2-b88cc06cef45', 'Yon Heri – Balikpapan', NULL, NULL),
('4bf9db90-af8d-41eb-831f-f2d5b076a5c9', 'H. Zainal – Gang PLN', NULL, NULL),
('52a0b05a-2c16-4216-a716-3849e1326d8f', 'Jonprizal Piliang – Balikpapan', NULL, NULL),
('52c5e69c-65a5-4aa2-9204-fc27aa33638c', 'Yadi – Gang PLN', NULL, NULL),
('57132483-c6be-4fdc-a5fb-bc9dcfba06c2', 'Bunda Ari – Komite Balikpapan', NULL, NULL),
('5bdfcc4f-732d-4bf3-bfb5-53f0854d49e0', 'Mujib – PAMA HO ', 'hadir', '2025-05-26 21:24:50'),
('60a36abd-4288-4d22-b84c-c22fff814178', 'Ulin Nuha – Batu Kajang', NULL, NULL),
('60cfda38-aa45-45f4-8beb-1c3625093646', 'Hj. Erawati – Balikpapan', NULL, NULL),
('6ba80822-68e3-4be2-ad3f-899660ec88c5', 'Sumadi – Gang PLN', NULL, NULL),
('73598231-dcb7-4dfb-b935-8405d7b091ad', 'Kepala Bank CIMB Niaga – Balikpapan ', NULL, NULL),
('74b12ece-f4f5-412d-a63f-0eb4dd3aefd0', 'Bpk. Kelapa Desa – Batu Kajang', NULL, NULL),
('79c9c522-2c9b-42e5-9621-ca463afdd63b', 'Rico & Istri – Balikpapan ', NULL, NULL),
('7b004080-9e31-4340-897c-93fc625c0d96', 'dr. Natasya Juliani – Balikpapan', NULL, NULL),
('7f7ebb3f-c409-4c2e-bcbb-ebb0e1fd3c33', 'Isur – Kideco', NULL, NULL),
('8222a350-ca0a-4515-869b-cacfe87f85a4', 'Bunda Kevin – Balikpapan', NULL, NULL),
('8232cb3f-8b61-4182-a91a-26f377eadf42', 'Masmudah, S.Pd – Kepala Sekolah SD IT Istiqamah', NULL, NULL),
('86a33e13-797e-42c4-8339-b6d36f2914e9', 'Waluyi – BCI Bogor', NULL, NULL),
('8e06076b-1709-4dde-8b5c-9d03ef72c2e8', 'H. Adam – Gang PLN', NULL, NULL),
('8f45504a-6525-4e73-8597-5f1839f371e1', 'Pak RT 009 – Praja Bakti', NULL, NULL),
('92003d05-d538-4672-957a-c56cf45e41d7', 'H. Anwar – Toko Laras Balikpapan', NULL, NULL),
('93532421-c5b1-4799-9924-d513b5468bb6', 'Vitryadi Setiawan – Dunia Pulsa BKJ', NULL, NULL),
('94d0612a-d487-4c80-b69c-ffc4fb1e0754', 'Welly – Balikpapan', NULL, NULL),
('94f58516-8a32-40c0-94da-8fef04302a62', 'Mamah Oka – Batu Kajang', NULL, NULL),
('9a91ee6b-36db-4f50-8339-3214bcba9ad0', 'Indriany, S. Pd., M. M – Kepala Sekolah SMA IT Istiqamah', NULL, NULL),
('9e2f764e-7752-4b6b-8fbe-a6997a6c930f', 'Kayat – Gang PLN', NULL, NULL),
('a2921e5e-a005-4380-8a44-c32c68eee14b', 'Hudri – Balikpapan', NULL, NULL),
('a972dda9-cda2-4a48-a8cd-2766d4ae2b42', 'H. Ariyati Rianisa – Balikpapan', NULL, NULL),
('aa9781fc-583d-40f1-a2f4-22fd14bd219f', 'Ustadzah Dian – Istiqomah Balikpapan', NULL, NULL),
('b9a48f15-56b6-4298-9e59-b4c8dea90bb5', 'Himart  – Gang PLN', NULL, NULL),
('baa95663-c6e2-42ad-990f-99fcffc8e46b', 'David –  Danamon Balikpapan', NULL, NULL),
('bd348699-ddc5-4dc4-bf7a-ba9c75377f4f', 'Market Diana – Batu Kajang', NULL, NULL),
('c4dfdf28-cdfa-43a2-a15d-ef0e2225f0c4', 'Joko Prakoso – PAMA Bontang', NULL, NULL),
('c639ad79-5937-4c83-9a24-c0accd4e32eb', 'Yunita Purnama, M.Pd – Kepala Sekolah SMP IT Istiqamah', NULL, NULL),
('c84c6b20-22d4-4df8-9c9b-e4c3661d60fa', 'Welmon – Balikpapan', NULL, NULL),
('c889845c-f631-491c-b163-f8426914ceb9', 'Juari – Balikpapan', NULL, NULL),
('c895422f-070a-4043-ba21-e0e9455e55f3', 'M. Fadli – Balikpapan', NULL, NULL),
('ca3df854-3e02-43e9-b264-dde3825faa3a', 'Market Jaya – Batu Kajang', NULL, NULL),
('ca8698ce-e4df-49f6-aed2-d7f819706dc7', 'Bunda Fevy Surya – Balikpapan', NULL, NULL),
('ca93b4d9-1ef9-4f31-a9d2-0cc22799ad68', 'Dewi  – Sundari Catering', NULL, NULL),
('d9cbf15c-36cb-4e1a-918c-d5793f220f2a', 'Bunda Indri – Balikpapan', NULL, NULL),
('dce2f35b-d71a-4554-9695-762ccb3592a9', 'Deby – BIMA Batu Kajang', NULL, NULL),
('dd89ead5-5a56-472d-94df-6bf779793174', 'Emi – Batu Kajang', NULL, NULL),
('df3cfaf4-e85a-4c68-8d6c-4318207d58a7', 'H. Firman – Batu Kajang', NULL, NULL),
('df85ebed-928c-43a3-b59e-87b0c6fce37b', 'Ibu Kepala Desa – Songka', NULL, NULL),
('e03de83c-64fd-4f52-8cf8-c2728f67c1ba', 'Nurul Khafiz Zufi – Balikpapan', NULL, NULL),
('e217dc1d-344a-4138-89ea-a444fc9ac723', 'Wiwin – Sundari Catering', NULL, NULL),
('e43b0346-2856-4eef-8061-5d5146d2829d', 'H. Alex Auto 2000 – Balikpapan', NULL, NULL),
('e599e03c-eddd-441c-b9aa-cd4eb8912a0c', 'Bunda Nia Ikbal – Balikpapan', NULL, NULL),
('ec1f5aef-1170-48e9-be83-1c0becdab353', 'Kepala Bank BCA – Balikpapan', NULL, NULL),
('ec3ab457-4350-4f7a-9bb7-9a1e8e5c8b06', 'Feny Rudi – Gang PLN', NULL, NULL),
('f1d600cf-00f7-45ad-a33c-36c72e63d7a8', 'Dodi – BCI Bogor', NULL, NULL),
('f719722b-80d6-4458-b5ea-e34e3d9d8ef5', 'Ajiz Muslim – BCI Bogor', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `manual_guests`
--

CREATE TABLE `manual_guests` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `status` varchar(20) DEFAULT 'hadir',
  `checkin_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manual_guests`
--

INSERT INTO `manual_guests` (`id`, `name`, `status`, `checkin_time`) VALUES
(1, 'Dhanendra – Balikpapan', 'hadir', '2025-05-21 21:45:02'),
(2, 'Hasan – Bandung', 'hadir', '2025-05-21 22:52:51'),
(3, 'Bambang – Bandung', 'hadir', '2025-05-26 21:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$ZNixHTPL39I1mebKlPERDO3piag3p688HFRcRDXz6dQEBo2jnscdu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manual_guests`
--
ALTER TABLE `manual_guests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `manual_guests`
--
ALTER TABLE `manual_guests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

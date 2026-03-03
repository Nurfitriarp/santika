-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Mar 02, 2026 at 07:22 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `santika-dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenis_opd`
--

CREATE TABLE `tbl_jenis_opd` (
  `ID_J-OPD` int NOT NULL,
  `NAMA_OPD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_jenis_opd`
--

INSERT INTO `tbl_jenis_opd` (`ID_J-OPD`, `NAMA_OPD`) VALUES
(1, 'Dinas'),
(2, 'Bidang'),
(3, 'Lainnya\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kegiatan`
--

CREATE TABLE `tbl_kegiatan` (
  `ID_KEGIATAN` int NOT NULL,
  `NAMA` varchar(255) NOT NULL,
  `TEMPAT` varchar(255) NOT NULL,
  `JAM` varchar(255) NOT NULL,
  `TANGGAL` date NOT NULL,
  `SKPD_PENYELENGGARA` varchar(255) NOT NULL,
  `PIMPINAN_RAPAT` varchar(255) NOT NULL,
  `ID_OPD` int NOT NULL,
  `JML_PESERTA` int NOT NULL,
  `STS` int NOT NULL,
  `SERTIFIKAT` int NOT NULL,
  `JAM_PELAJARAN` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_kegiatan`
--

INSERT INTO `tbl_kegiatan` (`ID_KEGIATAN`, `NAMA`, `TEMPAT`, `JAM`, `TANGGAL`, `SKPD_PENYELENGGARA`, `PIMPINAN_RAPAT`, `ID_OPD`, `JML_PESERTA`, `STS`, `SERTIFIKAT`, `JAM_PELAJARAN`) VALUES
(1, 'Sosialisasi Web Santika-Dev', 'Kantor Pemerintahan Daerah', '9.00', '2026-03-25', 'Penyelenggara', 'Pimpinan', 1, 25, 3, 1, '9');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_login`
--

CREATE TABLE `tbl_login` (
  `ID_KEGIATAN` int NOT NULL,
  `NAMA` varchar(225) NOT NULL,
  `JEN_KEL` int NOT NULL,
  `NO_HP` varchar(15) NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `ID_OPD` varchar(11) NOT NULL,
  `JABATAN` varchar(255) NOT NULL,
  `TTD` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_opd`
--

CREATE TABLE `tbl_opd` (
  `ID_OPD` int NOT NULL,
  `ID_J-OPD` int NOT NULL,
  `NAMA_OPD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_opd`
--

INSERT INTO `tbl_opd` (`ID_OPD`, `ID_J-OPD`, `NAMA_OPD`) VALUES
(1, 1, 'Dinas Sosial'),
(2, 2, 'Bidang Aplikasi Informatika'),
(3, 3, 'DIskominfo');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tanda_tangan`
--

CREATE TABLE `tbl_tanda_tangan` (
  `ID` int NOT NULL,
  `ID_KEGIATAN` int NOT NULL,
  `SKPD` varchar(255) NOT NULL,
  `NAMA` varchar(255) NOT NULL,
  `JEN_KEL` int NOT NULL,
  `TTD` text NOT NULL,
  `DATE_TIME` datetime NOT NULL,
  `JABATAN` varchar(255) NOT NULL,
  `NO_HP` varchar(15) NOT NULL,
  `URUT_CETAK` int NOT NULL,
  `EMAIL` varchar(255) NOT NULL,
  `STS` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_jenis_opd`
--
ALTER TABLE `tbl_jenis_opd`
  ADD PRIMARY KEY (`ID_J-OPD`);

--
-- Indexes for table `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  ADD PRIMARY KEY (`ID_KEGIATAN`),
  ADD KEY `ID_OPD` (`ID_OPD`);

--
-- Indexes for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD KEY `ID_KEGIATAN` (`ID_KEGIATAN`);

--
-- Indexes for table `tbl_opd`
--
ALTER TABLE `tbl_opd`
  ADD PRIMARY KEY (`ID_OPD`),
  ADD UNIQUE KEY `ID_J-OPD` (`ID_J-OPD`);

--
-- Indexes for table `tbl_tanda_tangan`
--
ALTER TABLE `tbl_tanda_tangan`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_KEGIATAN` (`ID_KEGIATAN`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  ADD CONSTRAINT `tbl_kegiatan_ibfk_1` FOREIGN KEY (`ID_OPD`) REFERENCES `tbl_opd` (`ID_OPD`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD CONSTRAINT `tbl_login_ibfk_1` FOREIGN KEY (`ID_KEGIATAN`) REFERENCES `tbl_kegiatan` (`ID_KEGIATAN`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tbl_opd`
--
ALTER TABLE `tbl_opd`
  ADD CONSTRAINT `tbl_opd_ibfk_1` FOREIGN KEY (`ID_J-OPD`) REFERENCES `tbl_jenis_opd` (`ID_J-OPD`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `tbl_tanda_tangan`
--
ALTER TABLE `tbl_tanda_tangan`
  ADD CONSTRAINT `tbl_tanda_tangan_ibfk_1` FOREIGN KEY (`ID_KEGIATAN`) REFERENCES `tbl_kegiatan` (`ID_KEGIATAN`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

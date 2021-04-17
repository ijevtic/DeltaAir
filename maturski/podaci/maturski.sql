-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2020 at 07:36 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maturski`
--

-- --------------------------------------------------------

--
-- Table structure for table `aerodromi`
--

CREATE TABLE `aerodromi` (
  `id_aerodroma` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `lokacija_x` double NOT NULL,
  `lokacija_y` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aerodromi`
--

INSERT INTO `aerodromi` (`id_aerodroma`, `ime`, `lokacija_x`, `lokacija_y`) VALUES
(1, 'Beograd', 44.816199, 20.460332),
(2, 'Moskva', 55.75579, 37.614606),
(3, 'Podgorica', 42.441158, 19.262872),
(4, 'Madrid', 40.415392, -3.707365),
(5, 'London', 51.507334, -0.127758);

-- --------------------------------------------------------

--
-- Table structure for table `karte`
--

CREATE TABLE `karte` (
  `id_karte` int(11) NOT NULL,
  `cena_karte` int(11) NOT NULL,
  `datum_pocetka` date NOT NULL,
  `datum_kraja` date DEFAULT NULL,
  `id_leta` int(11) NOT NULL,
  `id_klase` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `karte`
--

INSERT INTO `karte` (`id_karte`, `cena_karte`, `datum_pocetka`, `datum_kraja`, `id_leta`, `id_klase`) VALUES
(1, 300, '2020-04-21', NULL, 1, 1),
(2, 150, '2020-04-21', NULL, 1, 2),
(3, 70, '2020-04-21', NULL, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `klase`
--

CREATE TABLE `klase` (
  `id_klase` int(11) NOT NULL,
  `tip_klase` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `klase`
--

INSERT INTO `klase` (`id_klase`, `tip_klase`) VALUES
(1, 'prva klasa'),
(2, 'biznis klasa'),
(3, 'ekonomska klasa');

-- --------------------------------------------------------

--
-- Table structure for table `letovi`
--

CREATE TABLE `letovi` (
  `id_leta` int(11) NOT NULL,
  `vreme_polaska` datetime NOT NULL,
  `id_linije` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `letovi`
--

INSERT INTO `letovi` (`id_leta`, `vreme_polaska`, `id_linije`) VALUES
(1, '2020-11-09 15:45:21', 1),
(2, '2020-11-10 15:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `letovi_klase`
--

CREATE TABLE `letovi_klase` (
  `id_leta` int(11) NOT NULL,
  `id_klase` int(11) NOT NULL,
  `kapacitet` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `letovi_klase`
--

INSERT INTO `letovi_klase` (`id_leta`, `id_klase`, `kapacitet`) VALUES
(1, 1, 2),
(1, 2, 3),
(1, 3, 5),
(2, 1, 5),
(2, 2, 10),
(2, 3, 20);

-- --------------------------------------------------------

--
-- Table structure for table `linije`
--

CREATE TABLE `linije` (
  `id_linije` int(11) NOT NULL,
  `vreme_trajanja` int(11) NOT NULL,
  `id_pocetne_lokacije` int(11) NOT NULL,
  `id_krajnje_lokacije` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `linije`
--

INSERT INTO `linije` (`id_linije`, `vreme_trajanja`, `id_pocetne_lokacije`, `id_krajnje_lokacije`) VALUES
(1, 45, 1, 3),
(2, 195, 1, 4),
(3, 260, 2, 5),
(4, 50, 2, 1),
(5, 180, 3, 4),
(6, 350, 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `putnici`
--

CREATE TABLE `putnici` (
  `id_putnika` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `lozinka` longtext NOT NULL,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `jmbg` varchar(13) NOT NULL,
  `mejl` varchar(50) NOT NULL,
  `telefon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `putnici`
--

INSERT INTO `putnici` (`id_putnika`, `username`, `lozinka`, `ime`, `prezime`, `jmbg`, `mejl`, `telefon`) VALUES
(5, 'ivan3', '$2y$10$DVvIsxWEx2D7UXFEuGYUZuCITSATJzaqadpU8tKrDFFypYZI5I5Ni', '123', '123', '123', 'ivan@gmail.com', '123'),
(6, 'ivan5', '$2y$10$xM0yhUk/8aAPK/UwwfV0l.ehIU27EvF/MgBhfZ4AKhQyZQaEKNsau', 'Ivan', 'Jevtic', 'lolcina', 'ivan5@gmail.com', '123'),
(11, 'test', '$2y$10$hJc//3tZyDMx8L3AN/Lf1OcX6klYkACLVM0SNv8EXCx36jypB.a4S', 'test', 'test', '123', 'test@test.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `putnici_karte`
--

CREATE TABLE `putnici_karte` (
  `id_putnika` int(11) NOT NULL,
  `id_karte` int(11) NOT NULL,
  `datum_kupovine` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `putnici_karte`
--

INSERT INTO `putnici_karte` (`id_putnika`, `id_karte`, `datum_kupovine`) VALUES
(5, 2, '2020-04-22'),
(5, 3, '2020-04-22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aerodromi`
--
ALTER TABLE `aerodromi`
  ADD PRIMARY KEY (`id_aerodroma`);

--
-- Indexes for table `karte`
--
ALTER TABLE `karte`
  ADD PRIMARY KEY (`id_karte`);

--
-- Indexes for table `klase`
--
ALTER TABLE `klase`
  ADD PRIMARY KEY (`id_klase`);

--
-- Indexes for table `letovi`
--
ALTER TABLE `letovi`
  ADD PRIMARY KEY (`id_leta`);

--
-- Indexes for table `letovi_klase`
--
ALTER TABLE `letovi_klase`
  ADD PRIMARY KEY (`id_leta`,`id_klase`);

--
-- Indexes for table `linije`
--
ALTER TABLE `linije`
  ADD PRIMARY KEY (`id_linije`);

--
-- Indexes for table `putnici`
--
ALTER TABLE `putnici`
  ADD PRIMARY KEY (`id_putnika`);

--
-- Indexes for table `putnici_karte`
--
ALTER TABLE `putnici_karte`
  ADD PRIMARY KEY (`id_putnika`,`id_karte`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `letovi`
--
ALTER TABLE `letovi`
  MODIFY `id_leta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `linije`
--
ALTER TABLE `linije`
  MODIFY `id_linije` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `putnici`
--
ALTER TABLE `putnici`
  MODIFY `id_putnika` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

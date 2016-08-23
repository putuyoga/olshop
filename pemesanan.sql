-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 20 Jun 2013 pada 17.10
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `pemesanan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pemesanan`
--

CREATE TABLE IF NOT EXISTS `detail_pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id_pemesanan`, `id_produk`, `kuantitas`, `harga_total`) VALUES
(1, 4, 2, 220000),
(1, 6, 1, 200000),
(1, 10, 2, 150000),
(2, 2, 5, 650000),
(2, 6, 1, 200000),
(3, 13, 1, 200000),
(4, 13, 2, 400000),
(4, 11, 5, 375000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `halaman`
--

CREATE TABLE IF NOT EXISTS `halaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(75) NOT NULL,
  `konten` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `halaman`
--

INSERT INTO `halaman` (`id`, `judul`, `konten`) VALUES
(1, 'Tentang', '....'),
(2, 'Cara Pesan', 'Pilih barang blabla..\r\n\r\nno hp 0812324234');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(60) NOT NULL,
  `deskripsi` text NOT NULL,
  `dilihat` int(11) NOT NULL DEFAULT '0',
  `dipesan` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `deskripsi`, `dilihat`, `dipesan`) VALUES
(1, 'T-Shirt', '', 18, 2),
(2, 'Topi', 'Biar gak panas, pakai topi :D', 15, 0),
(3, 'Jaket', 'Melindungi biar anget', 15, 0),
(4, 'Celana', '', 17, 2),
(5, 'Sandal', 'Buat alas kaki', 21, 3),
(6, 'Tas', '', 9, 3),
(7, 'coba', 'safasfafa', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(80) NOT NULL,
  `nama_lengkap` varchar(160) NOT NULL,
  `no_hp` varchar(25) DEFAULT NULL,
  `alamat` text,
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data untuk tabel `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `email`, `nama_lengkap`, `no_hp`, `alamat`, `level`) VALUES
(1, 'admin', 'cefbb5052548885d5fb2813fd5af2f2b', 'putuyoga@gmail.com', 'I Putu Yoga Permana', '081217352034', 'Jalan terusan sigura-gura blok d-175', 256),
(16, 'billy', '827ccb0eea8a706c4c34a16891f84e7b', 'billy@gmail.com', 'Billy Joe', '0817232323', 'Merak Indah 58', 1),
(17, 'dhavin', '827ccb0eea8a706c4c34a16891f84e7b', 'dhavin@gmail.com', 'Dhavin', '08124567433', 'Jalan Dimana saja', 1),
(18, 'tes', '827ccb0eea8a706c4c34a16891f84e7b', 'tes@gmail.com', 'Test saja', '2342423', 'sdsfdsfds', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `harga_total` int(11) NOT NULL,
  `status` tinyint(11) NOT NULL DEFAULT '0',
  `catatan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `id_member`, `tanggal`, `harga_total`, `status`, `catatan`) VALUES
(1, 16, '2013-05-26 20:46:02', 570000, 2, 'Dibungkus dengan kertas koran saja :D'),
(2, 17, '2013-05-27 02:57:20', 850000, 1, 'Dibungkus pakai plastik giant saja'),
(3, 18, '2013-05-27 04:08:50', 200000, 1, 'tes'),
(4, 18, '2013-05-27 14:04:13', 775000, 1, 'ga pake lama');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE IF NOT EXISTS `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL DEFAULT '0',
  `nama` varchar(80) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text NOT NULL,
  `tersedia` tinyint(1) NOT NULL DEFAULT '1',
  `dilihat` int(11) NOT NULL DEFAULT '0',
  `dipesan` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `id_kategori`, `nama`, `harga`, `deskripsi`, `tersedia`, `dilihat`, `dipesan`) VALUES
(1, 1, 'Hellish Angel', 130000, 'Printed with imperial rubber at Gildan Ultra Cotton.', 1, 7, 0),
(2, 1, 'Goat bless you', 130000, 'Printed with imperial rubber at Gidan Ultra Cotton.', 1, 6, 1),
(3, 1, 'Smith MSPT08', 110000, 'Simple black and white.', 1, 4, 0),
(4, 1, 'Smith MSPT06', 110000, 'NKRI Harga Mati !', 1, 6, 1),
(5, 2, 'JIBERISH New Era Fleur J Black and Yellow', 225000, 'Black and Yellow Hat', 1, 3, 0),
(6, 4, 'Clare Brown', 200000, 'Good Quality inside', 1, 11, 2),
(7, 3, 'Retronin Brown', 285000, '', 1, 11, 0),
(8, 4, 'Paradox Brown', 260000, '', 1, 5, 0),
(9, 4, 'Dime Black', 260000, '', 1, 1, 0),
(10, 5, 'NOD09018', 75000, '', 1, 11, 1),
(11, 5, 'NOD09017', 75000, '', 1, 12, 2),
(12, 5, 'NOD09016', 75000, '', 1, 11, 0),
(13, 6, 'NOD05020', 200000, '', 1, 23, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

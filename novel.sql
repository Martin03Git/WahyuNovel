-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 11:45 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novel`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id_author` int(11) NOT NULL,
  `nama_author` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id_author`, `nama_author`) VALUES
(1, 'J.K. Rowling'),
(2, 'George R.R. Martin'),
(3, 'J.R.R. Tolkien'),
(4, 'Agatha Christie'),
(5, 'Stephen King');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id_genre` int(11) NOT NULL,
  `nama_genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id_genre`, `nama_genre`) VALUES
(1, 'Fantasy'),
(2, 'Mystery'),
(3, 'Thriller'),
(5, 'Science Fiction'),
(6, 'Horror'),
(7, 'misteri');

-- --------------------------------------------------------

--
-- Table structure for table `novel`
--

CREATE TABLE `novel` (
  `id_novel` int(11) NOT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `judul_novel` varchar(255) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `tahun_rilis` int(4) DEFAULT NULL,
  `id_author` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `jumlah_user` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `novel`
--

INSERT INTO `novel` (`id_novel`, `poster`, `judul_novel`, `sinopsis`, `tahun_rilis`, `id_author`, `rating`, `jumlah_user`) VALUES
(1, 'My Divine Diary.jpg', 'My Divine Diary', 'Sebuah kecelakaan memberi Su Hao kemampuan untuk bereinkarnasi tanpa batas. Tapi siapa yang bisa memberitahunya kenapa dia tidak bisa hidup melewati usia lima tahun setiap kali dia bereinkarnasi? Alam semesta berbahaya dan tidak ramah terhadap anak-anak. Su Hao memutuskan tujuan kecil pertamanya – menjadi dewasa. “Bagaimana mungkin aku tidak menjadi dewasa!” … Di tengah jutaan reinkarnasi Su Hao, satu demi satu. Setelah memperoleh pengetahuan yang cukup, ia menemukan cara untuk menjadi dewa. Ini adalah jalan manusia menuju keilahian. Mungkin… Anda juga bisa! ~ My Divine Diary', 2026, 1, '0.00', 0),
(2, 'I Became the Tyrant of a Defense Game.jpg', 'I Became the Tyrant of a Defense Game', '[Tower Defense & Dungeon Attack RPG] Saya melihat akhir dari game yang tidak dapat diselesaikan oleh siapa pun . Tapi, ketika saya sadar, saya berada di dalam permainan. Nyatanya, saya berada di tahap tutorial, tempat di mana strategi tidak mungkin dilakukan. “Aku akan menyelesaikan game omong kosong ini bagaimanapun caranya…!” ~ I Became the Tyrant of a Defense Game', 2020, 2, '0.00', 0),
(3, 'Ex Rank Supporting Role_s Replay in a Prestigious School.jpeg', 'Ex Rank Supporting', 'Peringkat EX menyelesaikan bab terakhir dari game nasional yang gagal dan menjadi karakter pendukung yang tidak disebutkan namanya di dalam game. Di bawah standar dan tidak dapat diukur, peringkat EX memainkan peran pendukung dengan peringkat yang tidak diketahui. ~ Ex Rank Supporting Role’s Replay in a Prestigious School', 2020, 3, '0.00', 0),
(4, 'Count_s Youngest Son is a Warlock.jpeg', 'Count’s Youngest Son is a Warlock', 'Putra bungsu Count of Chronia menjadi penyihir untuk hidup. ~ Count’s Youngest Son is a Warlock', 2020, 4, '0.00', 0),
(5, 'Return of Mount Hua Sect.jpeg', 'Return of Mount Hua Sect', 'Murid ke-13 dari Sekte Gunung Besar Hua. Salah satu Pendekar Pedang Generasi Ketiga Terhebat. Master Pedang Bunga Plum, Chungmyung. Setelah mengiris kepala Iblis Surgawi yang tak tertandingi, yang melemparkan dunia ke dalam kekacauan, dia tidur nyenyak di puncak Seratus Ribu Pegunungan Besar. Melompat lebih dari seratus tahun, dia kembali dalam tubuh seorang anak. Tapi apa? Gunung Hua menurun? Apa yang kamu bicarakan!? Wajar jika Anda ingin hidup jika Anda bangkrut. “Menolak? Meskipun aku di sini? Siapa yang berani!” Bunga plum akhirnya gugur. Tetapi ketika musim dingin berlalu dan musim semi tiba, bunga plum akan mekar lagi. “Tapi aku akan mati dulu sebelum Gunung Hua dihidupkan kembali! Jika Anda akan bangkrut, sebaiknya lakukan dengan benar, Anda bajingan! ” Awal dari Master Pedang Bunga Plum, perjuangan sendirian Chungmyung untuk menyelamatkan Sekte Gunung Hua yang benar-benar menurun. ~ Return of Mount Hua Sect', 2019, 1, '0.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `novel_genre`
--

CREATE TABLE `novel_genre` (
  `id_novel` int(11) NOT NULL,
  `id_genre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `novel_genre`
--

INSERT INTO `novel_genre` (`id_novel`, `id_genre`) VALUES
(1, 3),
(1, 5),
(2, 1),
(3, 1),
(4, 2),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id_user` int(11) NOT NULL,
  `id_novel` int(11) NOT NULL,
  `nilai` int(11) DEFAULT NULL CHECK (`nilai` between 1 and 10),
  `ulasan` text NOT NULL DEFAULT 'Tidak ada komentar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id_user`, `id_novel`, `nilai`, `ulasan`) VALUES
(2, 1, 9, 'Tidak ada komentar'),
(2, 2, 8, 'Tidak ada komentar'),
(3, 2, 7, 'Tidak ada komentar'),
(3, 3, 9, 'Tidak ada komentar'),
(4, 1, 8, 'Tidak ada komentar'),
(4, 3, 10, 'Tidak ada komentar'),
(4, 4, 7, 'Tidak ada komentar'),
(5, 1, 9, 'Tidak ada komentar'),
(5, 2, 8, 'Tidak ada komentar'),
(5, 3, 10, 'Tidak ada komentar');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','user') NOT NULL,
  `watchlist` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `level`, `watchlist`) VALUES
(1, 'Admin User', 'admin', 'adminpassword', 'admin', '-'),
(2, 'Alice Johnson', 'alicej', 'password1', 'user', '1,2'),
(3, 'Bob Smith', 'bobsmith', 'password2', 'user', '2,3'),
(4, 'Charlie Brown', 'charlieb', 'password3', 'user', '1,3,4'),
(5, 'Diana Prince', 'dianap', 'password4', 'user', '1,2,3,4'),
(8, 'agus', 'agus', 'agus', 'user', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id_author`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indexes for table `novel`
--
ALTER TABLE `novel`
  ADD PRIMARY KEY (`id_novel`),
  ADD KEY `id_author` (`id_author`);

--
-- Indexes for table `novel_genre`
--
ALTER TABLE `novel_genre`
  ADD PRIMARY KEY (`id_novel`,`id_genre`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id_user`,`id_novel`),
  ADD KEY `id_novel` (`id_novel`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id_author` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `novel`
--
ALTER TABLE `novel`
  MODIFY `id_novel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `novel`
--
ALTER TABLE `novel`
  ADD CONSTRAINT `novel_ibfk_1` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`);

--
-- Constraints for table `novel_genre`
--
ALTER TABLE `novel_genre`
  ADD CONSTRAINT `novel_genre_ibfk_1` FOREIGN KEY (`id_novel`) REFERENCES `novel` (`id_novel`),
  ADD CONSTRAINT `novel_genre_ibfk_2` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id_genre`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`id_novel`) REFERENCES `novel` (`id_novel`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

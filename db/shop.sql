-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2022 at 10:25 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `adm_email` varchar(100) NOT NULL,
  `adm_username` varchar(100) NOT NULL,
  `adm_password` varchar(100) NOT NULL,
  `status` enum('dev','admin','seller','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `adm_email`, `adm_username`, `adm_password`, `status`) VALUES
(1, 'admin@lol.com', 'ansln', '123', 'dev');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `uid`, `userId`, `productId`, `qty`) VALUES
(4, 'ct_90_737437360', 2, 1, 1),
(13, 'ct_30_1844934399', 3, 8, 2),
(19, 'ct_58_2108624043', 21, 9, 2),
(27, 'ct_38_555785461', 1, 8, 3);

-- --------------------------------------------------------

--
-- Table structure for table `dashboard`
--

CREATE TABLE `dashboard` (
  `id` int(11) NOT NULL,
  `slideimage_1` varchar(250) NOT NULL,
  `top_banner` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `db_test`
--

CREATE TABLE `db_test` (
  `id` int(11) NOT NULL,
  `gender` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `db_test`
--

INSERT INTO `db_test` (`id`, `gender`) VALUES
(50, 'Pria');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `pd_id` int(11) NOT NULL,
  `pd_img` varchar(500) NOT NULL,
  `img_uid` varchar(10) NOT NULL,
  `pd_name` varchar(250) NOT NULL,
  `pd_price` decimal(20,0) NOT NULL,
  `pd_stock` int(11) NOT NULL,
  `pd_weight` int(11) NOT NULL,
  `pd_desc` varchar(1000) NOT NULL,
  `pd_category` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `pd_rand` varchar(100) NOT NULL,
  `pd_link` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`pd_id`, `pd_img`, `img_uid`, `pd_name`, `pd_price`, `pd_stock`, `pd_weight`, `pd_desc`, `pd_category`, `status`, `pd_rand`, `pd_link`) VALUES
(1, '/shop/assets/product/img/kurma-ajwa.jpg', '', 'Kurma Ajwa', '200000', 50, 0, 'Kurma ajwa berbentuk bulat dengan tekstur kulit yang halus tetapi mengerut. Rasa daging buahnya agak manis seperti kismis. Namun teksturnya daging buahnya tidak begitu lembut. Biasanya kurma ajwa dikonsumsi pada tahap kematangan tamar, yakni ketika kulitnya sudah berwarna coklat kehitaman. Kurma ajwa yang belum masak atau masih khalal berwarna merah kesumba. Pada tahap rutap, kurma ajwa muncul warna hitam dari bagian ujungnya.', 'Buah', 0, 'pd-OH9ZE3', 'kurma-ajwa'),
(2, '/shop/assets/product/img/kurma-deglet-noor.jpg', '', 'Kurma Deglet Noor', '150000', 25, 0, 'kurma jenis ini memiliki rasa legit dan tidak terlalu manis. Jika bosan dimakan segar, kurma ini juga cocok diolah menjadi campuran pada puding dan kue. Kurma ini juga jadi favorit di Inggris dan Amerika terutama karena kerenyahannya yang cocok buat taburan cake. Selain garing legit rasanya kandungan nutrisi kurma ini juga cukup bagus. Tiap 100 g kurma deglet noor mengandung 282 kilo kalori, karbohidrat 75 g dan protein 2,5 g. Sedangkan kandungan mineral yang terpenting adalah potassium 655.94 mg, fosfor 62.01 mg, magnesium 43.01 mg dan kalisum 39.01 mg. Kandungan nutrisinya cukup untuk memberi pasokan kebutuhan nutrisi per hari untuk tubuh.', 'Buah', 0, 'pd-tqDsd8', 'kurma-deglet-noor'),
(3, 'https://bateel.com/media/catalog/product/cache/7b87012fab0eaa0484d820bd62ac7576/0/1/01_majdool_lf.jpg', '', 'Kurma Medjool', '200000', 25, 0, 'Kurma medjool adalah jenis kurma yang dinikmati karena rasa manisnya yang alami. Kurma medjool memiliki rasa yang lebih besar, lebih gelap, dan lebih seperti karamel daripada jenis umum lainnya seperti Deglet Noor. Sebagai buah batu tropis, mereka memiliki lubang tunggal yang dikelilingi oleh daging yang dapat dimakan. Kurma medjool berasal dari pohon kurma (Phoenix dactylifera) dan sekarang ditanam di daerah hangat di Amerika Serikat, Timur Tengah, Asia Selatan dan Afrika.', 'Buah', 0, 'pd-6z8jdA', 'kurma-medjool'),
(4, 'https://lifepack.id/wp-content/uploads/2020/04/Kurma.jpg', '', 'Kurma Sukkari', '250000', 50, 0, 'Kurma sukari adalah salah satu jenis kurma premium yang banyak dibudidayakan di area Irak dan Arab Saudi. Kata sukari sendiri berasal dari bahasa Arab \"sukkar\" yang berarti gula. Kurma ini berwarna lebih kuning dibanding dengan varian lainnya dan memiliki rasa yang lebih manis.', 'Buah', 0, 'pd-9hnfZc', 'kurma-sukkari'),
(5, 'https://cdn-cas.orami.co.id/parenting/images/zaghloul.width-800.png', '', 'Kurma Zaghloul', '250000', 25, 0, 'Kurma yang mahal harganya ternyata terdapat di negar Mesir dan bernama kurma zaghloul. Kurma ini berwarna merah gelap serta berbentuk lonjong. Hal yang menjadi keistimewaannya adalah karena kurma ini memiliki rasa manis yang tak habis-habis bahkan setelah dimakan.', 'Buah', 0, 'pd-b6HjQR', 'kurma-zaghloul'),
(6, 'https://cf.shopee.co.id/file/17982e2a3b71d4c333c8457ef2cfd57f', '', 'Kurma Safawi', '2000000', 10, 1000, 'Kurma Safawi adalah salah satu jenis kurma terbaik, bahkan yang terbaik setelah kurma Ajwa, sang kurma nabi. Pohon kurma Safawi sangat produktif sehingga di Madinah kurma Safawi lebih mudah dijumpai bila dibandingkan dengan kurma Ajwa.', 'Buah', 0, 'pd-pR74ex', 'kurma-safawi'),
(7, '/shop/sys/admin/auth/uploads/SD-IMG-62f38700641dc2.61427989.jpg', 'img-u35JUl', 'Kurma Ajwa', '40000', 100, 500, 'Kurma Ajwa atau Kurma Nabi merupakan jenis kurma yang populer di kalangan orang Indonesia. Kurma Ajwa juga dikenal dengan sebutan Raja dari Segala Kurma. Buah asal negeri Arab ini memiliki perpaduan rasa manis karamel, madu, dan kayu manis dengan tekstur yang kenyal. Tidak heran mengapa buah ini kerap dicari menjelang bulan Ramadhan, Toppers. Tahukah Toppers, di seluruh dunia terdapat lebih dari 1.400 jenis kurma? Nah, selain rasa khasnya, buah ini memiliki kandungan serat, mineral, dan vitamin yang tinggi. Ada segudang manfaat yang bisa kamu dapatkan dengan mengonsumsi kurma ajwa loh.', 'ajwa', 1, 'pd-mlZWuP', 'kurma-ajwa'),
(8, '/shop/sys/admin/auth/uploads/SD-IMG-62f3c949cec218.55141853.jpeg', 'img-AbSAFY', 'Kurma Khalas Saad', '32000', 100, 1000, 'Kurma Khalas memiliki cita rasa yang sedang, sehingga sangat cocok untuk lidah orang Indonesia. Tekstur buah kurma ini lembut, tidak punya serat, tidak terlalu manis, daging kurma lebih tebal, biji kurma kecil, bentuknya oval, warna coklat ke-emasan dan dagingnya lembut seperti karamel. Sangat Cocok digunakan sebagai camilan, MP-ASI, nutrisi untuk memperlancar ASI dan air nabeez kurma (air rendaman kurma)', 'khalas saad', 1, 'pd-Ru9CIf', 'kurma-khalas-saad'),
(9, '/shop/sys/admin/auth/uploads/SD-IMG-62f3ccb69b7127.66039871.jpeg', 'img-oX3Uxc', 'Kurma Sukari', '37500', 50, 500, 'Pada awalnya, kurma sukari dibudidayakan di wilayah Al Qaseem. Popularitasnya kemudian mendunia, terlebih di wilayah Asia Selatan. Sukkari atau dalam bahasa Arabnya &#039;Sukkur&#039; berarti sugar atau gula. Sesuai dengan namanya, kurma sukari memiliki rasa yang manis dan berdaging lembut seperti karamel. Sukkari sangat dimakan langsung atau diolah sebagai campuran bahan kue, roti, sereal, maupun smoothie atau jus.', 'sukari', 1, 'pd-yUihCJ', 'kurma-sukari');

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `id` int(11) NOT NULL,
  `img_uid` varchar(100) NOT NULL,
  `img_link` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`id`, `img_uid`, `img_link`) VALUES
(25, 'img-u35JUl', 'SD-IMG-62f38700641dc2.61427989.jpg'),
(26, 'img-u35JUl', 'SD-IMG-62f387006430a6.10221294.jpg'),
(27, 'img-AbSAFY', 'SD-IMG-62f3c949cec218.55141853.jpeg'),
(28, 'img-AbSAFY', 'SD-IMG-62f3c949ceec27.43221177.jpg'),
(31, 'img-oX3Uxc', 'SD-IMG-62f3ccb69b7127.66039871.jpeg'),
(32, 'img-oX3Uxc', 'SD-IMG-62f3ccb69b93d4.50105168.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `u_profilePict` varchar(150) NOT NULL,
  `u_fName` varchar(250) NOT NULL,
  `u_lName` varchar(250) NOT NULL,
  `u_email` varchar(250) NOT NULL,
  `u_username` varchar(150) NOT NULL,
  `u_password` varchar(150) NOT NULL,
  `u_phone` varchar(15) NOT NULL,
  `u_gender` enum('Male','Female','Other','') NOT NULL,
  `u_dob` date NOT NULL,
  `email_verification_code` varchar(100) NOT NULL,
  `email_verified_at` varchar(100) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `u_profilePict`, `u_fName`, `u_lName`, `u_email`, `u_username`, `u_password`, `u_phone`, `u_gender`, `u_dob`, `email_verification_code`, `email_verified_at`, `status`) VALUES
(1, '/shop/assets/etc/mybb.jpg', 'Antonius', 'Leonardo', 'le@a.c', 'ansln', '6001ccaf5cee2630f708310b7bae4ff7', '081386951599', 'Male', '2000-01-01', '649268', '04 Aug 2022 | 09:02:04pm', 1),
(2, '', 'a', 'a', 'a@a.c', 'a2', 'a', '123', 'Male', '2022-08-04', '725094', '', 0),
(3, '', 'Jeffrens', 'Tanadi', 'jeffrenss@gmail.com', 'jeffrenss', '202cb962ac59075b964b07152d234b70', '081123456789', 'Male', '2002-10-04', '', '', 0),
(21, '', 'Antonius', 'Leonardo', 'anroniusleonardo@gmail.com', 'antoniusln', '202cb962ac59075b964b07152d234b70', '081386951598', 'Male', '1998-01-01', '597601', '02 Sep 2022 | 01:08:46pm', 1),
(23, '', 'joni', 'kun', 'joniiiiiiiiiiiii@gmail.com', 'joniiii', '202cb962ac59075b964b07152d234b70', '081234567899', 'Male', '1997-01-01', ' ', ' ', 0),
(24, '', 'loi', 'kie', 'locdown@gmail.com', 'locdown', '202cb962ac59075b964b07152d234b70', '081312345678', 'Female', '1998-01-01', '893980', ' ', 0),
(25, '', 'nangning', 'nignang', 'nigang@gmail.com', 'nigang', '202cb962ac59075b964b07152d234b70', '082112345678', 'Male', '1998-01-01', ' ', ' ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `u_recName` varchar(100) NOT NULL,
  `u_phone` varchar(15) NOT NULL,
  `u_addressLabel` varchar(100) NOT NULL,
  `u_provinceId` int(11) NOT NULL,
  `u_provinceName` varchar(100) NOT NULL,
  `u_cityId` int(11) NOT NULL,
  `u_cityName` varchar(100) NOT NULL,
  `u_disctrict` varchar(100) NOT NULL,
  `u_postalCode` int(11) NOT NULL,
  `u_completeAddress` varchar(250) NOT NULL,
  `u_defaultAddress` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_address`
--

INSERT INTO `user_address` (`id`, `userId`, `u_recName`, `u_phone`, `u_addressLabel`, `u_provinceId`, `u_provinceName`, `u_cityId`, `u_cityName`, `u_disctrict`, `u_postalCode`, `u_completeAddress`, `u_defaultAddress`) VALUES
(27, 1, 'Leo', '081386951599', 'Rumah', 9, 'Jawa Barat', 55, 'Bekasi, Kota', 'Rawalumbu', 17116, 'Jl. Bojong Asri VI Blok E15 No.6', 1),
(29, 1, 'Samiha', '081386951599', 'Rumah 2', 6, 'DKI Jakarta', 153, 'Jakarta Selatan, Kota', 'Kuningan', 17226, 'Jl. Durian Runtuh Blok C2 No.69', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE `user_log` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `u_loginTime` varchar(100) NOT NULL,
  `u_loginDate` varchar(100) NOT NULL,
  `u_logoutTime` varchar(100) NOT NULL,
  `u_logoutDate` varchar(100) NOT NULL,
  `u_ipaddress` varchar(100) NOT NULL,
  `u_device` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`id`, `userId`, `u_loginTime`, `u_loginDate`, `u_logoutTime`, `u_logoutDate`, `u_ipaddress`, `u_device`) VALUES
(158, 1, '04:42:43pm', '02-09-2022', '04:44:10pm', '02-09-2022', '::1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1'),
(159, 1, '08:49:02pm', '02-09-2022', '09:54:42pm', '02-09-2022', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `uid` varchar(100) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `uid`, `userId`, `productId`) VALUES
(7, 'ws_36_1808042071', 2, 1),
(18, 'ws_28_610843949', 3, 8),
(19, 'ws_74_1649316234', 24, 9),
(21, 'ws_39_1975031198', 21, 9),
(32, 'ws_18_1803395990', 1, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_test`
--
ALTER TABLE `db_test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`pd_id`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_log`
--
ALTER TABLE `user_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `db_test`
--
ALTER TABLE `db_test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `pd_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user_log`
--
ALTER TABLE `user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

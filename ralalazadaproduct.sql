-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2020 at 11:20 AM
-- Server version: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testimport`
--

-- --------------------------------------------------------

--
-- Table structure for table `lazadaproduct`
--

CREATE TABLE `lazadaproduct` (
  `ProductID` int NOT NULL,
  `Name` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Description` varchar(500) NOT NULL,
  `SellerSku` varchar(50) NOT NULL,
  `Quantity` int NOT NULL,
  `MainImage` varchar(100) NOT NULL,
  `ModifiedUser` varchar(50) NOT NULL,
  `ModifiedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lazadaproduct`
--

INSERT INTO `lazadaproduct` (`ProductID`, `Name`, `Description`, `SellerSku`, `Quantity`, `MainImage`, `ModifiedUser`) VALUES
(1, 'ERNIE BALL® สายสะพายกีตาร์ 3in1 ลวดลายศิลปะ สำหรับกีตาร์โปร่ง/กีตาร์ไฟฟ้า/กีตาร์เบส รุ่น Classic Jacquard ** Made in USA**', '', 'Ernie-Ball-Classic-Jacquard-P04665', 1, 'https://th-live.slatic.net/p/4c23ad610529a210198d8ccc8afdc2ba.jpg', 'bot'),
(2, 'ERNIE BALL® สายสะพายกีตาร์ 3in1 ลวดลายศิลปะ สำหรับกีตาร์โปร่ง/กีตาร์ไฟฟ้า/กีตาร์เบส รุ่น Classic Jacquard ** Made in USA**', '', 'Ernie-Ball-Classic-Jacquard-P04667', 1, 'https://th-live.slatic.net/p/13f138a9bb3eaa861d7edfdfad671863.jpg', 'bot'),
(3, 'Blackstar® FLY 3 Acoustic แอมป์โปร่ง แอมป์อะคูสติก 3 วัตต์ เชื่อมต่อสมาร์ทโฟนได้ มีเสียงเอคโค่ในตัว + แถมฟรีถ่านพร้อมใช้งาน ** ประกันศูนย์ 1 ปี **', '', 'Blackstar-FLY-3-Acoustic', 2, 'https://th-live.slatic.net/p/47983cb42e2279b14c139e2d03da93e5.jpg', 'bot'),
(4, 'Fender® Super Switch สวิทช์ 5 ทาง สำหรับกีตาร์ทรง Strat / Tele พร้อมอุปกรณ์ / 5 Way Strat/Tele Super Switch (Model#: 0992251000) ** Made in Taiwan **', '', 'Fender-5-Way-Super-Switch-0992251000', 1, 'https://th-live.slatic.net/p/98a8ff5f049da4b3a13ae22785b631df.jpg', 'bot'),
(5, 'Fender® 50th Anniversary Woodstock Pick ปิ๊กกีตาร์ รุ่น ฉลองเทศกาลดนตรี Woodstock ครบ 50 ปี (Medium: 0.72 mm) / 1 แพ็ค มี 6 ตัว ** Made in Canada **', '', 'Fender-50th-Woodstock-Picks', 1, 'https://th-live.slatic.net/p/1dc2f6dec193c01854a996829c67321c.jpg', 'bot'),
(6, 'Epiphone® Les Paul Custom Pro Koa กีตาร์ไฟฟ้า ทรงเลสพอล 22 เฟร็ต Limited Edition ** ประกันศูนย์ 1 ปี **', '', 'Epiphone-LP-Custom-Pro-Koa', 1, 'https://th-live.slatic.net/p/a9d31e1a8d2625893ad4d2de1b6f6f7d.jpg', 'bot'),
(7, 'Ernie Ball® Prodigy Shield 2.0 มม. ปิ๊กกีตาร์ไฟฟ้า หนาทนพิเศษ วัสดุ Delrin® (สีดำ) ** Made in USA ** (Model#: P09331)', '', 'Ernie-Ball-Prodigy-Shield-Black-P09331', 6, 'https://th-live.slatic.net/p/db69ca2c735dbc646744a5f51a0ecbe3.jpg', 'bot'),
(8, 'KORG® B2 เปียโนไฟฟ้า เปียโนดิจิตอล 88 คีย์ ลำโพงสเตอริโอ ต่อคอมได้ (สีดำ) + แถมฟรี Pedal 1 แป้น & อแดปเตอร์ & ที่วางโน้ต ** ประกันศูนย์ 1 ปี **', '', 'Korg-B2-BK', 2, 'https://th-live.slatic.net/p/5abb7705fcc6ece8c957204c7226c147.jpg', 'bot'),
(9, 'Taiki T-D220 กีตาร์โปร่ง 41 นิ้ว ทรง Dreadnought ไม้โซลิดอีเกิ้ลแมนสปรูซ/ไม้แลนซ์วู้ด ** กีตาร์โปร่ง Finger Style ** + แถมฟรีกระเป๋า / คาโป้ / ปิ๊ก', '', 'Taiki-T-D220-Set-A', 3, 'https://th-live.slatic.net/p/38c6ca3916382601f9b53b90110ff62e.jpg', 'bot'),
(10, 'Mantic MG-1CE กีตาร์โปร่งไฟฟ้า 40 นิ้ว ทรง Grand Concert คอเว้า เคลือบด้าน ไม้ซิทก้าสปรูซ/โอกูเมะ ** มีเครื่องตั้งสายในตัว **', '', 'Mantic-MG-1CEN', 1, 'https://th-live.slatic.net/p/299aefa73a7c278f6c586c42d95affbd.jpg', 'bot'),
(11, 'Ernie Ball® Prodigy Sharp 2.0 มม. ปิ๊กกีตาร์ไฟฟ้า หนาทนพิเศษ วัสดุ Delrin® (สีดำ) ** Made in USA ** (Model#: P09335)', '', 'Ernie-Ball-Prodigy-Sharp-Black-P09335', 6, 'https://th-live.slatic.net/p/4426365df6cf1755ab9b98372721b605.jpg', 'bot'),
(12, 'Fender® วอลุ่มกีตาร์ไฟฟ้า / สวิทช์โทน 500K Split Shaft Potentiometer (Volume or Tone) (Model#: 0990834000) ** Made in Taiwan **', '', 'Fender-500K-Split-Shaft-Potentiometer-0990834000', 3, 'https://th-live.slatic.net/p/fb68fafedbd2a0d3a4774e65f9770f1e.jpg', 'bot'),
(13, 'Ernie Ball® Prodigy Teardrop 2.0 มม. ปิ๊กกีตาร์ไฟฟ้า หนาทนพิเศษ วัสดุ Delrin® (สีดำ) ** Made in USA ** (Model#: P09330)', '', 'Ernie-Ball-Prodigy-Teardrop-black-P09330', 6, 'https://th-live.slatic.net/p/561c6166b488e5d0b0ff637cf0785821.jpg', 'bot'),
(14, 'Fender® Rumble Studio 40 แอมป์เบส 40 วัตต์ ระบบดิจิตอล มีเอฟเฟคมากกว่า 40 แบบ มีเอฟเฟคลูปเสียง 60 วิ+ แถมฟรีแอปพลิเคชั่น Fender Tone ** ประกันศูนย์ 1 ปี **', '', 'Fender-Rumble-Studio-40', 1, 'https://th-live.slatic.net/p/4a6cf1b0acf8f5ed6b6d9da1b9437dc4.jpg', 'bot'),
(15, 'Korg® PC-300 เก้าอี้เปียโน ขาโลหะ เบาะหนานุ่ม ปรับระดับได้ 46-53 ซม. (Piano Stool / Piano Bench)', '', 'Korg-PC-300-BR', 1, 'https://th-live.slatic.net/p/19a4a931f8e05ec4617c420cdb4d67d5.jpg', 'bot'),
(16, 'Korg® PC-300 เก้าอี้เปียโน ขาโลหะ เบาะหนานุ่ม ปรับระดับได้ 46-53 ซม. (Piano Stool / Piano Bench)', '', 'Korg-PC-300-WH', 1, 'https://th-live.slatic.net/p/fb1e701bb86f9398d7d3aaa9abb7ec37.jpg', 'bot'),
(17, 'Korg® PC-300 เก้าอี้เปียโน ขาโลหะ เบาะหนานุ่ม ปรับระดับได้ 46-53 ซม. (Piano Stool / Piano Bench)', '', 'Korg-PC-300-BK', 1, 'https://th-live.slatic.net/p/6493d8d6d3e97669cc1a7e1f2e154e47.jpg', 'bot'),
(18, 'Mantic MG-1CE กีตาร์โปร่งไฟฟ้า 40 นิ้ว ทรง Grand Concert คอเว้า เคลือบด้าน ไม้ซิทก้าสปรูซ/โอกูเมะ + แถมฟรีกระเป๋า & คาโป้ & ปิ๊ก ** มีเครื่องตั้งสายในตัว **', '', 'Mantic-MG-1CEN-S1', 1, 'https://th-live.slatic.net/p/8b50dd76fd38461c0e18da309e378484.jpg', 'bot'),
(19, 'Taiki T-D220 กีตาร์โปร่ง 41 นิ้ว ทรง Dreadnought ไม้โซลิดอีเกิ้ลแมนสปรูซ (Solid Engelmann Spruce)/ไม้แลนซ์วู้ด (Lacewood) ลูกบิดนิเกิลสีทอง ** กีตาร์โปร่ง Finger Style **', '', 'Taiki-T-D220', 3, 'https://th-live.slatic.net/p/a29acb046c6a96280358312231c7c66b.jpg', 'bot'),
(20, 'Ernie Ball® Prodigy Large Shield 2.0 มม. ปิ๊กกีตาร์ไฟฟ้า หนาทนพิเศษ วัสดุ Delrin® (สีดำ) ** Made in USA ** (Model#: P09332)', '', 'Ernie-Ball-Prodigy-Large-Shield-Black-P09332', 6, 'https://th-live.slatic.net/p/058a5e04409b6da1d653869c9e29bf39.jpg', 'bot'),
(21, 'Samson® Meteorite ไมค์คอนเดนเซอร์ USB ไมโครโฟน ทรงกลม สำหรับงาน Live สดผ่านโซเชียล หมุนปรับตำแหน่งรับเสียงได้ มีฟังก์ชันลดเสียงรบกวน ใช้งานได้ทั้งกับคอมและสมาร์ทโฟน ** ประกันศูนย์ 1 ปี **', '', 'Samson-Meteorite', 1, 'https://th-live.slatic.net/p/a81a19f0e88c77cfd84a83e9302f84e6.jpg', 'bot'),
(22, 'Ernie Ball® Prodigy 2.0 มม. ปิ๊กกีตาร์ไฟฟ้า 6 แบบ หนาทนพิเศษ วัสดุ Delrin® (สีดำ) ** Made in USA ** (Mini / Teardrop / Standard / Sharp / Shield / Large Shield ) (Model#: P09342)', '', 'Ernie-Ball-Prodigy-Black-set-6-P09342', 2, 'https://th-live.slatic.net/p/8dcf098cfafbcb4391fd5b9bb6a5c6e7.jpg', 'bot'),
(23, 'Fender® สวิทช์ 5 ทาง สำหรับกีตาร์ทรง Strat พร้อมอุปกรณ์ / 5 Way Strat Pickup Selector Switch (Model#: 0991367000) ** Made in Taiwan **', '', 'Fender-5-Way-Switch-0991367000', 1, 'https://th-live.slatic.net/p/9b9e38138807c5fbe358f628744484cc.jpg', 'bot');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lazadaproduct`
--
ALTER TABLE `lazadaproduct`
  ADD PRIMARY KEY (`ProductID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lazadaproduct`
--
ALTER TABLE `lazadaproduct`
  MODIFY `ProductID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

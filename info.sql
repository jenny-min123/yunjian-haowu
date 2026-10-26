-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: info
-- ------------------------------------------------------
-- Server version	5.7.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `info`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `info` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `info`;

--
-- Table structure for table `seller`
--

DROP TABLE IF EXISTS `seller`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seller` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seller_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺名称',
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seller_name` (`seller_name`),
  UNIQUE KEY `uk_seller_username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='商家信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seller`
--

/*!40000 ALTER TABLE `seller` DISABLE KEYS */;
INSERT INTO `seller` VALUES (1,'云间汉服馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'云间汉服馆','北京市朝阳区建国路88号','13800000001','2026-04-20 22:35:10'),(2,'汉风堂','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'汉风堂','长沙市芙蓉区五一广场22号','13800000002','2026-04-20 22:35:10'),(3,'东方旗袍社','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'东方旗袍社','南京市秦淮区夫子庙街18号','13800000003','2026-04-20 22:35:10'),(4,'水墨服饰','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'水墨服饰','南京市秦淮区夫子庙街18号','13800000004','2026-04-20 22:35:10'),(5,'棉麻国风','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'棉麻国风','广州市天河区天河路365号','13800000005','2026-04-20 22:35:10'),(6,'冬韵汉服','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'冬韵汉服','北京市朝阳区建国路88号','13800000006','2026-04-20 22:35:10'),(7,'日常优品','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'日常优品','重庆市渝中区解放碑步行街66号','13800000007','2026-04-20 22:35:10'),(8,'牛仔世家','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'牛仔世家','北京市朝阳区建国路88号','13800000008','2026-04-20 22:35:10'),(9,'夏日清凉店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'夏日清凉店','西安市莲湖区回民街42号','13800000009','2026-04-20 22:35:10'),(10,'运动风','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'运动风','北京市朝阳区建国路88号','13800000010','2026-04-20 22:35:10'),(11,'内衣优品','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'内衣优品','青岛市市南区香港中路72号','13800000011','2026-04-20 22:35:10'),(12,'仙女衣橱','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'仙女衣橱','重庆市渝中区解放碑步行街66号','13800000012','2026-04-20 22:35:10'),(13,'温柔风','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'温柔风','杭州市西湖区文三路25号','13800000013','2026-04-20 22:35:10'),(14,'棉麻优品','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'棉麻优品','西安市莲湖区回民街42号','13800000014','2026-04-20 22:35:10'),(15,'国风鞋履馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'国风鞋履馆','西安市莲湖区回民街42号','13800000015','2026-04-20 22:35:10'),(16,'时尚女鞋店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'时尚女鞋店','郑州市金水区二七路31号','13800000016','2026-04-20 22:35:10'),(17,'运动潮鞋','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'运动潮鞋','武汉市武昌区楚河汉街45号','13800000017','2026-04-20 22:35:10'),(18,'小白鞋专属','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'小白鞋专属','西安市莲湖区回民街42号','13800000018','2026-04-20 22:35:10'),(19,'马丁靴世家','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'马丁靴世家','昆明市五华区南屏街58号','13800000019','2026-04-20 22:35:10'),(20,'夏日鞋店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'夏日鞋店','杭州市西湖区文三路25号','13800000020','2026-04-20 22:35:10'),(21,'商务鞋店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'商务鞋店','长沙市芙蓉区五一广场22号','13800000021','2026-04-20 22:35:10'),(22,'帆布鞋之家','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'帆布鞋之家','南京市秦淮区夫子庙街18号','13800000022','2026-04-20 22:35:10'),(23,'冬日鞋店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'冬日鞋店','长沙市芙蓉区五一广场22号','13800000023','2026-04-20 22:35:10'),(24,'国风包包馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'国风包包馆','昆明市五华区南屏街58号','13800000024','2026-04-20 22:35:10'),(25,'包包优选','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'包包优选','西安市莲湖区回民街42号','13800000025','2026-04-20 22:35:10'),(26,'时尚包包店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'时尚包包店','西安市莲湖区回民街42号','13800000026','2026-04-20 22:35:10'),(27,'背包之家','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'背包之家','青岛市市南区香港中路72号','13800000027','2026-04-20 22:35:10'),(28,'晚宴包包','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'晚宴包包','成都市锦江区春熙路99号','13800000028','2026-04-20 22:35:10'),(29,'运动包包','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'运动包包','北京市朝阳区建国路88号','13800000029','2026-04-20 22:35:10'),(30,'小包包店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'小包包店','上海市浦东新区陆家嘴大街120号','13800000030','2026-04-20 22:35:10'),(31,'托特包专属','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'托特包专属','苏州市姑苏区平江路33号','13800000031','2026-04-20 22:35:10'),(32,'云间果园','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'云间果园','北京市朝阳区建国路88号','13800000032','2026-04-20 22:35:10'),(33,'精品水果馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'精品水果馆','长沙市芙蓉区五一广场22号','13800000033','2026-04-20 22:35:10'),(34,'新疆水果直供','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'新疆水果直供','苏州市姑苏区平江路33号','13800000034','2026-04-20 22:35:10'),(35,'进口水果馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'进口水果馆','西安市莲湖区回民街42号','13800000035','2026-04-20 22:35:10'),(36,'岭南水果','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'岭南水果','重庆市渝中区解放碑步行街66号','13800000036','2026-04-20 22:35:10'),(37,'文房四宝店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'文房四宝店','青岛市市南区香港中路72号','13800000037','2026-04-20 22:35:10'),(38,'禅意生活馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'禅意生活馆','武汉市武昌区楚河汉街45号','13800000038','2026-04-20 22:35:10'),(39,'茶器生活馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'茶器生活馆','昆明市五华区南屏街58号','13800000039','2026-04-20 22:35:10'),(40,'文玩折扇店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'文玩折扇店','广州市天河区天河路365号','13800000040','2026-04-20 22:35:10'),(41,'古风配饰店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'古风配饰店','杭州市西湖区文三路25号','13800000041','2026-04-20 22:35:10'),(42,'日用百货店','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'日用百货店','青岛市市南区香港中路72号','13800000042','2026-04-20 22:35:10'),(43,'家居生活馆','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'家居生活馆','北京市朝阳区建国路88号','13800000043','2026-04-20 22:35:10'),(44,'tjm1','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'tjm1','西湖7号','13800000044','2026-04-21 00:07:05'),(45,'哈哈哈','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom',NULL,'哈哈哈','留仙洞','11111111111','2026-04-23 12:34:58'),(46,'shopTest01','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user1@example.com','测试小铺','深圳市南山区测试路1号','13800000045','2026-10-26 20:55:04');
/*!40000 ALTER TABLE `seller` ENABLE KEYS */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'tjm','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user2@example.com'),(9,'tjm1','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user3@example.com'),(10,'aaa','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user4@example.com'),(11,'超级无敌霹雳皇帝','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user5@example.com'),(12,'啊','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user6@example.com'),(14,'111','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','user7@example.com');
INSERT INTO `user` VALUES (15,'demo','$2y$10$TdkRyCLe1vHU/Ny9KEHCh.22HoE4ghJEdvDXDwuUiKh2TBLyYhXom','demo@example.com');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-26 22:01:47

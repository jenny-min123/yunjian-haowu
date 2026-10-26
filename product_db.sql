-- MySQL dump 10.13  Distrib 8.0.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: product_db
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
-- Current Database: `product_db`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `product_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `product_db`;

--
-- Table structure for table `clothes`
--

DROP TABLE IF EXISTS `clothes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clothes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '产品编码',
  `name` varchar(50) NOT NULL COMMENT '产品名称',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `description` varchar(200) DEFAULT '' COMMENT '产品描述',
  `type` varchar(10) NOT NULL COMMENT '类型：国风/日常',
  `seller` varchar(50) DEFAULT '云间好物官方旗舰店' COMMENT '商家名称',
  `coupon_info` varchar(100) DEFAULT '无优惠券' COMMENT '优惠券信息',
  `service` varchar(100) DEFAULT '7天无理由退换' COMMENT '服务说明',
  `return_free_shipping` tinyint(1) DEFAULT '1' COMMENT '退货包邮 1=是 0=否',
  `sales_count` int(11) DEFAULT '0',
  `sales_money` decimal(10,2) DEFAULT '0.00',
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='服装表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clothes`
--

/*!40000 ALTER TABLE `clothes` DISABLE KEYS */;
INSERT INTO `clothes` VALUES (1,'GF001','汉服齐胸襦裙',299.00,'传统唐风齐胸襦裙，印花刺绣工艺','国风','云间汉服馆','满299减30','7天无理由退换',1,0,0.00,NULL),(2,'GF002','明制圆领袍',399.00,'明制男士圆领袍，日常可穿','国风','汉风堂','满399减50','7天无理由退换',1,0,0.00,NULL),(3,'GF003','宋制褙子套装',269.00,'宋制清新褙子，透气舒适','国风','云间汉服馆','满269减20','7天无理由退换',1,0,0.00,NULL),(4,'GF004','国风改良旗袍',239.00,'现代改良短款旗袍，显瘦百搭','国风','东方旗袍社','无优惠券','7天无理由退换',1,0,0.00,NULL),(5,'GF005','唐制诃子裙',329.00,'唐风大袖诃子裙，仙气十足','国风','云间汉服馆','满329减40','7天无理由退换',1,2,658.00,'2026-04-22 10:56:26'),(6,'GF006','国风对襟长衫',189.00,'水墨印花对襟长衫，男女同款','国风','水墨服饰','无优惠券','7天无理由退换',1,0,0.00,NULL),(7,'GF007','马面裙半身裙',249.00,'织金妆花马面裙，日常通勤','国风','云间汉服馆','满249减25','7天无理由退换',1,2,498.00,'2026-04-23 14:25:45'),(8,'GF008','国风交领上衣',159.00,'棉麻交领上衣，透气亲肤','国风','棉麻国风','无优惠券','7天无理由退换',1,0,0.00,NULL),(9,'GF009','汉元素连衣裙',199.00,'汉元素改良连衣裙，简约大方','国风','云间汉服馆','满199减20','7天无理由退换',1,0,0.00,NULL),(10,'GF010','国风斗篷外套',279.00,'秋冬毛呢斗篷，古风保暖','国风','冬韵汉服','无优惠券','7天无理由退换',1,1,279.00,'2026-04-23 12:54:59'),(11,'RC001','纯棉圆领T恤',59.00,'基础款纯色T恤，百搭舒适','日常','日常优品','满59减5','7天无理由退换',1,0,0.00,NULL),(12,'RC002','休闲牛仔裤',129.00,'直筒宽松牛仔裤，显瘦显高','日常','牛仔世家','无优惠券','7天无理由退换',1,0,0.00,NULL),(13,'RC003','薄款防晒外套',89.00,'冰丝防晒衣，透气轻薄','日常','夏日清凉店','无优惠券','7天无理由退换',1,0,0.00,NULL),(14,'RC004','运动卫衣',99.00,'连帽宽松卫衣，春秋必备','日常','运动风','无优惠券','7天无理由退换',1,1,99.00,'2026-04-20 21:40:25'),(15,'RC005','休闲短裤',69.00,'纯棉五分短裤，夏日清凉','日常','夏日清凉店','无优惠券','7天无理由退换',1,0,0.00,NULL),(16,'RC006','修身打底衫',49.00,'长袖修身打底，内搭神器','日常','内衣优品','无优惠券','7天无理由退换',0,0,0.00,NULL),(17,'RC007','工装休闲裤',119.00,'多口袋工装裤，潮流百搭','日常','牛仔世家','无优惠券','7天无理由退换',1,0,0.00,NULL),(18,'RC008','碎花连衣裙',139.00,'雪纺碎花裙，温柔气质','日常','仙女衣橱','无优惠券','7天无理由退换',1,0,0.00,NULL),(19,'RC009','针织开衫',79.00,'薄款针织开衫，空调房必备','日常','温柔风','无优惠券','7天无理由退换',1,1,79.00,'2026-04-21 00:25:28'),(20,'RC010','宽松衬衫',109.00,'棉麻宽松衬衫，通勤休闲','日常','棉麻优品','无优惠券','7天无理由退换',1,0,0.00,NULL),(25,'GF111','丝袜',22.90,'好穿','clothes','tjm1','无优惠券','7天无理由退换',1,5,109.50,'2026-04-20 23:41:18'),(26,'YF001','11',12.00,'11','clothes','tjm1','无优惠券','7天无理由退换',1,0,0.00,NULL);
/*!40000 ALTER TABLE `clothes` ENABLE KEYS */;

--
-- Table structure for table `daily_goods`
--

DROP TABLE IF EXISTS `daily_goods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '产品编码',
  `name` varchar(50) NOT NULL COMMENT '产品名称',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `description` varchar(200) DEFAULT '' COMMENT '产品描述',
  `seller` varchar(50) DEFAULT '云间好物官方旗舰店' COMMENT '商家名称',
  `coupon_info` varchar(100) DEFAULT '无优惠券' COMMENT '优惠券信息',
  `service` varchar(100) DEFAULT '7天无理由退换' COMMENT '服务说明',
  `return_free_shipping` tinyint(1) DEFAULT '1' COMMENT '退货包邮 1=是 0=否',
  `sales_count` int(11) DEFAULT '0',
  `sales_money` decimal(10,2) DEFAULT '0.00',
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='百货表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `daily_goods`
--

/*!40000 ALTER TABLE `daily_goods` DISABLE KEYS */;
INSERT INTO `daily_goods` VALUES (1,'BH001','竹纹宣纸笔记本',29.90,'竹纹肌理宣纸本，书写温润雅致','文房四宝店','无优惠券','7天无理由退换',1,0,0.00,NULL),(2,'BH002','墨韵陶瓷香插',39.90,'青瓷釉面香插，祥云纹样','禅意生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(3,'BH003','云纹棉麻茶巾',19.90,'吸水棉麻茶巾，云纹刺绣','茶器生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(4,'BH004','山水折扇',25.90,'宣纸扇面，水墨山水','文玩折扇店','无优惠券','7天无理由退换',1,0,0.00,NULL),(5,'BH005','实木书签四件套',35.90,'黑檀木雕刻书签，梅兰竹菊','文房四宝店','无优惠券','7天无理由退换',1,0,0.00,NULL),(6,'BH006','青瓷小茶杯',45.90,'薄胎青瓷品茗杯，釉色清润','茶器生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(7,'BH007','古风布艺香囊',15.90,'刺绣布艺香囊，可装艾草','禅意生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(8,'BH008','竹制茶托盘',69.90,'天然竹制茶盘，纹理清新','茶器生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(9,'BH009','宣纸信笺套装',22.90,'古风信纸信封，手写书信','文房四宝店','无优惠券','7天无理由退换',1,0,0.00,NULL),(10,'BH010','陶瓷笔筒',32.90,'青瓷笔筒，简约造型','文房四宝店','无优惠券','7天无理由退换',1,0,0.00,NULL),(11,'BH011','流苏穗子挂件',12.90,'多色古风流苏，包挂扇坠','古风配饰店','无优惠券','7天无理由退换',0,0,0.00,NULL),(12,'BH012','竹节毛笔杆',29.90,'天然竹节笔杆，狼毫小楷','文房四宝店','无优惠券','7天无理由退换',1,0,0.00,NULL),(13,'BH013','青瓷香薰炉',59.90,'中式复古香薰炉，茶室氛围','禅意生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(14,'BH014','纯棉毛巾',15.90,'吸水柔软纯棉毛巾','日用百货店','无优惠券','7天无理由退换',0,0,0.00,NULL),(15,'BH015','抽纸3包',9.90,'原生木浆抽纸，柔韧不掉屑','日用百货店','无优惠券','不支持退换',0,0,0.00,NULL),(16,'BH016','洗衣液2kg',29.90,'深层洁净洗衣液，持久留香','日用百货店','无优惠券','7天无理由退换',0,0,0.00,NULL),(17,'BH017','保温杯',49.90,'304不锈钢保温杯，长效保温','家居生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(18,'BH018','收纳箱大号',39.90,'塑料收纳箱，衣物杂物收纳','家居生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(19,'BH019','衣架10个装',12.90,'防滑衣架，挂衣不变形','日用百货店','无优惠券','不支持退换',0,0,0.00,NULL),(20,'BH020','拖把套装',35.90,'旋转拖把，免手洗懒人拖把','家居生活馆','无优惠券','7天无理由退换',1,0,0.00,NULL);
/*!40000 ALTER TABLE `daily_goods` ENABLE KEYS */;

--
-- Table structure for table `fruits`
--

DROP TABLE IF EXISTS `fruits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fruits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '产品编码',
  `name` varchar(50) NOT NULL COMMENT '产品名称',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `description` varchar(200) DEFAULT '' COMMENT '产品描述',
  `seller` varchar(50) DEFAULT '云间果园' COMMENT '商家名称',
  `coupon_info` varchar(100) DEFAULT '无优惠券' COMMENT '优惠券信息',
  `service` varchar(100) DEFAULT '坏果包赔' COMMENT '服务说明',
  `return_free_shipping` tinyint(1) DEFAULT '0' COMMENT '退货包邮 1=是 0=否',
  `sales_count` int(11) DEFAULT '0',
  `sales_money` decimal(10,2) DEFAULT '0.00',
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='水果表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fruits`
--

/*!40000 ALTER TABLE `fruits` DISABLE KEYS */;
INSERT INTO `fruits` VALUES (1,'SG001','红富士苹果',8.90,'脆甜多汁红富士，新鲜当季','云间果园','满50减5','坏果包赔',0,0,0.00,NULL),(2,'SG002','砂糖橘',5.90,'皮薄肉甜砂糖橘，易剥皮','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(3,'SG003','海南香蕉',3.90,'香甜软糯香蕉，自然成熟','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(4,'SG004','阳光玫瑰葡萄',29.90,'无籽香甜阳光玫瑰，精品果','精品水果馆','无优惠券','坏果包赔',0,0,0.00,NULL),(5,'SG005','泰国龙眼',12.90,'肉厚核小龙眼，清甜可口','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(6,'SG006','红心火龙果',9.90,'红心火龙果，花青素满满','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(7,'SG007','新疆哈密瓜',15.90,'甜脆哈密瓜，果肉金黄','新疆水果直供','无优惠券','坏果包赔',0,0,0.00,NULL),(8,'SG008','四川丑橘',7.90,'丑八怪橘子，超甜多汁','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(9,'SG009','进口车厘子',59.90,'大果车厘子，脆甜多汁','进口水果馆','无优惠券','坏果包赔',0,0,0.00,NULL),(10,'SG010','广西芒果',11.90,'香甜小台农芒果，核小肉厚','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(11,'SG011','广东荔枝',25.90,'新鲜荔枝，冰镇口感更佳','岭南水果','无优惠券','坏果包赔',0,0,0.00,NULL),(12,'SG012','云南石榴',8.90,'软籽石榴，不用吐籽','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(13,'SG013','山东冬枣',13.90,'脆甜冬枣，一口一个','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(14,'SG014','福建柚子',6.90,'红心蜜柚，酸甜多汁','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(15,'SG015','智利蓝莓',19.90,'新鲜蓝莓，护眼小浆果','进口水果馆','无优惠券','坏果包赔',0,0,0.00,NULL),(16,'SG016','海南菠萝',4.90,'香甜菠萝，去皮即食','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(17,'SG017','陕西猕猴桃',9.90,'绿心猕猴桃，维C丰富','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(18,'SG018','新疆香梨',10.90,'皮薄肉香梨，清甜润肺','新疆水果直供','无优惠券','坏果包赔',0,0,0.00,NULL),(19,'SG019','云南草莓',22.90,'新鲜奶油草莓，香甜浓郁','云间果园','无优惠券','坏果包赔',0,0,0.00,NULL),(20,'SG020','台湾莲雾',18.90,'清甜莲雾，补水佳果','精品水果馆','无优惠券','坏果包赔',0,0,0.00,NULL);
/*!40000 ALTER TABLE `fruits` ENABLE KEYS */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `seller` varchar(50) NOT NULL,
  `type` varchar(10) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (10,'tjm1','GF010','国风斗篷外套',279.00,1,279.00,'冬韵汉服','clothes','2026-04-23 12:54:59'),(2,'tjm1','GF111','丝袜',21.90,3,65.70,'tjm1','clothes','2026-04-18 17:34:59'),(3,'aaa','RC004','运动卫衣',99.00,1,99.00,'运动风','clothes','2026-04-20 21:40:25'),(4,'aaa','GF111','丝袜',21.90,1,21.90,'tjm1','clothes','2026-04-20 21:40:45'),(5,'tjm1','GF111','丝袜',21.90,1,21.90,'tjm1','clothes','2026-04-20 23:41:18'),(6,'tjm1','RC009','针织开衫',79.00,1,79.00,'温柔风','clothes','2026-04-21 00:25:28'),(7,'tjm1','GF007','马面裙半身裙',249.00,1,249.00,'云间汉服馆','clothes','2026-04-21 00:25:28'),(8,'tjm1','GF005','唐制诃子裙',329.00,1,329.00,'云间汉服馆','clothes','2026-04-21 00:25:47'),(9,'啊','GF005','唐制诃子裙',329.00,1,329.00,'云间汉服馆','clothes','2026-04-22 10:56:26'),(11,'111','GF007','马面裙半身裙',249.00,1,249.00,'云间汉服馆','clothes','2026-04-23 14:25:45');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

--
-- Table structure for table `shoes_bags`
--

DROP TABLE IF EXISTS `shoes_bags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shoes_bags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT '产品编码',
  `name` varchar(50) NOT NULL COMMENT '产品名称',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `description` varchar(200) DEFAULT '' COMMENT '产品描述',
  `seller` varchar(50) DEFAULT '云间好物官方旗舰店' COMMENT '商家名称',
  `coupon_info` varchar(100) DEFAULT '无优惠券' COMMENT '优惠券信息',
  `service` varchar(100) DEFAULT '7天无理由退换' COMMENT '服务说明',
  `return_free_shipping` tinyint(1) DEFAULT '1' COMMENT '退货包邮 1=是 0=否',
  `sales_count` int(11) DEFAULT '0',
  `sales_money` decimal(10,2) DEFAULT '0.00',
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='鞋包表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shoes_bags`
--

/*!40000 ALTER TABLE `shoes_bags` DISABLE KEYS */;
INSERT INTO `shoes_bags` VALUES (1,'XB001','国风刺绣单鞋',129.00,'古风刺绣平底单鞋，搭配汉服','国风鞋履馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(2,'XB002','复古玛丽珍鞋',159.00,'英伦风玛丽珍，百搭小皮鞋','时尚女鞋店','无优惠券','7天无理由退换',1,0,0.00,NULL),(3,'XB003','运动老爹鞋',199.00,'厚底增高老爹鞋，舒适轻便','运动潮鞋','无优惠券','7天无理由退换',1,0,0.00,NULL),(4,'XB004','小白鞋板鞋',139.00,'经典百搭小白鞋，四季可穿','小白鞋专属','无优惠券','7天无理由退换',1,0,0.00,NULL),(5,'XB005','马丁靴短靴',229.00,'英伦风马丁靴，帅气有型','马丁靴世家','无优惠券','7天无理由退换',1,0,0.00,NULL),(6,'XB006','凉鞋拖鞋两用',79.00,'夏日凉拖，轻便舒适','夏日鞋店','无优惠券','7天无理由退换',0,0,0.00,NULL),(7,'XB007','国风绣花布鞋',99.00,'老北京绣花鞋，透气养脚','国风鞋履馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(8,'XB008','乐福鞋一脚蹬',169.00,'懒人乐福鞋，通勤必备','商务鞋店','无优惠券','7天无理由退换',1,0,0.00,NULL),(9,'XB009','高帮帆布鞋',89.00,'经典高帮帆布鞋，潮流百搭','帆布鞋之家','无优惠券','7天无理由退换',1,0,0.00,NULL),(10,'XB010','雪地靴棉鞋',179.00,'加绒保暖雪地靴，冬日必备','冬日鞋店','无优惠券','7天无理由退换',1,0,0.00,NULL),(11,'XB011','国风刺绣斜挎包',99.00,'古风刺绣小方包，汉服配饰','国风包包馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(12,'XB012','简约帆布包',39.00,'大容量帆布包，学生党必备','包包优选','无优惠券','7天无理由退换',0,0,0.00,NULL),(13,'XB013','时尚单肩包',129.00,'PU皮单肩包，百搭通勤','时尚包包店','无优惠券','7天无理由退换',1,0,0.00,NULL),(14,'XB014','双肩背包',159.00,'休闲双肩包，旅行上学','背包之家','无优惠券','7天无理由退换',1,0,0.00,NULL),(15,'XB015','手拿包晚宴包',89.00,'精致手拿包，派对约会','晚宴包包','无优惠券','7天无理由退换',1,0,0.00,NULL),(16,'XB016','国风手提包',119.00,'木质提手古风包，文艺复古','国风包包馆','无优惠券','7天无理由退换',1,0,0.00,NULL),(17,'XB017','链条小方包',149.00,'金属链条包，时尚气质','时尚包包店','无优惠券','7天无理由退换',1,0,0.00,NULL),(18,'XB018','运动健身包',139.00,'大容量健身包，干湿分离','运动包包','无优惠券','7天无理由退换',1,0,0.00,NULL),(19,'XB019','零钱包卡包',29.00,'小巧卡包，收纳卡片零钱','小包包店','无优惠券','7天无理由退换',0,0,0.00,NULL),(20,'XB020','托特包大包包',169.00,'超大容量托特包，通勤购物','托特包专属','无优惠券','7天无理由退换',1,0,0.00,NULL);
/*!40000 ALTER TABLE `shoes_bags` ENABLE KEYS */;

--
-- Table structure for table `user_browse_history`
--

DROP TABLE IF EXISTS `user_browse_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_browse_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `goods_code` varchar(20) NOT NULL,
  `goods_name` varchar(50) NOT NULL,
  `goods_price` decimal(10,2) NOT NULL,
  `goods_type` varchar(10) NOT NULL,
  `browse_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_browse_history`
--

/*!40000 ALTER TABLE `user_browse_history` DISABLE KEYS */;
INSERT INTO `user_browse_history` VALUES (1,'tjm1','GF005','唐制诃子裙',329.00,'clothes','2026-04-21 21:15:03'),(2,'tjm1','GF003','宋制褙子套装',269.00,'clothes','2026-04-21 21:24:44'),(3,'tjm1','RC009','针织开衫',79.00,'clothes','2026-04-21 21:34:58'),(4,'tjm1','RC004','运动卫衣',99.00,'clothes','2026-04-22 10:39:26'),(5,'啊','RC003','薄款防晒外套',89.00,'clothes','2026-04-22 10:46:28'),(6,'啊','GF005','唐制诃子裙',329.00,'clothes','2026-04-22 10:56:11'),(7,'啊','GF007','马面裙半身裙',249.00,'clothes','2026-04-22 10:59:41'),(8,'啊','RC001','纯棉圆领T恤',59.00,'clothes','2026-04-22 11:00:44'),(9,'啊','RC005','休闲短裤',69.00,'clothes','2026-04-22 11:00:48'),(10,'tjm1','GF010','国风斗篷外套',279.00,'clothes','2026-04-23 12:53:33'),(11,'tjm1','GF006','国风对襟长衫',189.00,'clothes','2026-04-23 12:53:44'),(13,'tjm1','GF001','汉服齐胸襦裙',299.00,'clothes','2026-04-23 12:59:14'),(14,'111','GF004','国风改良旗袍',239.00,'clothes','2026-04-23 14:24:47'),(15,'111','RC010','宽松衬衫',109.00,'clothes','2026-04-23 14:24:53'),(16,'111','RC006','修身打底衫',49.00,'clothes','2026-04-23 14:26:13');
/*!40000 ALTER TABLE `user_browse_history` ENABLE KEYS */;

--
-- Table structure for table `user_favorites`
--

DROP TABLE IF EXISTS `user_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `goods_code` varchar(20) NOT NULL,
  `goods_name` varchar(50) NOT NULL,
  `goods_price` decimal(10,2) NOT NULL,
  `goods_type` varchar(10) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_favorites`
--

/*!40000 ALTER TABLE `user_favorites` DISABLE KEYS */;
INSERT INTO `user_favorites` VALUES (5,'tjm1','GF006','国风对襟长衫',189.00,'clothes','2026-04-23 12:54:45'),(6,'111','RC010','宽松衬衫',109.00,'clothes','2026-04-23 14:25:03');
/*!40000 ALTER TABLE `user_favorites` ENABLE KEYS */;

--
-- Table structure for table `user_follow_shops`
--

DROP TABLE IF EXISTS `user_follow_shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_follow_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `seller_name` varchar(50) NOT NULL,
  `create_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_follow_shops`
--

/*!40000 ALTER TABLE `user_follow_shops` DISABLE KEYS */;
INSERT INTO `user_follow_shops` VALUES (4,'tjm1','汉风堂','2026-04-23 12:52:19'),(5,'111','云间汉服馆','2026-04-23 14:25:29');
/*!40000 ALTER TABLE `user_follow_shops` ENABLE KEYS */;

--
-- Table structure for table `user_profile`
--

DROP TABLE IF EXISTS `user_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profile` (
  `username` varchar(50) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'public/default-avatar.png',
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profile`
--

/*!40000 ALTER TABLE `user_profile` DISABLE KEYS */;
INSERT INTO `user_profile` VALUES ('tjm1','姜米汤','','','uploads/avatar_tjm1_1776788593.jpg','2026-04-23 12:55:26'),('tjm',NULL,NULL,NULL,'public/face.png','2026-04-22 10:40:56'),('啊',NULL,NULL,NULL,'public/face.png','2026-04-22 10:45:32'),('111','222','','','uploads/avatar_111_1776925590.jpg','2026-04-23 14:26:30');
/*!40000 ALTER TABLE `user_profile` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-26 22:01:49

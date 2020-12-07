CREATE DATABASE  IF NOT EXISTS `471Project` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `471Project`;
-- MySQL dump 10.13  Distrib 8.0.19, for macos10.15 (x86_64)
--
-- Host: localhost    Database: 471Project
-- ------------------------------------------------------
-- Server version	8.0.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ADMINISTRATOR`
--

DROP TABLE IF EXISTS `ADMINISTRATOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ADMINISTRATOR` (
  `Admin_id` int NOT NULL,
  `First_name` varchar(45) NOT NULL,
  `Last_name` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  PRIMARY KEY (`Admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADMINISTRATOR`
--

LOCK TABLES `ADMINISTRATOR` WRITE;
/*!40000 ALTER TABLE `ADMINISTRATOR` DISABLE KEYS */;
INSERT INTO `ADMINISTRATOR` VALUES (1,'Jennifer','Burke','jburke'),(2,'Matthew','Hudson','mhudson');
/*!40000 ALTER TABLE `ADMINISTRATOR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BOARDS_AT`
--

DROP TABLE IF EXISTS `BOARDS_AT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BOARDS_AT` (
  `Employee_id` int NOT NULL,
  `Address` varchar(45) NOT NULL,
  `Boarding_time` varchar(45) NOT NULL,
  PRIMARY KEY (`Employee_id`,`Address`,`Boarding_time`),
  KEY `Address_BOARDS_AT_idx` (`Address`),
  CONSTRAINT `Address_BOARDS_AT` FOREIGN KEY (`Address`) REFERENCES `LOCATION` (`Address`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Employee_id_BOARDS_AT` FOREIGN KEY (`Employee_id`) REFERENCES `PASSENGER` (`Employee_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BOARDS_AT`
--

LOCK TABLES `BOARDS_AT` WRITE;
/*!40000 ALTER TABLE `BOARDS_AT` DISABLE KEYS */;
INSERT INTO `BOARDS_AT` VALUES (1,'123 Apple Road','08:10:00'),(2,'123 Apple Road','08:10:05'),(3,'123 Apple Road','08:10:10'),(4,'123 Apple Road','08:10:15'),(5,'123 Apple Road','08:10:20'),(6,'123 Apple Road','08:10:25'),(7,'17 Pear Lane','08:20:00'),(8,'17 Pear Lane','08:20:05'),(9,'17 Pear Lane','08:20:10'),(10,'17 Pear Lane','08:20:15'),(11,'17 Pear Lane','08:20:20'),(12,'17 Pear Lane','08:20:25'),(13,'240 Cherry Way','08:30:00'),(14,'240 Cherry Way','08:30:05'),(15,'240 Cherry Way','08:30:10'),(16,'240 Cherry Way','08:30:15'),(17,'240 Cherry Way','08:30:20'),(18,'240 Cherry Way','08:30:25');
/*!40000 ALTER TABLE `BOARDS_AT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUS`
--

DROP TABLE IF EXISTS `BUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BUS` (
  `Vehicle_id` int NOT NULL,
  `Model_no` int NOT NULL,
  PRIMARY KEY (`Vehicle_id`),
  KEY `Model_no_idx` (`Model_no`),
  CONSTRAINT `Model_no` FOREIGN KEY (`Model_no`) REFERENCES `BUS_TYPE` (`Model_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS`
--

LOCK TABLES `BUS` WRITE;
/*!40000 ALTER TABLE `BUS` DISABLE KEYS */;
INSERT INTO `BUS` VALUES (100,1000),(200,1000),(300,2000),(400,2000);
/*!40000 ALTER TABLE `BUS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUS_DRIVER`
--

DROP TABLE IF EXISTS `BUS_DRIVER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BUS_DRIVER` (
  `Driver_id` int NOT NULL,
  `First_name` varchar(45) NOT NULL,
  `Last_name` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  PRIMARY KEY (`Driver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS_DRIVER`
--

LOCK TABLES `BUS_DRIVER` WRITE;
/*!40000 ALTER TABLE `BUS_DRIVER` DISABLE KEYS */;
INSERT INTO `BUS_DRIVER` VALUES (1,'James','Smith','jsmith'),(2,'Emma','Harris','eharris'),(3,'Richard','Brown','rbrown');
/*!40000 ALTER TABLE `BUS_DRIVER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BUS_TYPE`
--

DROP TABLE IF EXISTS `BUS_TYPE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `BUS_TYPE` (
  `Model_no` int NOT NULL,
  `No_of_rows` int NOT NULL,
  `No_of_cols` int NOT NULL,
  PRIMARY KEY (`Model_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BUS_TYPE`
--

LOCK TABLES `BUS_TYPE` WRITE;
/*!40000 ALTER TABLE `BUS_TYPE` DISABLE KEYS */;
INSERT INTO `BUS_TYPE` VALUES (1000,6,4),(2000,8,4);
/*!40000 ALTER TABLE `BUS_TYPE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DISEMBARKS_AT`
--

DROP TABLE IF EXISTS `DISEMBARKS_AT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DISEMBARKS_AT` (
  `Employee_id` int NOT NULL,
  `Address` varchar(45) NOT NULL,
  `Disembark_time` varchar(45) NOT NULL,
  PRIMARY KEY (`Employee_id`,`Address`,`Disembark_time`),
  KEY `Address_DISEMBARKS_AT_idx` (`Address`),
  CONSTRAINT `Address_DISEMBARKS_AT` FOREIGN KEY (`Address`) REFERENCES `LOCATION` (`Address`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Employee_id_DISEMBARKS_AT` FOREIGN KEY (`Employee_id`) REFERENCES `PASSENGER` (`Employee_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DISEMBARKS_AT`
--

LOCK TABLES `DISEMBARKS_AT` WRITE;
/*!40000 ALTER TABLE `DISEMBARKS_AT` DISABLE KEYS */;
INSERT INTO `DISEMBARKS_AT` VALUES (1,'123 Apple Road','17:30:25'),(2,'123 Apple Road','17:30:20'),(3,'123 Apple Road','17:30:15'),(4,'123 Apple Road','17:30:10'),(5,'123 Apple Road','17:30:05'),(6,'123 Apple Road','17:30:00'),(7,'17 Pear Lane','17:20:25'),(8,'17 Pear Lane','17:20:20'),(9,'17 Pear Lane','17:20:15'),(10,'17 Pear Lane','17:20:10'),(11,'17 Pear Lane','17:20:05'),(12,'17 Pear Lane','17:20:00'),(13,'240 Cherry Way','17:10:25'),(14,'240 Cherry Way','17:10:20'),(15,'240 Cherry Way','17:10:15'),(16,'240 Cherry Way','17:10:10'),(17,'240 Cherry Way','17:10:05'),(18,'240 Cherry Way','17:10:00');
/*!40000 ALTER TABLE `DISEMBARKS_AT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `IN_PROXIMITY`
--

DROP TABLE IF EXISTS `IN_PROXIMITY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `IN_PROXIMITY` (
  `Route_no_1` int NOT NULL,
  `Date_1` varchar(45) NOT NULL,
  `Start_time_1` varchar(45) NOT NULL,
  `Row_1` int NOT NULL,
  `Column_1` int NOT NULL,
  `Route_no_2` int NOT NULL,
  `Date_2` varchar(45) NOT NULL,
  `Start_time_2` varchar(45) NOT NULL,
  `Row_2` int NOT NULL,
  `Column_2` int NOT NULL,
  PRIMARY KEY (`Route_no_1`,`Date_1`,`Start_time_1`,`Row_1`,`Column_1`,`Route_no_2`,`Date_2`,`Start_time_2`,`Row_2`,`Column_2`),
  KEY `Seat_2_IN_PROXIMITY_idx` (`Route_no_2`,`Date_2`,`Start_time_2`,`Row_2`,`Column_2`),
  CONSTRAINT `Seat_1_IN_PROXIMITY` FOREIGN KEY (`Route_no_1`, `Date_1`, `Start_time_1`, `Row_1`, `Column_1`) REFERENCES `SEAT` (`Route_no`, `Date`, `Start_time`, `Row`, `Column`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Seat_2_IN_PROXIMITY` FOREIGN KEY (`Route_no_2`, `Date_2`, `Start_time_2`, `Row_2`, `Column_2`) REFERENCES `SEAT` (`Route_no`, `Date`, `Start_time`, `Row`, `Column`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `IN_PROXIMITY`
--

LOCK TABLES `IN_PROXIMITY` WRITE;
/*!40000 ALTER TABLE `IN_PROXIMITY` DISABLE KEYS */;
INSERT INTO `IN_PROXIMITY` VALUES (1,'2020/12/08','08:00:00',1,1,1,'2020/12/08','08:00:00',1,2),(1,'2020/12/08','08:00:00',1,3,1,'2020/12/08','08:00:00',1,4),(1,'2020/12/08','08:00:00',1,1,1,'2020/12/08','08:00:00',2,1),(1,'2020/12/08','08:00:00',1,2,1,'2020/12/08','08:00:00',2,1),(1,'2020/12/08','08:00:00',1,1,1,'2020/12/08','08:00:00',2,2),(1,'2020/12/08','08:00:00',1,2,1,'2020/12/08','08:00:00',2,2),(1,'2020/12/08','08:00:00',2,1,1,'2020/12/08','08:00:00',2,2),(1,'2020/12/08','08:00:00',1,3,1,'2020/12/08','08:00:00',2,3),(1,'2020/12/08','08:00:00',1,4,1,'2020/12/08','08:00:00',2,3),(1,'2020/12/08','08:00:00',1,3,1,'2020/12/08','08:00:00',2,4),(1,'2020/12/08','08:00:00',1,4,1,'2020/12/08','08:00:00',2,4),(1,'2020/12/08','08:00:00',2,3,1,'2020/12/08','08:00:00',2,4),(1,'2020/12/08','08:00:00',2,1,1,'2020/12/08','08:00:00',3,1),(1,'2020/12/08','08:00:00',2,2,1,'2020/12/08','08:00:00',3,1),(1,'2020/12/08','08:00:00',2,1,1,'2020/12/08','08:00:00',3,2),(1,'2020/12/08','08:00:00',2,2,1,'2020/12/08','08:00:00',3,2),(1,'2020/12/08','08:00:00',3,1,1,'2020/12/08','08:00:00',3,2),(1,'2020/12/08','08:00:00',2,3,1,'2020/12/08','08:00:00',3,3),(1,'2020/12/08','08:00:00',2,4,1,'2020/12/08','08:00:00',3,3),(1,'2020/12/08','08:00:00',2,3,1,'2020/12/08','08:00:00',3,4),(1,'2020/12/08','08:00:00',2,4,1,'2020/12/08','08:00:00',3,4),(1,'2020/12/08','08:00:00',3,3,1,'2020/12/08','08:00:00',3,4),(1,'2020/12/08','08:00:00',3,1,1,'2020/12/08','08:00:00',4,1),(1,'2020/12/08','08:00:00',3,2,1,'2020/12/08','08:00:00',4,1),(1,'2020/12/08','08:00:00',3,1,1,'2020/12/08','08:00:00',4,2),(1,'2020/12/08','08:00:00',3,2,1,'2020/12/08','08:00:00',4,2),(1,'2020/12/08','08:00:00',4,1,1,'2020/12/08','08:00:00',4,2),(1,'2020/12/08','08:00:00',3,3,1,'2020/12/08','08:00:00',4,3),(1,'2020/12/08','08:00:00',3,4,1,'2020/12/08','08:00:00',4,3),(1,'2020/12/08','08:00:00',3,3,1,'2020/12/08','08:00:00',4,4),(1,'2020/12/08','08:00:00',3,4,1,'2020/12/08','08:00:00',4,4),(1,'2020/12/08','08:00:00',4,3,1,'2020/12/08','08:00:00',4,4),(1,'2020/12/08','08:00:00',4,1,1,'2020/12/08','08:00:00',5,1),(1,'2020/12/08','08:00:00',4,2,1,'2020/12/08','08:00:00',5,1),(1,'2020/12/08','08:00:00',4,1,1,'2020/12/08','08:00:00',5,2),(1,'2020/12/08','08:00:00',4,2,1,'2020/12/08','08:00:00',5,2),(1,'2020/12/08','08:00:00',5,1,1,'2020/12/08','08:00:00',5,2),(1,'2020/12/08','08:00:00',4,3,1,'2020/12/08','08:00:00',5,3),(1,'2020/12/08','08:00:00',4,4,1,'2020/12/08','08:00:00',5,3),(1,'2020/12/08','08:00:00',4,3,1,'2020/12/08','08:00:00',5,4),(1,'2020/12/08','08:00:00',4,4,1,'2020/12/08','08:00:00',5,4),(1,'2020/12/08','08:00:00',5,3,1,'2020/12/08','08:00:00',5,4),(1,'2020/12/08','08:00:00',5,1,1,'2020/12/08','08:00:00',6,1),(1,'2020/12/08','08:00:00',5,2,1,'2020/12/08','08:00:00',6,1),(1,'2020/12/08','08:00:00',5,1,1,'2020/12/08','08:00:00',6,2),(1,'2020/12/08','08:00:00',5,2,1,'2020/12/08','08:00:00',6,2),(1,'2020/12/08','08:00:00',6,1,1,'2020/12/08','08:00:00',6,2),(1,'2020/12/08','08:00:00',5,3,1,'2020/12/08','08:00:00',6,3),(1,'2020/12/08','08:00:00',5,4,1,'2020/12/08','08:00:00',6,3),(1,'2020/12/08','08:00:00',5,3,1,'2020/12/08','08:00:00',6,4),(1,'2020/12/08','08:00:00',5,4,1,'2020/12/08','08:00:00',6,4),(1,'2020/12/08','08:00:00',6,3,1,'2020/12/08','08:00:00',6,4);
/*!40000 ALTER TABLE `IN_PROXIMITY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LOCATION`
--

DROP TABLE IF EXISTS `LOCATION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `LOCATION` (
  `Address` varchar(45) NOT NULL,
  PRIMARY KEY (`Address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LOCATION`
--

LOCK TABLES `LOCATION` WRITE;
/*!40000 ALTER TABLE `LOCATION` DISABLE KEYS */;
INSERT INTO `LOCATION` VALUES ('123 Apple Road'),('17 Pear Lane'),('240 Cherry Way'),('369 Orange Street'),('45 Kiwi Avenue'),('62 Berry Alley');
/*!40000 ALTER TABLE `LOCATION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PASSENGER`
--

DROP TABLE IF EXISTS `PASSENGER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PASSENGER` (
  `Employee_id` int NOT NULL,
  `First_name` varchar(45) NOT NULL,
  `Last_name` varchar(45) NOT NULL,
  `Department` varchar(45) NOT NULL,
  `Admin_id` int NOT NULL,
  PRIMARY KEY (`Employee_id`),
  KEY `Admin_id_PASSENGER_idx` (`Admin_id`),
  CONSTRAINT `Admin_id_PASSENGER` FOREIGN KEY (`Admin_id`) REFERENCES `ADMINISTRATOR` (`Admin_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PASSENGER`
--

LOCK TABLES `PASSENGER` WRITE;
/*!40000 ALTER TABLE `PASSENGER` DISABLE KEYS */;
INSERT INTO `PASSENGER` VALUES (1,'Adam','Chan','Marketing',1),(2,'Rebecca','Moore','Marketing',1),(3,'Katie','Fox','Accounting',1),(4,'Harry','Lewis','Marketing',1),(5,'Chris','Ford','Accounting',1),(6,'Charles','Scott','Accounting',1),(7,'Linda','White','Human Relations',1),(8,'Max','Hill','Human Relations',1),(9,'Jemma','Shepard','Engineering',2),(10,'Mitchel','Davis','Engineering',2),(11,'Clint','Slater','Engineering',2),(12,'Ruth','Bauer','Engineering',2),(13,'Sienna','Cassidy','Engineering',2),(14,'Stan','Hastings','Engineering',2),(15,'Cody','Burton','Engineering',2),(16,'Lucia','Kane','Engineering',2),(17,'Tyler','Curtis','Engineering',2),(18,'Anna','Piper','Engineering',2);
/*!40000 ALTER TABLE `PASSENGER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PASSENGER_SEAT`
--

DROP TABLE IF EXISTS `PASSENGER_SEAT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PASSENGER_SEAT` (
  `Route_no` int NOT NULL,
  `Date` varchar(45) NOT NULL,
  `Start_time` varchar(45) NOT NULL,
  `Seat_row` int NOT NULL,
  `Seat_col` int NOT NULL,
  `Employee_id` int NOT NULL,
  PRIMARY KEY (`Route_no`,`Date`,`Start_time`,`Seat_row`,`Seat_col`,`Employee_id`),
  KEY `Employee_id_PASSENGER_SEAT_idx` (`Employee_id`),
  CONSTRAINT `Employee_id_PASSENGER_SEAT` FOREIGN KEY (`Employee_id`) REFERENCES `PASSENGER` (`Employee_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Seat_PASSENGER_SEAT` FOREIGN KEY (`Route_no`, `Date`, `Start_time`, `Seat_row`, `Seat_col`) REFERENCES `SEAT` (`Route_no`, `Date`, `Start_time`, `Row`, `Column`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PASSENGER_SEAT`
--

LOCK TABLES `PASSENGER_SEAT` WRITE;
/*!40000 ALTER TABLE `PASSENGER_SEAT` DISABLE KEYS */;
INSERT INTO `PASSENGER_SEAT` VALUES (1,'2020/12/08','08:00:00',1,1,1),(1,'2020/12/08','08:00:00',1,3,2),(1,'2020/12/08','08:00:00',2,1,3),(1,'2020/12/08','08:00:00',2,3,4),(1,'2020/12/08','08:00:00',3,1,5),(1,'2020/12/08','08:00:00',3,3,6),(1,'2020/12/08','08:00:00',4,1,7),(1,'2020/12/08','08:00:00',4,3,8),(1,'2020/12/08','08:00:00',5,1,9),(1,'2020/12/08','08:00:00',5,3,10),(1,'2020/12/08','08:00:00',6,1,11),(1,'2020/12/08','08:00:00',6,3,12),(1,'2020/12/08','08:00:00',1,2,13),(1,'2020/12/08','08:00:00',1,4,14),(1,'2020/12/08','08:00:00',2,2,15),(1,'2020/12/08','08:00:00',2,4,16),(1,'2020/12/08','08:00:00',3,2,17),(1,'2020/12/08','08:00:00',3,4,18);
/*!40000 ALTER TABLE `PASSENGER_SEAT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ROUTE`
--

DROP TABLE IF EXISTS `ROUTE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ROUTE` (
  `Route_no` int NOT NULL,
  PRIMARY KEY (`Route_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ROUTE`
--

LOCK TABLES `ROUTE` WRITE;
/*!40000 ALTER TABLE `ROUTE` DISABLE KEYS */;
INSERT INTO `ROUTE` VALUES (1),(2);
/*!40000 ALTER TABLE `ROUTE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ROUTE_INSTANCE`
--

DROP TABLE IF EXISTS `ROUTE_INSTANCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ROUTE_INSTANCE` (
  `Route_no` int NOT NULL,
  `Date` varchar(45) NOT NULL,
  `Start_time` varchar(45) NOT NULL,
  `Driver_id` int NOT NULL,
  `Vehicle_id` int NOT NULL,
  PRIMARY KEY (`Route_no`,`Date`,`Start_time`),
  KEY `Driver_id_ROUTE_INSTANCE_idx` (`Driver_id`),
  KEY `Vehicle_id_ROUTE_INSTANCE_idx` (`Vehicle_id`),
  CONSTRAINT `Driver_id_ROUTE_INSTANCE` FOREIGN KEY (`Driver_id`) REFERENCES `BUS_DRIVER` (`Driver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Route_no` FOREIGN KEY (`Route_no`) REFERENCES `ROUTE` (`Route_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Vehicle_id_ROUTE_INSTANCE` FOREIGN KEY (`Vehicle_id`) REFERENCES `BUS` (`Vehicle_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ROUTE_INSTANCE`
--

LOCK TABLES `ROUTE_INSTANCE` WRITE;
/*!40000 ALTER TABLE `ROUTE_INSTANCE` DISABLE KEYS */;
INSERT INTO `ROUTE_INSTANCE` VALUES (1,'2020/12/08','08:00:00',1,100);
/*!40000 ALTER TABLE `ROUTE_INSTANCE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SEAT`
--

DROP TABLE IF EXISTS `SEAT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SEAT` (
  `Route_no` int NOT NULL,
  `Date` varchar(45) NOT NULL,
  `Start_time` varchar(45) NOT NULL,
  `Row` int NOT NULL,
  `Column` int NOT NULL,
  PRIMARY KEY (`Route_no`,`Date`,`Row`,`Start_time`,`Column`),
  KEY `Route_instance_SEAT_idx` (`Route_no`,`Date`,`Start_time`),
  CONSTRAINT `Route_instance_SEAT` FOREIGN KEY (`Route_no`, `Date`, `Start_time`) REFERENCES `ROUTE_INSTANCE` (`Route_no`, `Date`, `Start_time`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SEAT`
--

LOCK TABLES `SEAT` WRITE;
/*!40000 ALTER TABLE `SEAT` DISABLE KEYS */;
INSERT INTO `SEAT` VALUES (1,'2020/12/08','08:00:00',1,1),(1,'2020/12/08','08:00:00',1,2),(1,'2020/12/08','08:00:00',1,3),(1,'2020/12/08','08:00:00',1,4),(1,'2020/12/08','08:00:00',2,1),(1,'2020/12/08','08:00:00',2,2),(1,'2020/12/08','08:00:00',2,3),(1,'2020/12/08','08:00:00',2,4),(1,'2020/12/08','08:00:00',3,1),(1,'2020/12/08','08:00:00',3,2),(1,'2020/12/08','08:00:00',3,3),(1,'2020/12/08','08:00:00',3,4),(1,'2020/12/08','08:00:00',4,1),(1,'2020/12/08','08:00:00',4,2),(1,'2020/12/08','08:00:00',4,3),(1,'2020/12/08','08:00:00',4,4),(1,'2020/12/08','08:00:00',5,1),(1,'2020/12/08','08:00:00',5,2),(1,'2020/12/08','08:00:00',5,3),(1,'2020/12/08','08:00:00',5,4),(1,'2020/12/08','08:00:00',6,1),(1,'2020/12/08','08:00:00',6,2),(1,'2020/12/08','08:00:00',6,3),(1,'2020/12/08','08:00:00',6,4);
/*!40000 ALTER TABLE `SEAT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `STOPS_AT`
--

DROP TABLE IF EXISTS `STOPS_AT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `STOPS_AT` (
  `Address` varchar(45) NOT NULL,
  `Route_no` int NOT NULL,
  PRIMARY KEY (`Address`,`Route_no`),
  KEY `Route_no_STOPS_AT_idx` (`Route_no`),
  CONSTRAINT `Address_STOPS_AT` FOREIGN KEY (`Address`) REFERENCES `LOCATION` (`Address`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Route_no_STOPS_AT` FOREIGN KEY (`Route_no`) REFERENCES `ROUTE` (`Route_no`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `STOPS_AT`
--

LOCK TABLES `STOPS_AT` WRITE;
/*!40000 ALTER TABLE `STOPS_AT` DISABLE KEYS */;
INSERT INTO `STOPS_AT` VALUES ('123 Apple Road',1),('17 Pear Lane',1),('240 Cherry Way',1),('369 Orange Street',2),('45 Kiwi Avenue',2),('62 Berry Alley',2);
/*!40000 ALTER TABLE `STOPS_AT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database '471Project'
--

--
-- Dumping routines for database '471Project'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-06 19:10:15

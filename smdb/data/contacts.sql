-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 27, 2012 at 06:57 AM
-- Server version: 5.1.50
-- PHP Version: 5.3.5



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `contacts`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE contacts (
  id SERIAL NOT NULL PRIMARY KEY,
  name varchar(128) NOT NULL,
  phone varchar(64) DEFAULT NULL,
  email varchar(255) DEFAULT NULL,
  address varchar(255) DEFAULT NULL);

--
-- Dumping data for table `contacts`
--

INSERT INTO contacts (id, name, phone, email, address) VALUES
(1, 'Masud', '01722817654', 'masud.eden@gmail.com', 'Dhaka, Bangladesh'),
(2, 'Fuad', '0172345687', 'fuad@gmail.com', 'BNP, Bangladesh'),
(3, 'Zarif', '0123456789', 'zarif@gmail.com', 'Khulna, Bangladesh'),
(4, 'Abir', '018765432', 'abir@gmail.com', 'Dhaka,Bangladesh'),
(5, 'Tanvir', '01577876543', 'tanvir@bibsun.com', 'Dhaka, Bangladesh'),
(7, 'MAK JOY', '0111111111111', 'mak@joy.com', 'Dhaka, Bangladesh');

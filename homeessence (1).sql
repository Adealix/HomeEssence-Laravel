-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2025 at 01:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `homeessence`
--

-- --------------------------------------------------------

--
-- Table structure for table `barcode`
--

CREATE TABLE `barcode` (
  `barcode_ean` char(13) NOT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `title` char(4) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) NOT NULL,
  `addressline` text DEFAULT NULL,
  `town` varchar(32) DEFAULT NULL,
  `zipcode` char(10) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `title`, `fname`, `lname`, `addressline`, `town`, `zipcode`, `phone`, `profile_picture`, `user_id`) VALUES
(1, NULL, 'Admin', 'Account', 'kahit saan basta admin to', 'Admin Town', '1623', '092347637645', 'rrpaL32buTaJESDY0Z9j0ykq9DkxPEcfA5dNZ9b8.png', 1),
(2, 'Ms.', 'Admin 2nd', 'Account', 'sdjifhgisuyodfg', 'suidhfiusd', '2347', '2389047983', 'Tkh4zzsw5pjG10ptb2FXGQJE5TY19lROts2j437v.jpg', 2),
(3, 'Mr.', 'Try', 'Account', 'sadhufiusygdf', 'asddfg', '2378', '2338427', 'QzmdVaeNYp88gvMGKbBjIWo8h4Y03VrPInRUkBsK.jpg', 3),
(4, 'Mrs.', 'Try', 'Account 2', 'sodifhjiusdhf', 'oiwejouihsd iohdf', '1276', '23847929834', '4faPmdy37di2uM69IuyMCTw033H5dSyqAFU08BEF.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(200) NOT NULL,
  `category` varchar(100) NOT NULL,
  `cost_price` decimal(7,2) DEFAULT NULL,
  `sell_price` decimal(7,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `name`, `description`, `category`, `cost_price`, `sell_price`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Unang Gamit ulit edit naman restock', 'ewan, sana gumana kase kung inde iiyaq aq', 'kahit ano na muna to', 15.00, 25.00, '2025-03-14 19:36:51', '2025-03-15 17:28:40', NULL),
(5, 'Second Item', 'kahit ano', 'malinis?', 100.00, 250.00, '2025-03-15 16:13:51', '2025-03-15 16:13:51', NULL),
(6, 'Pangatlo na multiple images', 'gagana ba multiple?', 'sanagumana', 8.00, 15.00, '2025-03-15 16:16:32', '2025-03-15 16:16:32', NULL),
(7, 'Try Nga Create new Item', 'fhsdgbshdjkfg*&!#$^*@#&$(*@#$asd', 'Fashion', 15.00, 12.00, '2025-03-16 03:39:36', '2025-03-16 03:47:04', NULL),
(8, 'Hehe Xd', 'asdasdwqer', 'Home', 123.00, 234.00, '2025-03-16 03:46:26', '2025-03-16 03:46:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_02_12_032638_create_add_image_to_item_table', 2),
(5, '2014_10_12_000000_create_users_table', 3),
(6, '2014_10_12_100000_create_password_reset_tokens_table', 3),
(7, '2014_10_12_100000_create_password_resets_table', 3),
(8, '2019_08_19_000000_create_failed_jobs_table', 3),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 3),
(10, '2025_02_12_031743_add_image_to_item', 3),
(11, '2025_02_19_033251_add_user_id_to_customer', 3),
(12, '2025_02_20_004427_add_status_to_orderinfo_table', 3),
(13, '2025_02_26_023730_add_role_to_users', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orderinfo`
--

CREATE TABLE `orderinfo` (
  `orderinfo_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_placed` date NOT NULL,
  `date_shipped` date DEFAULT NULL,
  `shipping` decimal(7,2) DEFAULT NULL,
  `status` enum('pending','delivered','canceled') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderinfo`
--

INSERT INTO `orderinfo` (`orderinfo_id`, `customer_id`, `date_placed`, `date_shipped`, `shipping`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 16:14:11', '2025-03-15 16:14:11'),
(2, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 16:37:03', '2025-03-15 16:37:03'),
(3, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 16:37:48', '2025-03-15 16:37:48'),
(4, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 16:50:48', '2025-03-15 16:50:48'),
(5, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 16:54:18', '2025-03-15 16:54:18'),
(6, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 17:00:00', '2025-03-15 17:00:00'),
(7, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 17:06:26', '2025-03-15 17:06:26'),
(8, 4, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 17:21:17', '2025-03-15 17:21:17'),
(9, 4, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 17:23:56', '2025-03-15 17:23:56'),
(10, 4, '2025-03-16', NULL, 10.00, 'pending', '2025-03-15 17:25:13', '2025-03-15 19:04:35'),
(11, 1, '2025-03-16', '2025-03-16', 10.00, 'delivered', '2025-03-15 17:28:53', '2025-03-15 22:11:20'),
(12, 1, '2025-03-16', '2025-03-16', 10.00, 'canceled', '2025-03-15 17:29:48', '2025-03-15 18:30:51'),
(13, 1, '2025-03-16', '2025-03-16', 10.00, 'delivered', '2025-03-15 17:46:53', '2025-03-15 20:46:06'),
(14, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:44:53', '2025-03-15 22:44:53'),
(15, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:49:37', '2025-03-15 22:49:37'),
(16, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:50:28', '2025-03-15 22:50:28'),
(17, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:56:43', '2025-03-15 22:56:43'),
(18, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:57:41', '2025-03-15 22:57:41'),
(19, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 22:59:52', '2025-03-15 22:59:52'),
(20, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:13:21', '2025-03-15 23:13:21'),
(21, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:18:10', '2025-03-15 23:18:10'),
(22, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:21:15', '2025-03-15 23:21:15'),
(23, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:23:11', '2025-03-15 23:23:11'),
(24, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:27:46', '2025-03-15 23:27:46'),
(25, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-15 23:30:17', '2025-03-15 23:30:17'),
(26, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-16 00:06:32', '2025-03-16 00:06:32'),
(27, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-16 00:09:06', '2025-03-16 00:09:06'),
(28, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-16 00:27:37', '2025-03-16 00:27:37'),
(29, 1, '2025-03-16', '2025-03-21', 10.00, 'pending', '2025-03-16 00:33:04', '2025-03-16 00:33:04'),
(30, 1, '2025-03-16', '2025-03-16', 10.00, 'delivered', '2025-03-16 00:38:32', '2025-03-16 00:49:48'),
(31, 4, '2025-03-16', '2025-03-16', 10.00, 'delivered', '2025-03-16 03:08:05', '2025-03-16 03:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

CREATE TABLE `orderline` (
  `item_id` int(11) NOT NULL,
  `orderinfo_id` int(11) NOT NULL,
  `quantity` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderline`
--

INSERT INTO `orderline` (`item_id`, `orderinfo_id`, `quantity`) VALUES
(4, 1, 1),
(4, 2, 1),
(5, 2, 2),
(4, 3, 1),
(5, 3, 2),
(6, 3, 3),
(6, 4, 1),
(4, 5, 2),
(6, 6, 3),
(4, 7, 1),
(4, 8, 1),
(5, 8, 2),
(6, 9, 1),
(4, 10, 3),
(5, 10, 1),
(6, 10, 2),
(4, 11, 1),
(4, 12, 2),
(4, 13, 1),
(6, 13, 2),
(5, 14, 1),
(5, 15, 1),
(5, 16, 1),
(5, 17, 1),
(5, 18, 1),
(5, 19, 1),
(5, 20, 2),
(5, 21, 1),
(5, 22, 1),
(5, 23, 1),
(5, 24, 1),
(5, 25, 1),
(5, 26, 1),
(5, 27, 1),
(5, 28, 1),
(5, 29, 1),
(5, 30, 1),
(4, 31, 2),
(5, 31, 2),
(6, 31, 3);

-- --------------------------------------------------------

--
-- Stand-in structure for view `orders_view`
-- (See below for the actual view)
--
CREATE TABLE `orders_view` (
`orderinfo_id` int(11)
,`fname` varchar(32)
,`lname` varchar(32)
,`addressline` text
,`date_placed` date
,`status` enum('pending','delivered','canceled')
,`total` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('tryacc@gmail.com', '$2y$10$DiPDjteKsBo4d4.4SgcnOeEgUqBRMUuxXdDDxNeYRtmbP68leYZIG', '2025-03-04 19:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `item_id`, `image_path`, `created_at`, `updated_at`) VALUES
(15, 4, 'public/images/F1lC61mX5xkQjZvIwzhit6lAj3yg7VGSkpmAzvF8.png', '2025-03-14 19:37:33', '2025-03-14 19:37:33'),
(16, 5, 'public/images/5Hq58yU4gpIlfKSCMVvpq9ZWq8h7xN6MoJYehL3z.jpg', '2025-03-15 16:13:51', '2025-03-15 16:13:51'),
(17, 6, 'public/images/7ZsFwhCZNKb2QtipPTip9piiRi40gLF3Qa1dB6qb.png', '2025-03-15 16:16:32', '2025-03-15 16:16:32'),
(18, 6, 'public/images/w7vzltfDlJ9L0DemObfUW3roLte3ByADDJY06Wg0.jpg', '2025-03-15 16:16:32', '2025-03-15 16:16:32'),
(19, 6, 'public/images/3o7BqRWsPAzXSi6To5JDa46WwDZyB3rwBHJoL9V8.jpg', '2025-03-15 16:16:32', '2025-03-15 16:16:32'),
(20, 7, 'public/images/7CNDuQZSIBL3bSYRV9MkkxUUZfmMlE8sEoclMK8S.jpg', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(21, 7, 'public/images/U2UxakYWD8IcjZiCdDQvNXmesVQGNLL8uuL1TXaq.jpg', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(22, 7, 'public/images/wUPZmhekFMvSexr91Mar8MlxOaBBFNUdyFJMzRDj.jpg', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(23, 7, 'public/images/QohxoR8kht7lFbGKiHCxki91fioq5UzWOAKjZSAp.jpg', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(24, 7, 'public/images/hUnGGSiL4D9m5SNPYi452BV1Z3sde8ND3C0MZgXc.jpg', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(25, 7, 'public/images/MyrXAguTwSLCVApcj1XA4k4fhyoAtLBGYMrStZiF.png', '2025-03-16 03:39:36', '2025-03-16 03:39:36'),
(26, 8, 'public/images/cZ2jSVwRzW6sxTGZYwiUjBqbBgPe9r5enj5EBIxJ.jpg', '2025-03-16 03:46:26', '2025-03-16 03:46:26'),
(27, 8, 'public/images/amx4FdhbKXAlIMlgfkrApiRhIHT59HcM3zst4IeK.jpg', '2025-03-16 03:46:26', '2025-03-16 03:46:26');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` int(11) NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `item_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 3, 'edit to nagana ba review? sana gumana punyeta', '2025-03-16 01:07:45', '2025-03-16 01:20:13'),
(2, 1, 5, 5, 'masarap to kasi sisig tas may hersheys pa na chocolate eh gagi talaga', '2025-03-16 01:21:38', '2025-03-16 01:21:38'),
(4, 4, 4, 4, 'bakit hindi gumana yung review q?!', '2025-03-16 03:11:34', '2025-03-16 03:11:34'),
(5, 4, 5, 2, 'hello, demure na po aq magcomment, please payagan niyo na review q hehe fuck edit stupid', '2025-03-16 03:17:31', '2025-03-16 03:18:47'),
(6, 4, 6, 5, '5 rating kasi perfect na tong review q no? well validated na siya hehe', '2025-03-16 03:19:14', '2025-03-16 03:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `item_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`item_id`, `quantity`) VALUES
(4, 13),
(5, 17),
(6, 10),
(7, 5),
(8, 45);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `status`) VALUES
(1, 'Admin Account', 'admin@gmail.com', '2025-03-15 16:12:02', '$2y$10$5yQ4sWHDMXt7/Y2LQKWl/e6V4zrsr7QAFY9ybg56SZ2oZ3y5Q3rEa', NULL, '2025-03-15 16:10:51', '2025-03-15 16:12:02', 'admin', 'active'),
(2, 'Admin 2nd Account', 'admin2@gmail.com', '2025-03-15 17:12:26', '$2y$10$Vsvo.sRbGWGqvu.xQ7N5KOWa8l3CJj575gKEVgoDnxm6xJhr/X58S', NULL, '2025-03-15 17:12:07', '2025-03-15 17:28:15', 'admin', 'active'),
(3, 'Try Account', 'tryacc@gmail.com', '2025-03-15 17:17:37', '$2y$10$B6sYnZhs/dFcCkxaBvUMLeOGnbpRQLVGYCh6TcvB23OpDWTdkCu.u', NULL, '2025-03-15 17:16:53', '2025-03-16 03:07:17', 'admin', 'inactive'),
(4, 'Try Account 2', 'tryacc2@gmail.com', '2025-03-15 17:20:34', '$2y$10$q/LENs0pt0VdgExVNQo0Ku8BMycvs0oWwVma60A9hgqa89AvCsZM2', NULL, '2025-03-15 17:20:26', '2025-03-15 17:20:34', 'user', 'active');

-- --------------------------------------------------------

--
-- Structure for view `orders_view`
--
DROP TABLE IF EXISTS `orders_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `orders_view`  AS SELECT `o`.`orderinfo_id` AS `orderinfo_id`, `c`.`fname` AS `fname`, `c`.`lname` AS `lname`, `c`.`addressline` AS `addressline`, `o`.`date_placed` AS `date_placed`, `o`.`status` AS `status`, sum(`ol`.`quantity` * `i`.`sell_price`) AS `total` FROM (((`customer` `c` join `orderinfo` `o` on(`o`.`customer_id` = `c`.`customer_id`)) join `orderline` `ol` on(`o`.`orderinfo_id` = `ol`.`orderinfo_id`)) join `item` `i` on(`ol`.`item_id` = `i`.`item_id`)) GROUP BY `o`.`orderinfo_id`, `c`.`fname`, `c`.`lname`, `c`.`addressline`, `o`.`date_placed`, `o`.`status` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barcode`
--
ALTER TABLE `barcode`
  ADD PRIMARY KEY (`barcode_ean`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customer_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD PRIMARY KEY (`orderinfo_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orderline`
--
ALTER TABLE `orderline`
  ADD PRIMARY KEY (`orderinfo_id`,`item_id`),
  ADD KEY `item_item_id_fk` (`item_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_item_id_foreign` (`item_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orderinfo`
--
ALTER TABLE `orderinfo`
  MODIFY `orderinfo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barcode`
--
ALTER TABLE `barcode`
  ADD CONSTRAINT `barcode_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderinfo`
--
ALTER TABLE `orderinfo`
  ADD CONSTRAINT `orderinfo_customer_id_fk` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderline`
--
ALTER TABLE `orderline`
  ADD CONSTRAINT `item_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderline_orderinfo_id_fk` FOREIGN KEY (`orderinfo_id`) REFERENCES `orderinfo` (`orderinfo_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

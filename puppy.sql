-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2021 at 07:57 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `puppy`
--

-- --------------------------------------------------------

--
-- Table structure for table `breeds`
--

CREATE TABLE `breeds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `breeds`
--

INSERT INTO `breeds` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Jerman Shephard ', 1, NULL, '2021-08-10 14:12:31'),
(2, 'Black Shephard', 1, '2021-07-10 06:05:59', '2021-07-10 06:05:59');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Blacks', 1, NULL, '2021-07-10 06:26:19'),
(2, 'Brown', 1, '2021-07-10 06:24:51', '2021-07-10 06:24:51'),
(3, 'Gray', 0, '2021-08-10 14:13:32', '2021-08-10 14:13:43');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `comment`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, 'xdzfasd', 3, '2021-08-14 03:51:17', '2021-08-14 03:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flaged_contents`
--

CREATE TABLE `flaged_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flag_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_taken` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolved` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flaged_contents`
--

INSERT INTO `flaged_contents` (`id`, `flag_id`, `post_id`, `user_id`, `description`, `action_taken`, `resolved`, `created_at`, `updated_at`, `action_description`) VALUES
(1, 1, 2, 3, 'test', NULL, 0, '2021-08-14 04:47:58', '2021-08-14 04:47:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `flags`
--

CREATE TABLE `flags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flags`
--

INSERT INTO `flags` (`id`, `name`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Inappropriate', 'The video contains inappropriate content', 1, NULL, NULL),
(2, 'Violence', 'The video contains violence', 1, NULL, NULL),
(3, 'Misleading', 'The video misleading', 1, NULL, NULL),
(4, 'Abusive', 'The video contains abusive content', 1, NULL, NULL),
(5, 'Scam', 'The video promotes scam', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_06_27_161420_add_phone_to_users', 2),
(6, '2021_06_27_161710_add_username_to_users', 3),
(7, '2021_06_27_162003_add_state_id_to_users', 4),
(8, '2021_06_27_162047_add_address_to_users', 5),
(9, '2021_06_27_190605_create_states_table', 6),
(10, '2021_06_27_192914_add_active_to_users', 7),
(11, '2021_06_27_195051_add_type_to_users', 8),
(12, '2021_07_10_091403_create_breeds_table', 9),
(13, '2021_07_10_091509_create_colors_table', 10),
(16, '2021_07_10_091812_create_posts_table', 11),
(17, '2021_07_10_113325_add_flag_id_to_posts', 12),
(18, '2021_07_10_113458_create_flags_table', 13),
(19, '2021_07_10_113925_remove_flag_id_to_posts', 14),
(20, '2021_07_10_114459_create_flaged_contents_table', 15),
(21, '2021_07_31_113015_create_post_likes_table', 16),
(22, '2021_07_31_113032_create_post_saves_table', 16),
(23, '2021_08_07_092124_add_profile_picture_to_users', 17),
(24, '2021_08_14_082333_create_comments_table', 18),
(25, '2021_08_14_094221_add_changes_to_flaged_contents', 19);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'app', '2621d86753ecc99f8a8f25a31c70377b23822fb3a2c9d267a80d2a7478a76237', '[\"*\"]', NULL, '2021-06-27 14:30:03', '2021-06-27 14:30:03'),
(2, 'App\\Models\\User', 2, 'app', '943ac787c2c360d09f2c970a21931e3f0d797b67c969830455cadab3b41cba1c', '[\"*\"]', NULL, '2021-06-27 14:32:59', '2021-06-27 14:32:59'),
(3, 'App\\Models\\User', 3, 'app', '7e6e7db4a7c47537d01be5a641fd7d12d7e550816de0a534083eb696901783c3', '[\"*\"]', NULL, '2021-07-10 03:56:35', '2021-07-10 03:56:35'),
(4, 'App\\Models\\User', 3, 'app', 'aa91274379c64ddc30339a42d87dbcb1e4b7d418c6ff0d81eed30320e6ab7cd0', '[\"*\"]', NULL, '2021-07-10 04:44:29', '2021-07-10 04:44:29'),
(5, 'App\\Models\\User', 3, 'app', '0979372fc48af3d37a62127be76fbd80344da550e42eab3504c06bc3555f67aa', '[\"*\"]', NULL, '2021-07-10 04:44:54', '2021-07-10 04:44:54'),
(6, 'App\\Models\\User', 3, 'app', 'fa3bfcef9af8c2da20981d0b288738c14188d90e932dec709f936532d2cd429f', '[\"*\"]', '2021-07-10 05:02:13', '2021-07-10 04:45:06', '2021-07-10 05:02:13'),
(7, 'App\\Models\\User', 3, 'app', '498f75c9a6168c98c5b16b7487f665181743ef912b7f1925102dbad4e736c915', '[\"*\"]', NULL, '2021-07-16 13:52:53', '2021-07-16 13:52:53'),
(8, 'App\\Models\\User', 3, 'app', '304b26a086f720a6fc9bcdf064c663a8e08d47691eb1d27e6a84d1d623e9be30', '[\"*\"]', '2021-07-31 08:03:49', '2021-07-31 06:44:39', '2021-07-31 08:03:49'),
(9, 'App\\Models\\User', 4, 'app', '74d051269f29aa6fca2291b5c8f4038fce48a7384ab50c2849a3f0986c7a96c9', '[\"*\"]', NULL, '2021-08-07 04:31:12', '2021-08-07 04:31:12'),
(10, 'App\\Models\\User', 5, 'app', '45a2af71181ff68855dbd0b0762797c3221834ff42905ac71b58eb8a91b4acf5', '[\"*\"]', NULL, '2021-08-07 04:32:23', '2021-08-07 04:32:23'),
(11, 'App\\Models\\User', 6, 'app', 'c3de2601a04de80e6ab5bfb765fa65f84e42fd7148a9c22f28f750bf3e50e7f5', '[\"*\"]', NULL, '2021-08-07 04:33:43', '2021-08-07 04:33:43'),
(12, 'App\\Models\\User', 8, 'app', '3f4f868d8827a0fc19fc1f59d0bf413030b2ece790fb52b00e535b959f1051f3', '[\"*\"]', NULL, '2021-08-07 04:36:22', '2021-08-07 04:36:22'),
(13, 'App\\Models\\User', 9, 'app', 'b2586b7fc3ba7b43d4781043df590990fc6653d8c6766f2efe42d846ca162997', '[\"*\"]', NULL, '2021-08-07 04:37:17', '2021-08-07 04:37:17'),
(14, 'App\\Models\\User', 3, 'app', '9d4a1c47e00cd49e63cf9b4792147dbfbb15d950e95615d50c5f49ffa592a5c9', '[\"*\"]', '2021-08-07 06:16:13', '2021-08-07 05:18:32', '2021-08-07 06:16:13'),
(15, 'App\\Models\\User', 3, 'app', '40be56b6c0510e07c8b364a6be15ad8da89d6a429fb2eedea7590baca938e955', '[\"*\"]', '2021-08-14 04:47:58', '2021-08-14 03:48:01', '2021-08-14 04:47:58');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `breed_id` int(10) UNSIGNED NOT NULL,
  `color_id` int(10) UNSIGNED NOT NULL,
  `state_id` int(10) UNSIGNED NOT NULL,
  `shipping_available` tinyint(1) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name`, `user_id`, `breed_id`, `color_id`, `state_id`, `shipping_available`, `price`, `description`, `video`, `status`, `status_description`, `created_at`, `updated_at`) VALUES
(2, 'sdfsdf', 3, 1, 1, 1, 1, 1, 'asdkjasdjf', 'videos/wSAkwhepPfKf4YFY59rUHsTLjNeTpef5JOnyTW15.mp4', 'under-review', 'under-review', '2021-07-31 06:57:27', '2021-07-31 06:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`, `created_at`, `updated_at`) VALUES
(1, 2, 2, '2021-07-31 06:57:43', '2021-07-31 06:57:43'),
(8, 2, 2, '2021-07-31 08:03:47', '2021-07-31 08:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `post_saves`
--

CREATE TABLE `post_saves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_saves`
--

INSERT INTO `post_saves` (`id`, `user_id`, `post_id`, `created_at`, `updated_at`) VALUES
(4, 3, 2, '2021-07-31 08:02:03', '2021-07-31 08:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'ABC', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `username`, `state_id`, `address`, `active`, `type`, `profile_picture`) VALUES
(2, 'Muhammad Ali Haider', 'admin@mailinator.com', NULL, '$2y$10$s27Ccc9f8Vg.FMcvSUTlA.lgjMKywBwKu5vxwhSzAamJX1j5lkZoa', NULL, '2021-06-27 14:32:59', '2021-06-27 14:32:59', '923068888269', 'admin', 1, '123', 1, 'admin', 'users/oyFG8pCeHkMgKP1jA0CC4g3pFqeGNAwnzlfV4WS7.jpg'),
(3, 'Ahsan khan', 'user@mailinator.com', NULL, '$2y$10$Nsi8SPIzMfQmagm3lbxlvuRGAtnuseEiDqRDLPGmqXx4DQpbScUfu', NULL, '2021-07-10 03:56:35', '2021-08-07 05:46:45', '923011234567', 'ali', 1, '123', 1, 'user', 'users/-1975273621184969474.jpg'),
(9, 'ahsen', 'ahsen1@mailinator.com1', NULL, '$2y$10$gOm.zdSrD2g2B2EBOlvkbOzTuqZXn5TAyKVdz9m/5.ZZkooTkTux2', NULL, '2021-08-07 04:37:17', '2021-08-07 04:37:17', '+9230123456171', 'ahsen11', 1, 'test', 1, 'user', 'users/CoMdMQOM6ZJFUUbAoa7F52LIHNtJYLOsR4VpG6f4.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `breeds`
--
ALTER TABLE `breeds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `flaged_contents`
--
ALTER TABLE `flaged_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flaged_contents_flag_id_index` (`flag_id`),
  ADD KEY `flaged_contents_post_id_index` (`post_id`),
  ADD KEY `flaged_contents_user_id_index` (`user_id`);

--
-- Indexes for table `flags`
--
ALTER TABLE `flags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_user_id_index` (`user_id`),
  ADD KEY `posts_breed_id_index` (`breed_id`),
  ADD KEY `posts_color_id_index` (`color_id`),
  ADD KEY `posts_state_id_index` (`state_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_saves`
--
ALTER TABLE `post_saves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `breeds`
--
ALTER TABLE `breeds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `flaged_contents`
--
ALTER TABLE `flaged_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `flags`
--
ALTER TABLE `flags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post_saves`
--
ALTER TABLE `post_saves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

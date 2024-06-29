-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 29, 2024 at 04:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maintenance_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `calibration_performs`
--

CREATE TABLE `calibration_performs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calibration_request_id` bigint(20) UNSIGNED NOT NULL,
  `technician_id` bigint(20) UNSIGNED NOT NULL,
  `perform_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `service_report` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calibration_requests`
--

CREATE TABLE `calibration_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('In','Out','Warranty') NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `signed_to_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `number`, `location`, `created_at`, `updated_at`) VALUES
(1, 'maintenance', '1', 'floor1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sn` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `class` enum('A','B','C') DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `ppm` int(11) DEFAULT NULL COMMENT 'duration of maintenance each quarter 3,6,9',
  `need_calibration` tinyint(1) DEFAULT NULL,
  `calibration_cycle` text DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `maintenance_performs`
--

CREATE TABLE `maintenance_performs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_request_id` bigint(20) UNSIGNED NOT NULL,
  `technician_id` bigint(20) UNSIGNED NOT NULL,
  `perform_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `service_report` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_perform_details`
--

CREATE TABLE `maintenance_perform_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `maintenance_performs_id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `warranty` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('In','Out','Warranty') NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `signed_to_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_06_24_224808_create_roles_table', 1),
(6, '2024_06_24_224812_create_permissions_table', 1),
(7, '2024_06_24_224814_create_departments_table', 1),
(8, '2024_06_24_224816_create_equipment_table', 1),
(9, '2024_06_24_224819_create_spare_parts_table', 2),
(10, '2024_06_24_224821_create_maintenance_requests_table', 2),
(11, '2024_06_24_224824_create_maintenance_performs_table', 2),
(12, '2024_06_24_224826_create_calibration_requests_table', 2),
(13, '2024_06_24_224828_create_calibration_performs_table', 2),
(14, '2024_06_24_224831_create_user_roles_table', 2),
(15, '2024_06_24_224833_create_role_permissions_table', 2),
(16, '2024_06_25_063622_create_spare_part_requests_table', 2),
(17, '2024_06_25_065524_create_spare_part_performs_table', 3),
(18, '2024_06_25_070955_create_spare_part_perform_attachments_table', 3),
(19, '2024_06_25_071250_create_spare_part_perform_details_table', 3),
(20, '2024_06_27_002246_create_maintenance_perform_details_table', 3),
(21, '2024_06_27_004719_create_repair_requests_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('dkhalf37@gmail.com', '$2y$10$sp6S8iusvUIjVB5y5AHiK.pmb/tFMfCO4p5ohoG.lc5pC9SZmk4ZO', '2024-06-28 19:20:58');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `repair_requests`
--

CREATE TABLE `repair_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `report` text DEFAULT NULL,
  `request_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_parts`
--

CREATE TABLE `spare_parts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_part_performs`
--

CREATE TABLE `spare_part_performs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_request_id` bigint(20) UNSIGNED NOT NULL,
  `technician_id` bigint(20) UNSIGNED NOT NULL,
  `perform_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Completed',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_part_perform_attachments`
--

CREATE TABLE `spare_part_perform_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_perform_id` bigint(20) UNSIGNED NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_part_perform_details`
--

CREATE TABLE `spare_part_perform_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_perform_id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `warranty` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_part_requests`
--

CREATE TABLE `spare_part_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `equipment_id` bigint(20) UNSIGNED NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `signed_to_id` bigint(20) UNSIGNED NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `note` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_part_request_details`
--

CREATE TABLE `spare_part_request_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_request_id` bigint(20) UNSIGNED NOT NULL,
  `spare_part_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `user_type` enum('FullAdmin','Manager','Technician','RequestMaker') NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_type`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'doaa', 'dkhalf37@gmail.com', NULL, '$2y$10$nhHi7.5aoBYJQie5vU5e4.Igx2a7z9oojtXu6ohomfoMIdh4jMizS', 'FullAdmin', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calibration_performs`
--
ALTER TABLE `calibration_performs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calibration_performs_calibration_request_id_foreign` (`calibration_request_id`),
  ADD KEY `calibration_performs_technician_id_foreign` (`technician_id`);

--
-- Indexes for table `calibration_requests`
--
ALTER TABLE `calibration_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calibration_requests_equipment_id_foreign` (`equipment_id`),
  ADD KEY `calibration_requests_requester_id_foreign` (`requester_id`),
  ADD KEY `calibration_requests_signed_to_id_foreign` (`signed_to_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipment_department_id_foreign` (`department_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `maintenance_performs`
--
ALTER TABLE `maintenance_performs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_performs_maintenance_request_id_foreign` (`maintenance_request_id`),
  ADD KEY `maintenance_performs_technician_id_foreign` (`technician_id`);

--
-- Indexes for table `maintenance_perform_details`
--
ALTER TABLE `maintenance_perform_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_perform_details_maintenance_performs_id_foreign` (`maintenance_performs_id`),
  ADD KEY `maintenance_perform_details_spare_part_id_foreign` (`spare_part_id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenance_requests_equipment_id_foreign` (`equipment_id`),
  ADD KEY `maintenance_requests_requester_id_foreign` (`requester_id`),
  ADD KEY `maintenance_requests_signed_to_id_foreign` (`signed_to_id`);

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
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `repair_requests_equipment_id_foreign` (`equipment_id`),
  ADD KEY `repair_requests_requester_id_foreign` (`requester_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `spare_parts`
--
ALTER TABLE `spare_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_parts_equipment_id_foreign` (`equipment_id`);

--
-- Indexes for table `spare_part_performs`
--
ALTER TABLE `spare_part_performs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_part_performs_spare_part_request_id_foreign` (`spare_part_request_id`),
  ADD KEY `spare_part_performs_technician_id_foreign` (`technician_id`);

--
-- Indexes for table `spare_part_perform_attachments`
--
ALTER TABLE `spare_part_perform_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_part_perform_attachments_spare_part_perform_id_foreign` (`spare_part_perform_id`);

--
-- Indexes for table `spare_part_perform_details`
--
ALTER TABLE `spare_part_perform_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_part_perform_details_spare_part_perform_id_foreign` (`spare_part_perform_id`),
  ADD KEY `spare_part_perform_details_spare_part_id_foreign` (`spare_part_id`);

--
-- Indexes for table `spare_part_requests`
--
ALTER TABLE `spare_part_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_part_requests_equipment_id_foreign` (`equipment_id`),
  ADD KEY `spare_part_requests_requester_id_foreign` (`requester_id`),
  ADD KEY `spare_part_requests_signed_to_id_foreign` (`signed_to_id`);

--
-- Indexes for table `spare_part_request_details`
--
ALTER TABLE `spare_part_request_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spare_part_request_details_spare_part_request_id_foreign` (`spare_part_request_id`),
  ADD KEY `spare_part_request_details_spare_part_id_foreign` (`spare_part_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calibration_performs`
--
ALTER TABLE `calibration_performs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calibration_requests`
--
ALTER TABLE `calibration_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_performs`
--
ALTER TABLE `maintenance_performs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_perform_details`
--
ALTER TABLE `maintenance_perform_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repair_requests`
--
ALTER TABLE `repair_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_parts`
--
ALTER TABLE `spare_parts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_part_performs`
--
ALTER TABLE `spare_part_performs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_part_perform_attachments`
--
ALTER TABLE `spare_part_perform_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_part_perform_details`
--
ALTER TABLE `spare_part_perform_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_part_requests`
--
ALTER TABLE `spare_part_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spare_part_request_details`
--
ALTER TABLE `spare_part_request_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `calibration_performs`
--
ALTER TABLE `calibration_performs`
  ADD CONSTRAINT `calibration_performs_calibration_request_id_foreign` FOREIGN KEY (`calibration_request_id`) REFERENCES `calibration_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calibration_performs_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `calibration_requests`
--
ALTER TABLE `calibration_requests`
  ADD CONSTRAINT `calibration_requests_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`),
  ADD CONSTRAINT `calibration_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calibration_requests_signed_to_id_foreign` FOREIGN KEY (`signed_to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment`
--
ALTER TABLE `equipment`
  ADD CONSTRAINT `equipment_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `maintenance_performs`
--
ALTER TABLE `maintenance_performs`
  ADD CONSTRAINT `maintenance_performs_maintenance_request_id_foreign` FOREIGN KEY (`maintenance_request_id`) REFERENCES `maintenance_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_performs_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_perform_details`
--
ALTER TABLE `maintenance_perform_details`
  ADD CONSTRAINT `maintenance_perform_details_maintenance_performs_id_foreign` FOREIGN KEY (`maintenance_performs_id`) REFERENCES `maintenance_performs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_perform_details_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spare_parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD CONSTRAINT `maintenance_requests_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`),
  ADD CONSTRAINT `maintenance_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenance_requests_signed_to_id_foreign` FOREIGN KEY (`signed_to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `repair_requests`
--
ALTER TABLE `repair_requests`
  ADD CONSTRAINT `repair_requests_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `repair_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `spare_parts`
--
ALTER TABLE `spare_parts`
  ADD CONSTRAINT `spare_parts_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `spare_part_performs`
--
ALTER TABLE `spare_part_performs`
  ADD CONSTRAINT `spare_part_performs_spare_part_request_id_foreign` FOREIGN KEY (`spare_part_request_id`) REFERENCES `spare_part_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spare_part_performs_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spare_part_perform_attachments`
--
ALTER TABLE `spare_part_perform_attachments`
  ADD CONSTRAINT `spare_part_perform_attachments_spare_part_perform_id_foreign` FOREIGN KEY (`spare_part_perform_id`) REFERENCES `spare_part_performs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spare_part_perform_details`
--
ALTER TABLE `spare_part_perform_details`
  ADD CONSTRAINT `spare_part_perform_details_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spare_parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spare_part_perform_details_spare_part_perform_id_foreign` FOREIGN KEY (`spare_part_perform_id`) REFERENCES `spare_part_performs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spare_part_requests`
--
ALTER TABLE `spare_part_requests`
  ADD CONSTRAINT `spare_part_requests_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spare_part_requests_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spare_part_requests_signed_to_id_foreign` FOREIGN KEY (`signed_to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spare_part_request_details`
--
ALTER TABLE `spare_part_request_details`
  ADD CONSTRAINT `spare_part_request_details_spare_part_id_foreign` FOREIGN KEY (`spare_part_id`) REFERENCES `spare_parts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `spare_part_request_details_spare_part_request_id_foreign` FOREIGN KEY (`spare_part_request_id`) REFERENCES `spare_part_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

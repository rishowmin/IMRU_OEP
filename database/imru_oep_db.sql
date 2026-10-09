-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 02:38 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `imru_oep_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `aca_courses`
--

CREATE TABLE `aca_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `credits` varchar(255) DEFAULT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aca_courses`
--

INSERT INTO `aca_courses` (`id`, `course_title`, `course_code`, `credits`, `teacher_id`, `description`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ICT', 'ICT-1234', '3', 1, NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(2, 'Cryptography and Steganography', 'PMIT-6204', '3', 2, NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(3, 'UI and UX', 'PMIT-6224', '3', 3, NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(4, 'IoT and Fog Computing', 'PMIT-6223', '3', 4, NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(5, 'Human Computer Interaction', 'PMIT-6311', '3', 5, NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aca_enrollments`
--

CREATE TABLE `aca_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exams`
--

CREATE TABLE `aca_exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `exam_title` varchar(255) DEFAULT NULL,
  `exam_code` varchar(255) DEFAULT NULL,
  `exam_type` varchar(255) DEFAULT NULL,
  `exam_date` datetime DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `exam_duration_min` int(11) DEFAULT NULL,
  `total_marks` decimal(8,2) DEFAULT NULL,
  `passing_marks` decimal(8,2) DEFAULT NULL,
  `total_questions` int(11) DEFAULT NULL,
  `comments` longtext DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_answers`
--

CREATE TABLE `aca_exam_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_attempts`
--

CREATE TABLE `aca_exam_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'New' COMMENT 'New, Old',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_clipboard_logs`
--

CREATE TABLE `aca_exam_clipboard_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attempt_id` bigint(20) UNSIGNED NOT NULL,
  `action_type` varchar(255) NOT NULL COMMENT 'copy, paste, cut',
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_proctoring_events`
--

CREATE TABLE `aca_exam_proctoring_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attempt_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(255) NOT NULL COMMENT 'tab_switch, fullscreen_required, webcam_required, copy_attempt, paste_attempt, face_not_detected, multiple_faces, looking_away',
  `severity` varchar(255) NOT NULL DEFAULT 'low' COMMENT 'low, medium, high',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Extra AI details' CHECK (json_valid(`metadata`)),
  `detected_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_results`
--

CREATE TABLE `aca_exam_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `mcq_total` int(11) NOT NULL DEFAULT 0 COMMENT 'Total MCQ questions attempted',
  `mcq_correct` int(11) NOT NULL DEFAULT 0 COMMENT 'Correct MCQ answers',
  `mcq_wrong` int(11) NOT NULL DEFAULT 0 COMMENT 'Wrong MCQ answers',
  `mcq_unanswered` int(11) NOT NULL DEFAULT 0 COMMENT 'MCQ questions not answered',
  `mcq_marks_obtained` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Marks from MCQ',
  `mcq_total_marks` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Total possible MCQ marks',
  `subjective_total` int(11) NOT NULL DEFAULT 0 COMMENT 'Total subjective questions',
  `subjective_reviewed` int(11) NOT NULL DEFAULT 0 COMMENT 'How many have been reviewed',
  `subjective_marks_obtained` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Marks from subjective',
  `subjective_total_marks` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Total possible subjective marks',
  `total_marks_obtained` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_marks` decimal(8,2) NOT NULL DEFAULT 0.00,
  `percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `grade` varchar(255) DEFAULT NULL COMMENT 'A+, A, A-, B+, B, B-, C+, C, D, F',
  `is_pass` tinyint(1) NOT NULL DEFAULT 0,
  `grading_status` enum('pending','partial','complete') NOT NULL DEFAULT 'pending' COMMENT 'pending=not graded, partial=subjective pending, complete=fully graded',
  `graded_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_rules`
--

CREATE TABLE `aca_exam_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'instruction' COMMENT 'rule, instruction',
  `key` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aca_exam_rules`
--

INSERT INTO `aca_exam_rules` (`id`, `type`, `key`, `title`, `description`, `order`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'instruction', 'timer_policy', 'Timer Policy', 'The timer will start as soon as you click Start Exam. You cannot pause it.', 1, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(2, 'instruction', 'auto_submit', 'Auto Submit', 'Submit your answers before the timer runs out. The exam will be auto-submitted when time expires.', 2, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(3, 'instruction', 'single_attempt', 'Single Attempt', 'You can only attempt this exam once. Re-entry is not allowed after submission.', 3, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(4, 'instruction', 'internet_connection', 'Internet Connection', 'Ensure a stable internet connection before starting the exam.', 4, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(5, 'rule', 'tab_switching', 'Tab Switching', 'Do not switch tabs, minimize or restore the browser, or open any other browser tab during the exam. It will count as a violation.', 1, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(6, 'rule', 'fullscreen_required', 'Fullscreen Required', 'During the exam, your browser must remain in fullscreen mode at all times. Exiting fullscreen will be recorded as a violation and reported to your instructor.', 3, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(7, 'rule', 'webcam_required', 'Webcam Required', 'You must have a working webcam connected and enabled before starting the exam. If you do not give access, it will count as a violation', 3, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_rule_maps`
--

CREATE TABLE `aca_exam_rule_maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `rule_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_sets`
--

CREATE TABLE `aca_exam_sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL DEFAULT 'General',
  `question_type` varchar(255) NOT NULL DEFAULT 'All' COMMENT 'e.g. all, objective, subjective, mcq_4, mcq_2, short_question, long_question',
  `total_questions` int(10) UNSIGNED NOT NULL,
  `easy_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `medium_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `hard_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `qt1_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `qt2_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `duration_minutes` int(10) UNSIGNED NOT NULL DEFAULT 60,
  `total_marks` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ai_reasoning` text DEFAULT NULL COMMENT 'Gorq''s explanation of how it selected/balanced questions',
  `question_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Ordered list of selected question IDs' CHECK (json_valid(`question_ids`)),
  `custom_marks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Custom Marks for Each Questions' CHECK (json_valid(`custom_marks`)),
  `randomization_seed` varchar(255) DEFAULT NULL,
  `status` enum('draft','active','archived') NOT NULL DEFAULT 'draft',
  `published_exam_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'aca_exams.id created when this set was published',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_tab_switch_logs`
--

CREATE TABLE `aca_exam_tab_switch_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attempt_id` bigint(20) UNSIGNED NOT NULL,
  `switched_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `returned_at` timestamp NULL DEFAULT NULL,
  `duration_ms` int(11) DEFAULT NULL COMMENT 'Time away in milliseconds',
  `switch_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Cumulative switches in session',
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_exam_webcam_logs`
--

CREATE TABLE `aca_exam_webcam_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attempt_id` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) DEFAULT NULL COMMENT 'Stored in S3/cloud',
  `ai_flag` varchar(255) NOT NULL DEFAULT 'clear' COMMENT 'clear, no_face, multiple_faces, suspicious',
  `confidence` decimal(5,2) DEFAULT NULL COMMENT 'AI confidence score 0-100',
  `captured_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_questions`
--

CREATE TABLE `aca_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_id` bigint(20) UNSIGNED NOT NULL,
  `question_type` varchar(255) NOT NULL,
  `question_text` text NOT NULL,
  `difficulty_level` varchar(255) NOT NULL DEFAULT 'medium' COMMENT 'easy, medium, hard',
  `marks` decimal(5,2) UNSIGNED NOT NULL DEFAULT 1.00,
  `evaluation_type` varchar(255) NOT NULL DEFAULT 'automatic' COMMENT 'automatic=objective, manual=subjective',
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` longtext DEFAULT NULL,
  `question_figure` varchar(255) DEFAULT NULL,
  `question_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aca_question_libraries`
--

CREATE TABLE `aca_question_libraries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic` varchar(255) NOT NULL DEFAULT 'General',
  `question_type` varchar(255) NOT NULL,
  `difficulty_level` varchar(255) NOT NULL DEFAULT 'medium' COMMENT 'easy, medium, hard',
  `marks` decimal(5,2) UNSIGNED NOT NULL DEFAULT 1.00,
  `question_text` text NOT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` longtext DEFAULT NULL,
  `question_figure` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `aca_question_libraries`
--

INSERT INTO `aca_question_libraries` (`id`, `topic`, `question_type`, `difficulty_level`, `marks`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `question_figure`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ICT', 'mcq_4', 'easy', '1.00', 'What does CPU stand for?', 'Central Processing Unit', 'Computer Personal Unit', 'Central Program Utility', 'Core Processing Unit', 'Central Processing Unit', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(2, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is a non-volatile memory?', 'RAM', 'Cache', 'ROM', 'Register', 'ROM', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(3, 'ICT', 'mcq_4', 'easy', '1.00', 'What is the base of the binary number system?', '8', '10', '2', '16', '2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(4, 'ICT', 'mcq_4', 'easy', '1.00', 'Which protocol is used to send emails?', 'FTP', 'HTTP', 'SMTP', 'DNS', 'SMTP', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(5, 'ICT', 'mcq_4', 'easy', '1.00', 'What does URL stand for?', 'Uniform Resource Locator', 'Universal Record Link', 'Unified Runtime Layer', 'User Resource Log', 'Uniform Resource Locator', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(6, 'ICT', 'mcq_4', 'medium', '1.00', 'Which layer of the OSI model handles routing?', 'Physical', 'Data Link', 'Network', 'Transport', 'Network', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(7, 'ICT', 'mcq_4', 'medium', '1.00', 'Which storage has the fastest access speed?', 'Hard Disk', 'SSD', 'RAM', 'Cache', 'Cache', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(8, 'ICT', 'mcq_4', 'easy', '1.00', 'What does LAN stand for?', 'Large Area Network', 'Local Area Network', 'Linked Access Node', 'Layered Access Network', 'Local Area Network', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(9, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is an open-source operating system?', 'Windows', 'macOS', 'Linux', 'iOS', 'Linux', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(10, 'ICT', 'mcq_4', 'easy', '1.00', 'Which data structure uses LIFO order?', 'Queue', 'Stack', 'Linked List', 'Tree', 'Stack', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(11, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the time complexity of binary search?', 'O(n)', 'O(n²)', 'O(log n)', 'O(1)', 'O(log n)', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(12, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is NOT a programming paradigm?', 'Object-Oriented', 'Functional', 'Procedural', 'Relational', 'Relational', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(13, 'ICT', 'mcq_4', 'easy', '1.00', 'What does API stand for?', 'Application Programming Interface', 'Automated Program Interaction', 'Application Process Integration', 'Advanced Protocol Interface', 'Application Programming Interface', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(14, 'ICT', 'mcq_4', 'easy', '1.00', 'Which numbering system uses digits 0–7?', 'Binary', 'Decimal', 'Octal', 'Hexadecimal', 'Octal', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(15, 'ICT', 'mcq_4', 'easy', '1.00', 'What is the main function of a router?', 'Store data', 'Connect devices within a LAN', 'Forward packets between networks', 'Amplify signals', 'Forward packets between networks', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(16, 'ICT', 'mcq_4', 'medium', '1.00', 'Which sorting algorithm has the best average-case complexity?', 'Bubble Sort', 'Insertion Sort', 'Quick Sort', 'Selection Sort', 'Quick Sort', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(17, 'ICT', 'mcq_4', 'easy', '1.00', 'What does DBMS stand for?', 'Data Based Management System', 'Database Management System', 'Digital Binary Management Software', 'Dynamic Base Management System', 'Database Management System', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(18, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is a markup language?', 'Python', 'XML', 'C++', 'Java', 'XML', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(19, 'ICT', 'mcq_4', 'easy', '1.00', 'What is the purpose of a MAC address?', 'Identify a network', 'Identify a device on a network', 'Encrypt data', 'Route packets', 'Identify a device on a network', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(20, 'ICT', 'mcq_4', 'easy', '1.00', 'Which HTTP method is used to send data to a server?', 'GET', 'HEAD', 'POST', 'DELETE', 'POST', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(21, 'ICT', 'mcq_4', 'medium', '1.00', 'What does RAID stand for in storage?', 'Random Access Internal Drive', 'Redundant Array of Independent Disks', 'Rapid Access Index Drive', 'Redundant Allocation of Internal Data', 'Redundant Array of Independent Disks', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(22, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is an example of biometric authentication?', 'Password', 'PIN', 'Fingerprint scan', 'Security token', 'Fingerprint scan', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(23, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the function of an ALU?', 'Store data', 'Manage memory', 'Perform arithmetic and logic operations', 'Control I/O devices', 'Perform arithmetic and logic operations', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(24, 'ICT', 'mcq_4', 'easy', '1.00', 'Which generation of mobile networks supports speeds up to 20 Gbps?', '3G', '4G', '5G', '2G', '5G', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(25, 'ICT', 'mcq_4', 'medium', '1.00', 'What is a checksum used for?', 'Compressing data', 'Error detection', 'Encrypting files', 'Routing packets', 'Error detection', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(26, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is a relational database?', 'MongoDB', 'Redis', 'MySQL', 'Cassandra', 'MySQL', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(27, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is a NoSQL database?', 'PostgreSQL', 'Oracle', 'MongoDB', 'SQLite', 'MongoDB', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(28, 'ICT', 'mcq_4', 'easy', '1.00', 'What does the acronym AI stand for?', 'Automated Intelligence', 'Artificial Intelligence', 'Autonomous Integration', 'Applied Informatics', 'Artificial Intelligence', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(29, 'ICT', 'mcq_4', 'medium', '1.00', 'Which wireless standard is commonly known as Wi-Fi?', 'Bluetooth', 'IEEE 802.11', 'Zigbee', 'WiMAX', 'IEEE 802.11', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(30, 'ICT', 'mcq_4', 'easy', '1.00', 'What is the smallest unit of data in a computer?', 'Byte', 'Nibble', 'Bit', 'Kilobyte', 'Bit', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(31, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is a valid IPv4 address?', '256.1.1.1', '192.168.1.1', '300.0.0.1', '192.168.1.300', '192.168.1.1', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(32, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is used for wireless short-range communication?', 'Wi-Fi', 'Bluetooth', 'LTE', 'Satellite', 'Bluetooth', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(33, 'ICT', 'mcq_4', 'medium', '1.00', 'In OOP, what is overloading?', 'Extending a class', 'Multiple methods with same name but different parameters', 'Hiding parent class methods', 'Running multiple programs', 'Multiple methods with same name but different parameters', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(34, 'ICT', 'mcq_4', 'easy', '1.00', 'What type of error is detected at compile time?', 'Runtime error', 'Logical error', 'Syntax error', 'Semantic error', 'Syntax error', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(35, 'ICT', 'mcq_4', 'medium', '1.00', 'What is a subnet mask used for?', 'Encrypting data', 'Dividing an IP network into subnets', 'Identifying MAC addresses', 'Assigning dynamic IPs', 'Dividing an IP network into subnets', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(36, 'ICT', 'mcq_4', 'easy', '1.00', 'Which is the correct CSS property to change text color?', 'font-color', 'text-color', 'color', 'foreground', 'color', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(37, 'ICT', 'mcq_4', 'easy', '1.00', 'What does BIOS stand for?', 'Basic Input Output System', 'Binary Input Output Software', 'Base Integrated Operating System', 'Basic Integrated Operating Software', 'Basic Input Output System', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(38, 'ICT', 'mcq_4', 'easy', '1.00', 'Which protocol assigns IP addresses automatically?', 'FTP', 'DHCP', 'DNS', 'ARP', 'DHCP', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(39, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the purpose of a proxy server?', 'Store databases', 'Act as intermediary between clients and servers', 'Generate IP addresses', 'Manage email', 'Act as intermediary between clients and servers', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(40, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is a lossless compression format?', 'JPEG', 'MP3', 'PNG', 'MPEG', 'PNG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(41, 'ICT', 'mcq_4', 'easy', '1.00', 'What is a \"null\" value in programming?', 'Zero', 'Empty string', 'Absence of value', 'Negative number', 'Absence of value', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(42, 'ICT', 'mcq_4', 'medium', '1.00', 'What does HTTPS use to secure connections?', 'MD5', 'Base64', 'SSL/TLS', 'SHA-1', 'SSL/TLS', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(43, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following best describes a binary tree?', 'Each node has at most 2 children', 'Each node has exactly 2 children', 'Nodes are sorted alphabetically', 'A tree with one level', 'Each node has at most 2 children', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(44, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the purpose of NAT in networking?', 'Compress data', 'Translate private IPs to public IPs', 'Encrypt network traffic', 'Assign MAC addresses', 'Translate private IPs to public IPs', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(45, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of these is a feature of functional programming?', 'Mutable state', 'Objects and classes', 'Pure functions', 'Inheritance', 'Pure functions', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(46, 'ICT', 'mcq_4', 'easy', '1.00', 'What does GUI stand for?', 'Graphical User Interface', 'General Utility Integration', 'Global User Interaction', 'Graphical Unified Interface', 'Graphical User Interface', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(47, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is an example of a fourth-generation language?', 'Assembly', 'C', 'SQL', 'Machine Code', 'SQL', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(48, 'ICT', 'mcq_4', 'medium', '1.00', 'What type of attack involves intercepting and altering communication between two parties?', 'Phishing', 'Brute Force', 'Man-in-the-Middle', 'SQL Injection', 'Man-in-the-Middle', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(49, 'ICT', 'mcq_4', 'easy', '1.00', 'What does IoT stand for?', 'Internet of Things', 'Integration of Technology', 'Internal Operating Terminal', 'Interface of Tools', 'Internet of Things', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(50, 'ICT', 'mcq_4', 'medium', '1.00', 'Which data structure is used for implementing a priority queue?', 'Stack', 'Heap', 'Array', 'Linked List', 'Heap', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(51, 'ICT', 'mcq_4', 'easy', '1.00', 'What does DNS primarily do?', 'Assigns IP addresses dynamically', 'Translates domain names to IP addresses', 'Encrypts network traffic', 'Routes packets between networks', 'Translates domain names to IP addresses', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(52, 'ICT', 'mcq_4', 'medium', '1.00', 'Which layer of the OSI model is responsible for end-to-end communication?', 'Network', 'Session', 'Transport', 'Data Link', 'Transport', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(53, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is NOT a characteristic of cloud computing?', 'On-demand self-service', 'Resource pooling', 'Fixed hardware allocation', 'Broad network access', 'Fixed hardware allocation', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(54, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the purpose of the \"git commit\" command?', 'Push changes to remote repository', 'Save a snapshot of staged changes to local repository', 'Merge two branches', 'Clone a repository', 'Save a snapshot of staged changes to local repository', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(55, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following best describes polymorphism in OOP?', 'Hiding internal state', 'One interface, multiple implementations', 'A class inheriting from another', 'Grouping data and methods together', 'One interface, multiple implementations', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(56, 'ICT', 'mcq_4', 'hard', '1.00', 'Which protocol operates at the application layer and resolves email addresses?', 'ARP', 'DHCP', 'IMAP', 'ICMP', 'IMAP', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(57, 'ICT', 'mcq_4', 'hard', '1.00', 'What is the worst-case time complexity of QuickSort?', 'O(n log n)', 'O(n)', 'O(n²)', 'O(log n)', 'O(n²)', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(58, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is used to traverse a graph level by level?', 'Depth-First Search', 'Breadth-First Search', 'Binary Search', 'Merge Sort', 'Breadth-First Search', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(59, 'ICT', 'mcq_4', 'medium', '1.00', 'What does SQL\'s \"JOIN\" clause do?', 'Deletes records from a table', 'Combines rows from two or more tables based on a related column', 'Creates a new database', 'Filters duplicate records', 'Combines rows from two or more tables based on a related column', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(60, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is a stateless protocol?', 'FTP', 'SSH', 'HTTP', 'SMTP', 'HTTP', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(61, 'ICT', 'mcq_4', 'medium', '1.00', 'In which type of memory is the BIOS stored?', 'RAM', 'Cache', 'ROM / Flash', 'HDD', 'ROM / Flash', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(62, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the function of an index in a database?', 'Encrypt table data', 'Speed up data retrieval operations', 'Create foreign key constraints', 'Backup the database', 'Speed up data retrieval operations', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(63, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following best describes a microprocessor?', 'A memory chip', 'A complete CPU on a single chip', 'A network interface card', 'A storage controller', 'A complete CPU on a single chip', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(64, 'ICT', 'mcq_4', 'hard', '1.00', 'What is the difference between a hub and a switch?', 'A hub filters traffic; a switch broadcasts to all', 'A hub broadcasts to all ports; a switch sends data to the specific destination port', 'They are functionally identical', 'A switch operates at Layer 3; a hub at Layer 4', 'A hub broadcasts to all ports; a switch sends data to the specific destination port', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(65, 'ICT', 'mcq_4', 'medium', '1.00', 'Which of the following is a characteristic of a linked list over an array?', 'Faster random access', 'Fixed size allocation', 'Dynamic size and efficient insertion/deletion', 'Less memory usage', 'Dynamic size and efficient insertion/deletion', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(66, 'ICT', 'mcq_4', 'hard', '1.00', 'What is the primary purpose of the TCP three-way handshake?', 'Encrypt data before transmission', 'Establish a reliable connection between client and server', 'Assign an IP address to a device', 'Compress packets for faster delivery', 'Establish a reliable connection between client and server', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(67, 'ICT', 'mcq_4', 'hard', '1.00', 'Which of the following describes a \"race condition\" in software?', 'Two programs competing for screen space', 'An outcome that depends on unpredictable timing of concurrent operations', 'A sorting algorithm that prioritizes speed', 'A memory overflow caused by recursion', 'An outcome that depends on unpredictable timing of concurrent operations', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(68, 'ICT', 'mcq_4', 'easy', '1.00', 'Which type of software license allows anyone to view, modify, and distribute source code?', 'Proprietary', 'Freeware', 'Open Source', 'Shareware', 'Open Source', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(69, 'ICT', 'mcq_4', 'medium', '1.00', 'What does the term \"latency\" refer to in networking?', 'The total bandwidth available on a link', 'The delay between sending and receiving data', 'The number of connected devices', 'Data transfer rate per second', 'The delay between sending and receiving data', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(70, 'ICT', 'mcq_4', 'medium', '1.00', 'Which file system is natively used by Linux?', 'NTFS', 'FAT32', 'ext4', 'HFS+', 'ext4', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(71, 'ICT', 'mcq_4', 'hard', '1.00', 'What is a \"socket\" in networking programming?', 'A physical port on a switch', 'An endpoint for sending and receiving data across a network', 'A type of firewall rule', 'A wireless access point', 'An endpoint for sending and receiving data across a network', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(72, 'ICT', 'mcq_4', 'hard', '1.00', 'Which of the following correctly describes normalization in databases?', 'Combining all tables into one to speed up queries', 'Organizing data to reduce redundancy and improve integrity', 'Encrypting sensitive columns in a table', 'Adding indexes to all columns', 'Organizing data to reduce redundancy and improve integrity', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(73, 'ICT', 'mcq_4', 'medium', '1.00', 'What is the role of a CDN (Content Delivery Network)?', 'Assign domain names to websites', 'Distribute content from servers geographically closer to users', 'Encrypt all web traffic', 'Monitor server uptime', 'Distribute content from servers geographically closer to users', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(74, 'ICT', 'mcq_4', 'easy', '1.00', 'Which of the following is an example of a compiled language?', 'Python', 'JavaScript', 'Ruby', 'C++', 'C++', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(75, 'ICT', 'mcq_4', 'hard', '1.00', 'Which of the following correctly describes the CAP theorem in distributed systems?', 'A system can guarantee Consistency, Availability, and Partition tolerance simultaneously', 'A distributed system can only guarantee two of: Consistency, Availability, or Partition tolerance at a time', 'CAP refers to Cache, API, and Protocol layers', 'CAP theorem only applies to relational databases', 'A distributed system can only guarantee two of: Consistency, Availability, or Partition tolerance at a time', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(76, 'ICT', 'mcq_2', 'easy', '1.00', 'JavaScript is a server-side programming language only.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(77, 'ICT', 'mcq_2', 'medium', '1.00', 'IPv6 addresses are 128 bits long.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(78, 'ICT', 'mcq_2', 'easy', '1.00', 'A byte consists of 8 bits.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(79, 'ICT', 'mcq_2', 'easy', '1.00', 'The OSI model has 8 layers.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(80, 'ICT', 'mcq_2', 'easy', '1.00', 'SQL is used for managing relational databases.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(81, 'ICT', 'mcq_2', 'easy', '1.00', 'HTTP is a secure protocol.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(82, 'ICT', 'mcq_2', 'easy', '1.00', 'RAM is a primary storage device.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(83, 'ICT', 'mcq_2', 'medium', '1.00', 'A compiler translates code line by line.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(84, 'ICT', 'mcq_2', 'easy', '1.00', 'DNS stands for Domain Name System.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(85, 'ICT', 'mcq_2', 'medium', '1.00', 'Python is a statically typed language.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(86, 'ICT', 'mcq_2', 'medium', '1.00', 'The Internet and the World Wide Web are the same thing.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(87, 'ICT', 'mcq_2', 'easy', '1.00', 'A primary key in a relational database must be unique.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(88, 'ICT', 'mcq_2', 'easy', '1.00', 'Phishing is a type of social engineering attack.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(89, 'ICT', 'mcq_2', 'medium', '1.00', 'All high-level languages are compiled.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(90, 'ICT', 'mcq_2', 'medium', '1.00', 'Bandwidth and latency mean the same thing.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(91, 'ICT', 'mcq_2', 'medium', '1.00', 'A virus can spread without human interaction.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(92, 'ICT', 'mcq_2', 'medium', '1.00', 'Moore\'s Law states transistor count doubles every two years.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(93, 'ICT', 'mcq_2', 'medium', '1.00', 'Git is a centralized version control system.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(94, 'ICT', 'mcq_2', 'easy', '1.00', 'An intranet is accessible to the public.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(95, 'ICT', 'mcq_2', 'easy', '1.00', 'Optical fibers transmit data using light signals.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(96, 'ICT', 'mcq_2', 'easy', '1.00', 'JSON stands for JavaScript Object Notation.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(97, 'ICT', 'mcq_2', 'medium', '1.00', 'Every website must have a unique IP address.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(98, 'ICT', 'mcq_2', 'medium', '1.00', 'The stack data structure can be implemented using a linked list.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(99, 'ICT', 'mcq_2', 'easy', '1.00', 'Machine code is human-readable.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(100, 'ICT', 'mcq_2', 'easy', '1.00', 'SSD has moving mechanical parts.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(101, 'ICT', 'mcq_2', 'medium', '1.00', 'Python supports garbage collection.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(102, 'ICT', 'mcq_2', 'hard', '1.00', 'A database view is a physical table stored on disk.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(103, 'ICT', 'mcq_2', 'easy', '1.00', 'Agile is a software development methodology.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(104, 'ICT', 'mcq_2', 'easy', '1.00', 'Artificial Intelligence can fully replicate human consciousness.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(105, 'ICT', 'mcq_2', 'easy', '1.00', 'UML is used for software design modeling.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(106, 'ICT', 'mcq_2', 'medium', '1.00', 'A foreign key in a relational database references the primary key of another table.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(107, 'ICT', 'mcq_2', 'hard', '1.00', 'Docker containers include a full operating system.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(108, 'ICT', 'mcq_2', 'medium', '1.00', 'The \"ping\" command uses the ICMP protocol.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(109, 'ICT', 'mcq_2', 'easy', '1.00', 'JavaScript can run on both the client side and the server side.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(110, 'ICT', 'mcq_2', 'medium', '1.00', 'IPv4 supports more unique addresses than IPv6.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(111, 'ICT', 'mcq_2', 'hard', '1.00', 'A binary search tree guarantees O(log n) search time in all cases.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(112, 'ICT', 'mcq_2', 'medium', '1.00', 'REST APIs are stateless.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(113, 'ICT', 'mcq_2', 'medium', '1.00', 'Kubernetes is a container orchestration platform.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(114, 'ICT', 'mcq_2', 'medium', '1.00', 'A DDoS attack originates from a single source.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(115, 'ICT', 'mcq_2', 'easy', '1.00', 'Inheritance is one of the four pillars of Object-Oriented Programming.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(116, 'ICT', 'mcq_2', 'medium', '1.00', 'SHA-256 produces a 256-bit hash value.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(117, 'ICT', 'mcq_2', 'medium', '1.00', 'Blockchain is a centralized ledger technology.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(118, 'ICT', 'mcq_2', 'medium', '1.00', 'In a relational database, NULL means zero.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(119, 'ICT', 'mcq_2', 'hard', '1.00', 'Continuous Integration (CI) automatically tests code changes when pushed to a repository.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(120, 'ICT', 'mcq_2', 'hard', '1.00', 'Merge Sort is a stable sorting algorithm.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(121, 'ICT', 'short_question', 'easy', '1.00', 'What is cloud computing?', NULL, NULL, NULL, NULL, 'Cloud computing is the delivery of computing services (servers, storage, databases, networking, software) over the internet on a pay-as-you-go basis, enabling flexible resources without local hardware.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(122, 'ICT', 'short_question', 'easy', '1.00', 'Define an algorithm.', NULL, NULL, NULL, NULL, 'An algorithm is a finite, ordered set of well-defined instructions designed to solve a problem or accomplish a task within a finite amount of time.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(123, 'ICT', 'short_question', 'easy', '1.00', 'What is a firewall?', NULL, NULL, NULL, NULL, 'A firewall is a security system that monitors and controls incoming and outgoing network traffic based on predefined security rules to protect a network from unauthorized access.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(124, 'ICT', 'short_question', 'easy', '1.00', 'What is the difference between hardware and software?', NULL, NULL, NULL, NULL, 'Hardware refers to the physical components of a computer (CPU, RAM, keyboard), while software is the set of programs and operating instructions that run on hardware.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(125, 'ICT', 'short_question', 'easy', '1.00', 'What is an IP address?', NULL, NULL, NULL, NULL, 'An IP address is a unique numerical label assigned to each device on a computer network that uses the Internet Protocol for communication and identification.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(126, 'ICT', 'short_question', 'easy', '1.00', 'What is a database?', NULL, NULL, NULL, NULL, 'A database is an organized collection of structured data stored electronically, managed by a DBMS to allow efficient storage, retrieval, and manipulation of data.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(127, 'ICT', 'short_question', 'easy', '1.00', 'Define machine learning.', NULL, NULL, NULL, NULL, 'Machine learning is a subset of artificial intelligence where systems learn from data to improve their performance on tasks without being explicitly programmed.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(128, 'ICT', 'short_question', 'easy', '1.00', 'What is an operating system?', NULL, NULL, NULL, NULL, 'An operating system is system software that manages computer hardware and software resources, providing common services for computer programs (e.g., Windows, Linux, macOS).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(129, 'ICT', 'short_question', 'medium', '1.00', 'What is the difference between TCP and UDP?', NULL, NULL, NULL, NULL, 'TCP is connection-oriented and ensures reliable, ordered data delivery. UDP is connectionless and faster but does not guarantee delivery.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(130, 'ICT', 'short_question', 'easy', '1.00', 'What is encryption?', NULL, NULL, NULL, NULL, 'Encryption is the process of converting readable data (plaintext) into an unreadable format (ciphertext) using an algorithm and key to protect information from unauthorized access.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(131, 'ICT', 'short_question', 'medium', '1.00', 'What is the difference between a process and a thread?', NULL, NULL, NULL, NULL, 'A process is an independent program in execution with its own memory space. A thread is a lighter unit of execution within a process, sharing memory with other threads in the same process.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(132, 'ICT', 'short_question', 'easy', '1.00', 'Define bandwidth.', NULL, NULL, NULL, NULL, 'Bandwidth is the maximum rate of data transfer across a network path, measured in bits per second (bps). It determines how much data can be sent in a given time period.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(133, 'ICT', 'short_question', 'medium', '1.00', 'What is virtualization in computing?', NULL, NULL, NULL, NULL, 'Virtualization is the creation of virtual versions of physical resources using software, allowing multiple virtual machines to run on a single physical machine.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(134, 'ICT', 'short_question', 'hard', '1.00', 'What is a deadlock in operating systems?', NULL, NULL, NULL, NULL, 'A deadlock occurs when two or more processes are blocked forever, each waiting for a resource held by the other, resulting in a circular dependency with no progress.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(135, 'ICT', 'short_question', 'medium', '1.00', 'What is the difference between a compiler and an interpreter?', NULL, NULL, NULL, NULL, 'A compiler translates the entire source code into machine code before execution. An interpreter translates and executes code line by line at runtime without producing a separate executable.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(136, 'ICT', 'short_question', 'medium', '1.00', 'Define Big Data and its 3 Vs.', NULL, NULL, NULL, NULL, 'Big Data refers to extremely large datasets. The 3 Vs are: Volume (massive data size), Velocity (speed of generation/processing), and Variety (different data types).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(137, 'ICT', 'short_question', 'easy', '1.00', 'What is a VPN?', NULL, NULL, NULL, NULL, 'A VPN (Virtual Private Network) creates an encrypted tunnel over the internet, allowing users to send and receive data as if connected to a private network, ensuring privacy and security.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(138, 'ICT', 'short_question', 'medium', '1.00', 'What is the role of a switch in a network?', NULL, NULL, NULL, NULL, 'A switch connects devices within a LAN and uses MAC addresses to forward data to the correct device, operating at the Data Link layer (Layer 2) of the OSI model.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(139, 'ICT', 'short_question', 'easy', '1.00', 'What is the difference between HTTP and HTTPS?', NULL, NULL, NULL, NULL, 'HTTP transmits data in plain text without encryption. HTTPS adds SSL/TLS encryption, ensuring data confidentiality and integrity between browser and server.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(140, 'ICT', 'short_question', 'easy', '1.00', 'What is a denial-of-service (DoS) attack?', NULL, NULL, NULL, NULL, 'A DoS attack floods a server or network with excessive requests, overwhelming its resources and making it unavailable to legitimate users.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(141, 'ICT', 'short_question', 'easy', '1.00', 'What is version control?', NULL, NULL, NULL, NULL, 'Version control is a system that records changes to files over time, allowing developers to track history, revert to previous versions, and collaborate without overwriting each other\'s work.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(142, 'ICT', 'short_question', 'medium', '1.00', 'What is multitasking in an operating system?', NULL, NULL, NULL, NULL, 'Multitasking is the OS\'s ability to execute multiple processes concurrently by rapidly switching between them, giving the illusion they run simultaneously on a single CPU.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(143, 'ICT', 'short_question', 'hard', '1.00', 'What is an API gateway?', NULL, NULL, NULL, NULL, 'An API gateway is a server that acts as the entry point for client requests, routing them to appropriate microservices, handling authentication, rate limiting, and load balancing.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(144, 'ICT', 'short_question', 'medium', '1.00', 'Explain the concept of recursion.', NULL, NULL, NULL, NULL, 'Recursion is a programming technique where a function calls itself with a smaller input until a base condition is met. It simplifies complex problems like factorial computation and tree traversal.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(145, 'ICT', 'short_question', 'medium', '1.00', 'What is load balancing?', NULL, NULL, NULL, NULL, 'Load balancing distributes incoming network traffic across multiple servers to ensure no single server is overwhelmed, improving availability, reliability, and performance.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(146, 'ICT', 'short_question', 'easy', '1.00', 'What is a cache in computing?', NULL, NULL, NULL, NULL, 'A cache is a small, fast memory that stores frequently accessed data temporarily so future requests can be served faster, reducing access time to slower storage.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(147, 'ICT', 'short_question', 'easy', '1.00', 'What is responsive web design?', NULL, NULL, NULL, NULL, 'Responsive web design makes web pages render well on all devices and screen sizes by using flexible grids, fluid images, and CSS media queries.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(148, 'ICT', 'short_question', 'medium', '1.00', 'What is containerization in software deployment?', NULL, NULL, NULL, NULL, 'Containerization packages an application and its dependencies into a portable container (e.g., Docker), ensuring consistent behavior across different environments.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(149, 'ICT', 'short_question', 'medium', '1.00', 'What is DevOps?', NULL, NULL, NULL, NULL, 'DevOps combines software development and IT operations, emphasizing automation, continuous integration/delivery, and collaboration to shorten development cycles.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(150, 'ICT', 'short_question', 'medium', '1.00', 'What is the role of middleware?', NULL, NULL, NULL, NULL, 'Middleware is software that connects different applications or systems, facilitating communication and data management between them (e.g., message queues, API layers).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(151, 'ICT', 'short_question', 'hard', '1.00', 'What is edge computing?', NULL, NULL, NULL, NULL, 'Edge computing processes data near the source (edge devices) rather than sending it to a central cloud server, reducing latency and bandwidth usage for IoT and real-time applications.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(152, 'ICT', 'short_question', 'medium', '1.00', 'What is a stack overflow error?', NULL, NULL, NULL, NULL, 'A stack overflow occurs when a program\'s call stack exceeds its allocated memory, typically caused by infinite or deeply nested recursion, causing the program to crash.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(153, 'ICT', 'short_question', 'hard', '1.00', 'What is the difference between synchronous and asynchronous programming?', NULL, NULL, NULL, NULL, 'Synchronous programming executes tasks sequentially — each operation must complete before the next begins. Asynchronous programming allows operations (e.g., I/O) to run in the background, enabling other code to execute without waiting.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(154, 'ICT', 'short_question', 'medium', '1.00', 'What is a RESTful API?', NULL, NULL, NULL, NULL, 'A RESTful API follows REST (Representational State Transfer) constraints: statelessness, client-server architecture, uniform interface, and resource-based URLs. It uses HTTP methods (GET, POST, PUT, DELETE) to perform CRUD operations.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(155, 'ICT', 'short_question', 'hard', '1.00', 'What is the difference between stack and heap memory?', NULL, NULL, NULL, NULL, 'Stack memory is automatically managed, stores local variables and function calls (LIFO), and is limited in size. Heap memory is dynamically allocated at runtime for objects, managed manually or by garbage collection, and is larger but slower.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(156, 'ICT', 'short_question', 'easy', '1.00', 'Define a protocol in networking.', NULL, NULL, NULL, NULL, 'A network protocol is a set of rules and conventions that govern how data is transmitted between devices, defining format, timing, sequencing, and error handling (e.g., TCP/IP, HTTP, FTP).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(157, 'ICT', 'short_question', 'hard', '1.00', 'What is a hash function?', NULL, NULL, NULL, NULL, 'A hash function maps input data of arbitrary size to a fixed-size output (hash/digest). It is deterministic, one-way, and collision-resistant. Used in password storage, data integrity verification, and digital signatures (e.g., SHA-256).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(158, 'ICT', 'short_question', 'medium', '1.00', 'What is Docker and why is it used?', NULL, NULL, NULL, NULL, 'Docker is a containerization platform that packages applications and their dependencies into lightweight containers. It ensures consistent behavior across development, testing, and production environments, simplifying deployment and scaling.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(159, 'ICT', 'short_question', 'medium', '1.00', 'What is a relational schema?', NULL, NULL, NULL, NULL, 'A relational schema defines the structure of a database including table names, column names, data types, and constraints (primary keys, foreign keys). It is the logical blueprint for organizing relational data.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(160, 'ICT', 'short_question', 'easy', '1.00', 'What is two-factor authentication (2FA)?', NULL, NULL, NULL, NULL, '2FA is a security mechanism requiring users to provide two distinct forms of identification: something they know (password), something they have (OTP token), or something they are (biometric), reducing unauthorized access risk.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(161, 'ICT', 'short_question', 'hard', '1.00', 'What is a race condition and how can it be prevented?', NULL, NULL, NULL, NULL, 'A race condition occurs when the outcome of a program depends on unpredictable execution order of concurrent operations. Prevention: use synchronization mechanisms such as mutexes, semaphores, or atomic operations to control access to shared resources.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(162, 'ICT', 'short_question', 'easy', '1.00', 'What is the difference between RAM and ROM?', NULL, NULL, NULL, NULL, 'RAM (Random Access Memory) is volatile, read-write memory used for temporary data during program execution. ROM (Read-Only Memory) is non-volatile, stores firmware permanently, and retains data without power.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(163, 'ICT', 'short_question', 'medium', '1.00', 'What is the purpose of a foreign key in a relational database?', NULL, NULL, NULL, NULL, 'A foreign key is a column that references the primary key of another table, enforcing referential integrity by ensuring that a value in one table corresponds to an existing value in the referenced table.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(164, 'ICT', 'short_question', 'medium', '1.00', 'What is a denial-of-service (DDoS) attack and how does it differ from DoS?', NULL, NULL, NULL, NULL, 'A DoS attack originates from a single source flooding a server. A DDoS (Distributed DoS) uses multiple compromised machines (botnets) to simultaneously flood the target, making it harder to block since traffic comes from many IPs.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(165, 'ICT', 'short_question', 'hard', '1.00', 'What is the purpose of indexing in a database?', NULL, NULL, NULL, NULL, 'An index creates a separate data structure (B-tree or hash) that allows the database engine to locate records quickly without scanning entire tables, dramatically improving SELECT query performance at the cost of additional storage.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(166, 'ICT', 'short_question', 'hard', '1.00', 'What is the difference between a hub, switch, and router?', NULL, NULL, NULL, NULL, 'A hub broadcasts to all ports (Layer 1). A switch uses MAC addresses to forward data to specific ports (Layer 2). A router directs traffic between different networks using IP addresses (Layer 3), connecting LANs to the internet.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(167, 'ICT', 'short_question', 'medium', '1.00', 'What is a REST endpoint?', NULL, NULL, NULL, NULL, 'A REST endpoint is a specific URL in a RESTful API that represents a resource and responds to HTTP methods. For example, GET /users returns all users, POST /users creates one, and DELETE /users/{id} removes a specific user.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(168, 'ICT', 'short_question', 'easy', '1.00', 'What is a boolean data type?', NULL, NULL, NULL, NULL, 'A boolean is a primitive data type that represents only two values: true or false (1 or 0). It is fundamental to conditional logic, comparisons, and control flow in virtually all programming languages.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL);
INSERT INTO `aca_question_libraries` (`id`, `topic`, `question_type`, `difficulty_level`, `marks`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `question_figure`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(169, 'ICT', 'short_question', 'medium', '1.00', 'What is software testing and why is it important?', NULL, NULL, NULL, NULL, 'Software testing is the process of evaluating a program to detect defects, verify it meets requirements, and ensure quality. It reduces bugs, improves security, builds user trust, and lowers maintenance costs. Types include unit, integration, system, and acceptance testing.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(170, 'ICT', 'short_question', 'medium', '1.00', 'What is the difference between authentication and authorization?', NULL, NULL, NULL, NULL, 'Authentication verifies the identity of a user (who you are), typically via passwords or biometrics. Authorization determines what an authenticated user is permitted to do (what you can access). Authentication always precedes authorization.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(171, 'ICT', 'long_question', 'medium', '1.00', 'Explain the OSI model and describe the function of each layer.', NULL, NULL, NULL, NULL, 'The OSI model is a 7-layer framework: (1) Physical — transmits raw bits; (2) Data Link — error-free transfer between adjacent nodes; (3) Network — routing and logical addressing (IP); (4) Transport — end-to-end communication, TCP/UDP; (5) Session — manages sessions; (6) Presentation — translates, encrypts, compresses data; (7) Application — interfaces with end-user software (HTTP, FTP, DNS). Each layer serves the one above and is served by the one below.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(172, 'ICT', 'long_question', 'medium', '1.00', 'Compare and contrast structured and unstructured data with examples.', NULL, NULL, NULL, NULL, 'Structured data is organized in rows and columns (e.g., relational databases, spreadsheets) — easy to search using SQL. Unstructured data has no predefined format (e.g., emails, images, videos) — requires AI/NLP to analyze. Semi-structured data (JSON, XML) lies between both. Roughly 80% of enterprise data is unstructured.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(173, 'ICT', 'long_question', 'medium', '1.00', 'Discuss the evolution of the internet from Web 1.0 to Web 3.0.', NULL, NULL, NULL, NULL, 'Web 1.0 (1990s): static, read-only pages. Web 2.0 (2000s–present): interactive/social web — users create content (YouTube, Facebook). Web 3.0: emerging decentralized web using blockchain, AI, semantic data — enabling data ownership and decentralized apps (dApps). Key differences: interactivity, ownership, and decentralization.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(174, 'ICT', 'long_question', 'medium', '1.00', 'Explain the concept of Object-Oriented Programming and its four pillars.', NULL, NULL, NULL, NULL, 'OOP is based on objects combining data and behavior. The four pillars: (1) Encapsulation — bundling data and methods in a class; (2) Abstraction — hiding complexity, exposing interfaces; (3) Inheritance — child class inherits from parent; (4) Polymorphism — same interface, different implementations. OOP improves modularity, reusability, and maintainability.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(175, 'ICT', 'long_question', 'medium', '1.00', 'Describe different types of cybersecurity threats and prevention methods.', NULL, NULL, NULL, NULL, 'Threats: Malware (viruses, ransomware), Phishing (deceptive emails), Man-in-the-Middle (intercepting communications), DDoS (traffic flooding), SQL Injection (malicious database queries), Zero-day exploits. Prevention: firewalls, antivirus, encryption, multi-factor authentication, regular patching, security awareness training, and intrusion detection systems.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(176, 'ICT', 'long_question', 'medium', '1.00', 'Explain the concepts of Artificial Intelligence, Machine Learning, and Deep Learning and their relationships.', NULL, NULL, NULL, NULL, 'AI is the broad field of simulating human intelligence. ML is a subset of AI where algorithms learn from data. Deep Learning is a subset of ML using multi-layered neural networks excelling at image recognition and NLP. Relationship: AI ⊇ ML ⊇ DL. DL powers GPT, AlphaGo, and image classifiers, transforming healthcare, finance, and autonomous vehicles.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(177, 'ICT', 'long_question', 'medium', '1.00', 'Explain the importance of data security and privacy in modern digital systems.', NULL, NULL, NULL, NULL, 'Data security protects information via the CIA triad: Confidentiality, Integrity, Availability. Privacy ensures individuals control personal data, governed by GDPR and similar regulations. Organizations must implement encryption, access controls, auditing, and breach notification. Cloud computing and IoT amplify these challenges significantly.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(178, 'ICT', 'long_question', 'medium', '1.00', 'What is the difference between public cloud, private cloud, and hybrid cloud?', NULL, NULL, NULL, NULL, 'Public cloud (AWS, Azure): third-party owned, scalable, cost-efficient but less control. Private cloud: organization-hosted, maximum security and control, higher cost. Hybrid cloud: combines both, keeping sensitive data private while using public cloud for scale. Choice depends on data sensitivity, budget, compliance, and workload patterns.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(179, 'ICT', 'long_question', 'medium', '1.00', 'Explain the Software Development Life Cycle (SDLC) and its phases.', NULL, NULL, NULL, NULL, 'SDLC phases: (1) Planning — scope and feasibility; (2) Requirements Analysis — functional/non-functional requirements; (3) System Design — architecture and UI; (4) Implementation — coding; (5) Testing — unit, integration, UAT; (6) Deployment — production release; (7) Maintenance — bug fixes and updates. Models include Waterfall (sequential) and Agile (iterative sprints).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(180, 'ICT', 'long_question', 'medium', '1.00', 'Discuss the role of Artificial Intelligence in healthcare.', NULL, NULL, NULL, NULL, 'AI applications in healthcare: diagnostics (medical image analysis), drug discovery (reducing years to months), personalized medicine, predictive analytics (outbreak forecasting), virtual health assistants, and robot-assisted surgery. Challenges include data privacy, algorithmic bias, regulatory approval, and need for clinical validation.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(181, 'ICT', 'long_question', 'medium', '1.00', 'Explain the differences between procedural and object-oriented programming with examples.', NULL, NULL, NULL, NULL, 'Procedural programming structures code as procedures/functions with data and functions separate (e.g., C with calculateSalary()). OOP organizes code around objects encapsulating data and behavior (e.g., Java Employee class with salary attributes and calculateBonus()). OOP excels in large, complex systems through inheritance, polymorphism, and encapsulation.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(182, 'ICT', 'long_question', 'medium', '1.00', 'Compare relational and non-relational databases, providing use cases for each.', NULL, NULL, NULL, NULL, 'Relational (MySQL, PostgreSQL): structured tables, predefined schema, SQL queries, ACID transactions — ideal for banking, ERP, e-commerce. Non-relational (MongoDB, Cassandra): flexible schema, horizontal scaling, unstructured data — ideal for social media, real-time analytics, IoT. Modern apps often use polyglot persistence combining both.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(183, 'ICT', 'long_question', 'hard', '1.00', 'Explain the concept of Big Data and discuss the technologies used to process it.', NULL, NULL, NULL, NULL, 'Big Data: datasets too large for traditional tools, defined by 5 Vs (Volume, Velocity, Variety, Veracity, Value). Technologies: Hadoop (HDFS + MapReduce), Apache Spark (in-memory), Kafka (streaming), Hive (SQL on Hadoop), NoSQL databases. Applications: fraud detection, recommendations, social media analysis, smart cities.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(184, 'ICT', 'long_question', 'hard', '1.00', 'Discuss cybersecurity frameworks and their role in protecting organizations.', NULL, NULL, NULL, NULL, 'Key frameworks: NIST CSF (Identify, Protect, Detect, Respond, Recover), ISO/IEC 27001 (information security management), CIS Controls (prioritized defenses), COBIT (IT governance). Benefits: standardized risk management, compliance (GDPR, HIPAA), structured incident response. Implementation: risk assessment, gap analysis, control selection, continuous monitoring.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(185, 'ICT', 'long_question', 'medium', '1.00', 'Discuss the importance of the Internet of Things (IoT) and the challenges it presents.', NULL, NULL, NULL, NULL, 'IoT connects physical devices via internet for data exchange. Benefits: smart homes, healthcare monitoring, smart cities, industrial automation, precision agriculture. Challenges: security (weak credentials, large attack surface), privacy (continuous collection), interoperability, scalability, power consumption, and real-time data management addressed by edge computing.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(186, 'ICT', 'long_question', 'easy', '1.00', 'Discuss the impact of social media on privacy and data security.', NULL, NULL, NULL, NULL, 'Social media collects location, preferences, behavior, and contacts. Risks: data harvesting, third-party app access, account hacking, doxxing, surveillance capitalism (Cambridge Analytica case). Mitigation: GDPR compliance, data minimization, user consent, privacy settings, and digital literacy education.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(187, 'ICT', 'long_question', 'hard', '1.00', 'Explain the principles of ethical hacking and penetration testing.', NULL, NULL, NULL, NULL, 'Ethical hacking is authorized cyberattack simulation to find vulnerabilities. Principles: written permission, defined scope, non-disclosure, detailed reporting. Phases: Reconnaissance, Scanning, Exploitation, Post-Exploitation, Reporting. Types: white-box, black-box, grey-box. Tools: Nmap, Metasploit, Wireshark, Burp Suite. Certification: CEH.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(188, 'ICT', 'long_question', 'hard', '1.00', 'Explain the role of microservices architecture in modern software development.', NULL, NULL, NULL, NULL, 'Microservices decompose an application into small, independently deployable services. Benefits: independent scaling, technology flexibility, fault isolation, and faster deployment cycles. Challenges: distributed system complexity, inter-service communication (REST/gRPC), data consistency, and observability. Tools: Docker, Kubernetes, API gateways. Contrast with monolithic architecture.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(189, 'ICT', 'long_question', 'hard', '1.00', 'Discuss quantum computing and its potential impact on cybersecurity.', NULL, NULL, NULL, NULL, 'Quantum computing uses qubits in superposition and entanglement for exponentially faster computation. Threat: Shor\'s algorithm can break RSA and ECC encryption. Opportunity: Quantum Key Distribution (QKD) enables theoretically unbreakable encryption. Post-quantum cryptography standards (NIST FIPS 203/204) are being adopted. Timeline for cryptographically relevant quantum computers: estimated 10–20 years.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(190, 'ICT', 'long_question', 'medium', '1.00', 'Explain the MVC design pattern and why it is widely used in web development.', NULL, NULL, NULL, NULL, 'MVC separates application into: Model (data and business logic), View (user interface), Controller (handles input, updates Model/View). Benefits: separation of concerns, easier testing, parallel development, and reusability. Used in Laravel (PHP), Django (Python), Ruby on Rails, and ASPoint.NET MVC. It reduces coupling between UI and business logic, making applications maintainable at scale.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(191, 'ICT', 'long_question', 'hard', '1.00', 'Explain the concept of blockchain technology and its applications beyond cryptocurrency.', NULL, NULL, NULL, NULL, 'Blockchain is a distributed, immutable ledger where transactions are recorded in chronologically linked blocks secured by cryptographic hashes. Key properties: decentralization (no single authority), transparency, immutability, and consensus mechanisms (Proof of Work, Proof of Stake). Beyond cryptocurrency: (1) Supply chain transparency — tracking goods from origin to consumer (Walmart, Maersk); (2) Healthcare — secure patient record sharing; (3) Smart contracts — self-executing agreements on Ethereum; (4) Voting systems — tamper-proof digital elections; (5) NFTs — digital ownership verification; (6) Land registries — fraud-resistant property records. Challenges: scalability (Bitcoin processes ~7 TPS vs Visa\'s 24,000), energy consumption, regulatory uncertainty, and interoperability between chains.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(192, 'ICT', 'long_question', 'hard', '1.00', 'Discuss the principles and importance of software testing, including different testing types.', NULL, NULL, NULL, NULL, 'Software testing verifies that a system meets requirements and is free of critical defects. Key principles: testing shows presence of defects (not absence), exhaustive testing is impossible, early testing saves cost, defects cluster in modules, and tests must evolve. Types: (1) Unit Testing — individual functions/modules; (2) Integration Testing — interaction between modules; (3) System Testing — complete system validation; (4) Acceptance Testing (UAT) — client verification; (5) Regression Testing — ensuring changes do not break existing features; (6) Performance Testing — load, stress, scalability; (7) Security Testing — vulnerability identification. Methodologies: black-box (functional), white-box (structural), grey-box. Modern practices: TDD (Test-Driven Development), BDD, CI/CD pipelines with automated test suites.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(193, 'ICT', 'long_question', 'hard', '1.00', 'Explain the concept of operating system scheduling algorithms and compare them.', NULL, NULL, NULL, NULL, 'CPU scheduling allocates processor time to processes. Algorithms: (1) FCFS (First Come First Served) — simple, non-preemptive, suffers from convoy effect; (2) SJF (Shortest Job First) — optimal average waiting time but requires knowing burst time; (3) Round Robin — preemptive, each process gets a fixed time quantum, fair for time-sharing; (4) Priority Scheduling — higher priority runs first, risk of starvation mitigated by aging; (5) Multilevel Queue — separate queues for foreground/background processes; (6) Multilevel Feedback Queue — most complex, processes move between queues based on behavior. Metrics: CPU utilization, throughput, turnaround time, waiting time, response time. Modern OS (Linux CFS) use weighted fair scheduling for balance.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(194, 'ICT', 'long_question', 'hard', '1.00', 'Discuss the architecture and components of a modern web application.', NULL, NULL, NULL, NULL, 'Modern web apps follow a multi-tier architecture: (1) Client (Frontend) — browser renders HTML/CSS/JavaScript; frameworks: React, Vue, Angular; (2) Backend/API — server-side logic via REST or GraphQL APIs; frameworks: Laravel, Django, Express, Spring; (3) Database — relational (MySQL) for structured data, NoSQL (MongoDB) for flexible data; (4) Cache — Redis/Memcached for fast data retrieval; (5) CDN — static asset delivery; (6) Authentication — JWT, OAuth 2.0; (7) Message Queues — RabbitMQ, Kafka for async processing; (8) Load Balancer — distributes traffic; (9) Cloud Infrastructure — AWS, GCP, Azure with auto-scaling. Security considerations: HTTPS, input validation, CSRF protection, rate limiting. DevOps: CI/CD pipelines, Docker, Kubernetes for deployment.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(195, 'ICT', 'long_question', 'hard', '1.00', 'Explain data structures and algorithms and why they are fundamental to software engineering.', NULL, NULL, NULL, NULL, 'Data structures organize and store data for efficient access and modification. Key structures: Arrays (O(1) random access), Linked Lists (O(1) insertion), Stacks (LIFO, function calls), Queues (FIFO, scheduling), Hash Tables (O(1) average lookup), Trees (hierarchical data, BST for search), Graphs (networks, shortest path). Algorithms solve computational problems: Sorting (Merge Sort O(n log n), Quick Sort), Searching (Binary Search O(log n)), Graph traversal (BFS, DFS), Dynamic Programming (optimal substructure), Greedy Algorithms. Importance: efficient algorithms reduce time/space complexity, enabling systems to scale. A poorly chosen algorithm can make a feature unusable (O(n²) vs O(n log n) on millions of records). Mastery is essential for technical interviews and engineering large-scale systems.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(196, 'ICT', 'long_question', 'hard', '1.00', 'Describe the principles of network security and the main techniques used to secure networks.', NULL, NULL, NULL, NULL, 'Network security protects infrastructure from unauthorized access, misuse, and attacks. Core principles: CIA triad (Confidentiality, Integrity, Availability) + Authentication, Non-repudiation. Techniques: (1) Firewalls — packet filtering, stateful inspection, application-layer; (2) IDS/IPS — detect and prevent intrusions; (3) VPN — encrypted tunnels for remote access; (4) Encryption — TLS for data in transit, AES for data at rest; (5) DMZ — isolated zone for public-facing servers; (6) Network segmentation — VLANs limit blast radius; (7) Zero Trust Architecture — never trust, always verify; (8) Multi-Factor Authentication; (9) Security audits and penetration testing; (10) Security Information and Event Management (SIEM). Threats defended: MITM, DDoS, packet sniffing, ARP spoofing, DNS poisoning.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(197, 'ICT', 'long_question', 'hard', '1.00', 'Explain the concept of virtualization and how it differs from containerization.', NULL, NULL, NULL, NULL, 'Virtualization creates virtual machines (VMs) using a hypervisor (Type 1: VMware ESXi, Hyper-V; Type 2: VirtualBox). Each VM includes a full OS kernel, making it heavyweight (GBs) but fully isolated. Containerization (Docker, Podman) shares the host OS kernel, packaging only the app and dependencies in lightweight containers (MBs), starting in seconds. Comparison: VMs offer stronger isolation but use more resources; containers are faster, more portable, and efficient. Orchestration: Kubernetes manages containers at scale. Use cases: VMs for different OS requirements or strong security isolation; containers for microservices, CI/CD pipelines, and cloud-native apps. Hybrid: VMs run container clusters for layered security.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(198, 'ICT', 'long_question', 'medium', '1.00', 'Discuss the impact of artificial intelligence on employment and the future of work.', NULL, NULL, NULL, NULL, 'AI automates routine cognitive and physical tasks, displacing roles in manufacturing, data entry, customer service, and basic legal/medical analysis. McKinsey estimates 15-30% of current tasks could be automated by 2030. New roles created: AI trainers, prompt engineers, data scientists, AI ethicists, and automation specialists. Historical pattern: past industrial revolutions eliminated roles but created new industries (internet created millions of jobs). Key concerns: job displacement concentration in lower-income workers, skills gap, geographic inequality, and the pace of change exceeding workforce adaptation. Solutions: reskilling programs, lifelong learning culture, STEM education, UBI pilots, and government policy frameworks. Net impact is debated but structural adjustment is certain, requiring proactive social and educational policy.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(199, 'ICT', 'long_question', 'hard', '1.00', 'Explain the concept of computer memory hierarchy and its importance in system performance.', NULL, NULL, NULL, NULL, 'Memory hierarchy organizes storage from fastest/smallest/costliest to slowest/largest/cheapest: (1) CPU Registers — picoseconds, bytes; (2) L1 Cache — 1-4 cycles, 32-64KB per core; (3) L2 Cache — 4-12 cycles, 256KB–1MB; (4) L3 Cache — 10-40 cycles, 4-64MB shared; (5) RAM — 60-100ns, GBs; (6) SSD — 0.1ms, TBs; (7) HDD — 5-10ms, TBs; (8) Optical/Tape — seconds, archival. Locality principles: temporal locality (recently used data reused) and spatial locality (nearby data accessed). Cache hit/miss rates dramatically affect performance — a cache miss penalty can cost 200+ cycles. Design strategies: prefetching, cache-aware algorithms, memory pooling. Understanding hierarchy is essential for writing high-performance software and database query optimization.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(200, 'ICT', 'long_question', 'hard', '1.00', 'Discuss the principles of clean code and software design patterns.', NULL, NULL, NULL, NULL, 'Clean code (Robert C. Martin) is readable, maintainable, and expressive: meaningful names, small focused functions, no duplication (DRY), minimal comments (self-documenting code), and consistent formatting. SOLID principles: (S) Single Responsibility, (O) Open/Closed, (L) Liskov Substitution, (I) Interface Segregation, (D) Dependency Inversion. Design Patterns (Gang of Four): Creational (Singleton, Factory, Builder), Structural (Adapter, Decorator, Facade), Behavioral (Observer, Strategy, Command). Anti-patterns to avoid: God Object, Spaghetti Code, Magic Numbers, Copy-Paste Programming. Code smells: long methods, large classes, feature envy, data clumps. Refactoring addresses smells without changing behavior. Clean code reduces technical debt, onboarding time, and bug rates significantly in long-term projects.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(201, 'Cryptography and Steganography', 'mcq_4', 'easy', '1.00', 'What is steganography?', 'Encryption of messages', 'Hiding messages within other data', 'Compressing files', 'Hashing passwords', 'Hiding messages within other data', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(202, 'Cryptography and Steganography', 'mcq_4', 'medium', '1.00', 'Which of the following is a symmetric encryption algorithm?', 'RSA', 'DES', 'ECC', 'ElGamal', 'DES', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(203, 'Cryptography and Steganography', 'mcq_4', 'medium', '1.00', 'What is LSB steganography?', 'Least Significant Bit manipulation to hide data', 'Largest Steganographic Bandwidth', 'Linked Steganographic Block', 'Linear Signal Bandwidth', 'Least Significant Bit manipulation to hide data', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(204, 'Cryptography and Steganography', 'mcq_4', 'easy', '1.00', 'What is a Caesar cipher?', 'A hash function', 'A substitution cipher shifting letters by a fixed number', 'A block cipher', 'An asymmetric algorithm', 'A substitution cipher shifting letters by a fixed number', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(205, 'Cryptography and Steganography', 'mcq_4', 'easy', '1.00', 'Which algorithm is commonly used for public-key cryptography?', 'AES', 'MD5', 'RSA', 'SHA-256', 'RSA', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(206, 'Cryptography and Steganography', 'mcq_2', 'easy', '1.00', 'Public key and private key are the same in asymmetric encryption.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(207, 'Cryptography and Steganography', 'mcq_2', 'medium', '1.00', 'SHA-256 is an encryption algorithm.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(208, 'Cryptography and Steganography', 'mcq_2', 'medium', '1.00', 'AES-256 is more secure than AES-128.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(209, 'Cryptography and Steganography', 'short_question', 'medium', '1.00', 'What is the difference between cryptography and steganography?', NULL, NULL, NULL, NULL, 'Cryptography scrambles a message so it cannot be read without a key (its existence is known). Steganography hides the message inside another medium (image, audio) so the existence of the message is hidden.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(210, 'Cryptography and Steganography', 'short_question', 'medium', '1.00', 'What is a digital watermark?', NULL, NULL, NULL, NULL, 'A digital watermark is hidden information embedded into digital media to identify ownership, verify authenticity, or track unauthorized copying using steganographic techniques.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(211, 'Cryptography and Steganography', 'short_question', 'hard', '1.00', 'What is a hash collision?', NULL, NULL, NULL, NULL, 'A hash collision occurs when two different inputs produce the same hash output. Strong hash functions (SHA-256) minimize collision probability; weak ones (MD5) are vulnerable to collision attacks.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(212, 'Cryptography and Steganography', 'long_question', 'hard', '1.00', 'Explain the RSA algorithm and how it achieves security.', NULL, NULL, NULL, NULL, 'RSA: choose two large primes p and q, compute n=p×q, find φ(n)=(p-1)(q-1), select public exponent e (coprime to φ(n)), compute private key d where d×e≡1(mod φ(n)). Encryption: ciphertext = m^e mod n. Decryption: m = c^d mod n. Security relies on the computational difficulty of factoring the product of two large primes. RSA 2048-bit is infeasible to break with current computing.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(213, 'Cryptography and Steganography', 'long_question', 'medium', '1.00', 'Discuss the applications and ethical concerns of steganography.', NULL, NULL, NULL, NULL, 'Applications: digital watermarking, covert communication, copyright protection, military intelligence, and embedding patient data in medical images. Ethical concerns: criminals use steganography to avoid surveillance. Steganalysis tools detect hidden data. Balancing security, privacy, and legitimate use is a major ongoing ethical and regulatory challenge.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(214, 'UI and UX', 'mcq_4', 'easy', '1.00', 'What does UX stand for?', 'User Extension', 'User Experience', 'Unified Exchange', 'User Examination', 'User Experience', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(215, 'UI and UX', 'mcq_4', 'medium', '1.00', 'Which principle ensures that important elements stand out visually?', 'Proximity', 'Hierarchy', 'Alignment', 'Repetition', 'Hierarchy', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(216, 'UI and UX', 'mcq_4', 'easy', '1.00', 'What is a persona in UX design?', 'A real user being tested', 'A fictional character representing a user segment', 'A UI prototype', 'A testing tool', 'A fictional character representing a user segment', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(217, 'UI and UX', 'mcq_4', 'easy', '1.00', 'What is a prototype in UI design?', 'A final product', 'An interactive simulation of the final design', 'Source code', 'A user manual', 'An interactive simulation of the final design', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(218, 'UI and UX', 'mcq_2', 'easy', '1.00', 'UI and UX design are the same discipline.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(219, 'UI and UX', 'mcq_2', 'hard', '1.00', 'Heuristic evaluation involves real users testing the system.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(220, 'UI and UX', 'mcq_2', 'medium', '1.00', 'The \"fold\" in web design refers to the visible area without scrolling.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(221, 'UI and UX', 'short_question', 'easy', '1.00', 'What is a wireframe in UX design?', NULL, NULL, NULL, NULL, 'A wireframe is a low-fidelity schematic representation of a digital interface showing structure and layout without visual design details, used for planning and stakeholder feedback.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(222, 'UI and UX', 'short_question', 'hard', '1.00', 'What is Fitts\' Law and how does it apply to UI design?', NULL, NULL, NULL, NULL, 'Fitts\' Law states that the time to reach a target depends on its size and distance. In UI design, important buttons should be large and easily reachable to reduce interaction time and errors.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(223, 'UI and UX', 'short_question', 'medium', '1.00', 'What is a user journey map?', NULL, NULL, NULL, NULL, 'A user journey map visualizes the steps a user takes to achieve a goal when interacting with a product, highlighting touchpoints, emotions, pain points, and opportunities for improvement.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(224, 'UI and UX', 'long_question', 'medium', '1.00', 'Explain the UX design process from research to final design.', NULL, NULL, NULL, NULL, 'UX process: (1) Research — user interviews, surveys, personas; (2) Define — journey maps, problem statements; (3) Ideate — brainstorming, design thinking; (4) Prototype — wireframes and interactive mockups (Figma); (5) Test — usability testing, A/B testing; (6) Implement — developer collaboration; (7) Evaluate — analytics and iteration. The process is iterative, with insights looping back to earlier phases.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(225, 'UI and UX', 'long_question', 'hard', '1.00', 'Discuss the importance of accessibility in UI/UX design.', NULL, NULL, NULL, NULL, 'Accessibility ensures products are usable by people with disabilities. WCAG 2.1 defines levels A, AA, AAA. Key practices: color contrast (4.5:1 ratio), alt text, keyboard navigation, ARIA roles, video captions. The curb-cut effect shows accessibility improvements benefit all users. It is legally required in many countries (ADA, EN 301 549) and expands market reach.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(226, 'IoT and Fog Computing', 'mcq_4', 'easy', '1.00', 'What is fog computing?', 'Computing in outer space', 'Edge computing closer to IoT devices', 'Cloud computing for weather data', 'A type of network protocol', 'Edge computing closer to IoT devices', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(227, 'IoT and Fog Computing', 'mcq_4', 'medium', '1.00', 'Which protocol is commonly used in IoT communications?', 'FTP', 'HTTP', 'MQTT', 'SMTP', 'MQTT', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(228, 'IoT and Fog Computing', 'mcq_4', 'easy', '1.00', 'What is a sensor node in IoT?', 'A cloud server', 'A device that collects and transmits environmental data', 'A network router', 'An application server', 'A device that collects and transmits environmental data', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(229, 'IoT and Fog Computing', 'mcq_2', 'medium', '1.00', 'Fog computing reduces latency compared to cloud computing for IoT.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(230, 'IoT and Fog Computing', 'mcq_2', 'medium', '1.00', 'IoT devices always require a constant internet connection to function.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(231, 'IoT and Fog Computing', 'short_question', 'hard', '1.00', 'What is the difference between fog computing and edge computing?', NULL, NULL, NULL, NULL, 'Edge computing processes data directly on end devices (sensors, cameras). Fog computing is an intermediate layer between edge devices and the cloud, providing a gateway with more processing power than edge but less than full cloud.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(232, 'IoT and Fog Computing', 'short_question', 'medium', '1.00', 'What are the main security challenges in IoT?', NULL, NULL, NULL, NULL, 'IoT security challenges include weak default credentials, lack of encryption, infrequent firmware updates, large attack surface from millions of devices, limited computational power for security, and physical vulnerabilities (e.g., Mirai botnet exploited default passwords).', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(233, 'IoT and Fog Computing', 'long_question', 'hard', '1.00', 'Discuss the architecture of an IoT system and the role of fog computing.', NULL, NULL, NULL, NULL, 'IoT system layers: (1) Perception — sensors/actuators collect data; (2) Network — data via Wi-Fi, Zigbee, LoRa, cellular; (3) Application — data processed and presented. Fog computing adds an intermediate layer providing local computation, storage, and communication. Benefits: reduced latency, bandwidth conservation, improved privacy, offline operation. Applications: smart cities, industrial IoT, healthcare. Challenges: managing distributed nodes, security, standardization.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(234, 'IoT and Fog Computing', 'long_question', 'medium', '1.00', 'Explain how fog computing supports smart city applications.', NULL, NULL, NULL, NULL, 'Smart cities use IoT sensors for traffic, lighting, water, waste, and safety systems. Fog computing enables: real-time traffic signal control at intersections, instant emergency response alerts, smart grid demand-response, real-time video surveillance analytics, and optimized garbage truck routing. Benefits: sub-millisecond response, reduced network congestion, higher reliability. Challenges: coordinating distributed infrastructure and ensuring vendor interoperability.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(235, 'Human Computer Interaction', 'mcq_4', 'easy', '1.00', 'What does HCI stand for?', 'High Computer Interface', 'Human Computer Interaction', 'Hardware Control Integration', 'Human Command Interpreter', 'Human Computer Interaction', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(236, 'Human Computer Interaction', 'mcq_4', 'medium', '1.00', 'Which of Nielsen\'s heuristics says users should not have to remember information?', 'Visibility of system status', 'Recognition rather than recall', 'Error prevention', 'Aesthetic design', 'Recognition rather than recall', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(237, 'Human Computer Interaction', 'mcq_4', 'medium', '1.00', 'What is a think-aloud protocol?', 'A coding standard', 'Users verbalize their thoughts while performing tasks', 'A design pattern', 'An encryption method', 'Users verbalize their thoughts while performing tasks', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(238, 'Human Computer Interaction', 'mcq_4', 'medium', '1.00', 'Which research method in HCI involves observing users in their natural environment?', 'Surveys', 'Ethnographic study', 'A/B testing', 'Heuristic evaluation', 'Ethnographic study', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(239, 'Human Computer Interaction', 'mcq_2', 'easy', '1.00', 'HCI only focuses on the visual design of interfaces.', 'True', 'False', NULL, NULL, 'False', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(240, 'Human Computer Interaction', 'mcq_2', 'medium', '1.00', 'Mental models in HCI refer to users\' internal understanding of a system.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(241, 'Human Computer Interaction', 'mcq_2', 'easy', '1.00', 'Cognitive load refers to the mental effort required to use an interface.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(242, 'Human Computer Interaction', 'mcq_2', 'medium', '1.00', 'Consistency in UI design helps users transfer knowledge between different parts of a system.', 'True', 'False', NULL, NULL, 'True', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(243, 'Human Computer Interaction', 'short_question', 'easy', '1.00', 'What is usability in HCI?', NULL, NULL, NULL, NULL, 'Usability measures how effectively, efficiently, and satisfactorily users achieve goals with a system. ISO 9241 defines it through learnability, efficiency, memorability, error frequency, and satisfaction.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(244, 'Human Computer Interaction', 'short_question', 'medium', '1.00', 'What is affordance in interface design?', NULL, NULL, NULL, NULL, 'Affordance is the perceived property of an object that suggests how it should be used. A button that looks raised affords clicking; a slider affords dragging. Good design uses clear affordances to guide interaction intuitively.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(245, 'Human Computer Interaction', 'short_question', 'hard', '1.00', 'What is GOMS in HCI?', NULL, NULL, NULL, NULL, 'GOMS (Goals, Operators, Methods, Selection rules) is a cognitive model that predicts user performance by modeling goals, primitive actions, methods to achieve goals, and rules for selecting among competing methods.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(246, 'Human Computer Interaction', 'short_question', 'medium', '1.00', 'What is the difference between learnability and memorability in usability?', NULL, NULL, NULL, NULL, 'Learnability measures how easily new users accomplish tasks on first use. Memorability measures how well users can reuse the system after a period of not using it. Both are usability dimensions defined by Nielsen.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(247, 'Human Computer Interaction', 'long_question', 'hard', '1.00', 'Discuss Nielsen\'s 10 Usability Heuristics and their importance.', NULL, NULL, NULL, NULL, 'Nielsen\'s 10 Heuristics: (1) Visibility of system status; (2) Match between system and real world; (3) User control and freedom; (4) Consistency and standards; (5) Error prevention; (6) Recognition rather than recall; (7) Flexibility and efficiency; (8) Aesthetic and minimalist design; (9) Help users recognize and recover from errors; (10) Help and documentation. These guide expert heuristic evaluation, enabling early, low-cost identification of usability issues.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(248, 'Human Computer Interaction', 'long_question', 'medium', '1.00', 'Explain the evolution of human-computer interaction from command-line to natural interfaces.', NULL, NULL, NULL, NULL, 'HCI evolution: (1) CLI — text commands, steep learning curve (UNIX, DOS); (2) GUI — windows, icons, mouse democratized computing (Macintosh, Windows); (3) Touch — direct manipulation on smartphones; (4) Voice — natural language assistants (Siri, Alexa); (5) Gesture/AR/VR — motion sensing and immersive interfaces; (6) Brain-Computer Interfaces — emerging neural control. Each evolution reduced technical barriers and expanded access.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(249, 'Human Computer Interaction', 'long_question', 'hard', '1.00', 'Discuss the role of HCI in the design of medical devices and systems.', NULL, NULL, NULL, NULL, 'Poor HCI in healthcare causes medication errors and device failures. Applications: EHR usability reduces physician burnout, infusion pumps require clear displays and error prevention, telemedicine must be usable by elderly users, alert systems must avoid alert fatigue. HCI principles: error prevention, minimalist design, consistent feedback. FDA requires human factors engineering in medical device approval.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL),
(250, 'Human Computer Interaction', 'long_question', 'hard', '1.00', 'Explain multimodal interaction and its advantages in modern HCI.', NULL, NULL, NULL, NULL, 'Multimodal interaction combines multiple input/output channels — speech, touch, gesture, gaze, haptic feedback. Examples: speech + touch on smartphones, gesture + voice in AR/VR, eye-gaze for accessibility. Advantages: flexibility, efficiency, error reduction, accessibility, and natural interaction mimicking human communication. Challenges: integrating modalities, handling ambiguity, maintaining context. Central to smart speakers, autonomous vehicles, and XR environments.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:33', '2026-10-09 12:35:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aca_review_answers`
--

CREATE TABLE `aca_review_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_answers_id` bigint(20) UNSIGNED NOT NULL,
  `review` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Wrong, 1=Correct',
  `marks_awarded` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Marks given by teacher',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Deactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'admin@imru.com', NULL, '$2y$10$geAjbUMtyqvDEcKzARwxCeSzYXa5prdWUcwdDXhxcd38GuOda.AU2', NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `pro_created_by` varchar(255) DEFAULT NULL,
  `pro_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
(5, '2026_04_12_074354_create_admins_table', 1),
(6, '2026_04_12_075633_create_teachers_table', 1),
(7, '2026_04_12_075650_create_students_table', 1),
(8, '2026_04_12_075711_create_recruiters_table', 1),
(9, '2026_04_12_075727_create_candidates_table', 1),
(10, '2026_04_15_092227_create_aca_courses_table', 1),
(11, '2026_04_15_121523_create_aca_exams_table', 1),
(12, '2026_04_22_054805_create_aca_questions_table', 1),
(13, '2026_04_23_114752_create_aca_enrollments_table', 1),
(14, '2026_04_24_194450_create_aca_exam_answers_table', 1),
(15, '2026_04_25_161020_create_aca_review_answers_table', 1),
(16, '2026_04_25_175119_create_aca_exam_attempts_table', 1),
(17, '2026_04_26_164533_create_aca_exam_rules_table', 1),
(18, '2026_04_26_231202_create_aca_exam_rule_maps_table', 1),
(19, '2026_04_29_121950_create_student_infos_table', 1),
(20, '2026_04_29_172534_create_teacher_infos_table', 1),
(21, '2026_04_30_161341_create_aca_question_libraries_table', 1),
(22, '2026_05_03_123703_create_aca_exam_proctoring_events_table', 1),
(23, '2026_05_03_123839_create_aca_exam_webcam_logs_table', 1),
(24, '2026_05_03_123930_create_aca_exam_tab_switch_logs_table', 1),
(25, '2026_05_03_124057_create_aca_exam_clipboard_logs_table', 1),
(26, '2026_05_06_001215_create_aca_exam_sets_table', 1),
(27, '2026_05_09_163054_create_aca_exam_results_table', 1);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruiters`
--

CREATE TABLE `recruiters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `pro_created_by` varchar(255) DEFAULT NULL,
  `pro_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Md. Tanvir', 'Hossain', '223201@imru.com', NULL, '$2y$10$vUamtGGiEkC6tmNZiHA2p.pU5pM5c9aQath8gOmdng2zSXEchMqLq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(2, 'Md.', 'Mohshin', '223202@imru.com', NULL, '$2y$10$hBaxcA7KrjjdsWt9eKThx.m0O2BoWsc0jAcClzkdGKxihUJqIzutC', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(3, 'Goutam', 'Biswas', '223203@imru.com', NULL, '$2y$10$LhqMO6HP.DRyaWvJ3KocDu9Xzqfbrg.QnSHG/K0cpvhtqo1TEMDgC', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(4, 'Dulal Kumar', 'Gomasta', '223204@imru.com', NULL, '$2y$10$6n2yj9wmF5Wj9EqxBEaWn.G.n1NNt9pm6rTYUCilMaf8xfvc6PQtC', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(5, 'Tashfiq', 'Ahmed', '223205@imru.com', NULL, '$2y$10$P9a2qR/WWerqYfP0AHcKkuWxgqAELK0Zp5WQ25.Ks7scGJ61eTTiO', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(6, 'Mahmudur', 'Rahman', '223206@imru.com', NULL, '$2y$10$05sRAIj95VLhhAbpzhoGQOHEJXKaogq/poxXcISyx4tWqPcDbkja6', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(7, 'Aziza Sarker', 'Rimie', '223207@imru.com', NULL, '$2y$10$qNURpd.pDs81UuB1oIGmSOQYsPUrh4EmpL4pzA46TGsbP2wunJygK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(8, 'Md. Habibur Rahman', 'Papel', '223208@imru.com', NULL, '$2y$10$RzLPEMrFCYZJ.d7OTRHnMeJmu3NsImo9sBSk5NZkjsk/bZiP.htRm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(9, 'Md. Shadman', 'Sakib', '223209@imru.com', NULL, '$2y$10$tfu7zlpxsBdMIaMkIx8SNO5sCJQLOCdxuMmXSWHubaDx5eRP.r0t2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(10, 'Md. Shadman', 'Saeed', '223210@imru.com', NULL, '$2y$10$fAjpIm8UwFe2UpeSRtqr..rQIBPiw9vDANNaNvCbWTS/qPBQb.4Zi', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(11, 'Fahim', 'Iftekhar', '223211@imru.com', NULL, '$2y$10$AVy8NbycopG.33dmVqb26.iHAGDImRTz9gCyd3tgIoL88oyKnrhqG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(12, 'Md. Shefatul', 'Islam', '223212@imru.com', NULL, '$2y$10$mHs2CjXlRU.nB/YrM8Krz.68Vj4aYVdj3Gxelywd9yR1rP7V4OgmK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(13, 'Mehnaz Binte', 'Zia', '223213@imru.com', NULL, '$2y$10$pj0zREPkTsYMmxgvs1FG2uuUrUjZ9WgrSmjfxqjJMDcrWQEHed97K', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(14, 'Mokarromah', 'Akter', '223214@imru.com', NULL, '$2y$10$gvYk26tHwfMDcRPcJvg76unmcCp2obxZlkLgTvfN.EsPuSfyIzdm.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(15, 'Anik', 'Das', '223215@imru.com', NULL, '$2y$10$b2GRrd7bipaIrosZeHA6aO41kGgTh6BNDStLv510VLLGLln1lsVNW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(16, 'Md. Belawal Hoque', 'Adib', '223216@imru.com', NULL, '$2y$10$qtD5JpN36J1wphSFQu2bF.shk8yoH/onhtbo1e0.uUmQ/ZXngReha', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:26', '2026-10-09 12:35:26', NULL),
(17, 'Rakibul Hasan', 'Patwary', '223217@imru.com', NULL, '$2y$10$yTc48wAWmDwEkBzZFLf8O.yamtS.T3lZeEijoifPRe5asXB1cRAry', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(18, 'Jaka', 'Soran', '223218@imru.com', NULL, '$2y$10$thpvOct9a/7y1cQgwJ4Tf.ifYRMfUk2Yu7V0j3xWqvyVjF5lcsnFG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(19, 'Mir Sabbir Rahman', 'Ridoy', '223219@imru.com', NULL, '$2y$10$ZmbwBTQVquMIxegxr9Q2/uF.5lokmYnMvmNVO.M3VzSrVZX.Ouxfm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(20, 'Md. Zehad Hasan', 'Maruf', '223220@imru.com', NULL, '$2y$10$5ltSNougdfLUlpC/lxe.T.Kjiy9eWQYQkBSGnXN2gmJSn/JPaf2C6', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(21, 'Sharmin Akter', 'Kanta', '223221@imru.com', NULL, '$2y$10$uUGR1xCbnECcsZ..mnKc2.bqpY452ZDLU340ztdgDEb15bfrTH0hK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(22, 'Kaniz', 'Sultana', '223222@imru.com', NULL, '$2y$10$/d2jRsuIsXwqxV5s0whjkuQDXRNtHMlmnw4mWW2kHUR0cBVAB6mKm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(23, 'Iskedaheer', 'Alam', '223223@imru.com', NULL, '$2y$10$9vHkaV.6v5rArf97S1MIluji5nYqJfleXLyhMnW.fTp1rHs8pGXty', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(24, 'Mithun', 'Acharjee', '223224@imru.com', NULL, '$2y$10$wMJiVbkcSZ4OHYax2.bvlueE9JY3Sdob0xHew8Y2pvEqy4APRvWXe', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(25, 'Hafizul Islam', 'Khan', '223225@imru.com', NULL, '$2y$10$XUFVVMNBq3hJLt7s33q/xONMx.C6b1d4dA6hBJS5P7OPRBeXYhH5.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(26, 'Md. Abdullah Al', 'Shahriar', '223226@imru.com', NULL, '$2y$10$uz8v4OiiwbF0ZMLtEwNJLuVKn3uik7EZOYpdGSJNRdNknRoWPZpNG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(27, 'Md. Anik', 'Kamal', '223227@imru.com', NULL, '$2y$10$mMCSJc8iVc42I5QeuTYaROVYW85paGrJHND6iUvbmHmFngKvbgxG6', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(28, 'Md. Mahabubur', 'Rahman', '223228@imru.com', NULL, '$2y$10$6/iBzUZP8HtHpkqM0Et0R.v7Wqx.QNpNMCugJGcFSZO8YR3aVrBUi', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(29, 'Md. Belal', 'Hossain', '223229@imru.com', NULL, '$2y$10$namHBxIR3gLVC5sSHn5ST.zfppULnvS1gfQk.R1Aq783Ch7m4BtxK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(30, 'Shuva', 'Barua', '223230@imru.com', NULL, '$2y$10$HRBKIhjj1kSSPduN1G.8z.e4PnyYuGL9LUfnRFe/Gg4/vKj5Ki1p.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(31, 'Md. Nahidul', 'Islam', '223231@imru.com', NULL, '$2y$10$RK.WyIrzGiYGZ3RjNH8/leXgR1H3blqM2.ufjSVG.6QuQIQSi5fB2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:27', '2026-10-09 12:35:27', NULL),
(32, 'Md. Sabbir', 'Ahamed', '243023@imru.com', NULL, '$2y$10$Rg99oQGNkwsjeMEK8fVCVu17pRNSADsts5ETLl2HvuvDNZ2/PCaku', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(33, 'Md. Bayzid Hasan', 'Bhuyan', '251001@imru.com', NULL, '$2y$10$nnGeRH655CNQBgv0e72VdONylIfg/mQ9R8E499P3dW01G38.YEfkG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(34, 'Md. Monir ', 'Hossain', '251002@imru.com', NULL, '$2y$10$sDkWi.OwC8slzzdQfJGSquThGPDXwPtJ6Dqw9ct0DVh43KVGSJ86S', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(35, 'Tasmima', 'Haque', '251003@imru.com', NULL, '$2y$10$z7iCgx/2sK0WIBjUDLWB8u0M3.CmqISkIaKB7BFLx/x4ugbbIWvZC', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(36, 'Md. Rafatuzzaman', 'Khan', '251004@imru.com', NULL, '$2y$10$t/efRgI/63/nDPZa9TjxCOa8ZsC3pyCqzpTw4NXGICWb2TGp30WA2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(37, 'Sahriar Hossen', 'Imran', '251005@imru.com', NULL, '$2y$10$kV7Gj8uMy3EjHJ1wEs1zTOyQ.EkkLxQPJUlpd2aAPlqAgASxsm/zS', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(38, 'Md. Wasik', 'Billah', '251006@imru.com', NULL, '$2y$10$L6RI6t97gd8/dFAV/7eqZO4Lb8VFj5ywoJ0zqZ9tl6mhU1qEgtGYO', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(39, 'Shirina', 'Khatun', '251007@imru.com', NULL, '$2y$10$zRsSV9ikMOYAd2peyPZeYOozel7QcyJ8niv70fq/2XbRHoQAEmDui', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(40, 'Rokibul', 'Hasan', '251008@imru.com', NULL, '$2y$10$ThR7TWFZxgkSq.sIrgcItOWD4iyE3CviBcgrCyMww47rfmnp9jWJW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(41, 'Md. Salim', 'Uddin', '251009@imru.com', NULL, '$2y$10$bRY2VsdZXAqcmwI6xlgPmuZ7/vLko3BeDgAiEl955c6PkLaV/zqI2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(42, 'Minhazur', 'Rahaman', '251010@imru.com', NULL, '$2y$10$C7mg5xtgkJwoulmHswjXf.d4LmatOjk6H6Q0gDA/xxBfKxMIz11V2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(43, 'Md. Mirajul Islam', 'Tashfi', '251011@imru.com', NULL, '$2y$10$DfnXgGsiXgOdCMaxoMeYUOj88CI/xT.SK8.KUZegy4ean6hxCCmtG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(44, 'Anoy', 'Podder', '251012@imru.com', NULL, '$2y$10$mjfgAZy1.vK1Ix.cUrdSL.hurVoyruMsp8.dEqsf9c4ZKHZ9e6QZm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(45, 'Md. Kamrul', 'Islam', '251013@imru.com', NULL, '$2y$10$2mKW6vw7DJKgqQVUMbtNmueCH6Gv/NF0/FhmtAV5S6c2.Nt.ASswW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:28', '2026-10-09 12:35:28', NULL),
(46, 'Rahmat', 'Ullah', '251014@imru.com', NULL, '$2y$10$LtJzO0AquWw9x2PdEtHImeb7SyavHzEIBzGe2ma/jws4EAloUBGzq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(47, 'Sinthia', 'Mamtaz', '251015@imru.com', NULL, '$2y$10$3P7hi9zLDtwsa3VsLKQNt.ZZfPcAEAPw68WZccd2vPsK7N3sU7VWq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(48, 'Mobasshir', 'Kaisar', '251016@imru.com', NULL, '$2y$10$XgtFECSUSOqbUSK4OMDBPeAkJuQyQw60wi0OQKbbaPgmh7TsrYRVK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(49, 'Tahsin', 'Alam', '251017@imru.com', NULL, '$2y$10$RRmftHGmamS2BmYHIsPU1.q9F1zjlxmWQNOzuZUvWlSW9AEUMZIiG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(50, 'Amio', 'Ghosh', '251018@imru.com', NULL, '$2y$10$NiSFLEuamGX1yqnQC/MvPOTDf5np7JvXJgU4IAEpIcR5s9JeEZxPm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(51, 'Md. Zubaidur Rahman', 'Bagmar', '251019@imru.com', NULL, '$2y$10$KTtJOdVsOz9eYQcIH7HdfezdDK92ULF8GVivF9QD1RE0bJugFPxMe', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(52, 'Md. Jahidul Islam', 'Maruf', '251020@imru.com', NULL, '$2y$10$iUMk4.5.N/Je8xmkfZNCHuPqxYQW/WzB.n6sJFcEsT/C2iYhDxQHq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(53, 'K M Abdulla Al', 'Mamun', '251021@imru.com', NULL, '$2y$10$xMum3JZ2dHotPhrisJTWBuCHnW4a8jW00y.rKtL4Mqk6IeURFXvla', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(54, 'Kazi Rezaul', 'Karim', '251022@imru.com', NULL, '$2y$10$BGPkTDmjyo1Oq09TW8SBfewT7R0I1m5kmrloQmsGLvJc8DKGgTSTK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(55, 'Muhammad Raisul', 'Islam', '251023@imru.com', NULL, '$2y$10$n230K/uiZoMHOI8toCb1tu3Oba/SnNTiEIe82uI7Gz1ifKqx3JMQK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(56, 'Piash Kumar', 'Das', '251024@imru.com', NULL, '$2y$10$ueubtfXWG9XeryCifUBa6O5WfyEogJ4RhLbLd9AMPv4mpxbY5FXkS', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(57, 'Minhaz', 'Uddin', '251025@imru.com', NULL, '$2y$10$njpXYFMEopIy0nleWzFqHOICtdfgbuUZ7UWykps/a8BaPGlW6kW2K', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(58, 'Rounok Jahan', 'Priya', '251026@imru.com', NULL, '$2y$10$ed/02Vj9A5PEXD/25YHcl.IT6MSPDRGLAv3pWmEwfi4LtoBvlwcQ.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(59, 'Al-Amin Muhammad Murtaja', 'Ullah', '251027@imru.com', NULL, '$2y$10$Yfcd0yF9stVaTmHXA9Tb5OXyyeO2C7TcQZ6HSqLcUuYHpLki24yhy', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(60, 'Mashaba', 'Nawrin', '251028@imru.com', NULL, '$2y$10$0YDiJR64rAcJ0B2cXyxio.erpM4KD942urbwNP.Oz0JExmOSu9Lde', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:29', '2026-10-09 12:35:29', NULL),
(61, 'Md. Alvi', 'Nirob', '251029@imru.com', NULL, '$2y$10$qnrsN0BiF5wlR0WxuKey1.3ObhS41kBJH87nZWsCj/RATx.3osz2W', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(62, 'Md. Amanullah', 'Rafi', '251030@imru.com', NULL, '$2y$10$THWn.XE5yMgb.AxxvR7kDe3aeItLe4ihgQfRXLrMZ6NvgWsvkdYzq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(63, 'Zarin', 'Akter', '251031@imru.com', NULL, '$2y$10$nHYlHwqzvJrDCpSCOjSTke.X6qb2Ew2/uLk0CaReS/EvOYNfm0HFW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(64, 'Tanvir', 'Rahman', '251032@imru.com', NULL, '$2y$10$EYs70Z6icO8F5Dze486jd.keepSrjrHS6EijKx464HzFkizWIbzEe', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(65, 'Rafi Al', 'Adnan', '251033@imru.com', NULL, '$2y$10$LAgMScXP7MVaN83KnCQ.I.kvFsstiiG328Gi9NDRgzaQDV2FF8T8m', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(66, 'Md. Imam', 'Hosain', '251034@imru.com', NULL, '$2y$10$eOUkmzCXrBrf91CkwEK1LuWae31ePUw5H6.iNgrrToaYOgffXGRna', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(67, 'Farid', 'Ahmed', '251035@imru.com', NULL, '$2y$10$2Jw88RbvRRI1ol1WdwI8uuh4G9Il8.lSy7s8dNb7O.aXWyyGZDdcu', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(68, 'Md. Arosh', 'Prodhen', '251036@imru.com', NULL, '$2y$10$kwBpkfHyfMz4f2wZCCrcyuRf/xFVciCJJZuH2I1Lf2Iz2ZFL9GkAa', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(69, 'S. M. Shahidul', 'Alam', '251037@imru.com', NULL, '$2y$10$AqepZoNf59NVcqRa5He/BuN/2H4vtSfTv8YvsK1eRqBBLjFhnwb5q', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(70, 'Md. Monir', 'Alam', '251038@imru.com', NULL, '$2y$10$O7UvrRCreC.Lcg/RvgfkFupsxEXmum3Fmtit5hKziCYKUFB/sjeGC', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(71, 'Abu Naiim Md. Rayhan', 'Siddique', '251039@imru.com', NULL, '$2y$10$.n7yFybvhyKahG6Nu3DrzOn0NlQiy4pz1Q5UYy.rAL3yyfDfA30Y.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(72, 'Mashiur', 'Rahman', '251040@imru.com', NULL, '$2y$10$h7yteePt1k/23cJtL8aEJ.rf/d8P42Qg1YvmCBpaNOzDmAGLU8Sge', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(73, 'Md. Rakibul', 'Islam', '251041@imru.com', NULL, '$2y$10$uKuGiy3uZuUB/tMdZ/wNmubxwH8AH/1cQx8k.Nlcx5c6y9GNEIYRa', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(74, 'Farjana Akther', 'Hima', '251042@imru.com', NULL, '$2y$10$WJ9I2Zun7Ijzr69auz3m2O01wH5CcM9kCFmQXxSQpfBhKOgXR4jSu', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:30', '2026-10-09 12:35:30', NULL),
(75, 'Md. Muhaimin Islam', 'Tanvir', '251043@imru.com', NULL, '$2y$10$I4L7t6VIZebor/PKrbndVuUGq1yxZb39yYhR7JBH1aJcZWvgbmaDi', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(76, 'Zafrin', 'Chowdhury', '251044@imru.com', NULL, '$2y$10$ZUjO7H.3eg8u/pvcWeUzT.7NVb5R95Tatiz5v1wA.V6LZZY1GMQCu', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(77, 'Md. Tuhinur Rahman', 'Tuhin', '251045@imru.com', NULL, '$2y$10$rQfGYrkBwqYK8GuXVBqIYesvHmP8YiJ3Rpa/2NDx6gx4y4mA8mX5m', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(78, 'Mahmudul Hassan', 'Shihab', '251046@imru.com', NULL, '$2y$10$U1m2ZU0dZUlgQDf088o76eEAX93dLphQI2I7LlOu0NhR.cR5IQfpe', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(79, 'Mahmudur', 'Rahman', '251047@imru.com', NULL, '$2y$10$tcHnW1p8MOfusdop6Du.TuwpaWCO3pzdqTFBO9GsbZ5CmhiwxYv2a', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(80, 'Hosne Ara', 'Bithi', '251048@imru.com', NULL, '$2y$10$N/Y1D3EnZzm3dzr8HGGOweRKtrbgz.npyuR9NkzjqnH9qFyyRFIT6', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(81, 'Razown Ahamed', 'Sovuz', '251049@imru.com', NULL, '$2y$10$W.Z2ev8SBq6ogB/TTYJXZ.ZRz4GKRRH.0xCglwGRnjV5qOJdXwTTm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(82, 'Md. Masum', 'Pramanik', '251050@imru.com', NULL, '$2y$10$LWiaaexkk7/DbnyLAufOHuNib/cX7WDQ/Kx.KACLFwAQZpdlHmvt.', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(83, 'Amir', 'Hossain', '251051@imru.com', NULL, '$2y$10$sG1KjxhPB6jGctn7VSPaNuCfjSmORXGjUr/y7LTrgj9g4HqitwIW2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(84, 'Fouzia Akter', 'Rifa', '251052@imru.com', NULL, '$2y$10$yarbjtTzCphiiTDvOrJS3.WNSNInBJJzRkurIgK3oolFtr9HJrlXO', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(85, 'M. Neehal', 'Sharif', '251053@imru.com', NULL, '$2y$10$9uZGxJXi4UKfPkDoY6y7wevBWbSeT4NPv0YHNzJXvpWfnAwuEBbfq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(86, 'Md. Shaon', 'Khalifa', '251054@imru.com', NULL, '$2y$10$/Mc41c/g3ouxa8V/TA.xs.2IQHzleRgwTqc/ik0H5np5kmv/j3uXK', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(87, 'Romjan', 'Ali', '251055@imru.com', NULL, '$2y$10$YQvSnZ/QhFfG3yB7iDS3BeNJQvRQwSqxMrx04SV8f3zDgSMuiXrsm', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(88, 'Ratul', 'Biswas', '251056@imru.com', NULL, '$2y$10$hOw6Xxt1jdRpdHsxa9Kg8.E1.Pu.6z95tLerA4VFYDPpTsA0QpY02', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(89, 'S. M. Sohanur', 'Khan', '251057@imru.com', NULL, '$2y$10$u1wMB6Zxlc6jg9SPM1Sp4.pXD5By5i4ZZMl2lf6pqR9QJXWwKRHnG', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:31', '2026-10-09 12:35:31', NULL),
(90, 'Md. Alimul Islam', 'Imon', '251058@imru.com', NULL, '$2y$10$fkHbiHj8T0qKLrc.hd3qM.TDEuzSgEXtvSMos4I2p820yO.w9EsIW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(91, 'Arpita', 'Basak', '251059@imru.com', NULL, '$2y$10$pf6KptELgGehJ75et2EJbeqsi3pHjKM57vyT9/8LDZpzV8VQQEi3a', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(92, 'Md. Tariqul', 'Islam', '251060@imru.com', NULL, '$2y$10$bhaPv4aE7VfHzPhI2q02/O38JlDtFGlmzCmd78KgJlFj2IcQJh1g2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(93, 'Abdullah Al', 'Afraaz', '251061@imru.com', NULL, '$2y$10$c1ukTLewapOvjB05FCydnO.vI8YalDZkiFGvWOprwAVyxWJaPf/Mq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(94, 'Sadia', 'Islam', '251062@imru.com', NULL, '$2y$10$XFEEy9yhupSYbOaFTQgK8ekSQ5jzKZ84.MemvYxuRK11VOcUk7uxu', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(95, 'Aysha', 'Siddeka', '251063@imru.com', NULL, '$2y$10$.2Q.MH4aR/iuaGhXZaFCB.l23XyqeDtowpyRImYXNLkDq4RRXyBNa', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(96, 'Rakibul', 'Hasib', '251064@imru.com', NULL, '$2y$10$DgrhdJJlr7wXmlcg9pqnZOWOczLL3HUu6JNYQp9V.NWhv2kjkyv7e', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(97, 'Fahim', 'Ahmed', '251065@imru.com', NULL, '$2y$10$lK7zWQnclBPk2C3iSplOPOQVYtE05gYvPPnyW.GqU9VPqH001xg1y', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(98, 'S.M. Ferdous', 'Azad', '251066@imru.com', NULL, '$2y$10$VXAP0x/2YrNq55isPKF/Lev4BOUN8qXVg0nebvlD2UdoTGSrdV2Ye', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(99, 'Muhammad', 'Mahdi', '251067@imru.com', NULL, '$2y$10$dq1gCKwIz.HXuPTM59olEO.Lmu6O4zqV.ED3/iGlZpR64AEHhVAGy', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL),
(100, 'Nilufa', 'Yesmin', '251068@imru.com', NULL, '$2y$10$r8X95VPMUQbxdyEFEAlQre93Vsrjal0YH29pf2.tQ74L33QrcUgkq', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:32', '2026-10-09 12:35:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_infos`
--

CREATE TABLE `student_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `student_id_no` varchar(255) DEFAULT NULL COMMENT 'Academic student ID',
  `session` varchar(255) DEFAULT NULL COMMENT 'e.g. 2021-2022',
  `batch` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL COMMENT 'BSc, MSc, PhD',
  `admission_date` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL COMMENT 'male, female, other',
  `dob` date DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL COMMENT 'A+, A-, B+, B-, AB+, AB-, O+, O-',
  `religion` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT 'Bangladeshi',
  `marital_status` varchar(255) DEFAULT NULL COMMENT 'single, married, divorced, widowed',
  `nid_number` varchar(255) DEFAULT NULL,
  `birth_certificate_no` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relation` varchar(255) DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT 'Bangladesh',
  `profile_photo` varchar(255) DEFAULT NULL,
  `bio` longtext DEFAULT NULL,
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=Inactive, 1=Active',
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `remember_token`, `is_active`, `aca_created_by`, `aca_updated_by`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dr. Shamim Al', 'Mamun', 'shamim@juniv.edu', NULL, '$2y$10$4pJNWKk4zRisnY7fVWc0POfdUsZ/wmAf8G07bimdAw6u4IXvu4Hc6', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(2, 'Dr. M. Shamim', 'Kaiser', 'mskaiser@juniv.edu', NULL, '$2y$10$rPuRSDIrv5qkD0TFUvVEs.JQuLEZyFQWSGCe.4TwCADCDTd11tvzW', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(3, 'Dr. Risala Tasin', 'Khan', 'risala@juniv.edu', NULL, '$2y$10$88VJLOMs0eePln5JLe7vqenJZhCL01OZyekNmS6s55vTp3Z82LcCi', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(4, 'K M Akkas', 'Ali', 'akkas@juniv.edu', NULL, '$2y$10$X8.Uxo7XEWs0wINKsNSFaOIjUdnMMvQk1/1BKYWbIh1uXl5Dl.a9a', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL),
(5, 'Md. Fazlul Karim', 'Patwary', 'patwary@juniv.edu', NULL, '$2y$10$VXl2r7Bo4WwWCAe2c0GRTuAzkw.p8w0PJulv4qad90Wc7iBC4AN.2', NULL, 1, NULL, NULL, NULL, NULL, '2026-10-09 12:35:25', '2026-10-09 12:35:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_infos`
--

CREATE TABLE `teacher_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id_no` varchar(255) DEFAULT NULL COMMENT 'Academic teacher ID',
  `designation` varchar(255) DEFAULT NULL COMMENT 'e.g. Professor, Associate Professor, Lecturer',
  `department` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL COMMENT 'e.g. Machine Learning, Data Science',
  `qualification` varchar(255) DEFAULT NULL COMMENT 'e.g. PhD, MSc, BSc',
  `joining_date` varchar(255) DEFAULT NULL,
  `experience_years` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL COMMENT 'male, female, other',
  `dob` date DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL COMMENT 'A+, A-, B+, B-, AB+, AB-, O+, O-',
  `religion` varchar(255) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT 'Bangladeshi',
  `marital_status` varchar(255) DEFAULT NULL COMMENT 'single, married, divorced, widowed',
  `nid_number` varchar(255) DEFAULT NULL,
  `birth_certificate_no` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `emergency_contact_relation` varchar(255) DEFAULT NULL,
  `present_address` varchar(255) DEFAULT NULL,
  `permanent_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT 'Bangladesh',
  `profile_photo` varchar(255) DEFAULT NULL,
  `bio` longtext DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `google_scholar` varchar(255) DEFAULT NULL,
  `researchgate` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `aca_created_by` varchar(255) DEFAULT NULL,
  `aca_updated_by` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aca_courses`
--
ALTER TABLE `aca_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_courses_teacher_id_foreign` (`teacher_id`);

--
-- Indexes for table `aca_enrollments`
--
ALTER TABLE `aca_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aca_enrollments_course_id_student_id_unique` (`course_id`,`student_id`),
  ADD KEY `aca_enrollments_student_id_foreign` (`student_id`);

--
-- Indexes for table `aca_exams`
--
ALTER TABLE `aca_exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aca_exams_exam_code_unique` (`exam_code`),
  ADD KEY `aca_exams_course_id_foreign` (`course_id`);

--
-- Indexes for table `aca_exam_answers`
--
ALTER TABLE `aca_exam_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_answers_student_id_foreign` (`student_id`),
  ADD KEY `aca_exam_answers_exam_id_foreign` (`exam_id`),
  ADD KEY `aca_exam_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `aca_exam_attempts`
--
ALTER TABLE `aca_exam_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_attempts_student_id_foreign` (`student_id`),
  ADD KEY `aca_exam_attempts_exam_id_foreign` (`exam_id`);

--
-- Indexes for table `aca_exam_clipboard_logs`
--
ALTER TABLE `aca_exam_clipboard_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_clipboard_logs_attempt_id_foreign` (`attempt_id`);

--
-- Indexes for table `aca_exam_proctoring_events`
--
ALTER TABLE `aca_exam_proctoring_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_proctoring_events_attempt_id_foreign` (`attempt_id`);

--
-- Indexes for table `aca_exam_results`
--
ALTER TABLE `aca_exam_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aca_exam_results_student_id_exam_id_unique` (`student_id`,`exam_id`),
  ADD KEY `aca_exam_results_exam_id_foreign` (`exam_id`);

--
-- Indexes for table `aca_exam_rules`
--
ALTER TABLE `aca_exam_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aca_exam_rules_key_unique` (`key`);

--
-- Indexes for table `aca_exam_rule_maps`
--
ALTER TABLE `aca_exam_rule_maps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_rule_maps_exam_id_foreign` (`exam_id`),
  ADD KEY `aca_exam_rule_maps_rule_id_foreign` (`rule_id`);

--
-- Indexes for table `aca_exam_sets`
--
ALTER TABLE `aca_exam_sets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aca_exam_tab_switch_logs`
--
ALTER TABLE `aca_exam_tab_switch_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_tab_switch_logs_attempt_id_foreign` (`attempt_id`);

--
-- Indexes for table `aca_exam_webcam_logs`
--
ALTER TABLE `aca_exam_webcam_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_exam_webcam_logs_attempt_id_foreign` (`attempt_id`);

--
-- Indexes for table `aca_questions`
--
ALTER TABLE `aca_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_questions_exam_id_foreign` (`exam_id`);

--
-- Indexes for table `aca_question_libraries`
--
ALTER TABLE `aca_question_libraries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aca_review_answers`
--
ALTER TABLE `aca_review_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aca_review_answers_exam_answers_id_foreign` (`exam_answers_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidates_username_unique` (`username`),
  ADD UNIQUE KEY `candidates_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `recruiters`
--
ALTER TABLE `recruiters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recruiters_email_unique` (`email`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_email_unique` (`email`);

--
-- Indexes for table `student_infos`
--
ALTER TABLE `student_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_infos_student_id_no_unique` (`student_id_no`),
  ADD UNIQUE KEY `student_infos_nid_number_unique` (`nid_number`),
  ADD KEY `student_infos_student_id_foreign` (`student_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_email_unique` (`email`);

--
-- Indexes for table `teacher_infos`
--
ALTER TABLE `teacher_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_infos_teacher_id_no_unique` (`teacher_id_no`),
  ADD UNIQUE KEY `teacher_infos_nid_number_unique` (`nid_number`),
  ADD KEY `teacher_infos_teacher_id_foreign` (`teacher_id`);

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
-- AUTO_INCREMENT for table `aca_courses`
--
ALTER TABLE `aca_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `aca_enrollments`
--
ALTER TABLE `aca_enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exams`
--
ALTER TABLE `aca_exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_answers`
--
ALTER TABLE `aca_exam_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_attempts`
--
ALTER TABLE `aca_exam_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_clipboard_logs`
--
ALTER TABLE `aca_exam_clipboard_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_proctoring_events`
--
ALTER TABLE `aca_exam_proctoring_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_results`
--
ALTER TABLE `aca_exam_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_rules`
--
ALTER TABLE `aca_exam_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `aca_exam_rule_maps`
--
ALTER TABLE `aca_exam_rule_maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_sets`
--
ALTER TABLE `aca_exam_sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_tab_switch_logs`
--
ALTER TABLE `aca_exam_tab_switch_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_exam_webcam_logs`
--
ALTER TABLE `aca_exam_webcam_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_questions`
--
ALTER TABLE `aca_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aca_question_libraries`
--
ALTER TABLE `aca_question_libraries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `aca_review_answers`
--
ALTER TABLE `aca_review_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruiters`
--
ALTER TABLE `recruiters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `student_infos`
--
ALTER TABLE `student_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `teacher_infos`
--
ALTER TABLE `teacher_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aca_courses`
--
ALTER TABLE `aca_courses`
  ADD CONSTRAINT `aca_courses_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `aca_enrollments`
--
ALTER TABLE `aca_enrollments`
  ADD CONSTRAINT `aca_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `aca_courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_enrollments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exams`
--
ALTER TABLE `aca_exams`
  ADD CONSTRAINT `aca_exams_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `aca_courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_answers`
--
ALTER TABLE `aca_exam_answers`
  ADD CONSTRAINT `aca_exam_answers_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `aca_exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_exam_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `aca_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_exam_answers_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_attempts`
--
ALTER TABLE `aca_exam_attempts`
  ADD CONSTRAINT `aca_exam_attempts_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `aca_exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_exam_attempts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_clipboard_logs`
--
ALTER TABLE `aca_exam_clipboard_logs`
  ADD CONSTRAINT `aca_exam_clipboard_logs_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `aca_exam_attempts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_proctoring_events`
--
ALTER TABLE `aca_exam_proctoring_events`
  ADD CONSTRAINT `aca_exam_proctoring_events_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `aca_exam_attempts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_results`
--
ALTER TABLE `aca_exam_results`
  ADD CONSTRAINT `aca_exam_results_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `aca_exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_exam_results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_rule_maps`
--
ALTER TABLE `aca_exam_rule_maps`
  ADD CONSTRAINT `aca_exam_rule_maps_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `aca_exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aca_exam_rule_maps_rule_id_foreign` FOREIGN KEY (`rule_id`) REFERENCES `aca_exam_rules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_tab_switch_logs`
--
ALTER TABLE `aca_exam_tab_switch_logs`
  ADD CONSTRAINT `aca_exam_tab_switch_logs_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `aca_exam_attempts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_exam_webcam_logs`
--
ALTER TABLE `aca_exam_webcam_logs`
  ADD CONSTRAINT `aca_exam_webcam_logs_attempt_id_foreign` FOREIGN KEY (`attempt_id`) REFERENCES `aca_exam_attempts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_questions`
--
ALTER TABLE `aca_questions`
  ADD CONSTRAINT `aca_questions_exam_id_foreign` FOREIGN KEY (`exam_id`) REFERENCES `aca_exams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aca_review_answers`
--
ALTER TABLE `aca_review_answers`
  ADD CONSTRAINT `aca_review_answers_exam_answers_id_foreign` FOREIGN KEY (`exam_answers_id`) REFERENCES `aca_exam_answers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_infos`
--
ALTER TABLE `student_infos`
  ADD CONSTRAINT `student_infos_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_infos`
--
ALTER TABLE `teacher_infos`
  ADD CONSTRAINT `teacher_infos_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

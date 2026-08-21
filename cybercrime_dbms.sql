-- Cyber Crime Management System - Public Demo Database
-- Safe demo data only. No real user records or personal information.

CREATE DATABASE IF NOT EXISTS `cybercrime_dbms`;
USE `cybercrime_dbms`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `audit_log`, `evidence`, `reports`, `victims`, `suspects`, `cases`, `users`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Officer','Administrator') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cases` (
  `case_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Open','Under Investigation','Closed') DEFAULT 'Open',
  `date_reported` timestamp NOT NULL DEFAULT current_timestamp(),
  `officer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`case_id`),
  KEY `officer_id` (`officer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `evidence` (
  `evidence_id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`evidence_id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) DEFAULT NULL,
  `report_details` text DEFAULT NULL,
  `officer_id` int(11) DEFAULT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`report_id`),
  KEY `case_id` (`case_id`),
  KEY `officer_id` (`officer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `suspects` (
  `suspect_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `address` text DEFAULT NULL,
  `crime_associated` int(11) DEFAULT NULL,
  PRIMARY KEY (`suspect_id`),
  KEY `crime_associated` (`crime_associated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `victims` (
  `victim_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `case_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`victim_id`),
  KEY `case_id` (`case_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Demo accounts. Password for both accounts: Demo@123
INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1001, 'Demo Admin', 'admin@example.com', '$2y$12$vxvolreOLWMhyDjVPR3XF.SKm7RN6dqc6XU7FwAbF0TlDTcd39WCa', 'Administrator'),
(1002, 'Demo Officer', 'officer@example.com', '$2y$12$vxvolreOLWMhyDjVPR3XF.SKm7RN6dqc6XU7FwAbF0TlDTcd39WCa', 'Officer');

INSERT INTO `cases` (`case_id`, `title`, `description`, `status`, `date_reported`, `officer_id`) VALUES
(1, 'Online Banking Fraud', 'Demo case involving unauthorized online banking transactions.', 'Under Investigation', '2025-04-01 10:00:00', 1002),
(2, 'Fraudulent Online Store', 'Demo case involving a fictitious online store that accepted payments without delivering goods.', 'Open', '2025-04-05 11:30:00', 1002);

INSERT INTO `victims` (`victim_id`, `name`, `age`, `gender`, `contact`, `address`, `case_id`) VALUES
(1, 'Demo Victim', 30, 'Other', '9000000000', 'Demo Address', 1);

INSERT INTO `suspects` (`suspect_id`, `name`, `age`, `gender`, `address`, `crime_associated`) VALUES
(1, 'Demo Suspect', 28, 'Other', 'Demo Address', 1);

INSERT INTO `reports` (`report_id`, `case_id`, `report_details`, `officer_id`, `report_date`) VALUES
(1, 1, 'Demo investigation report for the online banking fraud case. This record is fictional and included only for demonstration.', 1002, '2025-04-02 12:00:00');

INSERT INTO `evidence` (`evidence_id`, `case_id`, `description`, `file_path`, `upload_date`) VALUES
(1, 1, 'Demo evidence record. No real file or personal information is included.', NULL, '2025-04-02 12:30:00');

INSERT INTO `audit_log` (`log_id`, `user_id`, `action`, `timestamp`) VALUES
(1, 1001, 'Demo login', '2025-04-02 09:00:00'),
(2, 1002, 'Viewed cases', '2025-04-02 09:05:00');

ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

ALTER TABLE `evidence`
  ADD CONSTRAINT `evidence_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE;

ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

ALTER TABLE `suspects`
  ADD CONSTRAINT `suspects_ibfk_1` FOREIGN KEY (`crime_associated`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE;

ALTER TABLE `victims`
  ADD CONSTRAINT `victims_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`case_id`) ON DELETE CASCADE;

ALTER TABLE `users` AUTO_INCREMENT = 1003;
ALTER TABLE `cases` AUTO_INCREMENT = 3;
ALTER TABLE `evidence` AUTO_INCREMENT = 2;
ALTER TABLE `reports` AUTO_INCREMENT = 2;
ALTER TABLE `suspects` AUTO_INCREMENT = 2;
ALTER TABLE `victims` AUTO_INCREMENT = 2;
ALTER TABLE `audit_log` AUTO_INCREMENT = 3;

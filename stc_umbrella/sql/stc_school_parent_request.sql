-- Public parent enquiries / requests — run once on the school DB (same as MCU/db.php).
-- Example: mysql -u USER -p stc_associate_go < sql/stc_school_parent_request.sql

CREATE TABLE IF NOT EXISTS `stc_school_parent_request` (
  `stc_school_parent_request_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `stc_school_parent_request_parent_name` VARCHAR(160) NOT NULL,
  `stc_school_parent_request_email` VARCHAR(190) NOT NULL,
  `stc_school_parent_request_phone` VARCHAR(40) NOT NULL,
  `stc_school_parent_request_student_name` VARCHAR(160) NOT NULL DEFAULT '',
  `stc_school_parent_request_student_id` VARCHAR(64) NOT NULL DEFAULT '',
  `stc_school_parent_request_subject` VARCHAR(255) NOT NULL,
  `stc_school_parent_request_message` TEXT NOT NULL,
  `stc_school_parent_request_action_taken` TEXT NULL,
  `stc_school_parent_request_status` ENUM('new','read','closed') NOT NULL DEFAULT 'new',
  `stc_school_parent_request_createdate` DATETIME NOT NULL,
  PRIMARY KEY (`stc_school_parent_request_id`),
  KEY `idx_status_createdate` (`stc_school_parent_request_status`,`stc_school_parent_request_createdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

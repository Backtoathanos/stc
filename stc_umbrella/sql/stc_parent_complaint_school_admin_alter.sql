-- ============================================================
-- Migration: add school-admin workflow columns to
--            stc_school_parent_request
-- Run once on the database used by MCU/db.php.
-- Safe to re-run (IF NOT EXISTS guard per column).
-- ============================================================

ALTER TABLE `stc_school_parent_request`
  ADD COLUMN IF NOT EXISTS `stc_school_parent_request_passed_to_school`
        TINYINT(1) NOT NULL DEFAULT 0
        COMMENT '1 = director forwarded to school admin',

  ADD COLUMN IF NOT EXISTS `stc_school_parent_request_passed_date`
        DATETIME NULL DEFAULT NULL
        COMMENT 'When director clicked Pass to School Admin',

  ADD COLUMN IF NOT EXISTS `stc_school_parent_request_school_note`
        TEXT NULL DEFAULT NULL
        COMMENT 'Resolution written by school admin',

  ADD COLUMN IF NOT EXISTS `stc_school_parent_request_school_status`
        VARCHAR(30) NOT NULL DEFAULT ''
        COMMENT 'resolved = school admin marked it resolved',

  ADD COLUMN IF NOT EXISTS `stc_school_parent_request_school_resolved_date`
        DATETIME NULL DEFAULT NULL
        COMMENT 'When school admin marked resolved';

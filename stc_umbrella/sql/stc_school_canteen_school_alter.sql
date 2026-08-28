-- ============================================================
-- Migration: add school column to stc_school_canteen
-- Values: SIS, SMS, SGMS, SHS
-- Safe to re-run (IF NOT EXISTS).
-- ============================================================

ALTER TABLE `stc_school_canteen`
  ADD COLUMN IF NOT EXISTS `stc_school_canteen_school`
    VARCHAR(10) NOT NULL DEFAULT ''
    COMMENT 'School code: SIS, SMS, SGMS, SHS'
    AFTER `stc_school_canteen_date`;

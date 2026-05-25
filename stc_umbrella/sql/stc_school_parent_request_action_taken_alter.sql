-- Add staff "action taken" text for parent requests (run on existing DBs after base table exists).
-- Example: mysql -u USER -p stc_associate_go < sql/stc_school_parent_request_action_taken_alter.sql

ALTER TABLE `stc_school_parent_request`
  ADD COLUMN `stc_school_parent_request_action_taken` TEXT NULL
  AFTER `stc_school_parent_request_message`;

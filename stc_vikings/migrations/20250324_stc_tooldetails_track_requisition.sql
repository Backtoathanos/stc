-- Links Tools Track (stc_tooldetails_track) to daily requisition dispatch.
-- Run once on the application database (e.g. via phpMyAdmin or mysql CLI).

ALTER TABLE `stc_tooldetails_track`
  ADD COLUMN `req_requisition_list_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'stc_cust_super_requisition_list header id' AFTER `id_type`,
  ADD COLUMN `req_requisition_item_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'stc_cust_super_requisition_list_items line id' AFTER `req_requisition_list_id`,
  ADD COLUMN `req_poa_adhoc_id` INT UNSIGNED NULL DEFAULT NULL COMMENT 'stc_purchase_product_adhoc_id (PPO line)' AFTER `req_requisition_item_id`;

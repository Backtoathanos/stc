-- Customer challan TO-address config (replaces hardcoded map in verify-challan-customer.php)
CREATE TABLE IF NOT EXISTS `stc_challan_customer_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL DEFAULT 0 COMMENT 'FK stc_cust_project.stc_cust_project_id (0 = site-key only)',
  `site_key` varchar(160) NOT NULL DEFAULT '' COMMENT 'Challan site label e.g. TSL AMC',
  `addresse` varchar(255) NOT NULL DEFAULT '',
  `sitenamecity` varchar(500) NOT NULL DEFAULT '',
  `gatename` varchar(255) NOT NULL DEFAULT '',
  `createdby` int(11) NOT NULL DEFAULT 0,
  `createddate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `site_key` (`site_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed site-key rows (project_id=0). Link specific projects later by updating project_id.
INSERT INTO `stc_challan_customer_address`
  (`project_id`, `site_key`, `addresse`, `sitenamecity`, `gatename`, `createdby`, `createddate`)
SELECT 0, v.site_key, v.addresse, v.sitenamecity, v.gatename, 0, NOW()
FROM (
  SELECT 'TSL AMC' AS site_key, 'The Head Security Work' AS addresse, 'TATA STEEL JAMSHEDPUR' AS sitenamecity, 'JMD GATE' AS gatename
  UNION ALL SELECT 'TATA STEEL AMC', 'The Head Security Work', 'TATA STEEL JAMSHEDPUR', 'JMD GATE'
  UNION ALL SELECT 'TINPLATE', 'The Head Security Work', 'TATA STEEL TINPLATE DIVISION', ''
  UNION ALL SELECT 'BF RELINING & TSG GAMHARIA & OLD GAMHARIA', 'The Head Security Work', 'TATA STEEL GAMHARIA', 'GAMHARIA'
  UNION ALL SELECT 'AMC OF TSG Gamharia', 'The Head Security Work', 'TATA STEEL GAMHARIA', 'GAMHARIA'
  UNION ALL SELECT 'GOLMURI SUBSTATION', 'The Head Security Work', 'GOLMURI SUBSTATION HVAC PROJECT', 'GOLMURI JAMSHEDPUR'
  UNION ALL SELECT 'O&M', 'The Head Security Work', CONCAT('ECR building of COB#6A&6B', CHAR(10), 'TATA STEEL JAMSHEDPUR'), ''
  UNION ALL SELECT 'XLRI', 'The Head Security Work', 'XLRI, JAMSHEDPUR', ''
  UNION ALL SELECT 'COKE OVEN', 'The Head Security Work', 'TATA STEEL LTD. JSR.', 'JMD GATE'
) v
WHERE NOT EXISTS (
  SELECT 1 FROM `stc_challan_customer_address` a
  WHERE UPPER(TRIM(a.`site_key`)) = UPPER(TRIM(v.site_key))
);

-- Also attach matching projects (by title) so project_id join works
INSERT INTO `stc_challan_customer_address`
  (`project_id`, `site_key`, `addresse`, `sitenamecity`, `gatename`, `createdby`, `createddate`)
SELECT p.`stc_cust_project_id`, a.`site_key`, a.`addresse`, a.`sitenamecity`, a.`gatename`, 0, NOW()
FROM `stc_challan_customer_address` a
INNER JOIN `stc_cust_project` p
  ON UPPER(p.`stc_cust_project_title`) LIKE CONCAT('%', UPPER(a.`site_key`), '%')
WHERE a.`project_id` = 0
  AND a.`site_key` <> ''
  AND NOT EXISTS (
    SELECT 1 FROM `stc_challan_customer_address` x
    WHERE x.`project_id` = p.`stc_cust_project_id`
      AND UPPER(TRIM(x.`site_key`)) = UPPER(TRIM(a.`site_key`))
  );

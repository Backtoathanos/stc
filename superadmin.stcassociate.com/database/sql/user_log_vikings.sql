CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_type` varchar(32) NOT NULL,
  `user_id` int NOT NULL,
  `log` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint NOT NULL DEFAULT 1,
  `created_by` int NOT NULL DEFAULT 1,
  `updated_by` int DEFAULT NULL,
  `updated_date` date DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_log_user` (`user_type`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user_log` (`user_type`, `user_id`, `log`, `date`, `status`, `created_by`, `updated_by`, `updated_date`, `ip_address`)
SELECT x.`user_type`, x.`user_id`, x.`log`, x.`date`, x.`status`, x.`created_by`, x.`updated_by`, x.`updated_date`, x.`ip_address`
FROM (
  SELECT 'vikings' AS `user_type`, u.`stc_user_id` AS `user_id`,
    LEFT(CONCAT('user created as ', u.`stc_user_name`), 255) AS `log`,
    '2025-07-01' AS `date`, 1 AS `status`, 1 AS `created_by`, u.`stc_user_id` AS `updated_by`,
    '2025-07-01' AS `updated_date`,
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29'
    ) AS `ip_address`
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_name`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user address ', u.`stc_user_address`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_address`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user phone ', u.`stc_user_phone`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_phone`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user whatsapp ', u.`stc_user_phone_again`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_phone_again`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user email ', u.`stc_user_email`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_email`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user id ', u.`stc_user_userid`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_userid`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user city ', c.`stc_city_name`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  INNER JOIN `stc_city` c ON c.`stc_city_id` = u.`stc_user_city_id`
  WHERE TRIM(IFNULL(c.`stc_city_name`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user state ', s.`stc_state_name`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  INNER JOIN `stc_state` s ON s.`stc_state_id` = u.`stc_user_state_id`
  WHERE TRIM(IFNULL(s.`stc_state_name`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user role ',
      CASE u.`stc_user_role`
        WHEN 1 THEN 'User'
        WHEN 2 THEN 'Report Access for school'
        WHEN 6 THEN 'Boss'
        WHEN 9 THEN 'Nausher'
        ELSE 'Accountant'
      END
    ), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('user status ', IF(u.`stc_user_status` = 1, 'Active', 'In-active')), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    'create password = 123456',
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_password`, '')) <> ''

  UNION ALL
  SELECT 'vikings', u.`stc_user_id`,
    LEFT(CONCAT('password update from 123456 to ', u.`stc_user_password`), 255),
    '2025-07-01', 1, 1, u.`stc_user_id`, '2025-07-01',
    ELT(MOD(u.`stc_user_id` - 1, 11) + 1,
      '192.168.1.25','192.168.1.4','192.168.1.5','192.168.1.8','192.168.1.10',
      '192.168.1.18','192.168.1.19','192.168.1.21','192.168.1.22','192.168.1.28','192.168.1.29')
  FROM `stc_user` u
  WHERE TRIM(IFNULL(u.`stc_user_password`, '')) <> ''
    AND u.`stc_user_password` <> '123456'
) x
LEFT JOIN `user_log` l
  ON l.`user_type` = x.`user_type`
 AND l.`user_id` = x.`user_id`
 AND l.`log` = x.`log`
WHERE l.`id` IS NULL;

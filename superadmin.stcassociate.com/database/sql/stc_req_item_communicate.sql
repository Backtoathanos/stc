CREATE TABLE IF NOT EXISTS `stc_req_item_communicate` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `req_item_id` INT NOT NULL,
  `req_id` INT NOT NULL DEFAULT 0,
  `message` TEXT NOT NULL,
  `image_url` VARCHAR(500) NOT NULL DEFAULT '',
  `issue_type` VARCHAR(32) NOT NULL DEFAULT 'general',
  `username` VARCHAR(150) NOT NULL DEFAULT '',
  `user_type` VARCHAR(32) NOT NULL DEFAULT '',
  `created_by` INT NOT NULL DEFAULT 0,
  `created_date` DATETIME NOT NULL,
  `status` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_req_item_date` (`req_item_id`, `created_date`),
  KEY `idx_req_comm_user` (`user_type`, `created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `stc_req_item_communicate_seen` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `req_item_id` INT NOT NULL,
  `user_type` VARCHAR(32) NOT NULL DEFAULT '',
  `created_by` INT NOT NULL DEFAULT 0,
  `last_seen_id` INT NOT NULL DEFAULT 0,
  `last_seen_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_req_comm_seen` (`req_item_id`, `user_type`, `created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- JWT support database migration for phpMyAdmin

ALTER TABLE `users`
  ADD COLUMN `jwt_token` VARCHAR(512) NULL AFTER `token`,
  ADD COLUMN `jwt_expires_at` DATETIME NULL AFTER `jwt_token`,
  ADD INDEX `idx_jwt_token` (`jwt_token`);

-- Optional: create a dedicated JWT storage table for token revocation or auditing.
CREATE TABLE IF NOT EXISTS `jwt_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `jwt_token` VARCHAR(512) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_jwt_user_id` (`user_id`),
  INDEX `idx_jwt_token` (`jwt_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `users`
  ADD COLUMN `jwt_token` VARCHAR(512) NULL AFTER `token`,
  ADD COLUMN `jwt_expires_at` DATETIME NULL AFTER `jwt_token`,
  ADD INDEX `idx_jwt_token` (`jwt_token`);

CREATE TABLE IF NOT EXISTS `jwt_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `jwt_token` VARCHAR(512) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_jwt_user_id` (`user_id`),
  INDEX `idx_jwt_token` (`jwt_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
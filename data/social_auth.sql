-- Run once on the production database after deploying the social-auth code.
CREATE TABLE `dp_cms_social_identity` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `provider` varchar(20) NOT NULL,
  `provider_user_id` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provider_subject` (`provider`, `provider_user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

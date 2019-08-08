CREATE TABLE `orange_migrations` (
  `package` varchar(255) NOT NULL,
  `version` bigint(20) NOT NULL,
  PRIMARY KEY (`package`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `orange_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` int(11) unsigned NOT NULL DEFAULT 1,
  `created_ip` varchar(15) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp(),
  `updated_by` int(11) unsigned NOT NULL DEFAULT 1,
  `updated_ip` varchar(15) DEFAULT NULL,
  `access` int(10) unsigned DEFAULT 0,
  `url` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `parent_id` int(11) unsigned NOT NULL DEFAULT 0,
  `sort` int(11) unsigned NOT NULL DEFAULT 0,
  `target` varchar(128) DEFAULT NULL,
  `class` varchar(32) DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `color` varchar(7) NOT NULL DEFAULT 'd28445',
  `icon` varchar(32) NOT NULL DEFAULT 'square',
  `read_role_id` int(10) unsigned DEFAULT 0,
  `edit_role_id` int(10) unsigned DEFAULT 0,
  `delete_role_id` int(10) unsigned DEFAULT 0,
  `migration` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`) USING BTREE,
  KEY `idx_access` (`access`) USING BTREE,
  KEY `idx_active` (`active`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

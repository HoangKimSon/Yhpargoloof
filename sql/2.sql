CREATE TABLE IF NOT EXISTS `link` (
 `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
 `origin_link` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
 `shorten_link` varchar(255) COLLATE utf32_unicode_ci NOT NULL,
 `created_at` datetime NOT NULL DEFAULT current_timestamp(),
 `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;
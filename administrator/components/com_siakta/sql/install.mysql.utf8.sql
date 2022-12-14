CREATE TABLE IF NOT EXISTS `#__siak_ta`
(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `mahasiswa_id` INT NOT NULL DEFAULT 0,
    `prodi_id` TINYINT(5) UNSIGNED NOT NULL DEFAULT 0,
    `konsentrasi_id` TINYINT(5) UNSIGNED NOT NULL DEFAULT 0,
    `tahun` YEAR NULL DEFAULT NULL,
    `title` VARCHAR(400) NOT NULL DEFAULT '',
    `update_judul_on` DATETIME NULL DEFAULT NULL,
    `dosbing1` INT UNSIGNED NOT NULL DEFAULT 0,
    `dosbing2` INT UNSIGNED NOT NULL DEFAULT 0,
    `sidang_proposal` DATETIME NULL DEFAULT NULL,
    `sidang_akhir` DATETIME NULL DEFAULT NULL,
    `ruang_sidang` VARCHAR(50) NOT NULL DEFAULT '',
    `penguji1` INT UNSIGNED NOT NULL DEFAULT 0,
    `penguji2` INT UNSIGNED NOT NULL DEFAULT 0,
    `penguji3` INT UNSIGNED NOT NULL DEFAULT 0,
    `penguji4` INT UNSIGNED NOT NULL DEFAULT 0,
    `tanggal_lulus` DATE NULL DEFAULT NULL,
    `file_ta` VARCHAR(400) NOT NULL DEFAULT '',
    `abstrak` TEXT NULL DEFAULT NULL,
    `keywords` VARCHAR(200) NULL DEFAULT NULL,
    `yudisium` VARCHAR(200) NOT NULL DEFAULT '',
    `created_on` DATETIME NULL DEFAULT NULL,
    `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `last_update_on` DATETIME  NULL DEFAULT NULL,
    `last_update_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `state` TINYINT(4) NOT NULL DEFAULT 0,
    `checked_out` INT UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` DATETIME NULL DEFAULT NULL,
    CONSTRAINT `ta_mhs_id` FOREIGN KEY (`mahasiswa_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


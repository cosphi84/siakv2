ALTER TABLE `#__siak_nilai` ADD `krs_id` INT(10) NULL AFTER `krs_item_id`;

DROP TRIGGER IF EXISTS `#__siak_krs_items_nilai_insert`;

CREATE TRIGGER IF NOT EXISTS `#__siak_krs_items_nilai_insert` AFTER INSERT ON `#__siak_krs_items` FOR EACH ROW 
	INSERT INTO `#__siak_nilai` (prodi, jurusan, kelas, tahun_ajaran, user_id, semester, matakuliah, krs_item_id, krs_id) 
		SELECT `k`.`prodi`, `k`.`jurusan`, `k`.`kelas`, `k`.`tahun_ajaran`, `k`.`user_id`, `k`.`semester`,`ki`.`matakuliah`, `ki`.`id`, `k`.`id` FROM `#__siak_krs` AS `k`
			LEFT JOIN `#__siak_krs_items` AS `ki` ON `k`.`id` = `ki`.`krs` WHERE `ki`.`id` = NEW.id;



CREATE TRIGGER IF NOT EXISTS `#__siak_krs_nilai_update` AFTER UPDATE ON `#__siak_krs` FOR EACH ROW
    UPDATE `#__siak_nilai` SET tahun_ajaran = NEW.tahun_ajaran WHERE `#__siak_nilai`.`krs_id` = OLD.id;


-- Prosedur MySQL untuk copy data KRS_ID dari tabel dev_siak_krs_items.krs ke tabel dev_siak_nilai.krs_id 
/*
DROP PROCEDURE IF EXISTS copy_krs_id;

DELIMITER //

CREATE PROCEDURE copy_krs_id()
BEGIN
DECLARE n INT DEFAULT 0;
DECLARE i INT DEFAULT 0;
SET n=40254;
SET i=13785;
WHILE i<=n DO
UPDATE `dev_siak_nilai` SET `krs_id` = ( 
SELECT a.krs FROM `dev_siak_krs_items` AS a LEFT JOIN `dev_siak_nilai` AS b ON a.id=b.krs_item_id WHERE b.id=i
) WHERE `dev_siak_nilai`.`id` = i;
SET i = i + 1;
END WHILE;
END;
//

DELIMITER ;

CALL copy_krs_id();
*/
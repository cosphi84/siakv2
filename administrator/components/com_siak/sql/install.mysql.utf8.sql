CREATE TABLE IF NOT EXISTS `#__siak_prodi` (
	`id` 				TINYINT(4) 	UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(50) 			NOT NULL DEFAULT '',
	`alias`				VARCHAR(50) 			NOT NULL DEFAULT '',
	`strata`			VARCHAR(20)				NOT NULL,
	`gelar`				VARCHAR(50)				NOT NULL,
	`state` 			TINYINT(3) 				NOT NULL DEFAULT 0,
	`kaprodi`			INT(10)					NOT NULL,
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 				NOT NULL,
	`created_user_id` 	INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	CONSTRAINT `kaprodi` FOREIGN KEY (`kaprodi`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
-- Dummy Data --


CREATE TABLE IF NOT EXISTS `#__siak_jurusan` (
	`id` 				TINYINT(4) 	UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(50) 			NOT NULL DEFAULT '',
	`alias`				VARCHAR(50) 			NOT NULL DEFAULT '',
	`prodi` 			TINYINT(4)	UNSIGNED	NOT NULL,
	`kajur`				INT(10)					NOT NULL,
	`state` 			TINYINT(3) 				NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 				NOT NULL,
	`created_user_id` 	INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
  	CONSTRAINT `prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi`(`id`) ON UPDATE CASCADE,
	CONSTRAINT `kajur` FOREIGN KEY (`kajur`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
-- Data Khusus untuk matakuliah ALL Jurusan --



CREATE TABLE IF NOT EXISTS `#__siak_kelas_mahasiswa` (
	`id` 				TINYINT(4) 	UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(20) 			NOT NULL DEFAULT '',
	`alias`				VARCHAR(50) 			NOT NULL DEFAULT '',
	`state` 			TINYINT(3) 				NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 				NOT NULL,
	`created_user_id` 	INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_jenis_mk` (
	`id` 				TINYINT(4) 	UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(50) 			NOT NULL DEFAULT '',
	`alias`				VARCHAR(50) 			NOT NULL DEFAULT '',
	`state` 			TINYINT(3) 				NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 				NOT NULL,
	`created_user_id` 	INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__siak_jenis_mk` (title,alias,state) VALUES ('TEORI', 'Teori', '1');
INSERT INTO `#__siak_jenis_mk` (title,alias,state) VALUES ('PRAKTIKUM', 'Praktikum', '1');
INSERT INTO `#__siak_jenis_mk` (title,alias,state) VALUES ('KP', 'Kerja Praktek', '1');
INSERT INTO `#__siak_jenis_mk` (title,alias,state) VALUES ('KKM', 'Kuliah Kerja Masyarakat', '1');
INSERT INTO `#__siak_jenis_mk` (title,alias,state) VALUES ('TA', 'Tugas Akhir', '1');


CREATE TABLE IF NOT EXISTS `#__siak_matakuliah` (
	`id` 				SMALLINT(5) UNSIGNED	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(20) 			NOT NULL DEFAULT '',
	`alias` 			VARCHAR(100) 			NOT NULL DEFAULT '',
    `sks` 				TINYINT(3) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `type` 				TINYINT(4) 	UNSIGNED 	NOT NULL ,
	`prodi`				TINYINT(4) 	UNSIGNED 	NOT NULL ,
	`jurusan` 			TINYINT(4) 	UNSIGNED 	NOT NULL ,
	`uang_mk`	 		INT(10) 				NULL DEFAULT 0,
	`state` 			TINYINT(3) 				NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 				NOT NULL,
	`created_user_id` 	INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	UNIQUE KEY `kode` (`title`),
	CONSTRAINT `mk_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `mk_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `mk_type` FOREIGN KEY (`type`) REFERENCES `#__siak_jenis_mk` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_semester` (
	`id` 				TINYINT(4) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 			VARCHAR(20) 				NOT NULL DEFAULT '',
	`alias`				TINYINT(2) 					NOT NULL DEFAULT 0,
    `totalSKS` 			TINYINT(2) 		UNSIGNED 	NOT NULL DEFAULT 0,
    `uangSKS` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
	`uangSPP` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
	`prodi`				TINYINT(4) 		UNSIGNED 	NOT NULL ,
	`jurusan` 			TINYINT(4) 		UNSIGNED 	NOT NULL ,
	`kelas` 			TINYINT(4) 		UNSIGNED 	NOT NULL DEFAULT 0,
	`state` 			TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 		DATETIME 					NOT NULL,
	`created_user_id` 	INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
    `asset_id` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	CONSTRAINT `s_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `s_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_paket_mk` (
	`id` 				INT(10) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`prodi`				TINYINT(4) 		UNSIGNED 	NOT NULL ,
	`jurusan` 			TINYINT(4) 		UNSIGNED 	NOT NULL ,
	`semester`			TINYINT(4)		UNSIGNED 	NOT NULL ,
	`matakuliah`		SMALLINT(5)		UNSIGNED	NOT NULL ,		
	`state` 			TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	CONSTRAINT `pmk_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pmk_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pmk_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pmk_matakuliah` FOREIGN KEY (`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TRIGGER IF NOT EXISTS  `#__semester_totalSKS_insert` AFTER INSERT ON `#__siak_paket_mk` FOR EACH ROW 
	UPDATE `#__siak_semester` SET `totalSKS` =  
	(
		SELECT SUM(`x`.`sks_matkul`) FROM 
		(
			SELECT `m`.`sks` AS `sks_matkul` FROM `#__siak_paket_mk` AS `sp` INNER JOIN `#__siak_matakuliah` AS `m` 
			ON `m`.`id` = `sp`.`matakuliah` WHERE `sp`.`semester` = NEW.semester
		) x
	) WHERE `id` = NEW.semester;

CREATE TRIGGER IF NOT EXISTS `#__semester_totalSKS_update` AFTER UPDATE ON `#__siak_paket_mk` FOR EACH ROW 
	UPDATE `#__siak_semester` SET `totalSKS` =  
	(
		SELECT SUM(`x`.`sks_matkul`) FROM 
		(
			SELECT `m`.`sks` AS `sks_matkul` FROM `#__siak_paket_mk` AS `sp` INNER JOIN `#__siak_matakuliah` AS `m` 
			ON `m`.`id` = `sp`.`matakuliah` WHERE `sp`.`semester` = NEW.semester
		) x
	) WHERE `id` = NEW.semester;

CREATE TRIGGER IF NOT EXISTS `#__semester_totalSKS_delete` AFTER DELETE ON `#__siak_paket_mk` FOR EACH ROW 
	UPDATE `#__siak_semester` SET `totalSKS` =  
	(
		SELECT SUM(`x`.`sks_matkul`) FROM 
		(
			SELECT `m`.`sks` AS `sks_matkul` FROM `#__siak_paket_mk` AS `sp` INNER JOIN `#__siak_matakuliah` AS `m` 
			ON `m`.`id` = `sp`.`matakuliah` WHERE `sp`.`semester` = OLD.semester
		) x
	) WHERE `id` = OLD.semester;



CREATE TABLE IF NOT EXISTS `#__siak_jenis_user` (
	`id` 				TINYINT(4) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title`				VARCHAR(50) 	 			NOT NULL ,
	`alias` 			VARCHAR(50) 		 		NOT NULL ,
	`state` 			TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`) 
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__siak_jenis_user` (title, alias, state) VALUES ('Mahasiswa Reguler', 'MABA', 1);
INSERT INTO `#__siak_jenis_user` (title, alias, state) VALUES ('Mahasiswa Pindahan', 'MAPIN', 1);
INSERT INTO `#__siak_jenis_user` (title, alias, state) VALUES ('Dosen', 'DOSEN', 1);
INSERT INTO `#__siak_jenis_user` (title, alias, state) VALUES ('Staff Fakultas', 'STAFF', 1);
INSERT INTO `#__siak_jenis_user` (title, alias, state) VALUES ('Management', 'Mgmt', 1);

CREATE TABLE IF NOT EXISTS `#__siak_user` (
	`id` 				INT(10) 		UNSIGNED 		NOT NULL AUTO_INCREMENT,
	`user_id` 			INT(10) 		 				NOT NULL,
	`tipe_user`			TINYINT(4)		UNSIGNED		NOT NULL DEFAULT 0,
	`prodi`				TINYINT(4) 		UNSIGNED 		NULL ,
	`jurusan` 			TINYINT(4) 		UNSIGNED 		NULL ,
	`kelas` 			TINYINT(4) 		UNSIGNED 		NULL ,
	`angkatan` 			YEAR 							NULL 		DEFAULT NULL,
	`no_ktp`			VARCHAR(18)						NULL,
	`nidn`				VARCHAR(30)						NULL 		DEFAULT NULL,
	`nik` 				VARCHAR(30)						NULL		DEFAULT NULL,
	`dob`				DATE							NOT NULL	DEFAULT '0000-00-00',
	`pob`				VARCHAR(70)						NULL,
	`jenis_kelamin`		ENUM('LAKI LAKI', 'PEREMPUAN')	NULL,
	`status_sipil`		VARCHAR(50)						NULL,
	`agama`				VARCHAR(50)						NULL,
	`tanggal_masuk` 	DATE 							NOT NULL 	DEFAULT '0000-00-00',
	`tanggal_keluar` 	DATE 							NOT NULL  	DEFAULT '0000-00-00',
	`alamat_1`			VARCHAR(100)					NULL,
	`alamat_2`			VARCHAR(100)					NULL DEFAULT NULL,
	`kelurahan`			VARCHAR(50)						NULL,
	`kecamatan`			VARCHAR(50)						NULL,
	`kabupaten`			VARCHAR(50)						NULL,
	`propinsi`			VARCHAR(50)						NULL,
	`kode_pos`			VARCHAR(10)						NULL,
	`telepon`			VARCHAR(20)						NOT NULL DEFAULT '', 
	`foto`				VARCHAR(150)					NULL DEFAULT NULL,
	`last_update`		DATETIME						NULL,
	`reset`				TINYINT(2)		UNSIGNED		NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 		NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`asset_id` 			INT(10) 		UNSIGNED 		NOT NULL DEFAULT 0,	
	PRIMARY KEY(`id`),	
	CONSTRAINT `usr_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__siak_dosen_wali` (
	`id` 				TINYINT(4) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`user_id` 			INT(10) 	 				NOT NULL ,	
	`angkatan` 			YEAR 						NULL 		DEFAULT NULL,
	`prodi` 			TINYINT(4) 		UNSIGNED 	NOT NULL,
	`kelas` 			TINYINT(4) 		UNSIGNED 	NOT NULL,
	`status` 			TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	CONSTRAINT `aa_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `aa_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `aa_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_mhs_status` (
	`id` 				INT(10) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`user_id` 			INT(10) 				 	NOT NULL ,		
	`semester`			TINYINT(4)		UNSIGNED	NOT NULL,
	`prodi` 			TINYINT(4) 		UNSIGNED 	NOT NULL,	
	`jurusan` 			TINYINT(4) 		UNSIGNED 	NOT NULL,
	`kelas`		 		TINYINT(4) 		UNSIGNED 	NOT NULL,
	`status` 			TINYINT(4) 		UNSIGNED	NOT NULL,
	`ta`				VARCHAR(10)					NOT NULL,
	`create_date`		DATETIME						NULL DEFAULT NULL,
	`confirm`			TINYINT(2)		UNSIGNED 	NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 			INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	PRIMARY KEY (`id`),
	CONSTRAINT `stt_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `stt_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `stt_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `stt_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `stt_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TRIGGER IF NOT EXISTS `#__siak_user_status_insert` AFTER INSERT ON `#__siak_mhs_statua` FOR EACH ROW
	UPDATE `#__siak_user` SET `prodi` = NEW.prodi, `jurusan` = NEW.jurusan, `kelas` = NEW.kelas WHERE `user_id` = NEW.user_id;

CREATE TRIGGER IF NOT EXISTS `#__siak_user_status_update` AFTER UPDATE ON `#__siak_mhs_statua` FOR EACH ROW
	UPDATE `#__siak_user` SET `prodi` = NEW.prodi, `jurusan` = NEW.jurusan, `kelas` = NEW.kelas WHERE `user_id` = NEW.user_id;

CREATE TABLE IF NOT EXISTS `#__siak_krs` (
	`id` 			INT(10) 			UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`user_id` 		INT(10) 						NOT NULL ,
	`dosen_wali` 	TINYINT(4) 			UNSIGNED 	NOT NULL,
	`prodi` 			TINYINT(4) 		UNSIGNED 	NOT NULL,	
	`jurusan` 			TINYINT(4) 		UNSIGNED 	NOT NULL,
	`kelas`		 		TINYINT(4) 		UNSIGNED 	NOT NULL,
	`semester`		TINYINT(4)			UNSIGNED 	NOT NULL ,
	`tahun_ajaran`  VARCHAR(10)						NOT NULL DEFAULT '',
	`ttl_sks`		TINYINT(3)			UNSIGNED	NOT NULL DEFAULT 0,
	`status`		TINYINT(4) 						NOT NULL default -1,
	`confirm_dw`	TINYINT(3)						NOT NULL,
	`confirm_time`	TIMESTAMP						NULL,
	`confirm_note`	VARCHAR(200)					NULL DEFAULT NULL,
	`checked_out` 	int(10) 			UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` DATETIME,
	`created_time` 	TIMESTAMP NULL,
	`created_by`	INT(10) NULL DEFAULT NULL,
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),	
	CONSTRAINT `k_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `k_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `l_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `k_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,	
	CONSTRAINT `k_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `k_dosen_wali` FOREIGN KEY (`dosen_wali`) REFERENCES `#__siak_dosen_wali` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__siak_krs_items` (
if not myFile.is_file():
    print('File tidak ditemukan!');
    exit();

try:
    dataFrame = pd.read_excel(io=myFile)
except:
    print('File tidak bisa dibuka!');
    exit()
	`id` 	INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`krs`	INT(10) UNSIGNED NOT NULL,
	`matakuliah` SMALLINT(5) UNSIGNED	NOT NULL,
	PRIMARY key(`id`),
	CONSTRAINT `krs_it_id` FOREIGN KEY(`krs`) REFERENCES `#__siak_krs` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `krs_it_mk` FOREIGN KEY(`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TRIGGER IF NOT EXISTS `#__siak_krs_items_nilai_insert` AFTER INSERT ON `#__siak_krs_items` FOR EACH ROW 
	INSERT INTO `#__siak_nilai` (prodi, jurusan, kelas, tahun_ajaran, user_id, semester, matakuliah, krs_item_id) 
		SELECT `k`.`prodi`, `k`.`jurusan`, `k`.`kelas`, `k`.`tahun_ajaran`, `k`.`user_id`, `k`.`semester`,`ki`.`matakuliah`, `ki`.`id` FROM `#__siak_krs` AS `k`
			LEFT JOIN `#__siak_krs_items` AS `ki` ON `k`.`id` = `ki`.`krs` WHERE `ki`.`id` = NEW.id;


CREATE TABLE IF NOT EXISTS `#__siak_pembayaran` (
	`id` 			INT(10) 			UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`user_id` 		INT(10) 					NOT NULL ,
	`no_ref`		VARCHAR(50)					NULL DEFAULT NULL,
	`pembayaran` 		VARCHAR(20) 					NOT NULL,
	`tipe_bayar`		ENUM('CASH','TRANSFER')			NOT NULL,
	`tanggal_bayar`	DATE						NOT NULL,
	`semester`		TINYINT(4)			UNSIGNED 	NOT NULL ,
	`jumlah`		TEXT						NOT NULL,
	`matakuliah`		TINYINT(4)			UNSIGNED	NULL DEFAULT NULL,
	`ta`			VARCHAR(10)					NULL DEFAULT NULL,
	`kuitansi`		VARCHAR(50)					NULL DEFAULT NULL,
	`lunas`		TINYINT(1)			UNSIGNED	NOT NULL DEFAULT 1,
	`confirm`		TINYINT(4)					NOT NULL DEFAULT 0,
	`comfirm_user`  	INT(10)			UNSIGNED 	NOT NULL DEFAULT 0,
	`confirm_time`		TIMESTAMP					NOT NULL,
	`confirm_note`		VARCHAR(200)					NULL DEFAULT NULL,
	`checked_out` 		int(10) 			UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	`created_time` 	DATETIME NOT NULL,
    	`asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),	
	CONSTRAINT `pb_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pb_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__siak_dosen_mk` (
	`id` 			SMALLINT(5) 	UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`prodi` 		TINYINT(4) 		UNSIGNED 	NOT NULL,	
	`jurusan` 		TINYINT(4) 		UNSIGNED 	NOT NULL,
	`kelas`		 	TINYINT(4) 		UNSIGNED 	NOT NULL,
	`tahun_ajaran`	VARCHAR(10) 				NOT NULL,
	`matakuliah`	SMALLINT(5)		UNSIGNED	NOT NULL,
	`user_id`		INT(10)						NOT NULL,	
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),	
	CONSTRAINT `dsn_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `dsn_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `dsn_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `dsn_matakuliah` FOREIGN KEY (`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `dsn_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `#__siak_ruangan` (
	`id` 			TINYINT(4) 			UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 		VARCHAR(20)						NOT NULL,
	`alias`			VARCHAR(50)					 	NULL DEFAULT NULL,
	`note`			VARCHAR(200)					NULL DEFAULT NULL,
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)	
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__siak_jadwal_kbm` (
	`id` 			SMALLINT(5)		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`hari` 			TINYINT(4)	  UNSIGNED				NOT NULL default 1,
	`jam`			VARCHAR(50)				 	NOT NULL,
	`tahun_ajaran`		VARCHAR(10) 	NOT NULL DEFAULT '',
	`matakuliah`	SMALLINT(5)		UNSIGNED	NOT NULL,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`			TINYINT(4)		UNSIGNED	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED	NOT NULL,
	`ruangan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 	int(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` DATETIME,
	`created_time` 	DATETIME NOT NULL,
    `asset_id` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	CONSTRAINT `kbm_matakuliah` FOREIGN KEY (`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `kbm_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `kbm_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `kbm_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `kbm_ruangan` FOREIGN KEY (`ruangan`) REFERENCES `#__siak_ruangan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `kbm_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_sk` (
	`id` 			SMALLINT(5)		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`title` 		VARCHAR(20)					NOT NULL,
	`alias`			VARCHAR(50)				 	NULL DEFAULT NULL,
	`note`			VARCHAR(200)				NULL DEFAULT NULL,
	`file`			VARCHAR(50)					NOT NULL,
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`access`		TINYINT(4) 					NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`)	
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_kp` (
	`id` 			INT(10)			UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`tahun_ajaran`	VARCHAR(10) 				NOT NULL,
	`user_id`		INT(10) 			 		NOT NULL,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`		 	TINYINT(4) 		UNSIGNED 	NOT NULL,	
	`instansi`		INT(10)			UNSIGNED 	NOT NULL,
	`tanggal_mulai`	DATE						NOT NULL DEFAULT '0000-00-00',
	`tanggal_selesai` DATE						NOT NULL DEFAULT '0000-00-00',
	`dosbing`		INT(10)						NOT NULL DEFAULT 0,		
	`no_surat`		VARCHAR(100)				NOT NULL DEFAULT '',	
	`judul_laporan`	VARCHAR(200)				NOT NULL,
	`tanggal_seminar` DATE						NOT NULL DEFAULT '0000-00-00',
	`file_laporan`		VARCHAR(50)					NOT NULL,
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
	`tanggal_daftar` DATETIME						NOT NULL DEFAULT '0000-00-00 00:00:00',
	`checked_out` 		INT(10) 	UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	PRIMARY KEY (`id`),
	CONSTRAINT `lkp_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `lkp_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `lkp_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `lkp_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `lkp_industri` FOREIGN KEY(`instansi`) REFERENCES `#__siak_industri`(`id`) ON DELETE RESTRICT
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_ta` (
	`id` 			INT(10) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`tahun_ajaran`	VARCHAR(10) 				NOT NULL,
	`user_id`		INT(10) 			 		NOT NULL,
	`dosbing_1`		INT(10)						NOT NULL,
	`dosbing_2`		INT(10)						NOT NULL,
	`judul` 		VARCHAR(200)					NOT NULL,
	`tanggal_seminar` DATE							NOT NULL DEFAULT '0000-00-00',
	`tanggal_lulus` DATE NOT NULL DEFAULT '0000-00-00',
	`nilai_akhir`	TINYINT(4)		UNSIGNED		NOT NULL DEFAULT 0,
	`nilai_angka`	VARCHAR(2)						NOT NULL DEFAULT '',
	`file`			VARCHAR(50)						NOT NULL,
	`state` 		TINYINT(3) 					NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 		INT(10) 		UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	CONSTRAINT `ta_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_nilai` (
	`id` 			INT(10) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`tahun_ajaran`	VARCHAR(10) 				NOT NULL,
	`prodi` 		TINYINT(4) 		UNSIGNED 	NOT NULL,	
	`jurusan` 		TINYINT(4) 		UNSIGNED 	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED		NOT NULL ,
	`kelas`		 	TINYINT(4) 		UNSIGNED 	NOT NULL,
	`user_id`		INT(10) 			 		NOT NULL,
	`dosen`			INT(10)						NULL,		
	`matakuliah`	SMALLINT(5)		UNSIGNED		NOT NULL,
	`nilai_final`	TINYINT(1)		UNSIGNED 		NOT NULL DEFAULT 0,
	`kehadiran`		TINYINT(4)		UNSIGNED		NULL DEFAULT NULL,
	`tugas`			TINYINT(4)		UNSIGNED		NULL DEFAULT NULL,
	`uts`			TINYINT(4)		UNSIGNED		NULL DEFAULT NULL,
	`uas`			TINYINT(4)		UNSIGNED		NULL DEFAULT NULL,
	`nilai_akhir`	TINYINT(4)		UNSIGNED		NOT NULL DEFAULT 0,
	`nilai_angka`	VARCHAR(4)						NOT NULL DEFAULT 'E',
	`nilai_mutu`	TINYINT(4)		UNSIGNED		NOT NULL DEFAULT 0,	
	`status`		enum('MURNI','REMIDIAL')			NOT NULL DEFAULT 'MURNI',
	`state` 		TINYINT(4) 						NOT NULL DEFAULT 0,
	`krs_item_id`   INT(10) 		UNSIGNED   NOT NULL,
	`krs_id`        INT(10)			UNSIGNED 	NOT NULL,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
	`checked_out_time` 	DATETIME,
	`created_by`	INT(10) 		NULL DEFAULT NULL,
	`created_date`	DATETIME		NULL DEFAULT NULL,  	
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	CONSTRAINT `scr_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `scr_matakuliah` FOREIGN KEY (`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `scr_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `scr_krs_items` FOREIGN KEY (`krs_item_id`) REFERENCES `#__siak_krs_items` (`id`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_sp` (
	`id` 			INT(10) 		UNSIGNED 	NOT NULL AUTO_INCREMENT,
	`nilai_id`		INT(10) 		UNSIGNED	NOT NULL,
	`tahun_ajaran`	VARCHAR(10) 				NOT NULL,
	`user_id`		INT(10) 			 		NOT NULL,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`			TINYINT(4)		UNSIGNED	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED	NOT NULL,
	`tanggal_daftar` DATETIME					NOT NULL DEFAULT '0000-00-00 00:00:00',
	`nilai_akhir_remid` TINYINT(4) 	UNSIGNED	NOT NULL DEFAULT 0,
	`nilai_remid_angka` VARCHAR(2)				NOT NULL DEFAULT '',
	`nilai_remid_mutu` TINYINT(2)	UNSIGNED	not null default 0,
	`input_nilai_by`	INT(10)					NOT NULL default 0,
	`input_nilai_time`	DATETIME 				NOT NULL DEFAULT '0000-00-00 00:00:00',
	`status_bayar`	TINYINT(4)		UNSIGNED	NOT NULL DEFAULT 0,
	`state` 		TINYINT(4) 						NOT NULL DEFAULT 0,
	`checked_out` 		INT(10) 		UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
    `asset_id` 		INT(10) UNSIGNED NOT NULL DEFAULT 0,
	PRIMARY KEY (`id`),
	CONSTRAINT `sp_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `sp_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `sp_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,	
	CONSTRAINT `sp_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `sp_nilai` FOREIGN KEY (`nilai_id`) REFERENCES `#__siak_nilai` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `sp_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TRIGGER IF NOT EXISTS `#__users_insert` AFTER INSERT ON `#__users` FOR EACH ROW 
	INSERT INTO `#__siak_user` (`user_id`) VALUE (NEW.id); 


CREATE TABLE IF NOT EXISTS `#__siak_praktikum` 
(
	`id` 	INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`matakuliah` SMALLINT(5) UNSIGNED NOT NULL,
	`user_id` 	INT(10) NOT NULL,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`			TINYINT(4)		UNSIGNED	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED		NOT NULL,
	`status`	TINYINT(4) NOT NULL DEFAULT 0,
	`create_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`ta` VARCHAR(10) NOT NULL DEFAULT '',
	`checked_out` 		INT(10) UNSIGNED 	NOT NULL DEFAULT 0,
  	`checked_out_time` 	DATETIME,
	PRIMARY KEY (`id`),
	CONSTRAINT `pk_prodi` FOREIGN KEY (`prodi`) REFERENCES `#__siak_prodi` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pk_jurusan` FOREIGN KEY (`jurusan`) REFERENCES `#__siak_jurusan` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pk_kelas` FOREIGN KEY (`kelas`) REFERENCES `#__siak_kelas_mahasiswa` (`id`) ON UPDATE CASCADE,	
	CONSTRAINT `pk_semester` FOREIGN KEY (`semester`) REFERENCES `#__siak_semester` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pk_matakuliah` FOREIGN KEY (`matakuliah`) REFERENCES `#__siak_matakuliah` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `pk_user` FOREIGN KEY (`user_id`) REFERENCES `#__users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_industri`
(
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nama` VARCHAR(100) NOT NULL DEFAULT '',
	`alamat` VARCHAR(1024) NOT NULL DEFAULT '',
	`kabupaten` VARCHAR(50) NOT NULL DEFAULT '',
	`propinsi` VARCHAR(50) NOT NULL DEFAULT '',
	`telepon` VARCHAR(20) NOT NULL DEFAULT '',
	`email` VARCHAR(50) NULL DEFAULT NULL,
	`pic` VARCHAR(50) NOT NULL DEFAULT '',
	`jabatan_pic` VARCHAR(50) NULL DEFAULT NULL,
	`telepon_pic` VARCHAR(50) NOT NULL DEFAULT '',
	`no_dokumen_kerjasama` VARCHAR(100) NULL DEFAULT NULL,
	`tanggal_kerjasama` DATE NULL DEFAULT NULL,
	`tanggal_berakhir` DATE NULL DEFAULT NULL,
	`dokumen_kerjasama` VARCHAR(100) NULL DEFAULT NULL,
	`create_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`create_user` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`state` TINYINT(4) NOT NULL DEFAULT 1,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__siak_bobot_nilai` (
	`id` TINYINT(2) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(25) NOT NULL DEFAULT '',
	`alias` VARCHAR(50) NOT NULL DEFAULT '',
	`bobot` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
	`state` TINYINT(4) NOT NULL DEFAULT 0,
	PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_penangguhan_bayar` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` 		INT(10) 		NOT NULL DEFAULT 0,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`			TINYINT(4)		UNSIGNED	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED	NOT NULL,
	`tahun_akademik` VARCHAR(10) 	NULL DEFAULT NULL,
	`jenis_bayaran`	VARCHAR(20) 	NULL DEFAULt NULL,
	`sudah_bayar`   INT(12)			NULL default 0,
	`perkiraan_tanggal_bayar` DATE 	NULL DEFAULT NULL,
	`created_time`	DATETIME		NOT NULL DEFAULT '0000-00-00 00:00:00',
	`approved`		TINYINT(4)		NOT NULL DEFAULT 0,
	`approved_by`	INT(10)			NOT NULL DEFAULT 0,
	`approved_time`	DATETIME		NOT NULL DEFAULT '0000-00-00 00:00:00',
	`state`			TINYINT(4)		NOT NULL DEFAULT 0,
	PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `#__siak_revisi_nilai` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`nilai_id` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	`jenis_nilai` VARCHAR(30) NOT NULL DEFAULT '',
	`nilai_input` TINYINT(5) NOT NULL DEFAULT 0,
	`nilai_ok`	TINYINT(5) NOT NULL DEFAULT 0,
	`alasan` VARCHAR(255) NOT NULL DEFAULT '',
	`dosen` INT(10) NOT NULL DEFAULT 0,
	`crated_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
	`approved` TINYINT(4) NOT NULL DEFAULT 0,
	`approved_time` DATETIME NULL DEFAULT NULL,
	`approved_by`	INT(10) NULL DEFAULT NULL,
	`inputed` TINYINT(4) NOT NULL DEFAULT 0,
	`inputed_time` DATETIME NULL DEFAULT NULL,
	`inputed_by` INT(10) NOT NULL DEFAULT 0,
	`state`			TINYINT(4)		NOT NULL DEFAULT 0,
	PRIMARY KEY(`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;



CREATE TABLE IF NOT EXISTS `#__siak_jadwal_ujian` (
	`id`			INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title`  		VARCHAR(30) NOT NULL,
	`prodi`			TINYINT(4)		UNSIGNED	NOT NULL,
	`jurusan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`kelas`			TINYINT(4)		UNSIGNED	NOT NULL,
	`semester`		TINYINT(4)		UNSIGNED	NOT NULL,
	`tahun_ajaran`	 VARCHAR(10) 	NULL DEFAULT NULL,
	`matakuliah`	SMALLINT(5)		UNSIGNED		NOT NULL,
	`dosen` 		INT(10) 		NOT NULL DEFAULT 0,
	`pengawas` 		INT(10) 		NOT NULL DEFAULT 0,
	`tanggal`		DATE			NOT NULL DEFAULT '0000-00-00',
	`jam_mulai`		VARCHAR(20) 	NOT NULL DEFAULT '00:00:00',
	`jam_akhir`		VARCHAR(20) 	NOT NULL DEFAULT '00:00:00',
	`ruangan`		TINYINT(4)		UNSIGNED	NOT NULL,
	`input_by` 		INT(10) 		NOT NULL DEFAULT 0,
	`input_time` 	DATETIME 	NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`state`			TINYINT(4)		NOT NULL default 0,
	PRIMARY KEY(`id`)			
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

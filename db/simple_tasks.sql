CREATE TABLE IF NOT EXISTS /*$wgDBprefix*/simple_tasks (
    `st_check_id` VARCHAR( 255 ) NOT NULL UNIQUE,
	`st_user` INT unsigned NOT NULL,
	`st_duedate` VARCHAR(15) NULL,
    `st_text` TEXT NOT NULL,
    `st_completed` INT unsigned NOT NULL
	) /*$wgDBTableOptions*/;

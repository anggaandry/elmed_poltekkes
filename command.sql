ALTER TABLE `Lm5_lecturers` ADD `lang` VARCHAR(255) NOT NULL DEFAULT 'id' AFTER `id`;

ALTER TABLE `Lm5_collegers` ADD `lang` VARCHAR(255) NOT NULL DEFAULT 'id' AFTER `id`;

ALTER TABLE `Lm5_admins` ADD `lang` VARCHAR(255) NOT NULL DEFAULT 'id' AFTER `id`;

ALTER TABLE `Lm5_study_program_full` ADD `lang` VARCHAR(255) NOT NULL DEFAULT 'id' AFTER `id`;

ALTER TABLE `Lm5_admins` CHANGE `username` `username` VARCHAR(255) CHARACTER SET utf COLLATE utf8_general_ci NULL;
SET FOREIGN_KEY_CHECKS = 0;
SELECT CONCAT("DROP TABLE `",table_name,"`;") FROM information_schema.tables WHERE table_schema = 'salomon';
SET FOREIGN_KEY_CHECKS = 1;
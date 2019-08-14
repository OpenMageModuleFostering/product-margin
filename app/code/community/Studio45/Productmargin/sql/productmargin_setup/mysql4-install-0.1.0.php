<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('productmargin_forms')};
CREATE TABLE {$this->getTable('productmargin_forms')} (
 	`forms_index` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`no_of_fields` INT(50) NULL DEFAULT '0',
	`product_name` text NOT NULL ,
	`product_sku` VARCHAR(255) NULL,
	`margin_price` VARCHAR(255) NULL,
	`product_price` VARCHAR(255) NULL,
	`ebay_price` VARCHAR(255) NULL DEFAULT '#fbfaf6',
	`created_time` DATETIME NULL DEFAULT NULL,
	`update_time` DATETIME NULL DEFAULT NULL,
	`status` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`forms_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();
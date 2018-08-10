-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------

-- -------------------------------------------
-- TABLE `tbl_user`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(128) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` int(11) DEFAULT '0',
  `about_me` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `address` varchar(512) DEFAULT NULL,
  `latitude` varchar(512) DEFAULT NULL,
  `longitude` varchar(512) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `profile_file` varchar(255) DEFAULT NULL,
  `tos` int(11) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT '0',
  `last_visit_time` datetime DEFAULT NULL,
  `last_action_time` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `login_error_count` int(11) DEFAULT NULL,
  `activation_key` varchar(128) DEFAULT NULL,
  `access_token` varchar(128) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_company`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_company`;
CREATE TABLE IF NOT EXISTS `tbl_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `company_name` varchar(256) NOT NULL,
  `known_as` varchar(256) DEFAULT NULL,
  `registration_number` varchar(256) NOT NULL,
  `vat_registration_number` varchar(256) NOT NULL,
  `country` varchar(256) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_company_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  KEY `fk_company_plan_id` (`plan_id`),
  CONSTRAINT `fk_company_plan_id` FOREIGN KEY (`plan_id`) REFERENCES `tbl_plan_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_company_admin`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_company_admin`;
CREATE TABLE IF NOT EXISTS `tbl_company_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `salutation` varchar(255) NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `address_line1` varchar(256) NOT NULL,
  `address_line2` varchar(256) DEFAULT NULL,
  `city` varchar(256) NOT NULL,
  `country` varchar(256) NOT NULL,
  `pincode` varchar(256) NOT NULL,
  `permission` varchar(256) NOT NULL,
  `passport_image` varchar(256) DEFAULT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_admin_created_by_id` (`created_by_id`),
  KEY `fk_company_admin_user_id` (`user_id`),
  KEY `fk_company_admin_company_id` (`company_id`),
  CONSTRAINT `fk_company_admin_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_company_admin_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_company_admin_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_company_prescriber`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_company_prescriber`;
CREATE TABLE IF NOT EXISTS `tbl_company_prescriber` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `salutation` varchar(255) NOT NULL,
  `registration_number` varchar(255) NOT NULL,
  `company_type` int(11) NOT NULL,
  `address_line1` varchar(256) NOT NULL,
  `address_line2` varchar(256) DEFAULT NULL,
  `city` varchar(256) NOT NULL,
  `country` varchar(256) NOT NULL,
  `pincode` varchar(256) NOT NULL,
  `permission` varchar(256) NOT NULL,
  `passport_image` varchar(256) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_prescriber_created_by_id` (`created_by_id`),
  KEY `fk_company_prescriber_user_id` (`user_id`),
  KEY `fk_company_prescriber_company_id` (`company_id`),
  CONSTRAINT `fk_company_prescriber_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_company_prescriber_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_company_prescriber_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_invoice_address`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_invoice_address`;
CREATE TABLE IF NOT EXISTS `tbl_invoice_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_line1` varchar(256) NOT NULL,
  `address_line2` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `country` varchar(256) NOT NULL,
  `pincode` varchar(256) NOT NULL,
  `company_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_address_created_by_id` (`created_by_id`),
  KEY `fk_invoice_address_company_id` (`company_id`),
  CONSTRAINT `fk_invoice_address_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_invoice_address_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_delivery_address`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_delivery_address`;
CREATE TABLE IF NOT EXISTS `tbl_delivery_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `address_line1` varchar(256) NOT NULL,
  `address_line2` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `country` varchar(256) NOT NULL,
  `pincode` varchar(256) NOT NULL,
  `company_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_delivery_address_created_by_id` (`created_by_id`),
  KEY `fk_delivery_address_company_id` (`company_id`),
  CONSTRAINT `fk_delivery_address_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_delivery_address_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_deal`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_deal`;
CREATE TABLE IF NOT EXISTS `tbl_deal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,  
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_deal_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_deal_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_brand`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_brand`;
CREATE TABLE IF NOT EXISTS `tbl_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,  
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_deal_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_deal_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_product`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_product`;
CREATE TABLE IF NOT EXISTS `tbl_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,  
  `code` varchar(125) NOT NULL,
  `price` varchar(125) NOT NULL,
  `actual_quantity` varchar(125) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `is_fav` tinyint(2) DEFAULT 0,
  `state_id` tinyint(2) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_created_by_id` (`created_by_id`),
  KEY `fk_product_deal_id` (`deal_id`),
  KEY `fk_product_category_id` (`category_id`),
  CONSTRAINT `fk_product_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  CONSTRAINT `fk_product_deal_id` FOREIGN KEY (`deal_id`) REFERENCES `tbl_deal` (`id`),
  CONSTRAINT `fk_product_category_id` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_category`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE IF NOT EXISTS `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL, 
  `description` text DEFAULT NULL,  
  `image_file` varchar(256) NOT NULL, 
  `state_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL, 
  PRIMARY KEY (`id`),
  KEY `fk_category_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_category_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_cart`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_cart`;
CREATE TABLE IF NOT EXISTS `tbl_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `actual_price` varchar(125) NOT NULL,  
  `discounted_price` varchar(125) DEFAULT NULL,  
  `company_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_created_by_id` (`created_by_id`),
  KEY `fk_cart_company_id` (`company_id`),
  CONSTRAINT `fk_cart_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_cart_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_cart_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_cart_item`;
CREATE TABLE IF NOT EXISTS `tbl_cart_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `actual_price` varchar(125) NOT NULL,  
  `discounted_price` varchar(125) DEFAULT NULL,  
  `quantity` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_item_created_by_id` (`created_by_id`),
  KEY `fk_cart_item_product_id` (`product_id`),
  KEY `fk_cart_item_cart_id` (`cart_id`),
  CONSTRAINT `fk_cart_item_cart_id` FOREIGN KEY (`cart_id`) REFERENCES `tbl_cart` (`id`),
  CONSTRAINT `fk_cart_item_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`),
  CONSTRAINT `fk_cart_item_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_wishlist`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_wishlist`;
CREATE TABLE IF NOT EXISTS `tbl_wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `price` varchar(125) DEFAULT NULL,  
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_wishlist_created_by_id` (`created_by_id`),
  KEY `fk_wishlist_company_id` (`company_id`),
  CONSTRAINT `fk_wishlist_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_wishlist_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_order`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_order`;
CREATE TABLE IF NOT EXISTS `tbl_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL,
  `price` varchar(125) DEFAULT NULL,
  `delivery_address` longtext NOT NULL,
  `delivery_status` int(11) DEFAULT 0,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_created_by_id` (`created_by_id`),
  KEY `fk_order_company_id` (`company_id`),
  CONSTRAINT `fk_order_company_id` FOREIGN KEY (`company_id`) REFERENCES `tbl_company` (`id`),
  CONSTRAINT `fk_order_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_order_item`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_order_item`;
CREATE TABLE IF NOT EXISTS `tbl_order_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL,
  `price` varchar(125) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `delivery_status` int(11) DEFAULT 1,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_item_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_order_item_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  KEY `fk_order_item_product_id` (`product_id`),
  CONSTRAINT `fk_order_item_product_id` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`id`),
  KEY `fk_order_item_order_id` (`order_id`),
  CONSTRAINT `fk_order_item_order_id` FOREIGN KEY (`order_id`) REFERENCES `tbl_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_coupon`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_coupon`;
CREATE TABLE IF NOT EXISTS `tbl_coupon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(125) NOT NULL,
  `percentage` varchar(125) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_coupon_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_coupon_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_coupon_user`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_coupon_user`;
CREATE TABLE IF NOT EXISTS `tbl_coupon_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT 1,
  `type_id` int(11) DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_coupon_user_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_coupon_user_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`),
  KEY `fk_coupon_user_coupon_id` (`coupon_id`),
  CONSTRAINT `fk_coupon_user_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `tbl_coupon` (`id`),
  KEY `fk_coupon_user_user_id` (`user_id`),
  CONSTRAINT `fk_coupon_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_email_queue`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_email_queue`;
CREATE TABLE IF NOT EXISTS `tbl_email_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_email` varchar(128) DEFAULT NULL,
  `to_email` varchar(128) DEFAULT NULL,
  `message` text,
  `subject` varchar(255) DEFAULT NULL,
  `date_published` datetime DEFAULT NULL,
  `last_attempt` datetime DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `attempts` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `model_type` varchar(128) DEFAULT NULL,
  `email_account_id` int(11) DEFAULT NULL,
  `message_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- -------------------------------------------
-- TABLE `tbl_notice`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_notice`;
CREATE TABLE IF NOT EXISTS `tbl_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `model_type` varchar(128),
  `model_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notice_created_by` (`created_by_id`),
  CONSTRAINT `fk_notice_created_by` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_log`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_log`;
CREATE TABLE IF NOT EXISTS `tbl_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `error` varchar(256) NOT NULL,
  `api` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `state_id` int(11) DEFAULT '1',
  `link` varchar(255) NOT NULL,
  `type_id` int(11) DEFAULT '0',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_login_history`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_login_history`;
CREATE TABLE IF NOT EXISTS `tbl_login_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `failer_reason` varchar(255) DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_page`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_page`;
CREATE TABLE IF NOT EXISTS `tbl_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `description` longtext NOT NULL,
  `state_id` int(11) DEFAULT '1',
  `type_id` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL,
  `updated_on` datetime DEFAULT NULL,
  `created_by_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_page_created_by_id` (`created_by_id`),
  CONSTRAINT `fk_page_created_by_id` FOREIGN KEY (`created_by_id`) REFERENCES `tbl_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- -------------------------------------------
-- TABLE `tbl_setting`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_setting`;
CREATE TABLE IF NOT EXISTS `tbl_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type_id` varchar(255) DEFAULT NULL,
  `state_id` int(11) DEFAULT '0',
  `created_by_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- -------------------------------------------
-- TABLE `tbl_plan_type`
-- -------------------------------------------
DROP TABLE IF EXISTS `tbl_plan_type`;
CREATE TABLE IF NOT EXISTS `tbl_plan_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `title` varchar(255) NOT NULL, 
  `percent` varchar(255) DEFAULT '0',
  `type_id` varchar(255) DEFAULT NULL,
  `state_id` int(11) DEFAULT '0',
  `created_by_id` int(11) DEFAULT NULL,
   PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
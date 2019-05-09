<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 建立 注單 table
		$sql = <<<SQL
CREATE TABLE `d_bet_order` (
	`bet_order_id` BIGINT UNSIGNED NOT NULL COMMENT 'PK',
	`username` VARCHAR(32) COLLATE utf8mb4_bin NOT NULL COMMENT '帳號',
	`platform` VARCHAR(8) NOT NULL COMMENT '平台',
	`game_name` VARCHAR(8) NOT NULL COMMENT '遊戲名稱',
	`bet_amount` DECIMAL(10, 2) NOT NULL COMMENT '投注金額',
	`payout_amount` DECIMAL(10, 2) NOT NULL COMMENT '派彩金額',
	`bet_time` DATETIME NOT NULL COMMENT '投注時間',
	PRIMARY KEY (`bet_order_id`),
	KEY `idx_username_bet_time` (`username`, `bet_time`)
)
COMMENT='注單'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);


		// 建立 注單申請 table
		$sql = <<<SQL
CREATE TABLE `d_bet_order_apply` (
	`bet_order_apply_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`bet_order_id` BIGINT UNSIGNED NOT NULL COMMENT '注單 PK',
	`username` VARCHAR(32) COLLATE utf8mb4_bin NOT NULL COMMENT '帳號',
	`platform` VARCHAR(8) NOT NULL COMMENT '平台',
	`game_name` VARCHAR(8) NOT NULL COMMENT '遊戲名稱',
	`bet_amount` DECIMAL(10, 2) NOT NULL COMMENT '投注金額',
	`payout_amount` DECIMAL(10, 2) NOT NULL COMMENT '派彩金額',
	`bet_time` DATETIME NOT NULL COMMENT '投注時間',
	`activity_id` INT UNSIGNED NOT NULL COMMENT '活動 PK',
	`activity_rule_id` INT UNSIGNED NOT NULL COMMENT '活動規則 PK',
	`bonus` DECIMAL(10, 2) NOT NULL COMMENT '獎金',
	`deposited` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '已上分',
	PRIMARY KEY (`bet_order_apply_id`),
	KEY `idx_bet_order_id` (`bet_order_id`),
	KEY `idx_username` (`username`)
)
COMMENT='注單申請'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);


		// 建立 活動 table
		$sql = <<<SQL
CREATE TABLE `m_activity` (
	`activity_id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`name` VARCHAR(32) NOT NULL COMMENT '名稱',
	`disabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '禁用',
	`created_at` DATETIME NOT NULL COMMENT '建立日期',
	`updated_at` DATETIME COMMENT '更新日期',
	PRIMARY KEY (`activity_id`)
)
COMMENT='活動'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);


		// 建立 活動規則 table
		$sql = <<<SQL
CREATE TABLE `m_activity_rule` (
	`activity_rule_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`activity_id` INT UNSIGNED NOT NULL COMMENT '活動 PK',
	`name` VARCHAR(32) NOT NULL COMMENT '名稱',
	`rule` VARCHAR(256) NOT NULL COMMENT '規則',
	`order` SMALLINT NOT NULL DEFAULT 0 COMMENT '順序',
	`disabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '禁用',
	`created_at` DATETIME NOT NULL COMMENT '建立日期',
	`updated_at` DATETIME COMMENT '更新日期',
	PRIMARY KEY (`activity_rule_id`),
	KEY `idx_activity_id` (`activity_id`),
	CONSTRAINT `fk_activity_id` FOREIGN KEY (`activity_id`) REFERENCES `m_activity` (`activity_id`)
)
COMMENT='活動規則'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);


		// 建立 網站設定 table
		$sql = <<<SQL
CREATE TABLE `m_settings` (
	`setting_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`title` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '網頁標頭',
	`link1` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結一',
	`link2` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二',
	`link3` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結三',
	`link4` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結四',
	`link5` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結五',
	`link6` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結六',
	`link7` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結七',
	`link8` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結八',
	`link9` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結九',
	`link10` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十',
	`link11` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十一',
	`link12` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十二',
	`link13` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十三',
	`link14` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十四',
	`link15` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十五',
	`link16` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十六',
	`link17` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十七',
	`link18` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十八',
	`link19` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結十九',
	`link20` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二十',
	`link21` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二一',
	`link22` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二二',
	`link23` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二三',
	`link24` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二四',
	`link25` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '連結二五',
	`link1_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結一另開',
	`link2_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二另開',
	`link3_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結三另開',
	`link4_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結四另開',
	`link5_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結五另開',
	`link6_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結六另開',
	`link7_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結七另開',
	`link8_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結八另開',
	`link9_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結九另開',
	`link10_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十另開',
	`link11_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十一另開',
	`link12_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十二另開',
	`link13_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十三另開',
	`link14_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十四另開',
	`link15_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十五另開',
	`link16_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十六另開',
	`link17_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十七另開',
	`link18_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十八另開',
	`link19_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結十九另開',
	`link20_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二十另開',
	`link21_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二一另開',
	`link22_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二二另開',
	`link23_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二三另開',
	`link24_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二四另開',
	`link25_blank` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '連結二五另開',
	`created_at` DATETIME NOT NULL COMMENT '建立日期',
	`updated_at` DATETIME COMMENT '更新日期',
	PRIMARY KEY (`setting_id`)
)
COMMENT='網站設定'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);


		// 建立 機器人設定 table
		$sql = <<<SQL
CREATE TABLE `m_bot_settings` (
	`bot_setting_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK',
	`api_url` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'API URL',
	`login_url` VARCHAR(128) NOT NULL DEFAULT '' COMMENT '登入 URL',
	`login_account` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '登入帳號',
	`login_password` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '登入密碼',
	`auto_deposit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '自動派彩',
	`created_at` DATETIME NOT NULL COMMENT '建立日期',
	`updated_at` DATETIME COMMENT '更新日期',
	PRIMARY KEY (`bot_setting_id`)
)
COMMENT='機器人設定'
DEFAULT CHARSET=utf8mb4
ENGINE=InnoDB
;
SQL;
		DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

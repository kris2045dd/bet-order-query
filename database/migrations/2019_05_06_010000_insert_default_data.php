<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 清空資料
		$sql = <<<SQL
SET FOREIGN_KEY_CHECKS =0;
SQL;
		DB::statement($sql);

		$sql = <<<SQL
TRUNCATE TABLE `m_activity_rule`;
SQL;
		DB::statement($sql);

		$sql = <<<SQL
TRUNCATE TABLE `m_activity`;
SQL;
		DB::statement($sql);

		$sql = <<<SQL
TRUNCATE TABLE `m_settings`;
SQL;
		DB::statement($sql);

		$sql = <<<SQL
TRUNCATE TABLE `m_bot_settings`;
SQL;
		DB::statement($sql);

		$sql = <<<SQL
SET FOREIGN_KEY_CHECKS =1;
SQL;
		DB::statement($sql);


		// 新增 活動 資料
		$sql = <<<SQL
INSERT INTO `m_activity` (`activity_id`, `name`, `disabled`, `created_at`, `updated_at`) VALUES
(1, '电子五重曲 - 老虎机 300 倍彩金', 0, NOW(), NULL),
(2, '电子六重曲 - 畅享赔率彩金', 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 活動規則 資料
		$sql = <<<SQL
INSERT INTO `m_activity_rule` (`activity_rule_id`, `activity_id`, `name`, `rule`, `order`, `disabled`, `created_at`, `updated_at`) VALUES
(1, 1, '注单尾数 555', '555|10|1555', 1, 0, NOW(), NULL),
(2, 1, '注单尾数 5555', '5555|30|2555', 2, 0, NOW(), NULL),
(3, 1, '注单尾数 55555', '55555|50|6555', 3, 0, NOW(), NULL),
(4, 1, '注单尾数 555555', '555555|100|10555', 4, 0, NOW(), NULL),
(5, 1, '注單尾數 5555555', '5555555|300|35555', 5, 0, NOW(), NULL),
(6, 2, '派彩/投注 >= 30倍', '30|3|8888', 1, 0, NOW(), NULL),
(7, 2, '派彩/投注 >= 60倍', '60|5|8888', 2, 0, NOW(), NULL),
(8, 2, '派彩/投注 >= 120倍', '120|10|8888', 3, 0, NOW(), NULL),
(9, 2, '派彩/投注 >= 200倍', '200|15|8888', 4, 0, NOW(), NULL),
(10, 2, '派彩/投注 >= 300倍', '300|20|8888', 5, 0, NOW(), NULL),
(11, 2, '派彩/投注 >= 500倍', '500|30|8888', 6, 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 網站設定 資料
		$sql = <<<SQL
INSERT INTO `m_settings` (`setting_id`, `title`, `link1`, `link2`, `link3`, `link4`, `link5`, `link6`, `link7`, `link8`, `link9`, `link10`, `link11`, `link12`, `link13`, `link14`, `link15`, `link16`, `link17`, `link18`, `link19`, `link20`, `link21`, `link22`, `link23`, `link24`, `link25`, `link1_blank`, `link2_blank`, `link3_blank`, `link4_blank`, `link5_blank`, `link6_blank`, `link7_blank`, `link8_blank`, `link9_blank`, `link10_blank`, `link11_blank`, `link12_blank`, `link13_blank`, `link14_blank`, `link15_blank`, `link16_blank`, `link17_blank`, `link18_blank`, `link19_blank`, `link20_blank`, `link21_blank`, `link22_blank`, `link23_blank`, `link24_blank`, `link25_blank`, `created_at`, `updated_at`) VALUES
(1, '金沙 - 注单查询系统', 'http://example.com?n=最新优惠', 'http://example.com?n=升级模式', 'http://example.com?n=免登录充值中心', 'http://example.com?n=自助客服', 'http://example.com?n=代理加盟', 'http://example.com?n=官网首页', 'http://example.com?n=24x在线客服', 'http://example.com?n=一键入款', 'http://example.com?n=十五大捕鱼机', 'http://example.com?n=申请大厅', 'http://example.com?n=天天红包', 'http://example.com?n=手机下注', 'http://example.com?n=满意度调查', 'http://example.com?n=资讯端', 'http://example.com?n=注册会员', 'http://example.com?n=关于我们', 'http://example.com?n=联系我们', 'http://example.com?n=代理加盟', 'http://example.com?n=存款帮助', 'http://example.com?n=取款帮助', 'http://example.com?n=常见问题', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 機器人設定 資料
		$sql = <<<SQL
INSERT INTO `m_bot_settings` (`bot_setting_id`, `api_url`, `login_url`, `login_account`, `login_password`, `auto_deposit`, `created_at`, `updated_at`) VALUES
(1, 'http://45.195.146.42:1048', 'https://cj168.01jsdc.com', 'paicongzd', '123qwe', 0, NOW(), NULL);
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

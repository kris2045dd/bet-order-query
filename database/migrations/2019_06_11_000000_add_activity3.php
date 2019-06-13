<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivity3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 新增 活動 3 資料
		$sql = <<<SQL
INSERT INTO `m_activity` (`activity_id`, `name`, `disabled`, `created_at`, `updated_at`) VALUES
(3, '畅玩棋牌 - 第 2 惠', 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 活動 3 規則 資料
		$sql = <<<SQL
INSERT INTO `m_activity_rule` (`activity_rule_id`, `activity_id`, `name`, `rule`, `order`, `disabled`, `created_at`, `updated_at`) VALUES
(12, 3, '派彩/投注 >= 3倍', '3|1|1888', 1, 0, NOW(), NULL),
(13, 3, '派彩/投注 >= 6倍', '6|2|1888', 2, 0, NOW(), NULL),
(14, 3, '派彩/投注 >= 15倍', '15|5|1888', 3, 0, NOW(), NULL),
(15, 3, '派彩/投注 >= 30倍', '30|10|1888', 4, 0, NOW(), NULL);
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

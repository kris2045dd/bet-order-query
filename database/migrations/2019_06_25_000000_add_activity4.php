<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivity4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 新增 活動 4 資料
		$sql = <<<SQL
INSERT INTO `m_activity` (`activity_id`, `name`, `disabled`, `created_at`, `updated_at`) VALUES
(4, '捕鱼大师 - 超级奖上奖', 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 活動 4 規則 資料
		$sql = <<<SQL
INSERT INTO `m_activity_rule` (`activity_rule_id`, `activity_id`, `name`, `rule`, `order`, `disabled`, `created_at`, `updated_at`) VALUES
(16, 4, '中奖金额 100+', '100|5', 1, 0, NOW(), NULL),
(17, 4, '中奖金额 300+', '300|15', 2, 0, NOW(), NULL),
(18, 4, '中奖金额 500+', '500|25', 3, 0, NOW(), NULL),
(19, 4, '中奖金额 1,000+', '1000|55', 4, 0, NOW(), NULL),
(20, 4, '中奖金额 2,000+', '2000|125', 5, 0, NOW(), NULL),
(21, 4, '中奖金额 5,000+', '5000|355', 6, 0, NOW(), NULL),
(22, 4, '中奖金额 10,000+', '10000|755', 7, 0, NOW(), NULL),
(23, 4, '中奖金额 30,000+', '30000|1555', 8, 0, NOW(), NULL);
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

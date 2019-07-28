<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivity5 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 新增 活動 5 資料
		$sql = <<<SQL
INSERT INTO `m_activity` (`activity_id`, `name`, `disabled`, `created_at`, `updated_at`) VALUES
(5, '电子四重曲 - 盈利加赠', 0, NOW(), NULL);
SQL;
		DB::statement($sql);

		// 新增 活動 5 規則 資料
		$sql = <<<SQL
INSERT INTO `m_activity_rule` (`activity_id`, `name`, `rule`, `order`, `disabled`, `created_at`, `updated_at`) VALUES
(5, '派彩金额 1,000+', '1000|18', 1, 0, NOW(), NULL),
(5, '派彩金额 2,000+', '2000|58', 2, 0, NOW(), NULL),
(5, '派彩金额 5,000+', '5000|88', 3, 0, NOW(), NULL),
(5, '派彩金额 10,000+', '10000|388', 4, 0, NOW(), NULL),
(5, '派彩金额 30,000+', '30000|588', 5, 0, NOW(), NULL),
(5, '派彩金额 50,000+', '50000|888', 6, 0, NOW(), NULL),
(5, '派彩金额 100,000+', '100000|3888', 7, 0, NOW(), NULL),
(5, '派彩金额 300,000+', '300000|6888', 8, 0, NOW(), NULL);
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

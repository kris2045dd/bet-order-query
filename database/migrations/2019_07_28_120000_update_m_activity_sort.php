<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMActivitySort extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 變更 活動 排序
		$sql = <<<SQL
UPDATE `m_activity` SET `sort` =CASE
	WHEN `activity_id` = 1 THEN 2
	WHEN `activity_id` = 2 THEN 3
	WHEN `activity_id` = 3 THEN 4
	WHEN `activity_id` = 4 THEN 5
	WHEN `activity_id` = 5 THEN 1
END
WHERE `activity_id` IN (1, 2, 3, 4, 5);
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDBetOrderApply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 新增 建立日期、更新日期 欄位
		$sql = <<<SQL
ALTER TABLE `d_bet_order_apply`
	ADD `created_at` DATETIME NOT NULL COMMENT '建立日期' AFTER `memo`,
	ADD `updated_at` DATETIME COMMENT '更新日期' AFTER `created_at`;
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

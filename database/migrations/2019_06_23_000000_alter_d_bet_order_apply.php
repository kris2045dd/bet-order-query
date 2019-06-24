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
		// 新增 備註 欄位
		$sql = <<<SQL
ALTER TABLE `d_bet_order_apply`
	ADD `memo` VARCHAR(64) NULL DEFAULT NULL COMMENT '備註' AFTER `deposited`;
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

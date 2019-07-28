<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		// 新增 排序 欄位
		$sql = <<<SQL
ALTER TABLE `m_activity`
	ADD `sort` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序' AFTER `disabled`;
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

<?php

namespace App\Tasks;

class Delete7DaysAgoBetOrders extends TaskBase
{

	public function run()
	{
		$target_date = date('Y-m-d 00:00:00', strtotime('-7 days'));
		\Illuminate\Support\Facades\DB::delete('DELETE FROM d_bet_order WHERE bet_time < ?', [$target_date]);
	}

}

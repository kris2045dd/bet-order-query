<?php

namespace App\Activities;

abstract class ActivityBase
{

	/**
		判斷是否匹配規則

		@return array('matched' => [0:不匹配 , 1:匹配], 'bonus' => 0)
	*/
	public function match(\App\Models\DBetOrder $bet_order, \App\Models\MActivityRule $activity_rule)
	{
		throw new \Exception(get_called_class() . ' 尚未实作判断式.');
	}

}

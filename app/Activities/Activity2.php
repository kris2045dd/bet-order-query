<?php

namespace App\Activities;

/**
	電子六重曲 - 畅享赔率彩金
*/
class Activity2 extends ActivityBase
{

	// 規則樣式
	const RULE_PATTERN = '/^\d+\|\d+\|\d+$/';

	// 規則樣式範例
	const RULE_PATTERN_SAMPLE = '{中奖赔率}|{加赠彩金}|{彩金上限}';

	// 規則說明
	const RULE_DESC =<<<RULE_DESC
<table class="table">
	<thead>
		<tr>
			<th>中奖赔率</th>
			<th>加赠彩金</th>
			<th>彩金上限</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>≥<span style="color:red;font-weight:bold;">30</span>倍</td>
			<td>当局有效投注 * <span style="color:red;font-weight:bold;">3</span></td>
			<td rowspan="3" style="vertical-align:middle"><span style="color:red;font-weight:bold;">8888</span>元</td>
		</tr>
		<tr>
			<td>≥<span style="color:red;font-weight:bold;">60</span>倍</td>
			<td>当局有效投注 * <span style="color:red;font-weight:bold;">5</span></td>
		</tr>
		<tr>
			<td>≥<span style="color:red;font-weight:bold;">120</span>倍</td>
			<td>当局有效投注 * <span style="color:red;font-weight:bold;">10</span></td>
		</tr>
	</tbody>
</table>
<p>P.S. &#34;顺序&#34;值请依照奖项大小由高到低设置 <small>(判断顺序是由高到低)</small></p>
RULE_DESC;

	/**
		判斷是否匹配規則

		@return array('matched' => [0:不匹配 , 1:匹配], 'bonus' => 0)
	*/
	public function match(\App\Models\DBetOrder $bet_order, \App\Models\MActivityRule $activity_rule)
	{
		if ($bet_order->payout_amount <= 0) {
			return ['matched' => 0, 'bonus' => 0];
		}

		list($odds, $times, $bonus_ceiling) = explode('|', $activity_rule->rule);

		if (($bet_order->payout_amount / $bet_order->bet_amount) < $odds) {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 計算獎金
		$bonus = $bet_order->bet_amount * $times;
		if ($bonus > $bonus_ceiling) {
			$bonus = $bonus_ceiling;
		}

		return ['matched' => 1, 'bonus' => $bonus];
	}

}

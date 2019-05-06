<?php

namespace App\Activities;

/**
	電子五重曲 - 老虎機 300 倍彩金
*/
class Activity1 extends ActivityBase
{

	// 規則樣式
	const RULE_PATTERN = '/^\d+\|\d+\|\d+$/';

	// 規則樣式範例
	const RULE_PATTERN_SAMPLE = '{注单尾数}|{可获彩金}|{彩金上限}';

	// 範例說明
	const RULE_DESC =<<<RULE_DESC
<table class="table">
	<thead>
		<tr>
			<th>注单尾数</th>
			<th>可获彩金</th>
			<th>彩金上限</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>最后三位为 <span style="color:red;font-weight:bold;">555</span></td>
			<td>下注金额的 <span style="color:red;font-weight:bold;">10</span> 倍</td>
			<td><span style="color:red;font-weight:bold;">1555</span>元</td>
		</tr>
		<tr>
			<td>最后四位为 <span style="color:red;font-weight:bold;">5555</span></td>
			<td>下注金额的 <span style="color:red;font-weight:bold;">30</span> 倍</td>
			<td><span style="color:red;font-weight:bold;">2555</span>元</td>
		</tr>
		<tr>
			<td>最后五位为 <span style="color:red;font-weight:bold;">55555</span></td>
			<td>下注金额的 <span style="color:red;font-weight:bold;">50</span> 倍</td>
			<td><span style="color:red;font-weight:bold;">6555</span>元</td>
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
		list($tail_no, $times, $bonus_ceiling) = explode('|', $activity_rule->rule);

		$pattern = '/' . $tail_no . '$/';
		if (! preg_match($pattern, $bet_order->bet_order_id)) {
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

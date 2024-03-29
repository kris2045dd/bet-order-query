<?php

namespace App\Activities;

/**
	電子五重曲 - 老虎機 300 倍彩金
	參與遊戲: BB電子老虎機系列

	參考:
		http://7182004.com/
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
		// BB電子限定
		if ($bet_order->platform != 'BB电子') {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 免費遊戲的注單，不參與活動 (至少 1 元)
		if ($bet_order->bet_amount < 1) {
			return ['matched' => 0, 'bonus' => 0];
		}

		list($tail_no, $times, $bonus_ceiling) = explode('|', $activity_rule->rule);

		$pattern = '/' . $tail_no . '$/';
		if (! preg_match($pattern, $bet_order->bet_order_id)) {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 是否可以申請
		$this->canApply($bet_order);

		// 計算獎金
		$bonus = $bet_order->bet_amount * $times;
		if ($bonus > $bonus_ceiling) {
			$bonus = $bonus_ceiling;
		}

		return ['matched' => 1, 'bonus' => $bonus];
	}

	/*
		是否可以申請
	*/
	protected function canApply(\App\Models\DBetOrder $bet_order)
	{
		list($target_date, $target_time) = explode(' ', $bet_order->bet_time);

		// 計算注單遊戲日申請過的注單
		$count = \App\Models\DBetOrderApply::where('username', '=', $bet_order->username)
			->where('activity_id', '=', '1')
			->whereDate('bet_time', $target_date)
			->count();

		if ($count >= 1) {
			throw new \Exception('电子五重曲 每位会员每天仅限申请一次.');
		}
	}

}

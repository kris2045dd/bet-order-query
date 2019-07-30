<?php

namespace App\Activities;

/**
	暢玩棋牌 - 第 2 惠
	註: 限 開元棋牌、BB棋牌

	參考:
		http://7182004.com/
*/
class Activity3 extends ActivityBase
{

	// 規則樣式
	const RULE_PATTERN = '/^\d+\|\d+\|\d+$/';

	// 規則樣式範例
	const RULE_PATTERN_SAMPLE = '{中奖赔率}|{加赠彩金}|{彩金上限}';

	// 範例說明
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
			<td>派彩金额÷投注金额≥<span style="color:red;font-weight:bold;">3</span>倍</td>
			<td>投注金额x<span style="color:red;font-weight:bold;">1</span>倍</td>
			<td rowspan="3" style="vertical-align:middle"><span style="color:red;font-weight:bold;">1888</span>元</td>
		</tr>
		<tr>
			<td>派彩金额÷投注金额≥<span style="color:red;font-weight:bold;">6</span>倍</td>
			<td>投注金额x<span style="color:red;font-weight:bold;">2</span>倍</td>
		</tr>
		<tr>
			<td>派彩金额÷投注金额≥<span style="color:red;font-weight:bold;">15</span>倍</td>
			<td>投注金额x<span style="color:red;font-weight:bold;">5</span>倍</td>
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
		// 活動只限 開元棋牌、BB棋牌、易游棋牌
		if ($bet_order->platform!='开元棋牌' && $bet_order->platform!='BB棋牌' && $bet_order->platform!='易游棋牌') {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 三公、森林舞會、金鯊銀鯊、奔馳寶馬 遊戲除外
		if ($bet_order->game_name=='三公' || $bet_order->game_name=='森林舞会' || $bet_order->game_name=='金鲨银鲨' || $bet_order->game_name=='奔驰宝马') {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 免費遊戲的注單，不參與活動 (至少 1 元)
		if ($bet_order->bet_amount < 1) {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 派彩金額 <= 0
		if ($bet_order->payout_amount <= 0) {
			return ['matched' => 0, 'bonus' => 0];
		}

		list($odds, $times, $bonus_ceiling) = explode('|', $activity_rule->rule);

		if (($bet_order->payout_amount / $bet_order->bet_amount) < $odds) {
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
			->where('activity_id', '=', '3')
			->whereDate('bet_time', $target_date)
			->count();

		if ($count >= 1) {
			throw new \Exception('畅玩棋牌第2惠 每位会员每天仅限申请一次.');
		}
	}

}

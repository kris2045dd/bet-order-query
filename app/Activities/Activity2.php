<?php

namespace App\Activities;

/**
	電子六重曲 - 畅享赔率彩金
	註: BBIN電子 & BBII電子 不參與此項優惠

	參考:
		https://www.7092004.com:6899/
		http://7182004.com/
*/
class Activity2 extends ActivityBase
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
		// BB電子 或 BBII電子 或 非電子(棋牌) 不參與此項活動
		if ($bet_order->platform=='BB电子' || $bet_order->platform=='BBII电子' || strpos($bet_order->platform, '电子')===false) {
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
			->where('activity_id', '=', '2')
			->whereDate('bet_time', $target_date)
			->count();

		if ($count >= 1) {
			throw new \Exception('电子六重曲 每位会员每天仅限申请一次.');
		}
	}

}

<?php

namespace App\Activities;

/**
	電子四重曲 - 盈利加贈
	參與遊戲: BB電子、BBII電子

	參考:
		http://7182004.com/
*/
class Activity5 extends ActivityBase
{

	// 規則樣式
	const RULE_PATTERN = '/^\d+\|\d+$/';

	// 規則樣式範例
	const RULE_PATTERN_SAMPLE = '{派彩金额}|{加赠彩金}';

	// 範例說明
	const RULE_DESC =<<<RULE_DESC
<table class="table">
	<thead>
		<tr>
			<th>派彩金额</th>
			<th>加赠彩金</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><span style="color:red;font-weight:bold;">1000</span>+</td>
			<td><span style="color:red;font-weight:bold;">18</span> 元</td>
		</tr>
		<tr>
			<td><span style="color:red;font-weight:bold;">2000</span>+</td>
			<td><span style="color:red;font-weight:bold;">58</span> 元</td>
		</tr>
		<tr>
			<td><span style="color:red;font-weight:bold;">5000</span>+</td>
			<td><span style="color:red;font-weight:bold;">88</span> 元</td>
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
		// BB電子 & BBII電子 限定
		if ($bet_order->platform!='BB电子' && $bet_order->platform!='BBII电子') {
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

		list($winning_amount, $bonus) = explode('|', $activity_rule->rule);

		if ($bet_order->payout_amount < $winning_amount) {
			return ['matched' => 0, 'bonus' => 0];
		}

		// 是否可以申請
		$this->canApply($bet_order);

		return ['matched' => 1, 'bonus' => $bonus];
	}

	/*
		是否可以申請
	*/
	protected function canApply(\App\Models\DBetOrder $bet_order)
	{
		$today = date('Y-m-d');

		// 計算今日申請過的注單
		$count = \App\Models\DBetOrderApply::where('username', '=', $bet_order->username)
			->where('activity_id', '=', '5')
			->whereDate('created_at', $today)
			->count();

		if ($count >= 1) {
			throw new \Exception('电子四重曲 每位会员每天仅限申请一次.');
		}
	}

}

<?php

namespace App\Activities;

/**
	捕魚大師 - 捕魚達人
	超級獎上獎

	註: 限 BBIN捕魚大師、BBIN捕魚達人

	參考:
		http://7182004.com/
*/
class Activity4 extends ActivityBase
{

	// 規則樣式
	const RULE_PATTERN = '/^\d+\|\d+$/';

	// 規則樣式範例
	const RULE_PATTERN_SAMPLE = '{中奖金额}|{超级奖上奖彩金}';

	// 範例說明
	const RULE_DESC =<<<RULE_DESC
<table class="table">
	<thead>
		<tr>
			<th>中奖金额</th>
			<th>超级奖上奖彩金</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><span style="color:red;font-weight:bold;">100</span>+</td>
			<td><span style="color:red;font-weight:bold;">5</span>元</td>
		</tr>
		<tr>
			<td><span style="color:red;font-weight:bold;">300</span>+</td>
			<td><span style="color:red;font-weight:bold;">15</span>元</td>
		</tr>
		<tr>
			<td><span style="color:red;font-weight:bold;">500</span>+</td>
			<td><span style="color:red;font-weight:bold;">25</span>元</td>
		</tr>
	</tbody>
</table>
<p>P.S. &#34;顺序&#34;值请依照奖项大小由高到低设置 <small>(判断顺序是由高到低)</small></p>
RULE_DESC;

	// 存放會員統計資料
	protected static $statistics = [];

	/**
		判斷是否匹配規則

		@return array('matched' => [0:不匹配 , 1:匹配], 'bonus' => 0)
	*/
	public function match(\App\Models\DBetOrder $bet_order, \App\Models\MActivityRule $activity_rule)
	{
		// 活動只限 BBIN捕魚大師、BBIN捕魚達人
		if ($bet_order->platform!='BB捕鱼大师' && $bet_order->platform!='BB捕鱼达人') {
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

		$this->isValid($bet_order->bet_order_id, $bet_order->username);

		return ['matched' => 1, 'bonus' => $bonus];
	}

	// 檢查是否超過每日申請上限 & 注單號是否重覆
	protected function isValid($bet_order_id, $username)
	{
		if (empty(self::$statistics[$username])) {
			$this->doStatistic($username);
		}

		$s = self::$statistics[$username];

		if ($s['sum_bet_amount'] >= 1000000) {
			if (count($s['bet_order_ids']) >= 4) {
				throw new \Exception('超过申请上限. (当日有效投注 1,000,000+ 可申请四次)');
			}
		} else if ($s['sum_bet_amount'] >= 300000) {
			if (count($s['bet_order_ids']) >= 3) {
				throw new \Exception('超过申请上限. (当日有效投注 300,000+ 可申请三次)');
			}
		} else if ($s['sum_bet_amount'] >= 50000) {
			if (count($s['bet_order_ids']) >= 2) {
				throw new \Exception('超过申请上限. (当日有效投注 50,000+ 可申请二次)');
			}
		} else if ($s['sum_bet_amount'] >= 1) {
			if (count($s['bet_order_ids']) >= 1) {
				throw new \Exception('超过申请上限. (当日有效投注 1元+ 可申请一次)');
			}
		}

		if (in_array($bet_order_id, $s['bet_order_ids'])) {
			throw new \Exception('同一注单号不可重覆申请.');
		}
	}

	// 統計資料
	protected function doStatistic($username)
	{
		self::$statistics[$username] = [
			'sum_bet_amount' => 0,	// 今日投注總額
			'bet_order_ids' => [],	// 申請過的注單
		];

		$today = date('Y-m-d');

		// 計算今日總投注額
		$sql =
			"SELECT IFNULL(SUM(bet_amount), 0) AS sum_bet_amount
			FROM d_bet_order
			WHERE
				username =:username
				AND bet_time BETWEEN :from_date AND :to_date
				AND platform IN ('BB捕鱼大师', 'BB捕鱼达人')";
		$result = \Illuminate\Support\Facades\DB::select($sql, [
			'username' => $username,
			'from_date' => $today . ' 00:00:00',
			'to_date' => $today . ' 23:59:59',
		]);

		self::$statistics[$username]['sum_bet_amount'] = $result[0]->sum_bet_amount;

		// 計算今日申請過的注單
		$sql =
			"SELECT DISTINCT bet_order_id
			FROM d_bet_order_apply
			WHERE
				username =:username
				AND bet_time BETWEEN :from_date AND :to_date
				AND activity_id =4
				AND deposited IN (" . \App\Models\DBetOrderApply::DEPOSITED_DEFAULT . ", " . \App\Models\DBetOrderApply::DEPOSITED_SUCCESS .")";
		$rows = \Illuminate\Support\Facades\DB::select($sql, [
			'username' => $username,
			'from_date' => $today . ' 00:00:00',
			'to_date' => $today . ' 23:59:59',
		]);
		foreach ($rows as $row) {
			self::$statistics[$username]['bet_order_ids'][] = $row->bet_order_id;
		}
	}

}

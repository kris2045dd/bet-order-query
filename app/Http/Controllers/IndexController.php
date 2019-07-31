<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
	// 首頁
	public function index(Request $request)
	{
		try {
			return view('index.index', $this->getVars($request));
		} catch (\Exception $e) {
			return '网页发生错误 (' . $e->getMessage() . ')';
		}
	}

	protected function getVars(Request $request)
	{
		// 會員資料
		$member = null;
		if ($request->session()->exists('member')) {
			$member = $request->session()->get('member');
		}

		$vars = [
			// TODO: 用 cache
			'm_setting' => \App\Models\MSetting::firstOrFail(),
			'member' => $member,
		];

		return $vars;
	}

	// 登入
	public function login(Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			// 檢查
			if ($request->session()->exists('member')) {
				// 您已经登入
				throw new \Exception($request->session()->get('member.username'), 100);
			}

			$username = $request->input('username');
			if (! $username) {
				throw new \Exception('请输入会员帐号.', 101);
			}

			$balance = $request->input('balance');
			if (! is_numeric($balance)) {
				throw new \Exception('帐户余额只能是数字.', 102);
			}


			// 取得設定檔
			$bot_setting = \App\Models\MBotSetting::findOrFail(1);
			if (empty($bot_setting->api_url)) {
				throw new \Exception('尚未设置机器人 API URL.', 103);
			}


			// 呼叫 API
			$api = rtrim($bot_setting->api_url, '/') . '/login_member';

			$client = new \GuzzleHttp\Client();
			$response = $client->request('GET', $api, [
				'query' => [
					'username' => $username,
					'balance' => $balance,
				]
			]);

			if ($response->getStatusCode() != 200) {
				throw new \Exception('GuzzleHttp request failed. (' . $response->getStatusCode() . ')', 104);
			}

			$result = json_decode($response->getBody(), true);
			if (json_last_error() !== \JSON_ERROR_NONE) {
				throw new \Exception('JSON decode failed.', 105);
			}


			// 檢查
			if (!isset($result['state']) || $result['state']!=0) {
				throw new \Exception('无此会员 或 帐号与余额不相符.' . (empty($result['message']) ? '' : " ({$result['message']})"));
			}


			// 會員資料存入 session
			$request->session()->put('member', [
				'username' => $username,
			]);


			$res['error'] = -1;
			$res['msg'] = $username;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}

	// 登出
	public function logout(Request $request)
	{
		$request->session()->forget('member');
		/* 會連 CSRF toekn 一起刪除
		$request->session()->flush();
		*/
	}

	// 取得注單資料
	public function getBetOrders(Request $request)
	{
		$res = ['error' => '', 'data' => '', 'msg' => ''];

		try {
			if (! $request->session()->exists('member')) {
				throw new \Exception('会员未登录.');
			}

			$member = $request->session()->get('member');

			// 第一次登入 或 最後更新時間超過 60 秒，便向機器人要新資料，
			if (!isset($member['last_updated']) || (time() - $member['last_updated'] > 60)) {
				try {
					\App\Managers\Bot::getInstance()->fetchMemberBetOrders($member['username']);
				} catch (\Exception $e) {
					throw new \Exception('注单数据获取失败.');
				}
				$request->session()->put('member.last_updated', time());
			}

			$sql =
				"SELECT
					o.*,
					oa.deposited,
					oa.memo
				FROM d_bet_order AS o
					LEFT JOIN d_bet_order_apply AS oa USING(bet_order_id)
				WHERE
					o.username =:username
					AND o.bet_time >= :bet_time
				GROUP BY o.bet_order_id
				ORDER BY o.bet_order_id DESC
				#LIMIT 200";
			$bet_orders = \Illuminate\Support\Facades\DB::select($sql, [
				'username' => $member['username'],
				'bet_time' => date('Y-m-d 00:00:00', strtotime('-1 day')),
			]);

			$res['error'] = -1;
			$res['data'] = $bet_orders;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}

	// 取得 Activities (for 前端使用)
	public function getActivities(Request $request)
	{
		$res = ['error' => '', 'data' => '', 'msg' => ''];

		try {
			$res['error'] = -1;
			$res['data'] = \App\Managers\Activity::getInstance()->getActivities();
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}

	// 活動申請
	public function activityApplying(Request $request)
	{
		$res = ['error' => '', 'data' => '', 'msg' => ''];

		try {
			if (! $request->session()->exists('member')) {
				throw new \Exception('会员未登录.');
			}

			$member = $request->session()->get('member');

			$bet_order_id = $request->input('bet_order_id');
			$bet_order = \App\Models\DBetOrder::find($bet_order_id);
			if (! $bet_order) {
				throw new \Exception('查无注单.');
			}

			if ($bet_order->username != $member['username']) {
				throw new \Exception('只能申请自己的注单.');
			}


			// 檢查是否申請過
			$applied_bet_orders = \App\Models\DBetOrderApply::where('bet_order_id', '=', $bet_order_id)->get();
			if ($applied_bet_orders->isNotEmpty()) {
				foreach ($applied_bet_orders as $v) {
					if ($v->deposited == \App\Models\DBetOrderApply::DEPOSITED_SUCCESS) {
						$res['data'] = ['deposited' => \App\Models\DBetOrderApply::DEPOSITED_SUCCESS];
						throw new \Exception('注单已派彩.');
					}
					if ($v->deposited == \App\Models\DBetOrderApply::DEPOSITED_REJECTED) {
						$res['data'] = [
							'deposited' => \App\Models\DBetOrderApply::DEPOSITED_REJECTED,
							'memo' => $v->memo,
						];
						throw new \Exception('申请已拒絕.');
					}
				}
				$res['data'] = ['deposited' => \App\Models\DBetOrderApply::DEPOSITED_DEFAULT];
				throw new \Exception('审核处理中.');
			}


			$activity_m = new \App\Managers\Activity();
			$matched_rules = $activity_m->getMatchedRules($bet_order);
			if (! $matched_rules) {
				throw new \Exception('无符合的活动.');
			}


			// 新增申請注單
			$orders = [];
			foreach ($matched_rules as $matched_rule) {
				$order = new \App\Models\DBetOrderApply();
				$order->bet_order_id = $bet_order->bet_order_id;
				$order->username = $bet_order->username;
				$order->platform = $bet_order->platform;
				$order->game_name = $bet_order->game_name;
				$order->bet_amount = $bet_order->bet_amount;
				$order->payout_amount = $bet_order->payout_amount;
				$order->bet_time = $bet_order->bet_time;
				$order->activity_id = $matched_rule['activity_id'];
				$order->activity_rule_id = $matched_rule['activity_rule_id'];
				$order->bonus = $matched_rule['bonus'];
				$order->save();

				$orders[] = $order;
			}

			$res['data'] = ['deposited' => \App\Models\DBetOrderApply::DEPOSITED_DEFAULT];


			// 是否自動上分 (派彩)
			$bot_m = \App\Managers\Bot::getInstance();
			$bot_setting = $bot_m->getSettings();
			if ($bot_setting->auto_deposit) {
				foreach ($orders as $order) {
					$bot_m->deposit($order);
					$res['data'] = ['deposited' => \App\Models\DBetOrderApply::DEPOSITED_SUCCESS];
				}
			}

			$res['error'] = -1;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}

	// 查詢進度
	public function queryProgress(Request $request)
	{
		$res = ['error' => '', 'data' => '', 'msg' => ''];

		try {
			if (! $request->session()->exists('member')) {
				throw new \Exception('会员未登录.');
			}

			$member = $request->session()->get('member');

			$sql =
				"SELECT
					o.bet_order_id,
					o.platform,
					o.deposited,
					o.memo,
					a.name AS activity_name
				FROM d_bet_order_apply AS o
					LEFT JOIN m_activity AS a USING(activity_id)
				WHERE
					o.username =:username
					AND o.bet_time >= :bet_time
				GROUP BY o.bet_order_id
				ORDER BY o.bet_order_id DESC";
			$bet_orders = \Illuminate\Support\Facades\DB::select($sql, [
				'username' => $member['username'],
				'bet_time' => date('Y-m-d 00:00:00', strtotime('-7 days')),
			]);

			$res['error'] = -1;
			$res['data'] = $bet_orders;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
}

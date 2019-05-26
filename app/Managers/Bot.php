<?php

namespace App\Managers;

class Bot extends ManagerBase
{

	// 取得會員注單資料
	public function fetchMemberBetOrders($username)
	{
		// 取得設定檔
		$bot_setting = \App\Models\MBotSetting::findOrFail(1);
		if (empty($bot_setting->api_url)) {
			throw new \Exception('尚未设置机器人 API URL.', 300);
		}


		// 呼叫 API
		$api = rtrim($bot_setting->api_url, '/') . '/query_note';

		$client = new \GuzzleHttp\Client();
		$response = $client->request('GET', $api, [
			'query' => [
				'username' => $username,
			]
		]);

		if ($response->getStatusCode() != 200) {
			throw new \Exception('GuzzleHttp request failed. (' . $response->getStatusCode() . ')', 301);
		}

		$result = json_decode($response->getBody(), true);
		if (json_last_error() !== \JSON_ERROR_NONE) {
			throw new \Exception('JSON decode failed.', 302);
		}


		// 檢查
		if (!isset($result['state']) || $result['state']!=0) {
			throw new \Exception('查询失败.');
		}


		// Bulk insert
		try {
			/* Transaction begin
			\Illuminate\Support\Facades\DB::beginTransaction();
			*/

			$bet_orders = $result['data'];
			$arr_obj = new \ArrayObject($bet_orders);
			$iterator = $arr_obj->getIterator();
			while ($chunk = $this->getChunk($iterator)) {
				$sql = "INSERT IGNORE INTO d_bet_order (`bet_order_id`, `username`, `platform`, `game_name`, `bet_amount`, `payout_amount`, `bet_time`) VALUES ";
				$values = array_fill(0, count($chunk), "(?, ?, ?, ?, ?, ?, ?)");
				$binds = [];
				foreach ($chunk as $v) {
					$binds[] = $v['note_num'];
					$binds[] = $username;
					$binds[] = $v['platform'];
					$binds[] = $v['game_name'];
					$binds[] = $v['bet_amount'];
					$binds[] = $v['payout_amount'];
					$binds[] = $v['bet_time'];
				}
				$sql .= implode(', ', $values);
				\Illuminate\Support\Facades\DB::insert($sql, $binds);
			}

			/* Transaction commit
			\Illuminate\Support\Facades\DB::commit();
			*/
		} catch (\Exception $e) {
			/* Transaction rollback
			\Illuminate\Support\Facades\DB::rollBack();
			*/
			throw $e;
		}
	}

    protected function getChunk(\ArrayIterator &$arr_iterator, $chunk_size = 200)
    {
        $chunk = [];

        $i = 0;
        while ($arr_iterator->valid()) {
            $chunk[] = $arr_iterator->current();
            $arr_iterator->next();
            if (++$i >= $chunk_size) {
                break;
            }
        }

        return $chunk;
    }

}

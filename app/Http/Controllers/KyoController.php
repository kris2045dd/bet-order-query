<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KyoController extends Controller
{

	public function test(Request $request)
	{
		/*
		$remote_ip = '103.71.50.199';
		$util = new \App\Helper\Util();
		echo 'IP: ' . $util->ipDetection($remote_ip);
		*/

		/*
		echo (session('not_exists') === null) ? 'Y' : 'N';
		echo (session('not_exists') === '') ? 'Y' : 'N';
		*/

		/*
		echo storage_path('app/public');							// D:\Websites\ggl\storage\app/public
        echo env('APP_URL') . '/storage';							// url: http://localhost/storage
		echo Storage::disk('admin')->url('images/reward_vip.jpg');	// 對應 config/filesystems.php 中 disks => admin => url . 'images/reward_vip.jpg'
		echo public_path('app/public');								// D:\Websites\ggl\public\app/public
		echo get_class(Storage::disk('admin'));						// Illuminate\Filesystem\FilesystemAdapter
		*/

		/*
		$file = 'images/reward_vip.jpg';
		if (Storage::disk('admin')->exists($file)) {
			// base64 編碼
			//$content = Storage::disk('admin')->get($file);
			//echo base64_encode($content);

			// 輸出圖片
			//$file_path = Storage::disk('admin')->path($file);
			//return response()->file($file_path);
		} else {
			echo 'file not exists.';
		}
		*/

		/* pull 會取得並刪除資料
		$member = $request->session()->get('member');
		print_r($member);

		$member = $request->session()->pull('member');
		print_r($member);
		*/

		/*
		$sql =
			"SELECT
				r.reward_id,
				r.name,
				r.amount,
				r.img
			FROM d_play_times_reward AS p
				JOIN m_reward AS r USING(reward_id)
			ORDER BY p.play_times DESC";
		$rewards = DB::select($sql);
		$game_times = count($rewards);
		$played_times = \App\Model\DPlayRecord::where('account', '=', 'kyoTest')->count();

		// 會員資料存入 session
		$request->session()->put('member', [
			'name' => 'Kyo',
			'age' => 30
		]);

		$request->session()->put('member.account', 'kyoTest');
		$request->session()->put('member.can_play_times', $game_times - $played_times);
		$request->session()->put('member.rewards', $rewards);

		echo 'OK';
		*/

		/*
		echo \App\Model\DPlayTimesReward::count();
		*/

		/*
		echo $request->ip();
		*/

		/*
		$model = \App\Model\MSetting::firstOrFail();
		echo $model->title;
		*/

		/*
		echo 'A: ' . \Storage::disk('local')->getAdapter()->getPathPrefix();
		echo '<br />';
		echo 'B: ' . \Storage::disk('admin')->getAdapter()->getPathPrefix();
		echo '<br />';
		echo 'C: ' . public_path('admin/upload');
		echo '<br />';
		echo 'D: ' . \Storage::disk('admin')->url('image');
		echo '<br />';
		echo 'E: ' . \Storage::disk('admin')->path('image');
		*/

		/*
		$img = 'images/coin10.jpg';
		echo asset($img);	// for css、js、img
		echo '<br />';
		echo url($img);		// for link
		*/

		/*
		$account = 'kyoTest';
		$play_records = \App\Model\DPlayRecord::where('account', '=', $account)
			->select('name', 'amount', 'issued', 'created_at')
			->get();
		print_r($play_records->toArray());
		*/

		/*
		$reward_id = 4;
		$sql =
			"SELECT d.*
			FROM d_dice_set_reward AS r
				LEFT JOIN m_dice_set AS d USING (dice_set_id)
			WHERE r.reward_id =:reward_id";
		$dice_sets = DB::select($sql, ['reward_id' => $reward_id]);
		$r = $dice_sets[array_rand($dice_sets, 1)];
		print_r($r);
		*/

		/*
		$reward_id = 1;
		$dice_sets = \App\Model\DDiceSetReward::where('reward_id', '=', $reward_id)->get();
		$dice_sets = $dice_sets->toArray();
		$dice_set = $dice_sets[array_rand($dice_sets)];
		print_r($dice_set);
		*/

		/*
		$bet_amount = 2000;
		$vip_level = \App\Model\MVipLevel::where('bet_amount', '<=', $bet_amount)
			->orderBy('bet_amount', 'DESC')
			->first();
		print_r($vip_level->toArray());
		*/

		/*
		$account = 'kyo';
		$today = '2018-09-17';//date('Y-m-d');
		$play_count = \App\Model\DPlayRecord::where('account', '=', $account)
			->whereBetween('created_at', ["{$today} 00:00:00", "{$today} 23:59:59"])
			->count();
		echo $play_count;
		*/

		/*
		$rows = \App\Model\MReward::all();
		$r = $this->playLot($rows);
		print_r($r->toArray());
		*/

		/*
		$request->session()->forget('user');

		$request->session()->put('user', 'Iori');
		$request->session()->put('user.name', 'kyo');
		$request->session()->put('user.age', 30);
		$request->session()->put('user.skills', ['HTML', 'PHP', 'MySQL']);

		$user = $request->session()->get('user');
		print_r($user);
		*/

		/*
		$username = 'kyoTest';
		$member = \App\Model\DMember::where('account', '=', $username)->first();
		echo $member ? 'Y' : 'N';
		echo "\r\n";
		echo get_class($member);
		*/

		/*
		$name = $request->input('name');
		$age = $request->query('age');
		$sex = $request->post('sex');

		echo "name:{$name}, age:{$age}, sex:{$sex}";
		*/
	}

}

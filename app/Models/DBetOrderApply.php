<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DBetOrderApply extends Model
{

	const DEPOSITED_DEFAULT = 0;
	const DEPOSITED_SUCCESS = 1;
	const DEPOSITED_REJECTED = 2;

	protected $table = 'd_bet_order_apply';
	protected $primaryKey = 'bet_order_apply_id';

	/* Indicates if the model should be timestamped. (沒有 created_at, updated_at 欄位)
	public $timestamps = false;
	*/

}

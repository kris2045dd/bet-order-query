<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DBetOrderApply extends Model
{

	protected $table = 'd_bet_order_apply';
	protected $primaryKey = 'bet_order_apply_id';

	// Indicates if the model should be timestamped. (沒有 created_at, updated_at 欄位)
	public $timestamps = false;

}

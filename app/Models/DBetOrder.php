<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DBetOrder extends Model
{

	protected $table = 'd_bet_order';
	protected $primaryKey = 'bet_order_id';

	// Indicates if the model should be timestamped. (沒有 created_at, updated_at 欄位)
	public $timestamps = false;

}

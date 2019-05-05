<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MActivity extends Model
{

	protected $table = 'm_activity';
	protected $primaryKey = 'activity_id';

	// one to many
	public function m_activity_rule()
	{
		return $this->hasMany(\App\Models\MActivityRule::class, 'activity_id', 'activity_id')
			->orderBy('order', 'DESC');
	}

}

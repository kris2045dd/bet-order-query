<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MActivityRule extends Model
{

	protected $table = 'm_activity_rule';
	protected $primaryKey = 'activity_rule_id';

	protected $fillable = ['name', 'rule', 'order', 'disabled'];

	// one to many (inverse)
	public function m_activity()
	{
		return $this->belongsTo(\App\Models\MActivity::class, 'activity_id', 'activity_id');
	}

}

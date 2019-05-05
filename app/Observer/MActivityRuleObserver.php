<?php

namespace App\Observer;

use App\Models\MActivityRule;

class MActivityRuleObserver
{
	// Handle the User "creating" event.
	public function creating(MActivityRule $model)
	{
	}

	// Handle the User "created" event.
	public function created(MActivityRule $model)
	{
	}

	// Handle the User "updating" event.
	public function updating(MActivityRule $model)
	{
	}

	// Handle the User "updated" event.
	public function updated(MActivityRule $model)
	{
	}

	// Handle the User "saving" event.
	public function saving(MActivityRule $model)
	{
		// 檢查 Rule 格式
		if ($model->rule == '') {
			throw new \Exception("规格不可为空. ({$model->name})");
		}
		$activity_class = '\App\Activities\Activity' . $model->activity_id;
		if (! class_exists($activity_class)) {
			throw new \Exception("未知的活动. (activity_id: {$model->activity_id})");
		}
		if (! preg_match($activity_class::RULE_PATTERN, $model->rule)) {
			throw new \Exception("规则格式不符. ({$model->name})");
		}
	}

	// Handle the User "saved" event.
	public function saved(MActivityRule $model)
	{
	}

	// Handle the User "deleting" event.
	public function deleting(MActivityRule $model)
	{
	}

	// Handle the User "deleted" event.
	public function deleted(MActivityRule $model)
	{
	}

}

<?php

namespace App\Managers;

class Activity extends ManagerBase
{

	protected $items = [];

	public function __construct()
	{
		$activities = \App\Models\MActivity::where('disabled', '=', 0)->get();
		foreach ($activities as $activity) {
			$this->items[] = [
				'activity_id' => $activity->activity_id,
				'activity_class' => $this->getActivityClassById($activity->activity_id),
				'rules' => $activity->m_activity_rule()->where('disabled', '=', 0)->get(),
			];
		}
	}

	// 取得符合的规则
	public function getMatchedRules(\App\Models\DBetOrder $bet_order)
	{
		$matched_rules = [];

		foreach ($this->items as $item) {
			foreach ($item['rules'] as $rule) {
				$result = $item['activity_class']->match($bet_order, $rule);
				if ($result['matched']) {
					$matched_rules[] = [
						'activity_id' => $rule->activity_id,
						'activity_rule_id' => $rule->activity_rule_id,
						'bonus' => $result['bonus'],
					];
					// 若匹配則判斷下個活動
					continue 2;
				}
			}
		}

		return $matched_rules;
	}

	public function getActivityClassById($activity_id)
	{
		$activity_class = '\App\Activities\Activity' . $activity_id;
		if (! class_exists($activity_class)) {
			throw new \Exception('Class 不存在. (' . $activity_class . ')');
		}

		return new $activity_class();
	}

}

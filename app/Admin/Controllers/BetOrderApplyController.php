<?php

namespace App\Admin\Controllers;

use App\Models\DBetOrderApply;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BetOrderApplyController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('注单申请')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('注单申请')
            ->description('检视')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('注单申请')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('注单申请')
            ->description('新建')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DBetOrderApply);

		// 資料
		$activity_options = $this->getActivityOptions();
		$activity_rule_options = $this->getActivityRuleOptions();

		// 由新到舊
		$grid->model()->orderBy('bet_order_id', 'DESC');

		// 關閉選擇器
		$grid->disableRowSelector();
		// 自訂搜尋
		$grid->filter(function ($filter) use ($activity_options, $activity_rule_options) {
			// Remove the default id filter
			$filter->disableIdFilter();

			// 篩選條件
			$filter->like('bet_order_id', '注单号');
			$filter->like('username', '帐号');
			$filter->like('platform', '平台');
			$filter->like('game_name', '游戏名称');
			$filter->where(function ($query) {
				$query->where('bet_amount', '>=', $this->input);
			}, '投注金额 (>=)')->currency();
			$filter->where(function ($query) {
				$query->where('bet_amount', '<', $this->input);
			}, '投注金额 (<)')->currency();
			$filter->where(function ($query) {
				$query->where('payout_amount', '>=', $this->input);
			}, '派彩金额 (>=)')->currency();
			$filter->where(function ($query) {
				$query->where('payout_amount', '<', $this->input);
			}, '派彩金额 (<)')->currency();
			$filter->between('bet_time', '投注时间')->datetime();
			$filter->equal('activity_id', '活动')->select($activity_options);
			$filter->equal('activity_rule_id', '规则')->select($activity_rule_options);
			$filter->equal('deposited', '已派彩')->radio(array_replace(
				['' => '全部'],
				[
					'0' => '否',
					'1' => '是',
				]
			));
		});
		// 關閉新建按鈕
		$grid->disableCreateButton();
		// 關閉操作按鈕
		$grid->disableActions();

		$grid->column('bet_order_id', '注单号');
		$grid->column('username', '帐号');
		$grid->column('platform', '平台');
		$grid->column('game_name', '游戏名称');
		$grid->column('bet_amount', '投注金额');
		$grid->column('payout_amount', '派彩金额');
		$grid->column('bet_time', '投注时间');
		$grid->column('activity_id', '活动')->display(function ($activity_id) use ($activity_options) {
			if (isset($activity_options[$activity_id])) {
				return $activity_options[$activity_id];
			}
			return "未知 ({$activity_id})";
		});
		$grid->column('activity_rule_id', '规则')->display(function ($activity_rule_id) use ($activity_rule_options) {
			if (isset($activity_rule_options[$activity_rule_id])) {
				return $activity_rule_options[$activity_rule_id];
			}
			return "未知 ({$activity_rule_id})";
		});
		$grid->column('bonus', '彩金');
		$grid->column('deposited', '已派彩')->switch([
			'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		]);

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(DBetOrderApply::findOrFail($id));

        $show->bet_order_id('Bet order id');
        $show->username('Username');
        $show->platform('Platform');
        $show->game_name('Game name');
        $show->bet_amount('Bet amount');
        $show->payout_amount('Payout amount');
        $show->bet_time('Bet time');
        $show->activity_id('Activity id');
        $show->activity_rule_id('Activity rule id');
        $show->bonus('Bonus');
        $show->deposited('Deposited');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DBetOrderApply);

        $form->text('username', 'Username');
        $form->text('platform', 'Platform');
        $form->text('game_name', 'Game name');
        $form->decimal('bet_amount', 'Bet amount');
        $form->decimal('payout_amount', 'Payout amount');
        $form->datetime('bet_time', 'Bet time')->default(date('Y-m-d H:i:s'));
        $form->number('activity_id', 'Activity id');
        $form->number('activity_rule_id', 'Activity rule id');
        $form->decimal('bonus', 'Bonus');
        $form->switch('deposited', 'Deposited');

        return $form;
    }

	// 取得活動 options for select
	protected function getActivityOptions()
	{
		$rows = \App\Models\MActivity::select('activity_id', 'name')
			->get()
			->toArray();
		return array_column($rows, 'name', 'activity_id');
	}

	// 取得規則 options for select
	protected function getActivityRuleOptions()
	{
		$rows = \App\Models\MActivityRule::select('activity_rule_id', 'name')
			->orderBy('activity_id', 'ASC')
			->orderBy('order', 'DESC')
			->get()
			->toArray();
		return array_column($rows, 'name', 'activity_rule_id');
	}
}

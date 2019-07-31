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
		$deposited_table = $this->getDepositedTable();

		// 由新到舊
		$grid->model()->orderBy('bet_order_apply_id', 'DESC');

		// 關閉選擇器
		$grid->disableRowSelector();
		// 自訂搜尋
		$grid->filter(function ($filter) use ($activity_options, $deposited_table) {
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
			$filter->equal('activity_rule_id', '规则')->select($this->getActivityRuleOptions(true));
			$filter->equal('deposited', '派彩状态')->radio(array_replace(
				['' => '全部'],
				$deposited_table
			));
			$filter->between('created_at', '申请日期')->datetime();
		});
		// 關閉新建按鈕
		$grid->disableCreateButton();
		/* 關閉操作按鈕
		$grid->disableActions();
		*/
		$grid->actions(function ($actions) {
			/*
			$actions->disableEdit();
			*/
			$actions->disableView();
			$actions->disableDelete();
			// 已申請
			if ($actions->row->deposited == \App\Models\DBetOrderApply::DEPOSITED_DEFAULT) {
				// 執行派彩 按钮
				$actions->append(new \App\Admin\Extensions\ExecDeposit($actions->getKey(), admin_base_path('betOrderApply/execDeposit')));
			}
		});

		//$grid->column('bet_order_apply_id', '编号');
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
		$grid->column('', '派彩状态')->display(function () use ($deposited_table) {
			if (isset($deposited_table[$this->deposited])) {
				return $deposited_table[$this->deposited];
			}
			return "未知 ({$this->deposited})";
		});
		$deposited_options = $deposited_table;
		unset($deposited_options[\App\Models\DBetOrderApply::DEPOSITED_DEFAULT]);
		unset($deposited_options[\App\Models\DBetOrderApply::DEPOSITED_SUCCESS]);
		$grid->column('deposited', '审核')->editable('select', $deposited_options);
		$grid->column('memo', '备注')->display(function ($memo) {
			return str_limit($memo, 16, ' ...');
		})->editable('textarea');
		//$grid->column('created_at', '申请日期');

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

		// 資料
		$activity_options = $this->getActivityOptions();
		$activity_rule_options = $this->getActivityRuleOptions();
		$deposited_table = $this->getDepositedTable();

		// 關閉工具
		$form->tools(function (Form\Tools $tools) {
			$tools->disableView();
			$tools->disableDelete();
			/*
			$tools->disableList();
			$tools->disableBackButton();
			$tools->disableListButton();
			*/
		});
		// 表單 Footer
		$form->footer(function ($footer) {
			$footer->disableReset();
			$footer->disableViewCheck();
			$footer->disableCreatingCheck();
			$footer->disableEditingCheck();
			$footer->disableSubmit();
			/*
			*/
		});

        $form->text('bet_order_id', '注单号')->readonly();
        $form->text('username', '帐号')->readonly();
        $form->text('platform', '平台')->readonly();
        $form->text('game_name', '游戏名称')->readonly();
		$form->currency('bet_amount', '投注金额')->attribute(['readonly' => 'readonly']);
        $form->currency('payout_amount', '派彩金额')->attribute(['readonly' => 'readonly']);
        $form->datetime('bet_time', '投注时间')->default(date('Y-m-d H:i:s'))->readonly();
		$form->select('activity_id', '活动')->options($activity_options)->readonly();
		$form->select('activity_rule_id', '规则')->options($activity_rule_options)->readonly();
        $form->currency('bonus', '彩金')->attribute(['readonly' => 'readonly']);
        $form->select('deposited', '派彩状态')->options($deposited_table)->rules('required', ['派彩状态 不可为空.']);
        $form->textarea('memo', '备注');
        $form->datetime('created_at', '申请日期')->default(date('Y-m-d H:i:s'))->readonly();

        return $form;
    }

	// 取得活動 options for select
	protected function getActivityOptions()
	{
		$rows = \App\Models\MActivity::select('activity_id', 'name')
			->orderBy('sort', 'ASC')
			->orderBy('activity_id', 'ASC')
			->get()
			->toArray();
		return array_column($rows, 'name', 'activity_id');
	}

	// 取得規則 options for select
	protected function getActivityRuleOptions($with_activity_name = false)
	{
		$sql =
			"SELECT
				ar.activity_rule_id,
				" . ($with_activity_name ? "CONCAT(ar.name, ' (', a.name, ')') AS name" : "ar.name") . "
			FROM m_activity_rule AS ar
				LEFT JOIN m_activity AS a USING(activity_id)
			ORDER BY a.sort ASC, a.activity_id ASC, ar.`order` ASC";
		$rows = \Illuminate\Support\Facades\DB::select($sql);
		return array_column($rows, 'name', 'activity_rule_id');
	}

	// 取得未派彩注單數量
	public function getUndepositedCount(\Illuminate\Http\Request $request)
	{
		$res = ['error' => '', 'data' => '', 'msg' => ''];

		try {
			$count = \App\Models\DBetOrderApply::where('deposited', 0)->count();

			// Response
			$res['error'] = -1;
			$res['data'] = $count;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}

	// 取得上分狀態表
	protected function getDepositedTable()
	{
		return [
			\App\Models\DBetOrderApply::DEPOSITED_DEFAULT => '已申请',
			\App\Models\DBetOrderApply::DEPOSITED_SUCCESS => '已派彩',
			\App\Models\DBetOrderApply::DEPOSITED_REJECTED => '拒绝',
			\App\Models\DBetOrderApply::DEPOSITED_MANUAL => '人工派彩',
		];
	}

	// 執行派彩
	public function execDeposit(\Illuminate\Http\Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			// 接收參數
			$pk = $request->input('pk');


			// 執行派彩
			$bet_order_apply = \App\Models\DBetOrderApply::findOrFail($pk);
			\App\Managers\Bot::getInstance()->deposit($bet_order_apply);


			// Response
			$res['error'] = -1;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
}

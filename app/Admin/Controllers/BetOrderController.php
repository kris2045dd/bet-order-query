<?php

namespace App\Admin\Controllers;

use App\Models\DBetOrder;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BetOrderController extends Controller
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
            ->header('注单')
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
            ->header('注单')
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
            ->header('注单')
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
            ->header('注单')
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
        $grid = new Grid(new DBetOrder);

		// 由新到舊
		$grid->model()->orderBy('bet_order_id', 'DESC');

		// 關閉選擇器
		$grid->disableRowSelector();
		// 自訂搜尋
		$grid->filter(function ($filter) {
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
		});
		// 關閉新建按鈕
		$grid->disableCreateButton();
		// 關閉操作按鈕
		$grid->disableActions();
		// 自訂工具
		$grid->tools(function ($tools) {
			$tools->append(new \App\Admin\Extensions\Tools\TruncateTable(admin_base_path('betOrder/truncate')));
		});

		$grid->column('bet_order_id', '注单号');
		$grid->column('username', '帐号');
		$grid->column('platform', '平台');
		$grid->column('game_name', '游戏名称');
		$grid->column('bet_amount', '投注金额');
		$grid->column('payout_amount', '派彩金额');
		$grid->column('bet_time', '投注时间');

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
        $show = new Show(DBetOrder::findOrFail($id));

        $show->bet_order_id('Bet order id');
        $show->username('Username');
        $show->platform('Platform');
        $show->game_name('Game name');
        $show->bet_amount('Bet amount');
        $show->payout_amount('Payout amount');
        $show->bet_time('Bet time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DBetOrder);

        $form->text('username', 'Username');
        $form->text('platform', 'Platform');
        $form->text('game_name', 'Game name');
        $form->decimal('bet_amount', 'Bet amount');
        $form->decimal('payout_amount', 'Payout amount');
        $form->datetime('bet_time', 'Bet time')->default(date('Y-m-d H:i:s'));

        return $form;
    }

	// 資料清空
	public function truncate(\Illuminate\Http\Request $request)
	{
		$res = ['error' => '', 'msg' => ''];

		try {
			\Illuminate\Support\Facades\DB::statement('TRUNCATE TABLE d_bet_order');

			// Response
			$res['error'] = -1;
		} catch (\Exception $e) {
			$res['error'] = $e->getCode();
			$res['msg'] = $e->getMessage();
		}

		return response()->json($res);
	}
}

<?php

namespace App\Admin\Controllers;

use App\Models\MBotSetting;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BotSettingController extends Controller
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
            ->header('机器人')
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
            ->header('机器人')
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
            ->header('机器人')
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
            ->header('机器人')
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
        $grid = new Grid(new MBotSetting);

		// 關閉選擇器
		$grid->disableRowSelector();
		// 關閉篩選器
		$grid->disableFilter();
		// 關閉新建按鈕
		$grid->disableCreateButton();
		// 關閉匯出按鈕
		$grid->disableExport();
		// 關閉操作按鈕
		//$grid->disableActions();
		$grid->actions(function ($actions) {
			/*
			$actions->disableEdit();
			*/
			$actions->disableView();
			$actions->disableDelete();
		});

		//$grid->column('bot_setting_id', '编号');
		$grid->column('api_url', 'API URL');
		$grid->column('login_url', '登录 URL');
		$grid->column('login_account', '登录帐号');
		$grid->column('login_password', '登录密码');
		$grid->column('auto_deposit', '自动派彩')->switch([
			'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		]);
		//$grid->column('created_at', '建立日期');
		//$grid->column('updated_at', '更新日期');

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
        $show = new Show(MBotSetting::findOrFail($id));

        $show->bot_setting_id('Bot setting id');
        $show->api_url('Api url');
        $show->login_url('Login url');
        $show->login_account('Login account');
        $show->login_password('Login password');
        $show->auto_deposit('Auto deposit');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MBotSetting);

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
			/*
			$footer->disableSubmit();
			*/
		});

        $form->url('api_url', 'API URL');
        $form->url('login_url', '登录 URL')->help('重要: 网址最后要加斜线 &quot;/&quot;');
        $form->text('login_account', '登录帐号');
        $form->text('login_password', '登录密码');
        $form->switch('auto_deposit', '自动派彩')->states([
			'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		]);

        return $form;
    }
}

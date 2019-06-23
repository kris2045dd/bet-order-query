<?php

namespace App\Admin\Controllers;

use App\Models\MSetting;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SettingController extends Controller
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
            ->header('网站设置')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('网站设置')
            ->description('检视')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('网站设置')
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
            ->header('网站设置')
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
        $grid = new Grid(new MSetting);

		// 關閉選擇器
		$grid->disableRowSelector();
		// 關閉搜尋
		$grid->disableFilter();
		// 關閉新建按鈕
		$grid->disableCreateButton();
		// 關閉匯出按鈕
		$grid->disableExport();
		// 關閉刪除按鈕
		$grid->actions(function ($actions) {
			/*
			$actions->disableEdit();
			*/
			$actions->disableView();
			$actions->disableDelete();
		});

		//$grid->column('setting_id', '编号');
		$grid->column('title', '网页标头');
		$grid->column('created_at', '建立日期');
		$grid->column('updated_at', '更新日期');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(MSetting::findOrFail($id));

        $show->setting_id('Setting id');
        $show->title('Title');
        $show->demo_account('Demo account');
        $show->link1('Link1');
        $show->link2('Link2');
        $show->link3('Link3');
        $show->link4('Link4');
        $show->link5('Link5');
        $show->link6('Link6');
        $show->link7('Link7');
        $show->link8('Link8');
        $show->link9('Link9');
        $show->link10('Link10');
        $show->link11('Link11');
        $show->link1_blank('Link1 blank');
        $show->link2_blank('Link2 blank');
        $show->link3_blank('Link3 blank');
        $show->link4_blank('Link4 blank');
        $show->link5_blank('Link5 blank');
        $show->link6_blank('Link6 blank');
        $show->link7_blank('Link7 blank');
        $show->link8_blank('Link8 blank');
        $show->link9_blank('Link9 blank');
        $show->link10_blank('Link10 blank');
        $show->link11_blank('Link11 blank');
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
        $form = new Form(new MSetting);

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

		$form->tab('基本设定', function ($form) {
			$form->text('title', '网页标头');
		})->tab('Header 连结设定', function ($form) {
			$form->url('link1', '最新优惠')->rules('nullable');
			$form->switch('link1_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link2', '升级模式')->rules('nullable');
			$form->switch('link2_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link3', '免登录充值中心')->rules('nullable');
			$form->switch('link3_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link4', '自助客服')->rules('nullable');
			$form->switch('link4_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link5', '代理加盟')->rules('nullable');
			$form->switch('link5_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link6', '官网首页')->rules('nullable');
			$form->switch('link6_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link22', '金沙赌场')->rules('nullable');
			$form->switch('link22_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link7', '24 在线客服')->rules('nullable');
			$form->switch('link7_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
		})->tab('选单连结设定', function ($form) {
			$form->url('link8', '一键入款')->rules('nullable');
			$form->switch('link8_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link9', '二十三大捕鱼机')->rules('nullable');
			$form->switch('link9_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link10', '申请大厅')->rules('nullable');
			$form->switch('link10_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link11', '天天红包')->rules('nullable');
			$form->switch('link11_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link12', '手机下注')->rules('nullable');
			$form->switch('link12_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link13', '满意度调查')->rules('nullable');
			$form->switch('link13_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link14', '资讯端')->rules('nullable');
			$form->switch('link14_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link15', '注册会员')->rules('nullable');
			$form->switch('link15_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
		})->tab('Footer 连结设定', function ($form) {
			$form->url('link16', '关于我们')->rules('nullable');
			$form->switch('link16_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link17', '联系我们')->rules('nullable');
			$form->switch('link17_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link18', '代理加盟')->rules('nullable');
			$form->switch('link18_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link19', '存款帮助')->rules('nullable');
			$form->switch('link19_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link20', '取款帮助')->rules('nullable');
			$form->switch('link20_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->url('link21', '常见问题')->rules('nullable');
			$form->switch('link21_blank', '另开')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
		});

        return $form;
    }
}

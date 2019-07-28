<?php

namespace App\Admin\Controllers;

use App\Models\MActivity;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ActivityController extends Controller
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
            ->header('活动')
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
            ->header('活动')
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
            ->header('活动')
            ->description('编辑')
            ->body($this->form($id)->edit($id));
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
            ->header('活动')
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
        $grid = new Grid(new MActivity);

		// 照 排序 順序
		$grid->model()->orderBy('sort', 'ASC')->orderBy('activity_id', 'ASC');

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

		//$grid->column('activity_id', '编号');
		$grid->column('name', '名称');
		$grid->column('disabled', '禁用')->switch([
			'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
			'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
		]);
		$grid->column('sort', '排序')->editable();
		$grid->column('created_at', '建立日期');
		$grid->column('updated_at', '更新日期');

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
        $show = new Show(MActivity::findOrFail($id));

        $show->activity_id('Activity id');
        $show->name('Name');
        $show->disabled('Disabled');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form(new MActivity);

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
			$form->text('name', '名称')->readonly();
			$form->switch('disabled', '禁用')->states([
				'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
				'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
			]);
			$form->number('sort', '排序')->min(0)->max(255)->rules('between:0,255');
		})->tab('規則設定', function ($form) use ($id) {
			$activity_class = '\App\Activities\Activity' . $id;
			if (class_exists($activity_class)) {
				$rule_pattern_sample = $activity_class::RULE_PATTERN_SAMPLE;
				$rule_desc = $activity_class::RULE_DESC;
			} else {
				$rule_pattern_sample = '';
				$rule_desc = '';
			}

			$html = <<<HTML
规则格式: {$rule_pattern_sample}
<br /><br />
范例说明:<br />
{$rule_desc}
HTML;
			$form->html($html);

			$form->hasMany('m_activity_rule', '', function (Form\NestedForm $form) {
				$form->text('name', '名称');
				$form->text('rule', '规则');
				$form->number('order', '顺序')->min(0)->default(0);
				$form->switch('disabled', '禁用')->states([
					'on'  => ['value' => 1, 'text' => '是', 'color' => 'primary'],
					'off' => ['value' => 0, 'text' => '否', 'color' => 'default'],
				]);
			});
		});

        return $form;
    }
}

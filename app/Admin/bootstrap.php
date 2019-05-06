<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

Encore\Admin\Form::forget(['map', 'editor']);


Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
	// 機器人監控
	$navbar->left(view('admin.tools.navbar_bot_monitor', [
		'api_url' => admin_base_path('bot/loginState'),
	]));
	// 鈴噹通知 (注單申請)
	$navbar->right(view('admin.tools.navbar_bell_notification', [
		'api_url' => admin_base_path('betOrderApply/getUndepositedCount'),
		'on_click_go_to' => admin_base_path('betOrderApply?deposited=0'),
	]));
});

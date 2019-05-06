<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

	$router->post('betOrder/truncate', 'BetOrderController@truncate');
	$router->post('betOrderApply/getUndepositedCount', 'BetOrderApplyController@getUndepositedCount');

	$router->get('bot', 'BotController@index');
	$router->post('bot/logIn', 'BotController@logIn');
	$router->post('bot/loginState', 'BotController@loginState');

	$router->resource('betOrder', BetOrderController::class);
	$router->resource('betOrderApply', BetOrderApplyController::class);
	$router->resource('activity', ActivityController::class);
	$router->resource('botSettings', BotSettingController::class);
	$router->resource('settings', SettingController::class);

});

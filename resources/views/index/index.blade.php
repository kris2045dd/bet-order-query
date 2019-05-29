<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0" />
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<title>{{ $m_setting->title }}</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
		<link rel="stylesheet" href="{{ asset('css/front/main.css') }}" />
	</head>
	<body ng-controller="BodyCtrl as body">

		<div class="wrap">
			<div class="header">
				<div class="jstop">
					<div class="content">
						<span class="header-text1">7x24</span>
						<span class="header-text2">小时在线电话客服</span>
						<span class="header-text3">00853-6258-0555</span>

						<div class="menber_btns">
							<a href="javascript:void(0)" class="login_btn" ng-hide="body.username" ng-click="body.loginPopup()">登录</a>
							<span id="login_name" style="display:inline-block" ng-show="body.username">欢迎，<span ng-bind="body.username"></span></span>
							<a href="javascript:void(0)" class="logout_btn" style="display:inline-block" ng-show="body.username" ng-click="body.logout()">登出</a>
						</div>

						<div class="likeul">
							<a id="ch" href="{{ $m_setting->link1 }}" {{ $m_setting->link1_blank ? 'target="_blank"' : '' }}>最新优惠</a>|
							<a href="{{ $m_setting->link2 }}" {{ $m_setting->link2_blank ? 'target="_blank"' : '' }}>升级模式</a>|
							<a id="ch1" href="{{ $m_setting->link3 }}" {{ $m_setting->link3_blank ? 'target="_blank"' : '' }}>免登录充值中心</a>|
							<a id="ch2" href="{{ $m_setting->link4 }}" {{ $m_setting->link4_blank ? 'target="_blank"' : '' }}>自助客服</a>|
							<a id="ch3" href="{{ $m_setting->link5 }}" {{ $m_setting->link5_blank ? 'target="_blank"' : '' }}>代理加盟</a>|
							<a id="ch4" href="{{ $m_setting->link6 }}" {{ $m_setting->link6_blank ? 'target="_blank"' : '' }}>官网首页</a>|
							<a href="javascript:void(0)" onclick="javascript:try{window.external.AddFavorite('http://7802004.com/','金沙赌场-注单查询系統');}catch(e){(window.sidebar)?window.sidebar.addPanel('金沙赌场-注单查询系統','http://7802004.com/',''):alert('请点击进入网站后使用按键 Ctrl+d 收藏');}">加入收藏</a>
						</div>

					</div>
				</div>

				<div class="jseondhead">
					<div class="content">
						<a class="jslogo" href="{{ $m_setting->link22 }}" {{ $m_setting->link22_blank ? 'target="_blank"' : '' }}>
							<img src="{{ asset('images/logo.png') }}" />
						</a>
						<img class="myAnim" src="{{ asset('images/animate.gif') }}" />
						<div class="kf_right">
							<a class="zxkf" href="{{ $m_setting->link7 }}" {{ $m_setting->link7_blank ? 'target="_blank"' : '' }}></a>
						</div>
					</div>
				</div>

				<div class="jsnav">
					<ul>
						<li><a href="{{ $m_setting->link8 }}" {{ $m_setting->link8_blank ? 'target="_blank"' : '' }}>一键入款</a></li>
						<li><a href="{{ $m_setting->link9 }}" {{ $m_setting->link9_blank ? 'target="_blank"' : '' }}>十五大捕鱼机</a></li>
						<li><a href="{{ $m_setting->link10 }}" {{ $m_setting->link10_blank ? 'target="_blank"' : '' }}>申请大厅</a></li>
						<li><a href="{{ $m_setting->link11 }}" {{ $m_setting->link11_blank ? 'target="_blank"' : '' }}>天天红包</a></li>
						<li><a href="{{ $m_setting->link12 }}" {{ $m_setting->link12_blank ? 'target="_blank"' : '' }}>手机下注</a></li>
						<li><a href="{{ $m_setting->link13 }}" {{ $m_setting->link13_blank ? 'target="_blank"' : '' }}>满意度调查</a></li>
						<li><a href="{{ $m_setting->link14 }}" {{ $m_setting->link14_blank ? 'target="_blank"' : '' }}>资讯端</a></li>
						<li><a href="{{ $m_setting->link15 }}" {{ $m_setting->link15_blank ? 'target="_blank"' : '' }}>注册会员</a></li>
					</ul>
				</div>
			</div>{{-- .header END --}}

			<div class="main">
				<div class="content">
					<form ng-controller="SearchFormCtrl as sf">
						<label for="game_palt">平台</label>
						<select id="game_palt" ng-model="body.qs.platform">
							<option value="">全部平台</option>
							<option value="BB电子">BB电子</option>
							<option value="MG电子">MG电子</option>
							<option value="GNS电子">GNS电子</option>
							<option value="ISB电子">ISB电子</option>
							<option value="PT电子">PT电子</option>
							<option value="HB电子">HB电子</option>
							<option value="PP电子">PP电子</option>
							<option value="JDB电子">JDB电子</option>
							<option value="大满贯电子">大满贯电子</option>
							<option value="RT电子">RT电子</option>
							<option value="SG电子">SG电子</option>
							<option value="SW电子">SW电子</option>
							<option value="BNG电子">BNG电子</option>
							<option value="WM电子">WM电子</option>
							<option value="Gti电子">Gti电子</option>
							<option value="CQ9电子">CQ9电子</option>
							<option value="KA电子">KA电子</option>
							<option value="AW电子">AW电子</option>
							<option value="FG电子">FG电子</option>
							<option value="IN电子">IN电子</option>
						</select>

						<button id="getquery" class="betdata_btn autoWave" type="button"
							ng-click="body.getBetOrders()"
							ng-disabled="sf.countdown_sec"
							ng-class="{notwork:sf.countdown_sec}">
							<span ng-show="sf.countdown_sec"><span ng-bind="sf.countdown_sec"></span>秒后...</span>获取注单数据</button>

						<a class="open_sreach_btn" href="javascript:void(0)"></a>
						<div class="search_bet">
							<label for="bet_dollar">注单<span>(仅限1元以上)</span></label>
							<input id="bet_dollar" type="text" placeholder="1" ng-model="body.qs.amount" />

							<label for="bet_tail">注单尾数</label>
							<input id="bet_tail" type="text" placeholder="88888888" ng-model="body.qs.tail_no" />

							<input id="matched" type="checkbox" ng-model="body.qs.matched" />
							<label for="matched">只显示中奖注单</label>

							{{--
							<button type="button" class="check_btn">查询</button>
							--}}
						</div>
					</form>{{-- form END --}}


					<div class="data_table style1">
						<div class="d_t">
							<div>平台<span>(游戏)</span></div>
							<div>游戏名称</div>
							<div>注单号</div>
							<div>下注时间</div>
							<div><span>下注</span>金额</div>
							<div>派彩金额</div>
							<div>一键办理</div>
						</div>

						<div class="d_default" ng-show="body.msg == 1">
							<p>您必须先登入后才可查询，</p>
							<p>请点选右上方「登录」按钮后，</p>
							<p>输入帐号及帐户余额后方可查询⋯</p>
						</div>

						<div class="d_default" ng-show="body.msg == 2">
							<p>请点击「获取注单数据」来查看最近的注单内容</p>
						</div>

						<div class="d_default" ng-show="body.msg == 3">
							<p>查无最近的注单</p>
						</div>

						<div class="d_list" ng-repeat="o in body.filtered_bet_orders |
							offset: (body.paginator.current - 1) * body.paginator.per_page |
							limitTo: body.paginator.per_page
							track by o.bet_order_id">
							<div ng-bind="::o.platform"></div>
							<div ng-bind="::o.game_name"></div>
							<div ng-bind="::o.bet_order_id"></div>
							<div ng-bind="::o.bet_time"></div>
							<div ng-bind="::o.bet_amount"></div>
							<div ng-bind="::o.payout_amount"></div>
							<div class="regs"><a href="javascript:void(0)" ng-show="body.applicable(o)" ng-click="body.activityApplying(o)">一键办理</a></div>
						</div>
					</div>{{-- .data_table END --}}

					{{--
					<p class="totalBetNum" style="display: none">
						搜寻单数：<span id="searchcount">0</span> / 总注单数：<span id="resultall">0</span>
					</p>
					--}}

					<nav class="text-center">
						<ul class="pagination"
							uib-pagination
							{{-- 顯示第一 & 最後 箭頭
							boundary-links="true"
							--}}
							boundary-link-numbers="true"
							force-ellipses="true"
							rotate="false"
							total-items="body.filtered_bet_orders.length"
							ng-model="body.paginator.current"
							items-per-page="body.paginator.per_page"
							max-size="9"
							previous-text="&lsaquo;"
							next-text="&rsaquo;"
							first-text="&laquo;"
							last-text="&raquo;"
							ng-show="body.filtered_bet_orders.length"></ul>
					</nav>

				</div>{{-- .conetnt END --}}
			</div>{{-- .main END --}}


			<div class="footer">
				<div class="footer_link">
					<a href="{{ $m_setting->link16 }}" {{ $m_setting->link16_blank ? 'target="_blank"' : '' }}>关于我们</a>
					<a href="{{ $m_setting->link17 }}" {{ $m_setting->link17_blank ? 'target="_blank"' : '' }}>联系我们</a>
					<a href="{{ $m_setting->link18 }}" {{ $m_setting->link18_blank ? 'target="_blank"' : '' }}>代理加盟</a>
					<br class="m" />
					<a href="{{ $m_setting->link19 }}" {{ $m_setting->link19_blank ? 'target="_blank"' : '' }}>存款帮助</a>
					<a href="{{ $m_setting->link20 }}" {{ $m_setting->link20_blank ? 'target="_blank"' : '' }}>取款帮助</a>
					<a href="{{ $m_setting->link21 }}" {{ $m_setting->link21_blank ? 'target="_blank"' : '' }}>常见问题</a>
				</div>
				<p>Copyright © 金沙赌场版权所有 Reserved</p>
			</div>{{-- .footer END --}}
		</div>{{-- .wrap END --}}


		{{-- 登入彈窗 --}}
		<div class="login_pop popup">
			<div class="popup_content">
				<a class="popup_close" href="javascript:void(0)" ng-click="body.closeLoginPopup()">关闭</a>
				<p>账号余额查询</p>
				<form id="checkLogin" name="login_form">
					<input name="username" type="text" placeholder="填写会员账号" />
					<input name="balance" type="text" placeholder="填写账户余额" />
					<a class="send_btn" href="javascript:void(0)" ng-click="body.login()">立即验证</a>
				</form>
			</div>
		</div>


		{{-- waiting --}}
		<div class="waiting">
			<div class="loading-ani-icon-1">
				<div></div><div></div><div></div>
				<div></div><div></div><div></div>
				<div></div><div></div><div></div>
				<div></div><div></div><div></div>
			</div>
		</div>




		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-animate.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular-touch.min.js"></script>
		<script src="{{ asset('js/front/ui-bootstrap-tpls-2.5.0.min.js') }}"></script>
		<script src="{{ asset('js/front/index.js') }}"></script>
		<script src="{{ asset('js/front/app.js') }}"></script>
		<script>
		{{-- Angular - Setting --}}
		angular.module("jsApp")
			.value("username", "{{ $member ? $member['username'] : '' }}")
			.constant("BASE_URI", "{{ url('/') }}");

		angular.bootstrap(document, ["jsApp"], {
			strictDi: true
		});
		</script>
	</body>
</html>

<!DOCTYPE html>
<html>
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
					<form>
						<label for="game_palt">平台</label>
						<select id="game_palt">
							<option value="all">全部平台</option>
						</select>

						<button id="getquery" class="betdata_btn autoWave" type="button" ng-click="body.getBetOrders()">获取注单数据</button>

						<a class="open_sreach_btn" href="javascript:void(0)"></a>
						<div class="search_bet">
							<label for="bet_dollar">注单<span>(仅限1元以上)</span></label>
							<input id="bet_dollar" type="text" placeholder="1" ng-model="body.qs.amount" />

							<label for="bet_tail">注单尾数</label>
							<input id="bet_tail" type="text" placeholder="88888888" ng-model="body.qs.tail_no" />

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

						<div class="d_default" ng-hide="body.username">
							<p>您必须先登入后才可查询，</p>
							<p>请点选右上方「登录」按钮后，</p>
							<p>输入帐号及帐户余额后方可查询⋯</p>
						</div>

						<div class="d_default" ng-show="body.username && !body.bet_orders.length">
							<p>请点击「获取注单数据」来查看最近的注单内容</p>
						</div>

						<div class="d_list" dir-paginate="o in body.bet_orders |
							tailNo: body.qs.tail_no |
							amountGreaterThan: body.qs.amount |
							itemsPerPage: body.paginator.per_page
							track by o.bet_order_id">
							<div>{* o.platform *}</div>
							<div>{* o.game_name *}</div>
							<div>{* o.bet_order_id *}</div>
							<div>{* o.bet_time *}</div>
							<div>{* o.bet_amount *}</div>
							<div>{* o.payout_amount *}</div>
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
							dir-pagination-controls
							direction-links="true"
							boundary-links="true"
							max-size="body.paginator.per_page"></ul>
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
		<script src="{{ asset('js/front/dirPagination.js') }}"></script>
		<script src="{{ asset('js/front/index.js') }}"></script>
		<script>
		$(function () {
			var ajax_sent = false,
				base_uri = "{{ url('/') }}",
				totalSec = 600;
			var countTime;

			{{-- Laravel - CSRF Protection --}}
			$.ajaxSetup({
				headers: {
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
				},
				beforeSend: function () {
					ajax_sent = true;
				},
				error: function (jqXHR) {
					if (jqXHR.status == "419") {
						if (confirm("Session 已失效，请重新整理页面.")) {
							location.reload();
						}
					} else {
						alert("发生错误.");
					}
				},
				complete: function (jqXHR, textStatus) {
					ajax_sent = false;
				}
			});


			{{-- Angular - Start --}}
			var app = angular.module("myApp", ["angularUtils.directives.dirPagination"]);

			app.config([
				"$interpolateProvider",
				function ($interpolateProvider) {
					$interpolateProvider.startSymbol("{*");
					$interpolateProvider.endSymbol("*}");
				}
			]);

			app.filter("offset", function () {
				return function (input, start) {
					start = parseInt(start, 10);
					return input.slice(start);
				};
			});

			app.filter("tailNo", function () {
				return function (items, no) {
					if (no == "") {
						return items;
					}
					var filtered = [], i = 0, len = items.length,
						reg = new RegExp(no + "$");
					for (; i < len; i++) {
						var item = items[i];
						if (reg.test(item.bet_order_id)) {
							filtered.push(item);
						}
					}
					return filtered;
				};
			});

			app.filter("amountGreaterThan", function () {
				return function (items, amount) {
					if (isNaN(amount) || amount=="") {
						return items;
					}
					var filtered = [], i = 0, len = items.length;
					for (; i < len; i++) {
						var item = items[i];
						if (item.bet_amount >= amount) {
							filtered.push(item);
						}
					}
					return filtered;
				};
			});

			app.filter("myCurrency", function () {
				return function (input) {
					input = input || "";
					return input.replace(/\.00*$/, "");
				};
			});

			app.controller("BodyCtrl", ["$scope", "$http", function ($scope, $http) {

				var that = this;

				this.username = "{{ $member ? $member['username'] : '' }}";
				this.bet_orders = [];
				this.paginator = {
					current: 1,
					per_page: 10
				};
				this.qs = {
					amount: "",
					tail_no: ""
				};


				this.loginPopup = function () {
					$(".login_pop").fadeIn().find("input[name='username']").focus();
				};

				this.closeLoginPopup = function () {
					$(".login_pop").fadeOut();
				};

				this.login = function () {
					if (ajax_sent) {
						return;
					}

					var f = login_form;
					if (f.username.value == "") {
						f.username.focus();
						return;
					} else if (f.balance.value == "") {
						f.balance.focus();
						return;
					}
					if (isNaN(f.balance.value)) {
						alert("帳戶餘額格式不正確 !");
						f.balance.focus(), f.balance.select();
						return;
					}

					$.ajax({
						url: base_uri + "/login",
						type: "post",
						dataType: "json",
						data: $(f).serialize(),
						success: function (res) {
							if (res.error == -1) {
								{{-- 登入成功 --}}
								alert("登录成功.");
								$(".login_pop").fadeOut();
								that.username = res.msg;
								f.username.value = "", f.balance.value = "";
								$scope.$digest();
							} else if (res.error == "100") {
								{{-- 會員已登入 --}}
								console.log("會員已登入");
							} else if (res.msg) {
								alert(res.msg);
							} else {
								alert("发生未知的错误.");
							}
						}
					});
				};

				this.logout = function () {
					if (! confirm("是否登出 ?")) {
						return;
					}
					$http.get(base_uri + "/logout")
						.then(function (response) {
							{{-- 登出成功 --}}
							that.username = "", that.bet_orders = [];
							alert("登出成功.");
							clearInterval(countTime);
							totalSec = 600;
						}, function (response) {
							alert("登出失败. (" + response.status + ": " + response.statusText + ")");
						});
				};

				this.getBetOrders = function () {
					if (ajax_sent) {
						return;
					}
					if (! that.username) {
						that.loginPopup();
						return;
					}

					$.ajax({
						url: base_uri + "/getBetOrders",
						type: "post",
						dataType: "json",
						beforeSend: function(){
							$('.waiting').fadeIn();
							ajax_sent = true;
						},
						success: function (res) {
							if (res.error == -1) {
								{{-- 資料取得成功 --}}
								that.bet_orders = res.data;
								$scope.$digest();
								clearInterval(countTime);
								countTime = setInterval(countdown, 1000);
							} else if (res.msg) {
								alert(res.msg);
								ajax_sent = false;
							} else {
								alert("发生未知的错误.");
								ajax_sent = false;
							}
						},
						complete: function(){
							$('.waiting').fadeOut();
						}
					});
				}

				this.applicable = function (bet_order) {
					{{-- TODO: 待調整 --}}
					return true;
				};

				this.activityApplying = function (bet_order) {
					if (ajax_sent) {
						return;
					}
					$.ajax({
						url: base_uri + "/activityApplying",
						type: "post",
						data: {
							bet_order_id: bet_order.bet_order_id
						},
						dataType: "json",
						success: function (res) {
							if (res.error == -1) {
								alert("申请成功.");
							} else if (res.msg) {
								alert(res.msg);
							} else {
								alert("发生未知的错误.");
							}
						}
					});
				}
			}]);

			angular.bootstrap(document, ["myApp"]);
			{{-- Angular - End --}}


			function countdown(){
				$('#getquery').text(totalSec+'秒后...获取注单数据');
				$('#getquery').addClass('notwork');
				if(totalSec > 0){
					totalSec --;
				}else if(totalSec == 0){
					clearInterval(countTime);
					totalSec = 600;
					$('#getquery').text('获取注单数据');
					$('#getquery').removeClass('notwork');
					ajax_sent = false;
				}
			}
		});
		</script>
	</body>
</html>

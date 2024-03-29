(function () {
	var ajax_sent = false;

	/* Laravel - CSRF Protection */
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
				alert("网络连线错误.");
			}
		},
		complete: function (jqXHR, textStatus) {
			ajax_sent = false;
		}
	});


	/* Angular - Start */
	var app = angular.module("jsApp", ["ui.bootstrap"]);

	app.config([
		"$interpolateProvider",
		"$compileProvider",
		function ($interpolateProvider, $compileProvider) {
			$interpolateProvider.startSymbol("{*");
			$interpolateProvider.endSymbol("*}");
			$compileProvider.commentDirectivesEnabled(false);
			$compileProvider.cssClassDirectivesEnabled(false);
		}
	]);

	app.filter("offset", function () {
		return function (input, start) {
			start = parseInt(start, 10);
			return input.slice(start);
		};
	});

	app.filter("platform", function () {
		return function (items, platform) {
			if (platform == "") {
				return items;
			}
			var filtered = [], i = 0, len = items.length;
			for (; i < len; i++) {
				var item = items[i];
				if (item.platform == platform) {
					filtered.push(item);
				}
			}
			return filtered;
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
			var filtered = [], i = 0, len = items.length, amount = parseFloat(amount);
			for (; i < len; i++) {
				var item = items[i], bet_amount = parseFloat(item.bet_amount);
				if (bet_amount >= amount) {
					filtered.push(item);
				}
			}
			return filtered;
		};
	});

	app.filter("matchedOnly", function () {
		return function (items, activity_id) {
			if (activity_id === "") {
				return items;
			}
			var filtered = [], i = 0, len = items.length;
			for (; i < len; i++) {
				var item = items[i];
				if (item.matched) {
					// 所有中獎注單
					if (activity_id == "all") {
						filtered.push(item);
					} else if (item.matched == activity_id) {
						filtered.push(item);
					}
				}
			}
			return filtered;
		};
	});

	app.filter("targetDate", function () {
		return function (items, target_date) {
			if (target_date.val === "") {
				return items;
			}
			var filtered = [], i = 0, len = items.length,
				reg = new RegExp("^" + target_date.val);
			for (; i < len; i++) {
				var item = items[i];
				if (reg.test(item.bet_time)) {
					filtered.push(item);
				}
			}
			return filtered;
		};
	});

	app.filter("appliedStatus", function () {
		return function (deposited) {
			switch (deposited + "") {
				case "0":
					return "进度查询";
				case "1":
					return "已派彩";
				case "2":
					return "拒绝";
				default:
					return "一键办理";
			}
		};
	});

	app.filter("depositedStatus", function () {
		return function (deposited) {
			switch (deposited + "") {
				case "1":
					return "已派彩";
				case "2":
					return "拒绝";
				default:
					return "申请中";
			}
		};
	});

	/*
		活動 service

		活動參考:
			http://7182004.com/
	*/
	app.service("activityService", activityService);
	activityService.$inject = ["$http", "BASE_URI"];
	function activityService($http, BASE_URI) {
		var activities = [],
			manager = {
				activity1: new Activity1(),
				activity2: new Activity2(),
				activity3: new Activity3(),
				activity4: new Activity4(),
				activity5: new Activity5()
			};

		$http.get(BASE_URI + "/getActivities")
			.then(function (response) {
				activities = response.data.data;
			});

		this.markMatched = function (bet_order) {
			var i = 0, len = activities.length;
			for (; i < len; i++) {
				var activity_class = manager["activity" + activities[i].activity_id];
				if (! activity_class) {
					continue;
				}
				var j = 0; j_len = activities[i].rules.length;
				for (; j < j_len; j++) {
					if (activity_class.isMatch(bet_order, activities[i].rules[j])) {
						var bonus = activity_class.getBonus(bet_order, activities[i].rules[j]);
						bet_order.matched = activities[i].activity_id;
						bet_order.bonus = bonus ? parseFloat(parseFloat(bonus).toFixed(2)) : "";
						return;
					}
				}
			}
			bet_order.matched = 0;
			bet_order.bonus = "";
		};

		/*
			電子五重曲 - 老虎機 300 倍彩金

			參與遊戲: BB電子老虎機系列
		*/
		function Activity1() {
			this.isMatch = function (bet_order, rule) {
				// BB電子 限定
				if (bet_order.platform != "BB电子") {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				reg = new RegExp(rule.split("|")[0] + "$");
				if (reg.test(bet_order.bet_order_id)) {
					return true;
				}
				return false;
			};

			this.getBonus = function (bet_order, rule) {
				var r = rule.split("|"), bonus;
				bonus = bet_order.bet_amount * r[1];
				if (bonus > r[2]) {
					bonus = r[2];
				}
				return bonus;
			};
		}

		/*
			電子六重曲 - 畅享赔率彩金

			註: BBIN電子 & BBII電子 不參與此項優惠
		*/
		function Activity2() {
			this.isMatch = function (bet_order, rule) {
				// BB電子 或 BBII電子 或 非電子(棋牌) 不參與此項活動
				if (bet_order.platform=="BB电子" || bet_order.platform=="BBII电子" || bet_order.platform.indexOf("电子")==-1) {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				// 派彩金額 <= 0
				if (bet_order.payout_amount <= 0) {
					return false;
				}
				if ((bet_order.payout_amount / bet_order.bet_amount) >= rule.split("|")[0]) {
					return true;
				}
				return false;
			};

			this.getBonus = function (bet_order, rule) {
				var r = rule.split("|"), bonus;
				bonus = bet_order.bet_amount * r[1];
				if (bonus > r[2]) {
					bonus = r[2];
				}
				return bonus;
			};
		}

		/*
			暢玩棋牌 - 第 2 惠

			註: 限 開元棋牌、BB棋牌
		*/
		function Activity3() {
			this.isMatch = function (bet_order, rule) {
				// 活動只限 開元棋牌、BB棋牌、易游棋牌
				if (bet_order.platform!="开元棋牌" && bet_order.platform!="BB棋牌" && bet_order.platform!="易游棋牌") {
					return false;
				}
				// 三公、森林舞會、金鯊銀鯊、奔馳寶馬 遊戲除外
				if (bet_order.game_name=='三公' || bet_order.game_name=='森林舞会' || bet_order.game_name=='金鲨银鲨' || bet_order.game_name=='奔驰宝马') {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				// 派彩金額 <= 0
				if (bet_order.payout_amount <= 0) {
					return false;
				}
				if ((bet_order.payout_amount / bet_order.bet_amount) >= rule.split("|")[0]) {
					return true;
				}
				return false;
			};

			this.getBonus = function (bet_order, rule) {
				var r = rule.split("|"), bonus;
				bonus = bet_order.bet_amount * r[1];
				if (bonus > r[2]) {
					bonus = r[2];
				}
				return bonus;
			};
		}

		/*
			捕魚大師 捕魚達人 - 超級獎上獎

			註: 限 BBIN捕魚大師、BBIN捕魚達人
		*/
		function Activity4() {
			this.isMatch = function (bet_order, rule) {
				// 活動只限 BBIN捕魚大師、BBIN捕魚達人
				if (bet_order.platform!="BB捕鱼大师" && bet_order.platform!="BB捕鱼达人") {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				// 派彩金額 <= 0
				if (bet_order.payout_amount <= 0) {
					return false;
				}
				if (bet_order.payout_amount >= parseInt(rule.split("|")[0], 10)) {
					return true;
				}
				return false;
			};

			this.getBonus = function (bet_order, rule) {
				return "";
				/* 不顯示 可獲彩金 金額
				var r = rule.split("|"), bonus;
				bonus = r[1];
				return bonus;
				*/
			};
		}

		/*
			電子四重曲 - 盈利加贈

			參與遊戲: BB電子、BBII電子
		*/
		function Activity5() {
			this.isMatch = function (bet_order, rule) {
				// BB電子 & BBII電子 限定
				if (bet_order.platform!="BB电子" && bet_order.platform!="BBII电子") {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				// 派彩金額 <= 0
				if (bet_order.payout_amount <= 0) {
					return false;
				}
				// 用 (投注 + 派彩) 計算
				if ((bet_order.bet_amount + bet_order.payout_amount) >= parseInt(rule.split("|")[0], 10)) {
					return true;
				}
				return false;
			};

			this.getBonus = function (bet_order, rule) {
				var r = rule.split("|"), bonus;
				bonus = r[1];
				return bonus;
			};
		}
	}

	app.controller("BodyCtrl", BodyCtrl);
	BodyCtrl.$inject = [
		"$scope",
		"$http",
		"platformFilter",
		"tailNoFilter",
		"amountGreaterThanFilter",
		"matchedOnlyFilter",
		"targetDateFilter",
		"orderByFilter",
		"activityService",
		"username",
		"BASE_URI"
	];
	function BodyCtrl($scope, $http, platformFilter, tailNoFilter, amountGreaterThanFilter, matchedOnlyFilter, targetDateFilter, orderByFilter, activityService, username, BASE_URI) {
		// View Model
		var vm = this;

		vm.username = username;
		vm.bet_orders = [];
		vm.filtered_bet_orders = [];
		vm.applied_bet_orders = [];
		vm.msg = username ? 2 : 1;
		vm.progress_msg = "";
		vm.paginator = {
			current: 1,
			per_page: 10
		};
		vm.qs = {
			platform: "",
			amount: "",
			tail_no: "",
			matched: "",
			target_date: ""
		};
		vm.target_date_options = [
			{label:"所有时间", val:""},
			{label:moment().format("YYYY-MM-DD"), val:moment().format("YYYY-MM-DD")},
			{label:moment().subtract(1, "days").format("YYYY-MM-DD"), val:moment().subtract(1, "days").format("YYYY-MM-DD")}
		];
		vm.order_by = {
			column: "bonus",
			reverse: true
		};

		function filterBetOrders() {
			var filtered_bet_orders = matchedOnlyFilter(vm.bet_orders, vm.qs.matched);
			filtered_bet_orders = targetDateFilter(filtered_bet_orders, vm.qs.target_date);
			filtered_bet_orders = tailNoFilter(filtered_bet_orders, vm.qs.tail_no);
			filtered_bet_orders = platformFilter(filtered_bet_orders, vm.qs.platform);
			filtered_bet_orders = amountGreaterThanFilter(filtered_bet_orders, vm.qs.amount);
			if (vm.qs.matched == "all") {
				filtered_bet_orders = orderByFilter(filtered_bet_orders, vm.order_by.column, vm.order_by.reverse);
			}
			vm.filtered_bet_orders = filtered_bet_orders;
		}

		$scope.$watch(function () {return vm.qs;}, function(new_val, old_val) {
			filterBetOrders();
		}, true);

		vm.loginPopup = function () {
			$(".login_pop").fadeIn().find("input[name='username']").focus();
		};

		vm.closeLoginPopup = function () {
			$(".login_pop").fadeOut();
		};

		vm.login = function () {
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
				url: BASE_URI + "/login",
				type: "post",
				dataType: "json",
				data: $(f).serialize(),
				beforeSend: function () {
					ajax_sent = true;
					showLoading("登录中...");
				},
				success: function (res) {
					/* 登入成功 or 會員已登入 */
					if (res.error==-1 || res.error==100) {
						alert("登录成功.");
						vm.closeLoginPopup();
						vm.username = res.msg;
						vm.msg = 2;
						f.username.value = "", f.balance.value = "";
						$scope.$broadcast("afterLogin", vm.username);
						$scope.$digest();
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
				},
				complete: function () {
					closeLoading();
					ajax_sent = false;
				}
			});
		};

		vm.logout = function () {
			if (! confirm("是否登出 ?")) {
				return;
			}
			$http.get(BASE_URI + "/logout")
				.then(function (response) {
					/* 登出成功 */
					var username = vm.username;
					vm.username = "", vm.bet_orders = vm.filtered_bet_orders = [], vm.msg = 1;
					$scope.$broadcast("afterLogout", username);
					alert("登出成功.");
				}, function (response) {
					alert("登出失败. (" + response.status + ": " + response.statusText + ")");
				});
		};

		function markMatchedOrder() {
			var i = 0, len = vm.bet_orders.length;
			for (; i < len; i++) {
				activityService.markMatched(vm.bet_orders[i]);
			}
		}

		vm.getBetOrders = function () {
			if (ajax_sent) {
				return;
			}
			if (! vm.username) {
				vm.loginPopup();
				return;
			}

			$.ajax({
				url: BASE_URI + "/getBetOrders",
				type: "post",
				dataType: "json",
				beforeSend: function () {
					ajax_sent = true;
					showLoading("注单数据获取中，请耐心等待...");
				},
				success: function (res) {
					if (res.error == -1) {
						/* 資料取得成功 */
						vm.bet_orders = res.data;
						vm.msg = res.data.length ? 0 : 3;
						markMatchedOrder();
						filterBetOrders();
						$scope.$broadcast("afterGettingData");
						$scope.$digest();
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
				},
				complete: function () {
					closeLoading();
					ajax_sent = false;
				}
			});
		};

		vm.applicable = function (bet_order) {
			if (bet_order.matched) {
				return true;
			}
			return false;
		};

		vm.activityApplying = function (bet_order) {
			if (ajax_sent) {
				return;
			}
			$.ajax({
				url: BASE_URI + "/activityApplying",
				type: "post",
				data: {
					bet_order_id: bet_order.bet_order_id
				},
				dataType: "json",
				beforeSend: function () {
					ajax_sent = true;
					showLoading("申请办理中，请耐心等待...");
				},
				success: function (res) {
					if (res.error == -1) {
						alert("申请成功.");
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
					if (res.data !== "") {
						$.extend(bet_order, res.data);
						$scope.$digest();
					}
				},
				complete: function () {
					closeLoading();
					ajax_sent = false;
				}
			});
		};

		vm.showMemo = function (memo) {
			alert(memo);
		};

		vm.queryProgress = function () {
			if (ajax_sent) {
				return;
			}
			if (! vm.username) {
				vm.loginPopup();
				return;
			}

			$.ajax({
				url: BASE_URI + "/queryProgress",
				type: "post",
				dataType: "json",
				beforeSend: function () {
					ajax_sent = true;
					showLoading("进度查询中，请耐心等待...");
				},
				success: function (res) {
					if (res.error == -1) {
						/* 資料取得成功 */
						vm.applied_bet_orders = res.data;
						vm.progress_msg = res.data.length ? "" : "no data";
						updateBetOrders();
						$scope.$digest();
						progressPopup();
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
				},
				complete: function () {
					closeLoading();
					ajax_sent = false;
				}
			});
		};

		function progressPopup() {
			$(".progress_pop").fadeIn();
		}

		vm.closeProgressPopup = function () {
			$(".progress_pop").fadeOut();
		};

		function updateBetOrders() {
			if (! vm.applied_bet_orders) {
				return;
			}
			var i = 0, len = vm.bet_orders.length;
			for (; i < len; i++) {
				var o = vm.bet_orders[i], ao,
					j = 0, j_len = vm.applied_bet_orders.length;
				for (; j < j_len; j++) {
					ao = vm.applied_bet_orders[j];
					if (o.bet_order_id == ao.bet_order_id) {
						$.extend(o, {
							deposited: ao.deposited,
							memo: ao.memo
						});
					}
				}
			}
		}

		function showLoading(wording) {
			$(".waiting p").html(wording);
			$(".waiting").fadeIn();
		}

		function closeLoading() {
			$(".waiting").fadeOut();
		}
	}

	app.controller("SearchFormCtrl", ["$scope", "username", function ($scope, username) {
		// View Model
		var vm = this,
			_username = username,
			timeout_id;

		vm.countdown_sec = 0;
		vm.toggleSearchBet = function () {
			$(".search_bet").toggleClass("showSB");
		};

		function countdown() {
			if (--vm.countdown_sec <= 0) {
				stopCountdown();
			}
		}

		function startCountdown(sec) {
			vm.countdown_sec = sec ? sec : 600;
			// 先清掉前一次的倒數
			timeout_id && clearInterval(timeout_id);
			timeout_id = setInterval(function () {
				countdown();
				$scope.$digest();
			}, 1000);
		}

		function stopCountdown() {
			vm.countdown_sec = 0;
			timeout_id && clearInterval(timeout_id);
		}

		function getCountdownItemKey() {
			return "countdown_time:" + _username;
		}

		function saveCountdownTime() {
			if (typeof(Storage) === "undefined" || !vm.countdown_sec) {
				return;
			}
			var d = new Date();
			d.setTime(d.getTime() + (vm.countdown_sec * 1000));
			localStorage.setItem(getCountdownItemKey(), d.getTime());
		}

		function runCountdown() {
			if (typeof(Storage) == "undefined") {
				return;
			}
			var countdown_time = localStorage.getItem(getCountdownItemKey());
			if (countdown_time) {
				var now = new Date();
				countdown_time = parseInt(countdown_time, 10);
				if (countdown_time > now.getTime()) {
					var countdown_sec = parseInt((countdown_time - now.getTime()) / 1000, 10);
					startCountdown(countdown_sec);
				}
			}
		}

		$scope.$on("afterLogin", function (event, username) {
			// 檢查需不需要倒數
			_username = username;
			runCountdown();
		});

		$scope.$on("afterLogout", function (event, username) {
			stopCountdown();
		});

		$scope.$on("afterGettingData", function (event) {
			startCountdown();
			saveCountdownTime();
		});

		$scope.$on("$destroy", function (event) {
			stopCountdown();
		});

		runCountdown();
	}]);
	/* Angular - End */
})();

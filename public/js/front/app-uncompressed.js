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
				alert("发生错误.");
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

	app.filter("matchedOnly", function () {
		return function (items, matched_only) {
			if (! matched_only) {
				return items;
			}
			var filtered = [], i = 0, len = items.length;
			for (; i < len; i++) {
				var item = items[i];
				if (item.matched) {
					filtered.push(item);
				}
			}
			return filtered;
		};
	});

	app.filter("appliedStatus", function () {
		return function (deposited) {
			switch (deposited) {
				case 0:
					return "已申请";
				case 1:
					return "已派彩";
				default:
					return "一键办理";
			}
		};
	});

	/* 活動 service */
	app.service("activityService", activityService);
	activityService.$inject = ["$http", "BASE_URI"];
	function activityService($http, BASE_URI) {
		var activities = [],
			manager = {
				activity1: new Activity1(),
				activity2: new Activity2()
			};

		$http.get(BASE_URI + "/getActivities")
			.then(function (response) {
				activities = response.data.data;
			});

		this.isMatch = function (bet_order) {
			var i = 0, len = activities.length;
			for (; i < len; i++) {
				var activity_class = manager["activity" + activities[i].activity_id];
				if (! activity_class) {
					continue;
				}
				var j = 0; j_len = activities[i].rules.length;
				for (; j < j_len; j++) {
					if (activity_class.isMatch(bet_order, activities[i].rules[j])) {
						return true;
					}
				}
			}
			return false;
		};

		/*
			電子五重曲 - 老虎機 300 倍彩金
			參與遊戲: BB電子老虎機系列

			參考:
				http://7182004.com/
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
				var r = rule.split("|");
				reg = new RegExp(r[0] + "$");
				if (reg.test(bet_order.bet_order_id)) {
					return true;
				}
				return false;
			};
		}

		/*
			電子六重曲 - 畅享赔率彩金
			註: BBIN電子不參與此項優惠

			參考:
				http://7182004.com/
		*/
		function Activity2() {
			this.isMatch = function (bet_order, rule) {
				// BBIN電子 不參與此項活動
				if (bet_order.platform == "BB电子") {
					return false;
				}
				// 免費遊戲的注單，不參與活動 (至少 1 元)
				if (bet_order.bet_amount < 1) {
					return false;
				}
				if (bet_order.payout_amount <= 0) {
					return false;
				}
				var r = rule.split("|");
				if ((bet_order.payout_amount / bet_order.bet_amount) > r[0]) {
					return true;
				}
				return false;
			};
		}
	}

	app.controller("BodyCtrl", [
		"$scope",
		"$http",
		"platformFilter",
		"tailNoFilter",
		"amountGreaterThanFilter",
		"matchedOnlyFilter",
		"activityService",
		"username",
		"BASE_URI",
		function ($scope, $http, platformFilter, tailNoFilter, amountGreaterThanFilter, matchedOnlyFilter, activityService, username, BASE_URI) {

		// View Model
		var vm = this;

		vm.username = username;
		vm.bet_orders = [];
		vm.filtered_bet_orders = [];
		vm.msg = username ? 2 : 1;
		vm.paginator = {
			current: 1,
			per_page: 10
		};
		vm.qs = {
			platform: "",
			amount: "",
			tail_no: "",
			matched: false
		};

		function filterBetOrders() {
			var filtered_bet_orders = matchedOnlyFilter(vm.bet_orders, vm.qs.matched);
			filtered_bet_orders = tailNoFilter(filtered_bet_orders, vm.qs.tail_no);
			filtered_bet_orders = platformFilter(filtered_bet_orders, vm.qs.platform);
			filtered_bet_orders = amountGreaterThanFilter(filtered_bet_orders, vm.qs.amount);
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
					vm.username = "", vm.bet_orders = vm.filtered_bet_orders = [], vm.msg = 1;
					alert("登出成功.");
				}, function (response) {
					alert("登出失败. (" + response.status + ": " + response.statusText + ")");
				});
		};

		function markMatchedOrder() {
			var i = 0, len = vm.bet_orders.length;
			for (; i < len; i++) {
				vm.bet_orders[i].matched = activityService.isMatch(vm.bet_orders[i]) ? 1 : 0;
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
						$scope.$broadcast("afterGetData");
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
		}

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
						setBetOrderApplied(bet_order, res.data);
						$scope.$digest();
					}
				},
				complete: function () {
					closeLoading();
					ajax_sent = false;
				}
			});
		}

		function setBetOrderApplied(bet_order, applied) {
			bet_order.deposited = applied;
			var i = 0, len = vm.bet_orders.length;
			for (; i < len; i++) {
				var o = vm.bet_orders[i];
				if (o.bet_order_id == bet_order.bet_order_id) {
					o.deposited = applied;
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
	}]);

	app.controller("SearchFormCtrl", ["$scope", function ($scope) {
		// View Model
		var vm = this,
			timeout_id;

		vm.countdown_sec = 0;
		vm.toggleSearchBet = function () {
			$(".search_bet").slideToggle();
		};

		function countdown() {
			if (--vm.countdown_sec <= 0) {
				stopCountdown();
			}
		}

		function startCountdown(sec) {
			vm.countdown_sec = sec ? sec : 600;
			timeout_id = setInterval(function () {
				countdown();
				$scope.$digest();
			}, 1000);
		}

		function stopCountdown() {
			vm.countdown_sec = 0;
			timeout_id && clearInterval(timeout_id);
		}

		$scope.$on("afterGetData", function (event) {
			startCountdown();
		});

		$scope.$on("$destroy", function (event) {
			stopCountdown();
		});

		if (typeof(Storage) !== "undefined") {
			var countdown_time = localStorage.getItem("countdown_time");
			if (countdown_time) {
				var now = new Date();
				countdown_time = parseInt(countdown_time, 10);
				if (countdown_time > now.getTime()) {
					var countdown_sec = parseInt((countdown_time - now.getTime()) / 1000, 10);
					startCountdown(countdown_sec);
				}
			}
		}

		window.onbeforeunload = function () {
			if (typeof(Storage) === "undefined" || !vm.countdown_sec) {
				return;
			}
			var d = new Date();
			d.setTime(d.getTime() + (vm.countdown_sec * 1000));
			localStorage.setItem("countdown_time", d.getTime());
		};
	}]);
	/* Angular - End */
})();

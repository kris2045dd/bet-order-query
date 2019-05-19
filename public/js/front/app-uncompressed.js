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

	app.controller("BodyCtrl", [
		"$scope",
		"$http",
		"$interval",
		"tailNoFilter",
		"amountGreaterThanFilter",
		"username",
		"BASE_URI",
		function ($scope, $http, $interval, tailNoFilter, amountGreaterThanFilter, username, BASE_URI) {

		var that = this,
			timeout_id;

		that.username = username;
		that.bet_orders = [];
		that.filtered_bet_orders = [];
		that.paginator = {
			current: 1,
			per_page: 10
		};
		that.qs = {
			amount: "",
			tail_no: ""
		};
		that.countdown_sec = 0;

		function countdown() {
			if (--that.countdown_sec <= 0) {
				stopCountdown();
			}
		}

		function startCountdown(sec) {
			that.countdown_sec = sec ? sec : 600;
			timeout_id = $interval(countdown, 1000);
		}

		function stopCountdown() {
			that.countdown_sec = 0;
			timeout_id && $interval.cancel(timeout_id);
		}

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
			if (typeof(Storage) === "undefined" || !that.countdown_sec) {
				return;
			}
			var d = new Date();
			d.setTime(d.getTime() + (that.countdown_sec * 1000));
			localStorage.setItem("countdown_time", d.getTime());
		};

		function filterBetOrders() {
			var filtered_bet_orders = tailNoFilter(that.bet_orders, that.qs.tail_no);
			filtered_bet_orders = amountGreaterThanFilter(filtered_bet_orders, that.qs.amount);
			that.filtered_bet_orders = filtered_bet_orders;
		}

		$scope.$watch(function () {return that.qs;}, function(new_val, old_val) {
			filterBetOrders();
		}, true);

		that.loginPopup = function () {
			$(".login_pop").fadeIn().find("input[name='username']").focus();
		};

		that.closeLoginPopup = function () {
			$(".login_pop").fadeOut();
		};

		that.login = function () {
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
				success: function (res) {
					if (res.error == -1) {
						/* 登入成功 */
						alert("登录成功.");
						$(".login_pop").fadeOut();
						that.username = res.msg;
						f.username.value = "", f.balance.value = "";
						$scope.$digest();
					} else if (res.error == "100") {
						/* 會員已登入 */
						console.log("會員已登入");
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
				}
			});
		};

		that.logout = function () {
			if (! confirm("是否登出 ?")) {
				return;
			}
			$http.get(BASE_URI + "/logout")
				.then(function (response) {
					/* 登出成功 */
					that.username = "", that.bet_orders = [];
					alert("登出成功.");
				}, function (response) {
					alert("登出失败. (" + response.status + ": " + response.statusText + ")");
				});
		};

		that.getBetOrders = function () {
			if (ajax_sent) {
				return;
			}
			if (! that.username) {
				that.loginPopup();
				return;
			}

			$.ajax({
				url: BASE_URI + "/getBetOrders",
				type: "post",
				dataType: "json",
				beforeSend: function () {
					ajax_sent = true;
					$(".waiting").fadeIn();
				},
				success: function (res) {
					if (res.error == -1) {
						/* 資料取得成功 */
						that.bet_orders = res.data;
						filterBetOrders();
						startCountdown();
						$scope.$digest();
					} else if (res.msg) {
						alert(res.msg);
					} else {
						alert("发生未知的错误.");
					}
				},
				complete: function () {
					ajax_sent = false;
					$(".waiting").fadeOut();
				}
			});
		}

		that.applicable = function (bet_order) {
			/* TODO: 待調整 */
			return true;
		};

		that.activityApplying = function (bet_order) {
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
	/* Angular - End */
})();

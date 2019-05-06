<div class="bot-monitor" style="display:inline-block;margin:15px 0 0 4px;">
	<span class="bot-monitor-title" style="color:#fff" title="未登录." data-toggle="tooltip" data-placement="bottom" >机器人监控</span>
	<span class="hidden glyphicon glyphicon-ok" style="color:#0f0;" aria-hidden="true"></span>
	<span class="glyphicon glyphicon-remove" style="color:#f00;" aria-hidden="true"></span>
</div>


<script>
$(function () {
	var sent = false, logged_in = false;
	function updateBotMonitor() {
		if (sent) {
			return;
		}

		$.ajax({
			url: "{{ $api_url }}",
			type: "post",
			dataType: "json",
			headers: {
				"X-CSRF-TOKEN": LA.token
			},
			beforeSend: function () {
				sent = true;
			},
			success: function (res) {
				if (res.error == -1) {
					if (res.msg == "已登录." && !logged_in) {
						$(".bot-monitor-title").attr("title", res.msg).tooltip("fixTitle");
						$(".bot-monitor").find(".glyphicon-ok, .glyphicon-remove").toggleClass("hidden");
						logged_in = true;
					} else if (res.msg == "未登录." && logged_in) {
						$(".bot-monitor-title").attr("title", res.msg).tooltip("fixTitle");
						$(".bot-monitor").find(".glyphicon-ok, .glyphicon-remove").toggleClass("hidden");
						logged_in = false;
					}
				}
			},
			error: function (jqXHR, textStatus) {
			},
			complete: function () {
				sent = false;
			}
		});
	}
	updateBotMonitor();
	setInterval(updateBotMonitor, 60 * 1000);
});
</script>

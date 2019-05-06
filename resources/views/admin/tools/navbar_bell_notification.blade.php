<li>
	<a href="{{ $on_click_go_to }}">
		<i class="fa fa-bell-o"></i>
		<span id="navbar-bell-count" class="label label-warning"></span>
	</a>
</li>


<script>
$(function () {
	var sent = false;
	function updateBellCount() {
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
					$("#navbar-bell-count").text(res.data == 0 ? "" : res.data);
				}
			},
			error: function (jqXHR, textStatus) {
			},
			complete: function () {
				sent = false;
			}
		});
	}
	updateBellCount();
	setInterval(updateBellCount, 30 * 1000);
});
</script>

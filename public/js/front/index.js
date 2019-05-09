$(function () {
    new toggleColor('#ch', ['#fff', '#fae733'], 1400);
    new toggleColor('#ch1', ['#fff', '#fc36d8'], 600);
    new toggleColor('#ch2', ['#fff', '#27c246'], 1000);
    new toggleColor('#ch3', ['#fff', '#fc36d8'], 800);
    new toggleColor('#ch4', ['#fff', '#fc3658'], 600);

	function toggleColor(id, arr, s) {
		var self = this;
		self._i = 0;
		self._timer = null;

		self.run = function () {
			if (arr[self._i]) {
				$(id).css('color', arr[self._i]);
			}
			self._i == 0 ? self._i++ : self._i = 0;
			self._timer = setTimeout(function () {
				self.run(id, arr, s);
			}, s);
		}
		self.run();
	}
});

slidy = {
	page: 0
};

$(function () {
	$('pre:not([class])').addClass('jush-php');
	jush.style('jush.css');
	jush.highlight_tag('pre');

	if (location.hash) {
		slidy.page = parseInt(location.hash.substr(1));
	}

	$('div.slide').hide().eq(slidy.page).show();
});

$(window).keyup(function (e) {
	var current = $('div.slide:visible');

	if (e.keyCode == 37 && current.prev().size() > 0) {
		current.prev().show();
		current.hide();
		slidy.page--;
		location.hash = slidy.page;
	}

	if (e.keyCode == 39 && current.next().size() > 0) {
		current.next().show();
		current.hide();
		slidy.page++;
		location.hash = slidy.page;
	}
})
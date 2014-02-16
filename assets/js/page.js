$(document).ready(function() {

	updatePageOffset();

	function updatePageOffset() {
		var padding = 15;
		var width_toolbar = $('#toolbar').width();
		var width_sidebar = $('#sidebar').width();
		var correct_page_width = $(window).width() - width_toolbar - width_sidebar - (padding * 2);

		$('#page-container').css( { width: correct_page_width, marginLeft: width_toolbar + padding, marginRight: width_sidebar + padding } );
		console.log(width_sidebar);
	}

	$(window).resize(function() {
		updatePageOffset();
	});
});

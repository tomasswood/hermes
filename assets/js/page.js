$(document).ready(function() {

	updatePageOffset();

	function updatePageOffset() {
        // Elements
        var header = $('#header');
        var editorToolbar = $('#editor-toolbar');
        var leftToolbar = $('#left-toolbar');
        var sidebar = $('#sidebar');
        // Stuff
		var padding = 15;
        var height_header = header.height();
        var height_editortoolbar = editorToolbar.height();
		var width_toolbar = leftToolbar.width();
		var width_sidebar = sidebar.width();
		var correct_page_width = $(window).width() - width_toolbar - width_sidebar - (padding * 2);

        // Update Page Container
		$('#page-container').css( { width: correct_page_width, marginLeft: width_toolbar + padding, marginRight: width_sidebar + padding, paddingTop: (height_header + height_editortoolbar + (padding * 2)) } );
		console.log(width_sidebar);

        // Update top-offset of #toolbar & #sidebar
        leftToolbar.css( { top: (height_header + height_editortoolbar)});
        sidebar.css( { top: (height_header + height_editortoolbar)});
	}

	$(window).resize(function() {
		updatePageOffset();
	});
});

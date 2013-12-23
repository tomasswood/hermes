$(document).ready(function() {
	updateMinimisation();
	$('#sidebar-toggle').click(function() {
		toggleSidebar($(this));
	});
	$('.heading').click(function() {
		toggleHeading($(this));
	});
	$('.heading-toggle').click(function() {
		toggleHeading($(this));
	});
	function updateMinimisation() {
		$('.sidebar-inner').after('<div id="sidebar-toggle"></div>'); //Add our main minimisation toggle for the sidemenu
		$('ul.sidebar-submenu').before('<div class="heading-toggle"><span class="chevron glyphicon glyphicon-chevron-up"></span></div>'); //Add our sidemenu submenu minimisation toggle
	}

	function toggleSidebar(id) {
		var sidebar = id.parent(); //Gets the Parent <ul> object of our toggle
		//Calculate the sidebar width minus the toggle part
		var toggleWidth = sidebar.outerWidth() - id.outerWidth();
		//Check if the sidebar is expanded and also calculate the padding for #page
		if(sidebar.hasClass('expanded')) {
			$(sidebar).animate({'marginRight': -toggleWidth}).removeClass('expanded');
			$(".page").animate({'marginRight': 10});
		} else {
			$(sidebar).animate({'marginRight': 0}).addClass('expanded');
			$(".page").animate({'marginRight': toggleWidth - 10});
		}
	}

	function toggleHeading(id) {
		var heading_toggle = id;
		// Set heading to correct element, --> user didn't click chevron but span.heading
		if(heading_toggle.hasClass('heading')) {
			heading_toggle = id.next('.heading-toggle');
		}
		var sidebar = id.parent(); //Gets the Parent <ul> object of our toggle
		var child = sidebar.children(".sidebar-submenu"); //Grab the child <ul>
		//Check if the child <ul> is expanded or not
		if(child.hasClass('expanded')) {
			child.animate({'marginTop': -child.outerHeight()}).removeClass('expanded');
			heading_toggle.children('.chevron').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
		} else {
			child.animate({'marginTop': 0}).addClass('expanded');
			heading_toggle.children('.chevron').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
		}
	}
});

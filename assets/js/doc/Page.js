// ==============================================
// GLOBALS
// ==============================================
var ID_COUNTER = 1; // Increments for each element added
var A4_MULTIPLIER = 2.583;
var PX_TO_MM_MULTIPLIER = 0.0846;
var SELECTED = null;
var GRIDX = 20; // How large our X grid space is
var GRIDY = GRIDX; // How large our Y grid space is

// ----------------------------------
// ELEMENTS
// ----------------------------------
function addElement(element)
{
	activeDeselect();
	$('.page').append(element);
	// Reinitialize draggable so it recognizes dynamically added element
	initializeDraggable();
	// Change our Active Element
	SELECTED = $(element);
	sidebarShowElementData(getElementData(SELECTED));
	// Increment counter
	ID_COUNTER++;
}

function elementTextbox(id)
{
	var element_textbox = '<div id="ID'+ id +'" class="textbox element active" style="z-index:' + ID_COUNTER + ';" data-type="Textbox">'
						+ '<div class="toolbar">'
							+ '<div class="move"><span class="glyphicon glyphicon-move"></span></div>'
							+ '<div class="remove"><span class="glyphicon glyphicon-trash"></span></div>'
							+ '<div class="copy"><span class="glyphicon glyphicon-plus"></span></div>'
							+ '<div class="edit"><span class="glyphicon glyphicon-pencil"></span></div>'
						+ '</div>'
						+ '<div class="content"></div>'
					+ '</div>';

	return element_textbox;
}

function styleSelect(element)
{
	$('.modify').removeClass('modify');
	element.addClass('modify');
}

function styleDeselect(element)
{
	element.removeClass('modify');
}

function activeSelect(element)
{
	activeDeselect();
	element.children('.toolbar').show();
	element.addClass('active');
}

function activeDeselect()
{
	$('.toolbar').hide();
	$('.active').removeClass('active');
}

function highestZIndex(element)
{
	var index_highest = 0;
	// more effective to have a class for the div you want to search and
	// pass that to your selector
	$(element).each(function() {
		// always use a radix when using parseInt
		var index_current = parseInt($(this).css("zIndex"), 10);
		if(index_current > index_highest) {
			index_highest = index_current;
		}
	});
	return index_highest;
}

// ==============================================
// INTERFACE
// ==============================================
function initializeDraggable()
{
	// Draggable
	$( ".element" ).draggable({
		containment: "parent",
		start: function( element, ui ) {
			SELECTED = $(this);
			activeSelect(SELECTED);
			styleSelect(SELECTED);
		},
		drag: function( event, ui ){
			// Update sidebar #element-data as we drag
			sidebarShowElementData(getElementData(SELECTED));
		},
		stop: function( event, ui ){
			// Unstyle the element
			styleDeselect(SELECTED);
		},
		grid: [ GRIDX, GRIDY ],
		handle: ".toolbar"
	});

	// Resizable
	$( ".element" ).resizable({
		start: function( event, ui ) {
			SELECTED = ui.element;
			activeSelect(SELECTED);
			styleSelect(SELECTED);
		},
		resize: function( event, ui ){
			// Update sidebar #element-data as we resize
			sidebarShowElementData(getElementData(SELECTED));
		},
		stop: function( event, ui ){
			styleDeselect(SELECTED);
		},
		grid: [ GRIDX, GRIDY ]
	});
}

function changeActiveElement(element)
{
	activeSelect(element);
	sidebarShowElementData(getElementData(element));
}

// ==============================================
// GENERATE
// ==============================================
function pullPageData()
{
	var page_data = [];
	$('.page .element').each(function () {
		var cell = {
			"top": ($(this).position().top * A4_MULTIPLIER) * PX_TO_MM_MULTIPLIER,
			"left": ($(this).position().left * A4_MULTIPLIER) * PX_TO_MM_MULTIPLIER,
			"width": ($(this).width() * A4_MULTIPLIER) * PX_TO_MM_MULTIPLIER,
			"height": ($(this).height() * A4_MULTIPLIER) * PX_TO_MM_MULTIPLIER
		};
		page_data.push(cell);
	})
	return page_data;
}

// ==============================================
// AJAX
// ==============================================
function ajaxExportPDF(page_data)
{
	$.ajax({
		type: "POST",
		url: "create_pdf",
		data: {"data": JSON.stringify(page_data)},
		cache: false,
		success: function() {
			console.log(JSON.stringify(page_data));
			console.log("Exported succesfully!");
		}
	});
}

// ==============================================
// INTERACTION
// ==============================================
$(document).ready(function() {

	// ==============================================
	// TOOLBAR
	// ==============================================

	// Generate
	// ---------------------------------------------
	$('#toolbar-btn-generate').click(function() {
		ajaxExportPDF(pullPageData());
	});

	// Textbox
	// ---------------------------------------------
	$('#toolbar-btn-element-textbox').click(function() {
		addElement(elementTextbox(ID_COUNTER));
	});

	// ==============================================
	// RIGHT CLICK
	// ==============================================
	/*
		if (document.addEventListener) {
			document.addEventListener('contextmenu', function(e) {
				alert("You've tried to open context menu"); //here you draw your own menu
				e.preventDefault();
			}, false);
		} else {
			document.attachEvent('oncontextmenu', function() {
				alert("You've tried to open context menu");
				window.event.returnValue = false;
			});
		}
	*/
	// ==============================================
	// SELECTION
	// ==============================================
	$(document).click(function(e){
		var exclude_div = $("#context-menu");
		if( !exclude_div.is( e.target ) )  // if target div is not the one you want to exclude then add the class hidden
			$('#context-menu').hide();
	});
	$('.page').click(function() {
		activeDeselect();
		sidebarShowElementData(null);
	});
	$('.page').on('click', '.element' ,function(e){
		SELECTED = $(this);
		changeActiveElement(SELECTED);
		e.stopPropagation(); //THIS CUNT!
	});
	$('.page').on('mouseover', '.element' ,function(e){
		SELECTED = $(this);
		changeActiveElement(SELECTED);
		e.stopPropagation(); //THIS CUNT!
	});

	$('.page').on("contextmenu", ".element", function(e){
		SELECTED = $(this);
		changeActiveElement(SELECTED);
		e.stopPropagation(); //THIS CUNT!

		var y = $(this).position().top;
		var x = $(this).position().left;

		$("#context-menu").css({'top':y,'left':x});
		$('#context-menu').show();
		return false;
	});

	$('.page').on("click", "#move-top", function(e){
		var z_index = highestZIndex(".element");
		SELECTED.css('z-index', z_index);
	});

	$('.page').on("click", "#move-up", function(e){
		var z_index = parseInt(SELECTED.css('z-index'));
		SELECTED.css('z-index', z_index + 1);
	});

	$('.page').on("click", "#move-down", function(e){
		var z_index = parseInt(SELECTED.css('z-index'));
		SELECTED.css('z-index', z_index - 1);
	});

	$('.page').on("click", "#move-bottom", function(e){
		SELECTED.css('z-index', 0);
	});

	$('.page').on('click', '.edit' ,function(){

	});
	$('.page').on('click', '.remove' ,function(){
		$(this).parents('.element').remove();
	});
	$('.page').on('click', '.copy' ,function(){

		var orig_element = $(this).parents('.element');
		var new_element = orig_element.clone();

		new_element = new_element.attr("id","ID"+ID_COUNTER);
		ID_COUNTER++;

		orig_element.after(new_element);
	});
});
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
	activeDeselect(); // Unselect our Active element
	$('.page').append(element); // Add our new element to the page
	// Reinitialize draggable so it recognizes dynamically added element
	initializeDraggable();
	// Change our Active Element
	SELECTED = $(element);
	sidebarShowElementData(getElementData(SELECTED));
	// Increment counter
	ID_COUNTER++;
}

// Textbox element
function elementTextbox(id)
{
	var element_textbox = '<div id="ID'+ id +'" class="textbox element active" style="z-index:' + ID_COUNTER + ';" data-type="Textbox">'
						+ '<div class="toolbar">'
							+ '<div class="move"><span class="glyphicon glyphicon-move"></span></div>'
							+ '<div class="remove"><span class="glyphicon glyphicon-trash"></span></div>'
							+ '<div class="copy"><span class="glyphicon glyphicon-plus"></span></div>'
							+ '<div class="edit"><span class="glyphicon glyphicon-pencil"></span></div>'
						+ '</div>'
						+ '<div class="content form-control"></div>'
					+ '</div>';

	return element_textbox;
}

// Add styling for dragging/resizing
function styleSelect(element)
{
	$('.modify').removeClass('modify');
	element.addClass('modify');
}

// Remove styling for dragging/resizing
function styleDeselect(element)
{
	element.removeClass('modify');
}

// Add styling for selecting our Element
function activeSelect(element)
{
	activeDeselect(); // Deselect any other elements
	element.children('.toolbar').show(); // Display the toolbar
	element.addClass('active');
}

function activeDeselect()
{
	$('.toolbar').hide();
	$('#context-menu').hide();
	$('.active').removeClass('active');
}

// Return the highest Z-Index of element type
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
		},
		stop: function( event, ui ){
			// Unstyle the element
			styleDeselect(SELECTED);
			// Update sidebar #element-data as we drag
			sidebarShowElementData(getElementData(SELECTED));
		},
		grid: [ GRIDX, GRIDY ],
		handle: ".move"
	});

	// Resizable
	$( ".element" ).resizable({
		start: function( event, ui ) {
			SELECTED = ui.element;
			activeSelect(SELECTED);
			styleSelect(SELECTED);
		},
		resize: function( event, ui ){
		},
		stop: function( event, ui ){
			styleDeselect(SELECTED);
			// Update sidebar #element-data as we resize
			sidebarShowElementData(getElementData(SELECTED));
		},
		grid: [ GRIDX, GRIDY ]
	});
}

function changeActiveElement(element)
{
	activeSelect(element); // Style our Active Element
	sidebarShowElementData(getElementData(element)); // Display it in the Sidebar
}

// Return whether the current item is our Active Element or not
function isActiveElement(i)
{
	return i.is(SELECTED);
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
			"height": ($(this).height() * A4_MULTIPLIER) * PX_TO_MM_MULTIPLIER,
			"values": $(this).children('.content').html()
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
			var newURL = window.location.protocol + "//" + window.location.host + "/hermes/assets/saved/yourpdf.pdf";
			alert("You can find your document at: " + newURL);
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
	// SELECTION
	// ==============================================

	// Change our SELECTED element and make it active
	function changeActive(i, e)
	{
		SELECTED = i;
		changeActiveElement(SELECTED);
		e.stopPropagation();
	}

	$(document).click(function(e){
		var exclude_div = $("#context-menu");
		if(!exclude_div.is(e.target))  // If target div is not the one you want to exclude then hide it
			$('#context-menu').hide();
	});

	// Deselect and clear our SELECTED element
	$('.page').click(function(e) {
		if(e.target !== this) 
       		return;
		SELECTED = $(); // Empty our SELECTED object, but keep it as a jQuery object
		activeDeselect();
		sidebarShowElementData(null);
	}).on('click', '.element', function(e){ // Make our SELECTED element Active
		if(!isActiveElement($(this))) // Don't hide the toolbar if this is the currently selected element
			changeActive($(this), e);
	}).on('keyup', '.content', function(){ // When typing content, fill the Sidebar Textarea with HTML equivalent
		$('#element-attr-value').val($(this).html());
	});


	// ==============================================
	// TOOLBAR/CONTEXT MENU/RIGHT CLICK
	// ==============================================
	// When hovering over an element, display it's toolbar and hide it when we leave
	$('.page').on('mouseover', '.element', function(){
		$(this).find('.toolbar').show();
	}).on('mouseout', '.element', function(){
		if(!isActiveElement($(this))) // Don't hide the toolbar if this is the currently selected element
			$(this).find('.toolbar').hide();
	}).on("contextmenu", ".element", function(e){
		// Set the Element as Active if it isn't already
		if(!isActiveElement($(this)))
		{
			SELECTED = $(this);
			changeActiveElement(SELECTED);
			e.stopPropagation();
		}

		// Display our Context menu on the current element
		var y = $(this).position().top;
		var x = $(this).position().left;

		$("#context-menu").css({'top':y,'left':x});
		$('#context-menu').show();
		return false;
	}).on("click", "#move-top", function(){ // Move the Z-Index of our Element
		var z_index = parseInt(highestZIndex(".element"));
		SELECTED.css('z-index', z_index + 1);
	}).on("click", "#move-up", function(){
		var z_index = parseInt(SELECTED.css('z-index'));
		SELECTED.css('z-index', z_index + 1);
	}).on("click", "#move-down", function(){
		var z_index = parseInt(SELECTED.css('z-index'));
		SELECTED.css('z-index', z_index - 1);
	}).on("click", "#move-bottom", function(){
		SELECTED.css('z-index', 0);
	}).on('click', '.edit' ,function(){
		$(this).parents('.element').find('.content').focus();
	}).on('click', '.remove' ,function(){
		$(this).parents('.element').remove();
		activeDeselect();
		sidebarShowElementData(null);
		e.stopPropagation();
	}).on('click', '.copy' ,function(){

		var orig_element = $(this).parents('.element');
		var new_element = orig_element.clone();

		new_element = new_element.attr("id","ID"+ID_COUNTER);
		ID_COUNTER++;

		orig_element.after(new_element);
		initializeDraggable();
	});
});

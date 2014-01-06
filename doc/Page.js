// ==============================================
// GLOBALS
// ==============================================
var ID_COUNTER = 1; // Increments for each element added
var A4_MULTIPLIER = 2.583;
var PX_TO_MM_MULTIPLIER = 0.0846;
var SELECTED = null;

// ----------------------------------
// ELEMENTS
// ----------------------------------
function addElement(element)
{
	$('.page').append(element);
	// Reinitialize draggable so it recognizes dynamically added element
	initializeDraggable();
	// Increment counter
	ID_COUNTER++;
}

function elementTextbox(id)
{
	//var ELEMENT_TEXTBOX = '<div id="ID'+ id +'" class="textbox element" data-type="Textbox">Test</div>';
	var ELEMENT_TEXTBOX = '<div id="ID'+ id +'" class="textbox element" data-type="Textbox"><textarea></textarea></div>';

	return ELEMENT_TEXTBOX;
}

function styleSelect(element)
{
	element.css({outline: "2px dashed rgb(131, 210, 255)"})
}

function styleDeselect(element)
{
	element.css({outline: "2px dashed rgb(219, 219, 219)"})
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
			styleSelect(SELECTED);
		},
		drag: function( event, ui ){
			// Update sidebar #element-data as we drag
			sidebarShowElementData(getElementData(SELECTED));
		},
		stop: function( event, ui ){
			// Unstyle the element
			styleDeselect(SELECTED);
		}
	});

	// Resizable
	$( ".element" ).resizable({
		start: function( event, ui ) {
			SELECTED = ui.element;
			styleSelect(SELECTED);
		},
		resize: function( event, ui ){
			// Update sidebar #element-data as we resize
			sidebarShowElementData(getElementData(SELECTED));
		},
		stop: function( event, ui ){
			styleDeselect(SELECTED);
		}
	});

	// Editor
	tinymce.init({
		selector: "textarea",
		theme: "modern",
		plugins: [
			["advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker"],
			["searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking"],
			["save table contextmenu directionality emoticons template paste"]
		],
		add_unload_trigger: false,
		schema: "html5",
		inline: true,
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image     | print preview media",
		statusbar: true
	});
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
		url: "create_pdf.php",
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

	// ==============================================
	// SELECTION
	// ==============================================
	$('.page').on('click', '.element' ,function(){
		SELECTED = $(this);
		sidebarShowElementData(getElementData($(this)));
		console.log(1);
	});
});

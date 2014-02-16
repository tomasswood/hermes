
// ==============================================
// INTERACTION
// ==============================================
$(document).ready(function() {

	/*$('.page').on('click', '.element' ,function(){
		sidebarShowElementData(getElementData($(this)));
		console.log(1);
	});*/


	// ----------------------------------------------
	// ATTRIBUTE CHANGES
	// ----------------------------------------------
	$(document.body).on('change', '#element-attr-value' ,function(){
		SELECTED.text($(this).val());
	});

	$(document.body).on('change', '#element-attr-height' ,function(){
		SELECTED.css({height: $(this).val()});
	});

	$(document.body).on('change', '#element-attr-width' ,function(){
		SELECTED.css({width: $(this).val()});
	});

	$(document.body).on('change', '#element-attr-top' ,function(){
		SELECTED.css({top: $(this).val()});
	});

	$(document.body).on('change', '#element-attr-left' ,function(){
		SELECTED.css({left: $(this).val()});
	});

});

function getElementData(element)
{
	var data = {
		"id": $(element).attr('id'),
		"type": $(element).data('type'),
		"value": $(element).text(),
		"height": $(element).height(),
		"width": $(element).width(),
		"offset_top": $(element).position().top,
		"offset_left": $(element).position().left
	}

	return data;
}


function sidebarShowElementData(data)
{
	var displayDiv = $('#element-data');
	// Empty current info
	displayDiv.empty();

	// Populate with new info passed in
	var output =  '<li id="selected-id" class="text-center" data-selected="'+ data.id +'">'+ data.type + '</li>'
		+ '<li class="value">'
		+ '<label>Value</label>'
		+ '<textarea id="element-attr-value" class="form-control" type="text">'+ data.value +'</textarea>'
		+ '</li>'
		+ '<li class="attribute"><label>Height: </label><input id="element-attr-height" class="form-control" type="text" value="'+ data.height +'"></li>'
		+ '<li class="attribute"><label>Width: </label><input id="element-attr-width" class="form-control" type="text" value="'+ data.width +'"></li>'
		+ '<li class="attribute"><label>Top: </label><input id="element-attr-top" class="form-control" type="text" value="'+ data.offset_top +'"></li>'
		+ '<li class="attribute"><label>Left: </label><input id="element-attr-left" class="form-control" type="text" value="'+ data.offset_left +'"></li>';

	displayDiv.append(output);
}

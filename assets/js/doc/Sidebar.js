
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
		SELECTED.children('.content').text($(this).val());
	});

	$(document.body).on('change', '#element-attr-height' ,function(){
		SELECTED.css({height: $(this).val()});
	});

	$(document.body).on('change', '#element-attr-width' ,function(){
		SELECTED.css({width: $(this).val()});
	});

	$(document.body).on('change', '#element-attr-top' ,function(){
		SELECTED.css({top: $(this).val()+"px"});
	});

	$(document.body).on('change', '#element-attr-left' ,function(){
		SELECTED.css({left: $(this).val()+"px"});
	});

});

function getElementData(element)
{
	var data = {
		"id": $(element).attr('id'),
		"type": $(element).data('type'),
		"value": $(element).children('.content').text(),
		"height": $(element).height(),
		"width": $(element).width(),
		"offset_top": $(element).position().top,
		"offset_left": $(element).position().left
	}

	return data;
}

function sidebarShowElementData(data)
{
	$('.attribute').remove();
	var output = "";
	if(data == null)
	{
		$('#selected-id').attr('data-selected', null).html("No element currently selected.");
		$('#element-attr-value').val("");
	}
	else
	{
		// Populate with new info passed in
		$('#selected-id').attr('data-selected', data.id).html(data.type);
		$('#element-attr-value').val(data.value);
		output += '<li class="attribute"><label>Height: </label><input id="element-attr-height" class="form-control" type="text" value="'+ data.height +'"></li>'
			+ '<li class="attribute"><label>Width: </label><input id="element-attr-width" class="form-control" type="text" value="'+ data.width +'"></li>'
			+ '<li class="attribute"><label>Top: </label><input id="element-attr-top" class="form-control" type="text" value="'+ data.offset_top +'"></li>'
			+ '<li class="attribute"><label>Left: </label><input id="element-attr-left" class="form-control" type="text" value="'+ data.offset_left +'"></li>';
	}
	$('#element-data').append(output);

	createEditor('element-attr-value');
}

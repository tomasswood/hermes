<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="main">
	<div id="page-container">
		<div id="display">
			<div class="container">
				<div id="display-container">
					<div id="home-toolbar">
						<ul>
							<li id="properties">Properties</li>
						</ul>
					</div>
					<table id="display-table">

					</table>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view('modal'); ?>
</div>

<script>
	$('#properties').click(function() {
		var properties = createPropertiesMenu();
		properties += createPropertiesList();
		showModal('Properties', properties);
	});

	function createPropertiesMenu()
	{
		var menu = '<div class="col-lg-12 text-center">'
		+ '	<div class="btn-group btn-group-lg">'
		+ '		<button id="save-property" type="button" class="width-150 btn btn-primary">'
		+ '			<span class="glyphicon glyphicon-floppy-disk"></span> Save'
		+ '		</button>'
		+ '		<button id="add-property" type="button" class="width-150 btn btn-success">'
		+ '			<span class="glyphicon glyphicon-plus"></span> Add'
		+ '		</button>'
		+ '		<button id="remove-property" type="button" class="width-150 btn btn-danger">'
		+ '			<span class="glyphicon glyphicon-minus-sign"></span> Remove'
		+ '		</button>'
		+ '	</div>'
		+ '</div>'
		return menu;
	}

	function createPropertiesList()
	{
		var properties = '';
		properties += '<ul id="properties-list">';
		for(var i = 0; i < ENTITY.getLength(); i++)
		{
			var data = ENTITY.getSub(i);
			properties += '<li data-order="' + data['cp_order'] + '" data-header="' + data['cp_header'] + '" data-id="' + data['cp_id']
				+ '" class="form-control width-200">' + '<input type="checkbox" name="property-checkbox"> <label class="property-label">'
				+ data['cp_name'] + '</label>' + '<input type="text" class="property-input hide" value="' + data['cp_name'] + '" /></li>';
		}
		properties += '</ul>';

		return properties;
	}

	function addProperty()
	{
		$('#properties-list').append('<li data-order="' + data['cp_order'] + '" data-header="' + data['cp_header'] + '" data-id="' + data['cp_id']
			+ '" class="form-control width-200">' + '<input type="checkbox" name="property-checkbox"> <label class="property-label">'
			+ "New Property" + '</label>' + '<input type="text" class="property-input hide" value="' + "New Property" + '" /></li>');
	}
	function removeProperty()
	{
		var property = $('input[name=property-checkbox]:checked').parent();
		property.hide();
		property.attr('data-hide', true);
		resetProperty();
	}

	function resetProperty(array)
	{
		var all_properties = {};
		var properties = [];
		var del_properties = [];
		var count = 0;
		$('#properties-list').children().each(function () {
			if($(this).attr("data-hide"))
			{
				var id = $(this).data("id");
				if(id != null)
					del_properties.push(id);
			}
			else
			{
				count++;
				$(this).attr("data-order", count);
				var stage = {"id":$(this).data("id"), "name":$(this).children('.property-input').val(), "order": $(this).attr("data-order")};
				properties.push(properties);

			}
		});
		if(array)
		{
			all_properties.properties = properties;
			all_properties.del_properties = del_properties;
			return all_properties;
		}
		return count;
	}

	function ajaxSaveProperty()
	{
		var properties_data = resetProperty(true);
		console.log(properties_data);
	}

	$('#display').on('click', '.property-label', function() {
		$(this).hide();
		$(this).next().toggleClass('hide');
	});
	$('#display').on('blur', '.property-input', function() {
		$(this).prev().text($(this).val());
		$(this).toggleClass('hide');
		$(this).prev().show();
	});
	$('#display').on('click', '#save-property', function() {
		console.log("save");
		$(this).prop('disabled', true);
		ajaxSaveProperty();
	});
	$('#display').on('click', '#add-property', function() {
		addProperty();
	});
	$('#display').on('click', '#remove-property', function() {
		removeProperty();
	});
</script>
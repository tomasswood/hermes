/*
	DATA
		| entity
				| 0
					| properties...
*/
/*
	ENTITY
		| property
					| 0
						| p_id
						| p_name
						| p_header
 */

// Our Data object which Contains all of our Entities
var DATA = {
	entity: [],
	getLength: function() {
		return this.entity.length
	},
	addEntity: function(entity) {
		this.entity.push(entity);
	},
	getSubInfo: function(level, index) {
		// Return our value and default it to empty if there is nothing
		if(typeof this.entity[level][index] == 'undefined')
			return "";
		else
			return this.entity[level][index];
	}
}

// Our Entities object which contains properties
var ENTITY = {
	property: [],
	getLength: function() {
		return this.property.length;
	},
	getSub: function(level) {
		return this.property[level];
	},
	getSubInfo: function(level, index) {
		return this.property[level]['cp_' + index];
	}, // Add a Property to our object
	addProperty: function(property_id, property_name, header, property_order) {
		this.property.push({cp_id:property_id, cp_name: property_name, cp_header: header, cp_order:property_order});
	},
	// Takes either the names property or the index
	removeProperty: function(p) {
		var i = p;
		if(isNaN(p))
		{
			for(var j = 0; j < this.getLength(); j++)
			{
				if(this.getSubInfo(j, 'name') === p)
					i = j;
			}
		}
		if(i >= 0)
			this.property.splice(i, 1);
	}
}

function ajaxFetchProperties() {

	// Send our AJAX request with the query we want to use in the DO file
	$.ajax({
		type: "POST",
		url: "home/get-properties",
		cache: false,
		success: function(data) {
			data = $.parseJSON(data); //The JSON array returned from our AJAX request
			// Check if we have a valid return
			if(data.status == 1) {
				var p = data.content;
				// Insert each of our Properties into our ENTITY object and format them to the right type
				for(var i = 0; i < p.length; i++)
				{
					ENTITY.addProperty(parseInt(p[i]['cp_id']), p[i]['cp_name'], Boolean(p[i]['cp_header']), parseInt(p[i]['cp_order']));
				}
				ajaxFetchValues();
			}
		}

	});
}

function ajaxFetchValues() {

	// Send our AJAX request with the query we want to use in the DO file
	$.ajax({
		type: "POST",
		url: "home/get-values",
		cache: false,
		success: function(data) {
			data = $.parseJSON(data); //The JSON array returned from our AJAX request
			// Check if we have a valid return
			if(data.status == 1) {
				var v = data.content;

				// Loop through the rows of our Values from the DB (these rows will be entities)
				for(var i = 0; i < v.length; i++)
				{
					var entity = {}; // Create an entity
					// Loop through our Properties
					for(var j = 0; j < ENTITY.getLength(); j++)
					{
						// Loop through the values in the current row of our Values from the DB
						// This is to ensure that we capture instances where Properties are empty for an Entity
						for(var k = 0; k < v[i].length; k++)
						{
							// Check if the Property ID of the Database Value matches the Current Property of our Loop
							if(v[i][k]['cp_id'] == getProperty(j, "id"))
								entity[getProperty(j, "name")] = v[i][k]['value']; // Assign the corresponding value
						}
					}
					addEntities(entity); // Add the entity to our data object
				}

				renderEntitiesTable();
				console.log(ENTITY);
				console.log(DATA);
			}
		}

	});
}

$(document).ready(function() {
	ajaxFetchProperties();
});

// Add to our Entities object
function addEntities(e)
{
	DATA.addEntity(e);
}

// Add a Property from our Object
function getProperty(i, property)
{
	return ENTITY.getSubInfo(i, property);
}

// Remove a Property from our object
function deleteProperties(i)
{
	ENTITY.removeProperty(i);
}

// Render the table to display our entities
function renderEntitiesTable()
{
	var table =
		'<thead>'
			+ '<tr>';
			// Loop through all of our properties
			for(var i = 0; i < ENTITY.getLength(); i++)
			{
				if(ENTITY.getSubInfo(i,'header')) // Check if the property is set to be a header
					table += '<th>' + ENTITY.getSubInfo(i,'name') + '</th>';
			}
	table += '<th>Actions</th>'
			+ '</tr>'
			+ '</thead>'
			+ '<tbody>';
	// Loop through all of our entities
	for(var i = 0; i < DATA.getLength(); i++)
	{
		table += '<tr>';
		for(var j = 0; j < ENTITY.getLength(); j++)
		{
			if(ENTITY.getSubInfo(j,'header')) // Check if the property is set to be a header
				table += '<td>' + DATA.getSubInfo(i, ENTITY.getSubInfo(j,'name')) + '</td>';
		}
		table += getTemplateAction();
		table += '</tr>';
	}
	table += '</tbody>';
	$('#display-table').append(table);
}

// Return the Action span table row
function getTemplateAction()
{
	return '<td class="display-action"><span class="glyphicon glyphicon-cog"></span></td>';
}
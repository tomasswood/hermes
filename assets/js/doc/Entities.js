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
		return this.entity[level][index];
	}
}

// Our Entities object which contains properties
var ENTITY = {
	property: [],
	getLength: function() {
		return this.property.length
	},
	getInfo: function(level) {
		return this.property[level];
	},
	addProperty: function(property_name) {
		this.property.push(property_name);
	},
	// Takes either the names property or the index
	removeProperty: function(p) {
		if(isNaN(p))
			this.spliceProperty($.inArray(p, this.property));
		else
			this.spliceProperty(p);
	},
	spliceProperty: function(i) {
		if(i >= 0)
			this.property.splice(i, 1);
	}
}

$(document).ready(function() {
	addProperties('name');
	addProperties('date');
	addProperties('by');

	var temp_array = ['Document', '21/02/2014', 'Thomas Wood'];

	for(var i = 1; i <= 3; i++)
	{
		var entity = {}; // Create an entity
		// Loop through and assign our Properties to our Entity
		for(var j = 0; j < ENTITY.getLength(); j++)
		{
			entity[getProperty(j)] = temp_array[j];
		}
		addEntities(entity); // Add the entity to our data object
	}

	renderEntitiesTable();
	console.log(ENTITY);
	console.log(DATA);
});

// Add to our Entities object
function addEntities(e)
{
	DATA.addEntity(e);
}

// Add a Property from our Object
function getProperty(i)
{
	return ENTITY.getInfo(i);
}

// Add a Property to our object
function addProperties(property)
{
	ENTITY.addProperty(property);
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
			+ '<tr>'
			+ '<th>Name</th>'
			+ '<th>Modified</th>'
			+ '<th>By</th>'
			+ '<th>Actions</th>'
			+ '</tr>'
			+ '</thead>'
			+ '<tbody>';
	// Loop through all of our entities
	for(var i = 0; i < DATA.getLength(); i++)
	{
		table += '<tr>';
		table += '<td class="display-name">' + DATA.getSubInfo(i,'name') + '</td>';
		table += '<td class="display-date">' + DATA.getSubInfo(i,'date') + '</td>';
		table += '<td class="display-by">' + DATA.getSubInfo(i,'by') + '</td>';
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
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
	property: [{name:'id', header:false}, {name:'order', header:false}, {name:'name', header:true}],
	getLength: function() {
		return this.property.length;
	},
	getSubInfo: function(level, index) {
		return this.property[level][index];
	},
	addProperty: function(property_name, header) {
		this.property.push({name: property_name, header: header});
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

$(document).ready(function() {
	var temp_prop = ['date', 'by'];

	for(var i = 0; i < temp_prop.length; i++)
	{
		addProperties(temp_prop[i], true);
	}

	var temp_array = [1, 1, 'Document', '21/02/2014', 'Thomas Wood'];

	for(var i = 1; i <= 3; i++)
	{
		var entity = {}; // Create an entity
		// Loop through and assign our Properties to our Entity
		for(var j = 0; j < ENTITY.getLength(); j++)
		{
			entity[getProperty(j, "name")] = temp_array[j];
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
function getProperty(i, property)
{
	return ENTITY.getSubInfo(i, property);
}

// Add a Property to our object
function addProperties(property, header)
{
	ENTITY.addProperty(property, header);
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
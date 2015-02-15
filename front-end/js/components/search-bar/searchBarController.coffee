angular.module 'sightApp'
.controller 'SearchBarController', ($scope)->
	@allCollections = [
		{
			name:"Thesis",
			count:10
		},
		{
			name:"Projects",
			count:100
		},
		{
			name:"Research",
			count:99
		},
		{
			name:"Others",
			count:30
		}
	]
	@allNames = ['Metric','Title','Author','Year','Conference']
	@allMeasures = ['Relation','>','<','Has','=']
	@allConditions = [
		{
			name_id:1,
			measure_id:2,
			value:""
		},
		{
			name_id:2,
			measure_id:1,
			value:"aoeuA"
		},
		{
			name_id:3,
			measure_id:1,
			value:"aoeuAE"
		}
	]
	@removeCondition = (index)->
		@allConditions.splice index,1
	@addCondition = ()->
		@allConditions.push({name_id:0, measure_id:0, value:""})

	0
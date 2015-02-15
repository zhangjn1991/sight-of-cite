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
	@allNames = ['Title','Author','Year','Conference']
	@allMeasures = ['>','<','Has','=']
	@allConditions = [
		{
			name_id:0,
			measure_id:0,
			value:"aoeu"
		},
		{
			name_id:2,
			measure_id:1,
			value:"aoeuA"
		},
		{
			name_id:3,
			measure_id:0,
			value:"aoeuAE"
		},
		{
			name_id:0,
			measure_id:0,
			value:"aoeu"
		},
		{
			name_id:2,
			measure_id:1,
			value:"aoeuA"
		},
		{
			name_id:3,
			measure_id:0,
			value:"aoeuAE"
		}
	]
	0
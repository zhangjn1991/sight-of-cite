angular.module 'sightApp'
.controller 'SearchBarController', ($scope,$http)->
	self = @
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
	@allMetrics = [
		{name:'Metric',attr:'unknow'},
		{name:'Title',attr:'title'},
		{name:'Author',attr:'author'},
		{name:'Citation',attr:'cite_count'},
		{name:'Tag',attr:'tags'},
		{name:'Location',attr:'location'}
	]
	@allMeasures = ['Relation','>','<','Has','=']
	@allConditions = [
		# {
		# 	name_id:1,
		# 	measure_id:2,
		# 	value:""
		# },
		# {
		# 	name_id:2,
		# 	measure_id:1,
		# 	value:"aoeuA"
		# },
		# {
		# 	name_id:3,
		# 	measure_id:1,
		# 	value:"aoeuAE"
		# }
	]
	@removeCondition = (index)->
		@allConditions.splice index,1
	@addCondition = ()->
		@allConditions.push({metric_id:0, measure_id:0, value:""})

	
	@isConditionTrue = (d,condition)->
		metric_attr = @allMetrics[condition.metric_id].attr
		data_value = d[metric_attr]
		value = condition.value;

		if metric_attr == 'cite_count'
			data_value = parseInt(data_value)
			value = parseInt(value)

		if metric_attr == 'author'
			data_value = _.pluck(data_value,'name').join(',')

		if metric_attr == 'tags'
			data_value = data_value.join ' '
		
		switch @allMeasures[condition.measure_id]
			when '=' then return data_value == value
			when '<' then return data_value < value
			when '>' then return data_value > value
			when 'Has' then return _.isString(data_value) && data_value? && data_value.indexOf(value) > -1
		
	@search = ()->
		data = $scope.globalCtrl.tableViewCtrl.allData;
		res = data
		_.each(self.allConditions, (condition)->
			res = _.filter res,(d)->self.isConditionTrue(d,condition)
		)
		$scope.globalCtrl.tableViewCtrl.setData(res)
	@clearSearch = ()->
		@allConditions = []
		@search()

	0
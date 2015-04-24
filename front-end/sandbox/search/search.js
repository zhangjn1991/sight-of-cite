var data = []
$.get('data.js',function(json){
	data = json
	console.log(search())
},'json')


var allMetrics = [
		{name:'Metric',attr:'unknow'},
		{name:'Title',attr:'title'},
		{name:'Author',attr:'author'},
		{name:'Citation',attr:'cite_count'},
		{name:'Tag',attr:'tag'},
		{name:'Location',attr:'location'}
	]
var allMeasures = ['Relation','>','<','Has','=']


var allConditions = [
	{metric_id:1, measure_id:3,value:'Real'}
]

var isConditionTrue = function(d,condition){
	var metric_attr = allMetrics[condition.metric_id].attr
	var data_value = d[metric_attr]
	var value = condition.value;

	if(metric_attr == 'cite_count'){
		data_value = parseInt(data_value)
		value = parseInt(value)
	}

	switch (allMeasures[condition.measure_id]){
		case '=': return data_value == value
		case '<':  return data_value < value
		case '>' : return data_value > value
		case 'Has': return _.isString(data_value) && data_value.indexOf(value) > -1
	}
}


var search = function(){
	res = data
	_.each(allConditions, function(condition){
		res = _.filter(res,function(d){return isConditionTrue(d,condition)})
	})
	return res;
}



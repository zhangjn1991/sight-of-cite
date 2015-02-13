angular.module 'sightApp',['ngRoute']
.config(['$routeProvider',($routeProvider) -> 
	$routeProvider
		.when '/', 
			redirectTo:'/table'
		.when '/table', 
			templateUrl:'partials/table-view.html'
		.when '/graph', 
			templateUrl:'partials/graph-view.html'
])


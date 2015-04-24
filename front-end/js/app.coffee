angular.module('sightApp',['ngTouch', 'ui.grid', 'ui.grid.resizeColumns', 'ui.grid.moveColumns', 'ngRoute'])
.config(['$routeProvider',($routeProvider) -> 
	$routeProvider
		.when '/', 
			redirectTo:'/table'
		.when '/table', 
			templateUrl:'partials/table-view.html'
		.when '/graph', 
			templateUrl:'partials/graph-view.html'
			controller:'GraphController',
			controllerAs:'graphCtrl'
])


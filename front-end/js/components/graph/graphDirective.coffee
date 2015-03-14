angular.module 'sightApp'
.directive 'Graph', ()->
	{
		resstrict:'E',
		scope: true,
		templateUrl:'js/components/graph.html',
		controller:'GraphController',
		controllerAs:'graphCtrl'
	}
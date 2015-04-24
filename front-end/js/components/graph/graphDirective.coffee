angular.module 'sightApp'
.directive 'Graph', ()->
	{
		restrict:'E',
		scope: true,
		templateUrl:'js/components/graph.html',
		controller:'GraphController',
		controllerAs:'graphCtrl'
	}
angular.module 'sightApp'
.directive 'headerBar', ()->
	{
		restrict:'E',
		scope:true,
		templateUrl:'js/components/header-bar/header-bar.html'		
	}
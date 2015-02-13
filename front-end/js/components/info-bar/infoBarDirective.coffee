angular.module 'sightApp'
.directive 'infoBar', ()->
	{
		restrict:'E',
		scope:true,
		templateUrl:'js/components/info-bar/info-bar.html'
	}
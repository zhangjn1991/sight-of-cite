angular.module 'sightApp'
.directive 'searchBar', ()->
	{
		restrict:'E',
		scope:true,
		templateUrl:'js/components/search-bar/search-bar.html',
		controller:'SearchBarController'
		controllerAs:'searchBarCtrl'
	}
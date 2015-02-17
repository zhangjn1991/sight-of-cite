angular.module 'sightApp'
.directive 'tagContainer', ()->
	{
		restrict:'E',
		scope:{
			entity:'='
		},
		templateUrl:'js/components/tag-container/tag-container.html'		
	}
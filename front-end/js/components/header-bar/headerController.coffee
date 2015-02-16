angular.module 'sightApp'
.controller "HeaderController", ($scope)->
	@tabs=['Detail','Notes','Reference','Cited by']
	@isCurrentTab = (index)->$scope.globalCtrl.tabIndex == index
	@setTab = (index)->$scope.globalCtrl.tabIndex = index

	0
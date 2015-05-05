angular.module 'sightApp'
.controller "HeaderController", ($scope)->
	@tabs=['Detail','Abstract','Reference','Cited by']
	@isCurrentTab = (index)->$scope.globalCtrl.getTabIndex() == index
	@setTab = (index)->$scope.globalCtrl.setTabIndex(index)


	0
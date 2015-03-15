angular.module 'sightApp'
.controller "GlobalController", ($scope)->
	@.tabIndex = 0;
	@getServerAddr = ()->"http://127.0.0.1:3000/"	


	0
angular.module 'sightApp'
.controller "GlobalController", ($scope)->
	@.tabIndex = 0;
	@infoBarCtrl = null;
	@getServerAddr = ()->"http://127.0.0.1:3000/"	


	0
angular.module 'sightApp'
.controller "GlobalController", ($scope)->
	@.tabIndex = 0;
	@infoBarCtrl = null;
	@tableViewCtrl = null;
	# @getServerAddr = ()->"http://127.0.0.1:3000/"	
	@getServerAddr = ()->"http://192.168.0.103:8888/SightOfCite/API.php?action="

	@addNewPublication = ()->@tableViewCtrl.addEmptyRow()
	@removeSelectedPublication = ()->@tableViewCtrl.removeActiveRow()

	0
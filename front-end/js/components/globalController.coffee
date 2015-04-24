angular.module 'sightApp'
.controller "GlobalController", ($scope)->
	@tabIndex = 0;
	@infoBarCtrl = null;
	@tableViewCtrl = null;
	# @getServerAddr = ()->"http://127.0.0.1:3000/api"	
	# @getServerAddr = ()->"http://192.168.0.103:8888/sight-of-cite/back-end/api.php"
	@getServerAddr = ()->"../back-end/api.php"

	@addNewPublication = ()->@tableViewCtrl.addEmptyRow()
	@removeSelectedPublication = ()->@tableViewCtrl.removeActiveRow()

	@getTabIndex = ()->@tabIndex
	@setTabIndex = (index)->
		@tabIndex = index;
		if(@tabIndex == 2)
			@infoBarCtrl.setIsReference(true)
		else if (@tabIndex == 3)
			@infoBarCtrl.setIsReference(false)

	0
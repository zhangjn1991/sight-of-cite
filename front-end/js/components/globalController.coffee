angular.module 'sightApp'
.controller "GlobalController", ($scope)->
	@tabIndex = 0;
	@infoBarCtrl = null;
	@tableViewCtrl = null;
	@initialGraphPaperId = null;
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

	@switchView = ()->
		target = 'table';
		if (window.location.href.indexOf('table')>-1)
			target = 'graph'
			@initialGraphPaperId = @tableViewCtrl.activeRowEntity.pub_id

		

		window.location.href="#/#{target}"
			
	0
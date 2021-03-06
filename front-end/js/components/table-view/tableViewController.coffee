angular.module "sightApp"
.controller "TableViewController", ($scope,$http)->	
	self=@
	$scope.globalCtrl.tableViewCtrl = @

	@allData = []

	@activeRowEntity = null		

	@setActiveRow = (entity)->
		@activeRowEntity = entity
		$scope.globalCtrl.infoBarCtrl.setCurrentEntity entity;

	@addEmptyRow = ()->
		newEntity = {}
		@gridOptions.data.push(newEntity)
		@setActiveRow(newEntity)
	
	@removeFromArray = (array,element)-> 
		array.splice(array.indexOf(element),1)

	@removeActiveRow = ()->
		if(!@activeRowEntity?) then return
		@removeFromArray(@gridOptions.data,@activeRowEntity)
		$scope.globalCtrl.infoBarCtrl.removeCurrentEntity()

		console.log "AJAX: delete_paper"
		$.post($scope.globalCtrl.getServerAddr(),{action:"delete_paper",data:{pub_id:@activeRowEntity.pub_id}},(res)->
			console.log res			
		);

	@gridOptions = 
		enableSorting: true,
		columnDefs: [
			{field: 'title', width: '40%'}
			{name: 'Authors', field: 'author', width: '20%', cellFilter:'authorCellFilter'}
			# {name: 'year', field: 'pub_year', width: '10%',maxWidth:100 },
			# {name: 'year', field: 'pub_year', width: '10%'}
			{name: 'Citation Count', field: 'cite_count', width: '10%'}
			{name: 'location', field: 'location', width: '20%'}
			{name: "ISBN", field: 'ISBN', width: '10%'}
			{field: 'pub_id', width: '2%', minWidth:80}
			
		]

		rowTemplate: '<div ng-class="{active: grid.appScope.tableViewCtrl.activeRowEntity == row.entity}" ng-click="grid.appScope.tableViewCtrl.setActiveRow(row.entity)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>'
	
	@setData = (data)->
		data = _.map data,(d)->
			d.cite_count= parseInt(d.cite_count)
			d
		@gridOptions.data = data

	# $http.get('data/publication.json').success (data)-> self.gridOptions.data = data.slice(0,5);
	$http.get($scope.globalCtrl.getServerAddr()+'?action=get_all_paper').success (data)-> 
		self.allData = data
		self.setData(data)




	0
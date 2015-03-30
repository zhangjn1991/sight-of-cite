angular.module "sightApp"
.controller "TableViewController", ($scope,$http)->	
	self=@
	$scope.globalCtrl.tableViewCtrl = @


	@activeRowEntity = null		

	@setActiveRow = (entity)->
		@activeRowEntity = entity
		$scope.globalCtrl.infobarCtrl.setCurrentEntity entity;

	@addEmptyRow = ()->
		newEntity = {}
		@gridOptions.data.push(newEntity)
		@setActiveRow(newEntity)
	
	@removeFromArray = (array,element)-> 
		array.splice(array.indexOf(element),1)

	@removeActiveRow = ()->
		if(!@activeRowEntity?) then return
		@removeFromArray(@gridOptions.data,@activeRowEntity)
		$scope.globalCtrl.infobarCtrl.removeCurrentEntity()

		console.log "AJAX: delete_paper"
		$.post($scope.globalCtrl.getServerAddr(),{action:"delete_paper",data:{pub_id:@activeRowEntity.pub_id}},(res)->
			console.log res			
		);

	@gridOptions = 
		enableSorting: true,
		columnDefs: [
			{field: 'pub_id', width: '10%', minWidth:200},
			{field: 'title', width: '30%', minWidth:200},
			{field: 'author', width: '10%', minWidth:200},
			# {name: 'year', field: 'pub_year', width: '10%',maxWidth:100 },
			{name: 'year', field: 'pub_year', width: '10%',maxWidth:100 },
			{name: 'location', field: 'location', width: '20%', minWidth:200}
			{name: 'Citation Count', field: 'cite_count', width: '10%', minWidth:200}
			{name: "ISBN", field: 'ISBN', width: '10%', minWidth:200}
		]

		rowTemplate: '<div ng-class="{active: grid.appScope.tableViewCtrl.activeRowEntity == row.entity}" ng-click="grid.appScope.tableViewCtrl.setActiveRow(row.entity)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>'
	
	# $http.get('data/publication.json').success (data)-> self.gridOptions.data = data.slice(0,5);
	$http.get($scope.globalCtrl.getServerAddr()+'?action=get_all_paper').success (data)-> self.gridOptions.data = data;


	0
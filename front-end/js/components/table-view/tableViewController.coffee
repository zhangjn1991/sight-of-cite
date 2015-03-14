angular.module "sightApp"
.controller "TableViewController", ($scope,$http)->	
	$scope.activeRow = null	
	
	$scope.setActiveRow = (row)->
		@activeRow = row
		# console.log row

	$scope.gridOptions = 
		enableSorting: true,
		columnDefs: [
			{field: 'title', width: '40%', minWidth:200},
			{field: 'author', width: '20%', minWidth:200},
			# {name: 'year', field: 'pub_year', width: '10%',maxWidth:100 },
			{name: 'year', field: 'year', width: '10%',maxWidth:100 },
			{field: 'conference', width: '30%', minWidth:200}
		]

		rowTemplate: '<div ng-class="{active: grid.appScope.activeRow == row}" ng-click="grid.appScope.setActiveRow(row)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>'

	# $http.get('http://192.168.0.103:8888/SightOfCite/API.php?action=search_by_title&title=A Step').success (data)-> $scope.gridOptions.data = data;
	$http.get('data/publication.json').success (data)-> $scope.gridOptions.data = data;
angular.module "sightApp"
.controller "TableViewController", ($scope,$http)->	
	$scope.activeRow = null

	$scope.numbers = [1..10]
	
	$scope.setActiveRow = (row)->
		@activeRow = row
		# console.log row

	$scope.gridOptions = {
		enableSorting: true,
		columnDefs: [
			{ field: 'title', width: '40%'},
			{ field: 'author', width: '20%'},
			{ field: 'year', width: '10%',maxWidth:100 },
			{ field: 'conference', width: '30%' }
		]

		rowTemplate: '<div ng-class="{active: grid.appScope.activeRow == row}" ng-click="grid.appScope.setActiveRow(row)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>'
	};

	$http.get('data/publication.json')
	.success((data)->
		$scope.gridOptions.data = data;
	);
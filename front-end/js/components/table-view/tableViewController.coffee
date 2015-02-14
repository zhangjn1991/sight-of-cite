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
			{ field: 'name', minWidth: 200, width: '50%'},
			{ field: 'gender', width: '30%', maxWidth: 200, minWidth: 70 },
			{ field: 'company', width: '20%' }
		]
		rowTemplate: '<div ng-class="{active: grid.appScope.activeRow == row}" ng-click="grid.appScope.setActiveRow(row)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>'
	};

	$http.get('https://cdn.rawgit.com/angular-ui/ui-grid.info/gh-pages/data/100.json')
	.success((data)->
		$scope.gridOptions.data = data;
	);
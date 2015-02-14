angular.module "sightApp"
.controller "TableViewController", ($scope)->
	console.log $scope	
	$(".table-view-container table").colResizable
		liveDrag:true
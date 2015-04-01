angular.module 'sightApp'
.filter 'authorCellFilter', ()->(input)->
	_.pluck(input, 'name').join ', '	

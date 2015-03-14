angular.module 'sightApp'
.controller 'GraphController', ()->	
	console.log 'here'
	margin = { top: 10, right: 10, bottom: 10, left: 10 }	
	svg = d3.select 'svg'	    
	  .selectAll 'g'
	    .attr 'transform', 'translate#{margin.left},#{margin.top})';
	
	svg .append 'circle'
	    .attr 'cx', 100
	    .attr 'cy', 100
	    .attr 'r', 20
	    .style 'fill', '#000'
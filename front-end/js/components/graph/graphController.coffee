angular.module 'sightApp'
.controller 'GraphController', ()->	
	totalWidth = 960
	totalHeight = 640
	margin = { top: 10, right: 10, bottom: 10, left: 10 }
	svgWidth = totalWidth - margin.left - margin.right
	svgHeight = totalHeight - margin.top - margin.bottom

	svg = d3.select 'svg'
	    .attr 'width', totalWidth
	    .attr 'height', totalHeight
	  .selectAll 'g'
	    .attr 'transform', "translate(#{margin.left},#{margin.top})";	

	nodeSvg = d3.select '.node-canvas'
	linkSvg = d3.select '.link-canvas'

	new_nodes = [{id:1,name:'A', cite_count:35, neighbors:[{id:2}]},{id:2,name:'B',cite_count:2,neighbors:[{id:3}]},{id:3,cite_count:105,name:'C'},{id:1,name:'C'},{id:3,name:'D'}]
	all_links = []
	all_nodes = []



	tickEventHandler = ()->
		d3.selectAll '.link'
			.attr 'x1', (d)->d.source.x
			.attr 'x2', (d)->d.target.x
			.attr 'y1', (d)->d.source.y
			.attr 'y2', (d)->d.target.y
		d3.selectAll '.node'
			.attr 'transform', (d)->"translate(#{d.x},#{d.y})";			

	force = d3.layout.force()
		.charge(-2000)
		.linkDistance(100)
		.linkStrength(0.2)
		.size([svgWidth,svgHeight])
		.on 'tick',tickEventHandler

	updateGraph = ()->
		force.nodes(all_nodes)
			.links(all_links)
			.start()

		nodes = nodeSvg.selectAll '.node'
			.data(all_nodes)
			.enter()
			.append 'g'
			.attr 'class','node'
			.call force.drag

		nodes		
			.append 'circle'		
			.attr 'r', getRadius
			
		
		nodes.append 'text'
			.attr 'class', '.cite-count-text'
			.attr 'y', '0.3em'
			.text (d)->d.cite_count

		nodes.append 'text'
			.attr 'class', '.title'
			.attr 'y', (d)->getRadius(d)+20
			.text (d)->d.name

		links = linkSvg.selectAll '.link'
			.data(all_links)
			.enter()
			.append('line')
			.attr 'class','link'

	addNodes = (nodesToAdd)->
		all_nodes = _.uniq(_.flatten([all_nodes,nodesToAdd]),(d)->d.id)

		all_links = []

		_.each all_nodes,(d)->
			_.each d.neighbors,(n)->
				tar = _.find all_nodes,(d)->d.id==n.id
				if tar? then all_links.push {source:d, target:tar}

	addNewPapers = (papersToAdd)->
		addNodes papersToAdd
		updateGraph()

	getRadius = (d)->
		Math.max(Math.sqrt(d.cite_count)*5,10)

	addNewPapers(new_nodes)





	    
angular.module 'sightApp'
.controller 'GraphController', ($scope,$http)->	
	totalWidth = 1000
	totalHeight = 600
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

	CIRCLE_FILL = d3.hsl("#c1ced7")

	all_links = []
	all_nodes = []
	$scope.node_data = []
	$scope.cite_data = []

	# d3.json $scope.globalCtrl.getServerAddr()+'?action=get_cite',(json)->
	# 	$scope.cite_data=json		
	# 	$http.get($scope.globalCtrl.getServerAddr()+'?action=get_all_paper').success (json)->
	# 			$scope.node_data=json
	# 			initial_id = $scope.globalCtrl.initialGraphPaperId
	# 			if(initial_id?)
	# 				addPaperById(initial_id)
	# 			else
	# 				addPaperById("103")

	d3.json $scope.globalCtrl.getServerAddr()+'?action=get_cite',(json)->
		$scope.cite_data=json		
		if($scope.globalCtrl.tableViewCtrl? and $scope.globalCtrl.tableViewCtrl.allData?)
			$scope.node_data=$scope.globalCtrl.tableViewCtrl.allData
			initial_id = $scope.globalCtrl.initialGraphPaperId
			if(initial_id?)
				addPaperById(initial_id)
			else
				addPaperById("103")

	
	  


	tickEventHandler = ()->
		d3.selectAll '.link'
			.attr 'x1', (d)->d.source.x
			.attr 'x2', (d)->d.target.x
			.attr 'y1', (d)->d.source.y
			.attr 'y2', (d)->d.target.y
		d3.selectAll '.node'
			.attr 'transform', (d)->"translate(#{d.x},#{d.y})";			

	force = d3.layout.force()
		.charge(-1000)
		# .linkDistance(100)
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
			.on 'click', nodeClicked
			.on 'dblclick', nodeDoubleClicked
			.call force.drag

		nodes		
			.append 'circle'		
			.attr 'r', getRadius
			.attr 'fill',getFill
			
		
		nodes.append 'text'
			.attr 'class', 'cite-count'
			.attr 'y', '0.3em'
			.text (d)->d.cite_count

		nodes.append 'text'
			.attr 'class', 'title'
			.attr 'y', (d)->getRadius(d)+20
			.text (d)->d.title

		links = linkSvg.selectAll '.link'
			.data(all_links)
			.enter()
			.append('line')
			.attr 'class','link'

	addNodes = (nodesToAdd)->
		all_nodes = _.uniq(_.flatten([all_nodes,nodesToAdd]),(d)->d.pub_id)

		all_links = []

		_.each all_nodes,(d)->
			d.tarIds = _.pluck(_.where($scope.cite_data,{citer_id:d.pub_id}),'citee_id')
			d.srcIds = _.pluck(_.where($scope.cite_data,{citee_id:d.pub_id}),'citer_id')
			_.each d.tarIds,(n)->
				tar = _.findWhere all_nodes,{pub_id: n}
				if tar? then all_links.push {source:d, target:tar}
			_.each d.srcIds,(n)->
				src = _.findWhere all_nodes,{pub_id: n}
				if src? then all_links.push {source:src, target:d}		

			0
		all_links = _.uniq all_links,false,(d)->"#{d.source.pub_id}-#{d.target.pub_id}"


	addNewPapers = (papersToAdd)->
		addNodes papersToAdd
		updateGraph()

	expandPaper = (paper)->
		papersToAdd = []
		neighbor_ids = _.pluck(_.flatten([paper.references, paper.citedbys]),'pub_id')
		papersToAdd = _.filter $scope.node_data, (d)->_.contains(neighbor_ids,d.pub_id)
		addNewPapers(papersToAdd)

	nodeClicked = (d)->
		$scope.globalCtrl.infoBarCtrl.setCurrentEntity d;
		$scope.$apply()

	nodeDoubleClicked = (d)->
		expandPaper(d)

	getRadius = (d)->
		Math.max(Math.sqrt(parseInt(d.cite_count)),10)

	getFill = (d)->
			ratio = (d.cite_count / 500)^0.25
			CIRCLE_FILL.darker(ratio)

	addPaperById = (id)->
		expandPaper(_.where($scope.node_data,{pub_id:id})[0])









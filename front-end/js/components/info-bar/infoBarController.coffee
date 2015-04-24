angular.module 'sightApp'
.controller "InfoBarController", ($scope)->
	self = @
	$scope.globalCtrl.infoBarCtrl = @;
	@tabIndex = 0

	@entity={};

	@isEditing=false;

	@tempEntityDetail = null;
	
	@currentCitationList = [];
	@citationEntity = {}	

	@isReference = true;

	@testReferenes = [
		{
			pub_id:1
			title:"Test Refereces 1"
			note_content: "Test Note"
			rating: 5
			note_date: 1429863767
		},
		{
			pub_id:2
			title:"Test Refereces 2"
			note_content: "Test Note"
			rating: 3
			note_date: 1429863767
		},
		{
			pub_id:3
			title:"Test Refereces 3"
			note_content: "Test Note"
			rating: 1
			note_date: 1429863767
		}
	]

	@testCitedbys = [
		{
			pub_id:1
			title:"Test Citedbys 1"
			note_content: "Test Note"
			rating: 1
			note_date: 1429863767
		},
		{
			pub_id:2
			title:"Test Citedbys 2"
			note_content: "Test Note"
			rating: 3
			note_date: 1429863767
		},
		{
			pub_id:3
			title:"Test Citedbys 3"
			note_content: "Test Note"
			rating: 2
			note_date: 1429863767
		}
	]

	@startEdit = ()->
		@tempEntityDetail = @tempEntityDetail || {}
		@overwriteObject @entity, @tempEntityDetail
		@isEditing = true;

	@saveEdit = ()->
		isNewPaper = @isNewEntity(@entity) #if the entity was empty before editing, this is a newly add paper. 

		@overwriteObject @tempEntityDetail, @entity;
		#must use overwrite, so don't loose reference in the table.
		
		@isEditing = false;
		# @entity.pub_id = 124;		
		actionName = if isNewPaper then "add_paper" else "update_paper"
		
		console.log "AJAX: #{actionName}"
		$.post($scope.globalCtrl.getServerAddr(),{action:actionName,data:@entity},(res)->
			console.log res
			if isNewPaper then self.entity.pub_id = res.pub_id
		, 'json');

	@cancelEdit = ()->
		@tempEntityDetail = null;
		@isEditing = false;

	@setCurrentEntity = (entity)->
		@entity = entity;

		if @isNewEntity(entity)
		 	@startEdit()
		else
			@setCitationList(entity)
		


	@removeCurrentEntity = ()->
		@entity = {}


	@overwriteObject = (fromObj,toObj)->
		for k,v of fromObj
			toObj[k]=v

	@isNewEntity = (entity)-> !(entity? && entity.pub_id?)

	@setTabIndex = (index)->@tabIndex=index

	@setCitationList = (entity)->
		if(entity?)
			@currentCitationList = if @isReference then @testReferenes else @testCitedbys

	@setCitationEntity = (entity)->
		@citationEntity = entity
  	

	@setIsReference = (isReference)->
		@isReference = isReference
		@setCitationList(@entity)


	0
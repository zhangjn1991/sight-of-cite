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
			@currentCitationList = if @isReference then entity.references else entity.citedbys
			@currentCitationList = _.flatten(@currentCitationList)

	@setCitationEntity = (entity)->
		@citationEntity = entity
  	

	@setIsReference = (isReference)->
		@isReference = isReference
		@setCitationList(@entity)

	@saveCitationNote = ()->
		data = {
			pub_id_1:@entity.pub_id
			pub_id_2:@citationEntity.pub_id
			note_content:@citationEntity.note_content
			rating:@citationEntity.note_rating
			date:null
		}
		$.post($scope.globalCtrl.getServerAddr(),{action:'update_note_by_paper_ids',data:data},(res)->console.log res)

	@isInvalidCitation = (citation)->
		return citation.pub_id == @entity.pub_id
	
	

	0
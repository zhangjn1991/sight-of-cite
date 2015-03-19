angular.module 'sightApp'
.controller "InfoBarController", ($scope)->
	self = @
	$scope.globalCtrl.infobarCtrl = @;

	@entity={};

	@isEditing=false;

	@tempEntityDetail = null;

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
		);

	@cancelEdit = ()->
		@tempEntityDetail = null;
		@isEditing = false;

	@setCurrentEntity = (entity)->
		@entity = entity;
		if @isNewEntity(entity) then @startEdit()
	@removeCurrentEntity = ()->
		@entity = {}


	@overwriteObject = (fromObj,toObj)->
		for k,v of fromObj
			toObj[k]=v

	@isNewEntity = (entity)-> !(entity? && entity.pub_id?)

	0
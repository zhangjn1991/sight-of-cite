angular.module 'sightApp'
.controller "InfoBarController", ($scope)->
	@isEditing=false;
	@entity = {
		"title":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, odit!",
		"author":"Lorem ipsum dolor sit amet.",
		"year":2010,
		"conference":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat, minus.",
		"abstract":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi illum doloribus corrupti culpa unde consectetur dolores dolorum temporibus sunt quo ipsam dicta vero esse voluptatum harum a modi fugiat, labore voluptatem error ipsum totam, aliquam consequatur. Impedit hic numquam laborum inventore! Voluptatum commodi neque, odio quod tempora ut? Modi, harum? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis libero similique illo quidem alias repudiandae ad ut corrupti assumenda consequatur accusamus temporibus facere amet eius doloribus explicabo in, numquam, sapiente nihil voluptatibus exercitationem recusandae. Maxime dolores eaque sunt tempora, veniam accusantium distinctio deleniti, error illo reprehenderit natus, ducimus alias repudiandae?"
		"tag":["Visualization","Network","Graph"],
		"comment":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore accusantium eius adipisci illum mollitia possimus porro, id veniam nemo molestiae quaerat recusandae veritatis at vel aspernatur quia eum nostrum aperiam alias fuga! Hic, quaerat, placeat vitae nulla cumque iusto non possimus obcaecati, pariatur odio qui cum dicta. Nisi praesentium, aliquam."
	}

	@tempEntityDetail = null;


	@startEdit = ()->		
		@tempEntityDetail = _.clone @entity;
		@isEditing = true;

	@saveEdit = ()->
		@entity = _.clone @tempEntityDetail;
		@isEditing = false;
		$.post($scope.globalCtrl.getServerAddr()+"add_paper",@entity);

	@cancelEdit = ()->
		@tempEntityDetail = null;
		@isEditing = false;





	0
angular.module 'sightApp'
.controller "InfoBarController", ($scope)->
	@entity = {
		"title":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident, odit!",
		"author":"Lorem ipsum dolor sit amet.",
		"year":2010,
		"conference":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat, minus.",
		"abstract":"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi illum doloribus corrupti culpa unde consectetur dolores dolorum temporibus sunt quo ipsam dicta vero esse voluptatum harum a modi fugiat, labore voluptatem error ipsum totam, aliquam consequatur. Impedit hic numquam laborum inventore! Voluptatum commodi neque, odio quod tempora ut? Modi, harum? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis libero similique illo quidem alias repudiandae ad ut corrupti assumenda consequatur accusamus temporibus facere amet eius doloribus explicabo in, numquam, sapiente nihil voluptatibus exercitationem recusandae. Maxime dolores eaque sunt tempora, veniam accusantium distinctio deleniti, error illo reprehenderit natus, ducimus alias repudiandae?"
		"tag":["Visualization","Network","Graph"]
	}
	0
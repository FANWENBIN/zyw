$(function(){


	var page = {
		init: function(){
			$("#J_ConTop .item").hover(page.hover);
			$("#J_ConTop .l").hover(page.hover);
			$("#J_ConFocus .inner .cover").hover(page.hover);
			$("#J_ConEvent .inner .l").hover(page.hover);
			$("#J_ConEvent .inner .item").hover(page.hover);
			$("#J_ConStars .inner .l").hover(page.hover);
			$("#J_ConStars .inner .item").hover(page.hover);
			$("#J_ConStage .inner .item").hover(page.hover);
			$("#J_ConVideo .inner .cover").hover(page.hover);
			$("#J_ConYoulike .inner .l").hover(page.hover);
			$("#J_ConYoulike .inner .item").hover(page.hover);
		},
		hover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(100);
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(100);
			}
		}
	};
	page.init();


});
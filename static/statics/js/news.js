$(function(){


	var page = {
		init: function(){
			$("#J_ConFansFocus .inner .item").hover(page.hover);
			$("#J_ConStarsNews .inner .l").hover(page.hover);
			$("#J_ConStarsNews .inner .item").hover(page.hover);
			$("#J_ConChinaDream .inner .item").hover(page.hover);

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
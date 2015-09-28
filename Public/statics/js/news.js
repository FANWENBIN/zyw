$(function(){


	var page = {
		init: function(){
			$("#J_ConFansFocus .inner .item").hover(page.hover);
			$("#J_ConStarsNews .inner .l").hover(page.hover);
			$("#J_ConStarsNews .inner .item").hover(page.hover);
			$("#J_ConChinaDream .inner .item").hover(page.hover);
            $("#bannerList li").hover(page.hoverIn,function(){});
            $("#bannerList li:nth-child(1)").find(".frame").show()

		},
		hover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(100);
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(100);
			}
		},
        hoverIn: function(){
            $("#bannerList").find(".frame").hide();
            $(this).find(".frame").show()
            $("#imgBanner").attr("src","__PUBLIC__/statics/images/banner-news.jpg");
            $("#imgBanner").attr("alt",$(this).index())
        }
	};
	page.init();


});
$(function(){


	var page = {
		init: function(){
            //$("#J_ConFansFocus .inner .item").mouseenter(page.hover);
            //$("#J_ConFansFocus .inner .item").mouseleave(page.hover);
            //$("#J_ConStarsNews .inner .l").mouseenter(page.hover);
            //$("#J_ConStarsNews .inner .l").mouseleave(page.hover);
            //$("#J_ConStarsNews .inner .item").mouseenter(page.hover);
            //$("#J_ConStarsNews .inner .item").mouseleave(page.hover);
            //$("#J_ConChinaDream .inner .item").mouseenter(page.hover);
            //$("#J_ConChinaDream .inner .item").mouseleave(page.hover);
            $("#bannerList li").mouseenter(page.hoverIn);
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
            $(this).find(".frame").show();
            var _index = $(this).index();
            $("#imgList").find(".img").hide();
            $("#imgList").find(".img").eq(_index).show()
            $("#topText").find("span").hide();
            $("#topText").find("span").eq(_index).show();
        }
	};
	page.init();


});
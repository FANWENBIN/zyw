$(function(){


	var page = {
		init: function(){
			$("#J_ConStars .inner .item").hover(page.hover);
			$("#J_ConRule .inner .content1 .item").hover(page.hover);
			$("#J_ConVote .inner .item").hover(page.voteHover);
			$("#J_CommentSendbox .submit").on("click", page.commentSendClick);
			$("#J_ConRule .tab a").on("click", page.ruleTabClick);
      $("#J_groupList .all").on("click",page.tabAll)
      $("#J_groupList .red").on("click",page.tabRed)
      $("#J_groupList .blue").on("click",page.tabBlue)
      $("#J_groupList .green").on("click",page.tabGreen)
		},
		ruleTabClick: function(){
			var i = $(this).data("i");
			$("#J_ConRule .inner .content").hide();
			$("#J_ConRule .inner .content" + i).show();
			$("#J_ConRule .tab a.c").removeClass("c");
			$(this).addClass("c");
		},
		hover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(300);
        $(this).find(".txt").stop(true,true).fadeOut(300)
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(300);
        $(this).find(".txt").stop(true,true).fadeIn(300)
			}
		},
		voteHover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(300);
				$(this).find(".txt").stop(true,true).fadeOut(300);
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(300);
				$(this).find(".txt").stop(true,true).fadeIn(300);
			}
		},
    tabAll: function(){
      $("#J_groupList li").removeClass("active");
      $(this).addClass("active")
    },




    tabRed: function(){
      $("#J_groupList li").removeClass("active");
      $(this).addClass("active")
    },

    tabBlue: function(){
      $("#J_groupList li").removeClass("active");
      $(this).addClass("active")
    },

    tabGreen: function(){
      $("#J_groupList li").removeClass("active");
      $(this).addClass("active")
    },
		commentSendClick: function(e){
			var text = $.trim($("#J_CommentSendbox textarea").val());
			if (text == ""){
				alert("请输入评论内容");
			} else {
				var html = "<div class='item clearFix'><div class='head'><img src='"+STATIC_FILE_ROOT+"statics/images/p/a10.jpeg' /></div><div class='info'><span>V网友：清晰白阳2 发表日期：2015-06-07 19:56</span><p>" + text + "</p></div></div>";
                $("#J_CommentList").prepend(html);
                $("#J_CommentSendbox textarea").val("");
			}
		}
	};
	page.init();


});
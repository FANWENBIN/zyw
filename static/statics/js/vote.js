$(function(){


	var page = {
		init: function(){
			$("#J_ConStars .inner .list a").hover(page.hover);
			$("#J_ConRule .inner .content1 .item").hover(page.hover);
			$("#J_ConVote .inner .item").hover(page.voteHover);
			$("#J_CommentSendbox .submit").on("click", page.commentSendClick);
		},
		hover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(100);
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(100);
			}
		},
		voteHover: function(e){
			if (e.type == "mouseenter"){
				$(this).find(".hover").stop(true,true).fadeIn(100);
				$(this).find(".txt").stop(true,true).fadeOut(100);
			} else {
				$(this).find(".hover").stop(true,true).fadeOut(100);
				$(this).find(".txt").stop(true,true).fadeIn(100);
			}
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
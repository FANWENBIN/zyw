define(function(require, exports, module){
	var mediaPopup = require("com/wx_media_pop"); // 微信media的弹框与内容

	// 微信动作设置
	var wc = {
		init: function(){
			wc.dom = $("#wcInputs");
			if (wc.dom.length == 0){
				return false;
			}

			// 设置动作的类型切换
			wc.dom.find(".hd a").on("click", wc.switchMediaType);

			// 文本消息输入时显示剩余字数
			wc.checkTxtLength();
			wc.dom.find(".bd .bdtxt textarea").on("input", wc.checkTxtLength);

			// 已选择的音频试听
			wc.dom.find(".bd .bdaudio .content").on("click", ".item .play", wc.playAudio);

			// 事件的下拉列表
			wc.dom.find(".bd .bdevent select").selectInit();

			// 显示当前
			var c = wc.dom.find(".hd a.c").data("type");
			if (c){
				wc.dom.find(".bd .bditem").hide().siblings("." + c).show();
			}
		},
		checkTxtLength: function(){
			var t = wc.dom.find(".bd .bdtxt");
			var val = t.find("textarea").val(), len = val.length;
			if (600 - len >= 0){
				t.find(".tools p").html("还可以输入" + (600 - len) + "字");
			} else {
				t.find(".tools p").html("已经超出" + (len - 600) + "字");
			}
		},
		// 动作类型切换
		switchMediaType: function(){
			var th = $(this);
			var type = $(this).data("type"), id;
			if (th.hasClass("c")){
				id = wc.dom.find(".bd ." + type + " .content .item").data("id");
			}
			switch (type){
				case "bdtxt":
				case "bdlink":
				case "bdevent":
					$(this).addClass("c").siblings("a").removeClass("c");
					wc.dom.find(".bd ." + type).show().siblings(".bditem").hide();
				break;
				case "bdarticle":
					wc.openMediaPop(id, "article");
				break;
				case "bdimg":
					wc.openMediaPop(id, "photo");
				break;
				case "bdaudio":
					wc.openMediaPop(id, "audio");
				break;
			}
			return;
		},
		// 打开素材弹框
		openMediaPop: function(id, type){
			switch(type){
				case "article":
					mediaPopup.openArticle(id, wc.articlePopCallback);
				break;
				case "photo":
					mediaPopup.openPhoto(id, wc.photoPopCallback);
				break;
				case "audio":
					mediaPopup.openAudio(id, wc.audioPopCallback);
				break;
			}
		},
		// 图文素材弹框回调
		articlePopCallback: function(o){
			var html = "";
			if (o.type == "single"){
				html = "<div class='item wxMediaSingle' data-id='" + o.id + "'>" + o.html + "</div>";
			} else {
				html = "<div class='item wxMediaMulti' data-id='" + o.id + "'>" + o.html + "</div>";
			}
			var dom = wc.dom.find(".bd .bdarticle .content");
			dom.show().html(html).siblings(".append").hide();
			dom.find(".item .mask").remove();

			// 切换菜单当前
			wc.dom.find(".hd .article").addClass("c").siblings("a").removeClass("c");
			wc.dom.find(".bd .bdarticle").show().siblings(".bditem").hide();
		},
		// 图片素材弹框回调
		photoPopCallback: function(o){
			var dom = wc.dom.find(".bd .bdimg .content");
			dom.show().html(o.html).siblings(".append").hide();

			// 切换菜单当前
			wc.dom.find(".hd .img").addClass("c").siblings("a").removeClass("c");
			wc.dom.find(".bd .bdimg").show().siblings(".bditem").hide();
		},
		// 语音素材弹框回调
		audioPopCallback: function(o){
			var dom = wc.dom.find(".bd .bdaudio .content");
			dom.show().html(o.html).siblings(".append").hide();

			// 切换菜单当前
			wc.dom.find(".hd .audio").addClass("c").siblings("a").removeClass("c");
			wc.dom.find(".bd .bdaudio").show().siblings(".bditem").hide();
		},
		playAudio: function(){
			var th = $(this);
			if (!th.hasClass("pause")){
				$.audioPlayer.remove();
				var url = th.data("audio");
				if (url){
					th.addClass("pause");
					$.audioPlayer(url);
				}
			} else {
				th.removeClass("pause");
				$.audioPlayer.remove();
			}
		}
	};
	$(function(){
		wc.init();
	});

	

});





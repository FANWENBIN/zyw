define(function(require, exports, module){

	// 打开图文弹框
	exports.openArticle = function(id, fnCallback){
		module.articleId = id;
		$.alert({
			title: "添加图文消息",
			txt: "<div class='assetsList' id='_assetsListPop_'><div class='loading'></div></div>",
			init: function(){
				module.ajaxArticleHtml(1);
			},
			btnY: "确定",
			btnN: "取消",
			btnYClose: false,
			css: "pop-alert-assetsList",
			callbackY: function(){
				if (module.articleId){
					var c = $("#_assetsListPop_ .current");
					fnCallback({id: module.articleId, html: c.html(), type: c.data("type")});
				}
				$.alert.remove();
			}
		});
	}
	module.ajaxArticleHtml = function(page){
		var dom = $("#_assetsListPop_");
		$.ajaxSubmit({
			loading: false,
			url: webPath + "media/_jsonnews.php",
			data: {page: page},
			success: function(d){
				var pop = $("#pop-alert .pop");
				if (d.status == 0 && d.data.list){
					var l = 0, html = "";
					for (var i in d.data.list){
						l += 1;
						var dt = d.data.list[i];
						if (dt.length == 1){
							if (module.articleId != i){
								html += "<div class='item wxMediaSingle' data-type='single' data-id='" + i + "'>";
								html += "<div class='mask'></div>";
							} else {
								html += "<div class='item wxMediaSingle current' data-type='single' data-id='" + i + "'>";
								html += "<div class='mask' style='display:block'></div>";
							}
							html += "<h2>" + dt[0].title + "</h2><span class='date'>" + dt[0].instime + "</span>";
							html += "<div class='img'><img src='" + imgUploadFile + dt[0].picpath + "'></div><p>" + dt[0].description + "</p></div>";
						} else if (dt.length > 1){
							if (module.articleId != i){
								html += "<div class='item wxMediaMulti' data-type='multi' data-id='" + i + "'>";
								html += "<div class='mask'></div>";
							} else {
								html += "<div class='item wxMediaMulti current' data-type='multi' data-id='" + i + "'>";
								html += "<div class='mask' style='display:block'></div>";
							}
							html += "<span class='date'>" + dt[0].instime + "</span>";
							html += "<div class='cover'><div class='img'><img src='" + imgUploadFile + dt[0].picpath + "' /><p>" + dt[0].title + "</p></div></div>";
							for (var j = 1; j < dt.length; j++){
								html += "<div class='sub clearFix'><p>" + dt[j].title + "</p><div class='img'><img src='" + imgUploadFile + dt[j].picpath + "' /></div></div>";
							}
							html += "</div>";
						}
					}
					if (l != 0){
						dom.html(html).on("hover", ".item", module.articlePopItemHover).on("click", ".item", module.articlePopItemClick);
						$.waterfall.init(dom, "item", 3, 289, 15);
						// 添加翻页
						if (pop.find(".pagers").length == 0){
							pop.append("<div class='pagers' data-c='1' data-max='" + d.data.pageTotal + "'><ul class='clearFix'></ul></div>");
							pop.find(".pagers").on("click", "ul li a", module.articlePopPagerClick);
						} else {
							pop.find(".pagers").data("c", page);
						}
						html = "<li><p><span class='c'>" + page + "</span><span>/</span><span>" + d.data.pageTotal + "</span></p></li>";
						if (page == 1){
							html += "<li><a class='disabled prev' href='javascript:void(0);'>上一页</a></li>";
						} else {
							html += "<li><a class='prev' href='javascript:void(0);'>上一页</a></li>";
						}
						if (page < d.data.pageTotal){
							html += "<li><a class='next' href='javascript:void(0);'>下一页</a></li>";
						} else {
							html += "<li><a class='disabled next' href='javascript:void(0);'>下一页</a></li>";
						}
						pop.find(".pagers ul").html(html);
					} else {
						dom.html("<p class='errtips'>没有可用的素材，请到素材管理中添加</p>");
						pop.find(".pagers").off().remove();
					}
				} else {
					dom.html("<p class='errtips'>" + d.msg + "</p>");
					pop.find(".pagers").off().remove();
				}
			},
			error: function(){
				dom.html("<p class='errtips'>系统错误，请稍后再试！</p>");
				pop.find(".pagers").off().remove();
			}
		});
	}
	module.articlePopItemHover = function(e){
		var _this = $(this);
		if (e.type == "mouseenter"){
			if (!_this.hasClass("current")){
				$(this).find(".mask").show();
			}
		} else {
			if (!_this.hasClass("current")){
				$(this).find(".mask").hide();
			}
		}
	}
	module.articlePopItemClick = function(){
		$("#_assetsListPop_ .current").removeClass("current").find(".mask").hide();
		$(this).addClass("current").find(".mask").show();
		module.articleId = $(this).data("id");
	}
	module.articlePopPagerClick = function(){
		if (!$(this).hasClass("disabled")){
			var pagers = $("#pop-alert .pop .pagers");
			var th = $(this);
			var c = pagers.data("c");
			var max = pagers.data("max");
			if (th.hasClass("next") && max > c){
				c++;
				module.ajaxArticleHtml(c);
			} else if (th.hasClass("prev") && c > 1) {
				c--;
				module.ajaxArticleHtml(c);
			}
		}
	}


	// 打开图片弹框
	exports.openPhoto = function(id, fnCallback){
		module.picId = id;
		$.alert({
			title: "添加图片",
			txt: "<div class='photoList' id='_photoListPop_'><div class='loading'></div></div>",
			init: function(){
				module.ajaxPhotoHtml(1);
			},
			btnY: "确定",
			btnN: "取消",
			btnYClose: false,
			css: "pop-alert-photoList",
			callbackY: function(){
				if (module.picId){
					var c = $("#_photoListPop_ .current");
					var src = c.data("src");
					var html = "<div class='item wxMediaPhoto' data-id='" + module.picId + "'><img src='" + imgUploadFile + src + "' /></div>";
					fnCallback({id: module.picId, html: html, src: src});
				}
				$.alert.remove();
			}
		});
	}
	module.ajaxPhotoHtml = function(page){
		var dom = $("#_photoListPop_");
		$.ajaxSubmit({
			loading: false,
			url: webPath + "media/_jsonmedia.php",
			data: {page: 1, type: "image"},
			success: function(d){
				var pop = $("#pop-alert .pop");
				if (d.status == 0 && d.data.list){
					if (d.data.length != 0){
						var html = "";
						for (var i = 0; i < d.data.list.length; i++){
							var dt = d.data.list[i];
							if (module.picId != dt.id){
								html += "<div class='item' data-id='" + dt.id + "' data-src='" + dt.filepath + "'><div class='mask'></div><div class='img'><img src='" + imgUploadFile + dt.filepath + "' /></div><div class='name'><h6>name.jpg</h6></div></div>";
							} else {
								html += "<div class='item current' data-id='" + dt.id + "' data-src='" + dt.filepath + "'><div class='mask' style='display:block'></div><div class='img'><img src='" + imgUploadFile + dt.filepath + "' /></div><div class='name'><h6>name.jpg</h6></div></div>";
							}
						}
						dom.html(html).on("hover", ".item", module.photoPopItemHover).on("click", ".item", module.photoPopItemClick);
						$.waterfall.init(dom, "item", 4, 213, 15);
						// 添加翻页
						if (pop.find(".pagers").length == 0){
							pop.append("<div class='pagers' data-c='1' data-max='" + d.data.pageTotal + "'><ul class='clearFix'></ul></div>");
							pop.find(".pagers").on("click", "ul li a", module.photoPopPagerClick);
						} else {
							pop.find(".pagers").data("c", page);
						}
						html = "<li><p><span class='c'>" + page + "</span><span>/</span><span>" + d.data.pageTotal + "</span></p></li>";
						if (page == 1){
							html += "<li><a class='disabled prev' href='javascript:void(0);'>上一页</a></li>";
						} else {
							html += "<li><a class='prev' href='javascript:void(0);'>上一页</a></li>";
						}
						if (page < d.data.pageTotal){
							html += "<li><a class='next' href='javascript:void(0);'>下一页</a></li>";
						} else {
							html += "<li><a class='disabled next' href='javascript:void(0);'>下一页</a></li>";
						}
						pop.find(".pagers ul").html(html);
					} else {
						dom.html("<p class='errtips'>没有可用的素材，请到素材管理中添加</p>");
						pop.find(".pagers").off().remove();
					}
				} else {
					dom.html("<p class='errtips'>" + d.msg + "</p>");
					pop.find(".pagers").off().remove();
				}
			},
			error: function(){
				dom.html("<p class='errtips'>系统错误，请稍后再试！</p>");
			}
		});
	}
	module.photoPopItemHover = function(e){
		var _this = $(this);
		if (e.type == "mouseenter"){
			if (!_this.hasClass("current")){
				$(this).find(".mask").show();
			}
		} else {
			if (!_this.hasClass("current")){
				$(this).find(".mask").hide();
			}
		}
	}
	module.photoPopItemClick = function(){
		$("#_photoListPop_ .current").removeClass("current").find(".mask").hide();
		$(this).addClass("current").find(".mask").show();
		module.picId = $(this).data("id");
	}
	module.photoPopPagerClick = function(){
		if (!$(this).hasClass("disabled")){
			var pagers = $("#pop-alert .pop .pagers");
			var th = $(this);
			var c = pagers.data("c");
			var max = pagers.data("max");
			if (th.hasClass("next") && max > c){
				c++;
				module.ajaxPhotoHtml(c);
			} else if (th.hasClass("prev") && c > 1) {
				c--;
				module.ajaxPhotoHtml(c);
			}
		}
	}


	// 打开语音弹框
	exports.openAudio = function(id, fnCallback){
		module.audioId = id;
		$.alert({
			title: "添加语音",
			txt: "<div class='audioList' id='_audioListPop_'><div class='loading'></div></div>",
			init: function(){
				var html = "";
				for (var i = 0; i < 10; i++){
					if (module.audioId != "a_" + i){
						html += "<div class='item' data-size='1112.83' data-duration='57' data-id='a_" + i + "' data-audio='http://192.168.0.16/php/uploads/14200079099236.mp3'>";
						html += "<input type='radio' class='r' name='audioRadio' data-val='会痛的石头_1s.mp3' />"
					} else {
						html += "<div class='item current' data-size='1112.83' data-duration='57' data-id='a_" + i + "' data-audio='http://192.168.0.16/php/uploads/14200079099236.mp3'>";
						html += "<input type='radio' class='r' checked='checked' name='audioRadio' data-val='会痛的石头_1s.mp3' />"
					}
					html += "<div class='info'><a href='javascript:void(0)' class='play'><sub></sub></a>";
					html += "<div class='r'><span>大小：1112.83K</span><span>时长：57s</span></div></div></div>";
				}
				$("#_audioListPop_").html(html).on("click", ".info a", module.playPopAudio).on("click", ".js_radio", module.audioPopItemClick).find("input.r").inputInit();
				// 添加翻页
				html = "<div class='pagers'><ul class='clearFix'>";
				html += "<li><p><span class='c'>1</span><span>/</span><span>1</span></p></li>";
				html += "<li><a class='disabled prev' href='javascript:void(0);'>上一页</a></li>";
				html += "<li><a class='next' href='javascript:void(0);'>下一页</a></li>";
				html += "</ul></div>";
				$("#pop-alert .pop").append(html);
			},
			btnY: "确定",
			btnN: "取消",
			btnYClose: false,
			css: "pop-alert-audioList",
			callbackY: function(){
				if (module.audioId){
					var current = $("#_audioListPop_ .item .js_radioChecked").parent(".item");
					var id = current.data("id"),
						size = current.data("size"),
						duration = current.data("duration"),
						audio = current.data("audio"),
						name = current.find("input.r").data("val");

					var html = "<div class='item wxMediaAudio' data-id='" + module.audioId + "'><h2>" + name + "</h2>";
					html += "<div class='info'><a href='javascript:void(0)' data-audio='" + audio + "' class='play'><sub></sub></a>";
					html += "<div class='r'><span>大小：" + size + "K</span><span>时长：" + duration + "s</span></div></div></div>";
					fnCallback({id: module.audioId, html: html});
				}
				$.audioPlayer.remove();
				$.alert.remove();
			}
		});
	}
	module.audioPopItemClick = function(){
		module.audioId = $(this).parent(".item").data("id");
	};
	module.playPopAudio = function(){
		var th = $(this);
		if (!th.hasClass("pause")){
			$.audioPlayer.remove();
			$("#_audioListPop_").find(".item .info .pause").removeClass("pause");
			var url = th.parents(".item").data("audio");
			if (url){
				th.addClass("pause");
				$.audioPlayer(url);
			}
		} else {
			th.removeClass("pause");
			$.audioPlayer.remove();
		}
	}

});





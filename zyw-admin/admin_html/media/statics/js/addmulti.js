define(function(require, exports, module){
    require("common");
	var mediaPopup = require("com/wx_media_pop");

	$(function(){

		
		// 多图文
		var asset = {
			init: function(){
				asset.item = $("#itemMulit");
				asset.item.on("hover", ".cover, .sub", asset.toggleTools);
				asset.item.on("click", ".tools .edit", asset.editItem);
				asset.item.on("click", ".sub .tools .del", asset.deleteItem);
				$("#title").on("input", asset.inputTitle);
				asset.item.on("click", ".append a", asset.appendItem);
				asset.current = 0;
				asset.initItem();
				$("#article").html(__mulit.cover.article);
				asset.uditor = UE.getEditor("article",{
				    initialFrameWidth: 579,
				    toolbars: [['bold', 'italic', 'underline', '|', 'insertorderedlist', 'insertunorderedlist', '|', 'simpleupload', '|', 'removeformat', 'forecolor', 'backcolor', 'insertvideo']]
				});
				asset.restoreCurrent(false);
				$("#openMedia").on("click", asset.openMedia);
				$("#submit").on("click", asset.onSubmit);
			},
			openMedia: function(){
				mediaPopup.openPhoto(undefined, asset.openMediaCallback);
			},
			openMediaCallback: function(o){
				$("#media-news").imgUploadSetVal(o.src);
			},
			toggleTools: function(e){
				if (e.type == "mouseenter"){
					$(this).find(".tools").show();
				} else {
					$(this).find(".tools").hide();
				}
			},
			initItem: function(){
				if (window.__mulit && __mulit.cover && __mulit.sub){
					var html = "<div class='cover'><div class='img'>";
					if (__mulit.cover.coverimg){
						html += "<div class='p'><img src='" + imgUploadFile + __mulit.cover.coverimg + "' /></div>";
					} else {
						html += "<div class='p'>封面图片</div>";
					}
					if (__mulit.cover.title == ""){
						html += "<p><a href='javascript:void(0)'>标题</a></p>";
					} else {
						html += "<p><a href='javascript:void(0)'>" + __mulit.cover.title + "</a></p>";
					}
					html += "<div class='tools'><a href='javascript:void(0)' class='edit' data-i='cover'></a></div></div></div>";
					for (var i = 0; i < __mulit.sub.length; i++){
						var title = __mulit.sub[i].title || "标题";
						var img = __mulit.sub[i].coverimg ? "<img src='" + imgUploadFile + __mulit.sub[i].coverimg + "' />" : "缩略图"
						html += "<div class='sub clearFix'><p><a href='javascript:void(0)'>" + title + "</a></p>";
						html += "<div class='img'>" + img + "</div>";
						html += "<div class='tools'><a href='javascript:void(0)' class='edit'></a><a href='javascript:void(0)' class='del'></a></div></div>";
					}
					html += "<div class='append'><a href='javascript:void(0)' class='btnB btnSmall'>添加</a></div>";
					asset.item.html(html);
				} else {
					window.__mulit = {
						cover: { title: "", author: "", coverimg: "", show: true, article: "", link: ""},
						sub: [{ title: "", author: "", coverimg: "", show: true, article: "", link: "" }]
					}
					asset.initItem();
				}
			},
			appendItem: function(){
				var length = asset.item.find(".sub").length;
				if (length >= 7){
					$.tips.error("你最多只可以加入8条图文消息");
				} else {
					var html = "<div class='sub clearFix'><p><a href='javascript:void(0)'>标题</a></p><div class='img'>缩略图</div><div class='tools'><a href='javascript:void(0)' class='edit'></a><a href='javascript:void(0)' class='del'></a></div></div>";
					asset.item.find(".append").before(html);
					__mulit.sub.push({title: "", author: "", coverimg: "", show: false, article: "", link: ""});
				}
				if (length == 6){
					asset.item.find(".append").hide();
				}
			},
			editItem: function(){
				asset.saveCurrent();
				if ($(this).data("i") == "cover"){
					asset.current = 0;
				} else {
					asset.current = $(this).parents(".sub").index();
				}
				$(this).parent(".tools").hide();
				asset.restoreCurrent();
			},
			deleteItem: function(){
				var length = asset.item.find(".sub").length;
				if (length <= 1){
					$.tips.error("无法删除，多条图文至少需要2条消息");
				} else {
					var dom = $(this).parents(".sub");
					var index = dom.index();
					$("#wechatMulitAsset .form .uploadbtn").hide(); // swf bug,会遮挡弹出框的文字部分
					$.alert({
						title: "温馨提示",
						txt: "确定要删除此素材吗？",
						btnY: "删除",
						btnYcss: "btnC",
						btnN: "取消",
						callbackY: function(){
							$.loading();
							$("#wechatMulitAsset .form .uploadbtn").show();
							dom.fadeOut(200, function(){
								$.loading.remove();
								asset.saveCurrent();
								dom.remove();
								__mulit.sub.splice(index - 1, 1);
								asset.item.find(".append").show();
								if (index == asset.current){
									asset.current = 0;
									asset.restoreCurrent();
								} else if (index < asset.current) {
									asset.current = index;
									asset.restoreCurrent();
								}
							});
						},
						callbackN: function(){
							$("#wechatMulitAsset .form .uploadbtn").show();
						}
					});
				}
			},
			saveCurrent: function(){
				var title = $.trim($("#title").val());
				var author = $.trim($("#author").val());
				var coverimg = $.trim($("#media-news").val());
				var showInArticle = $("#showInArticle")[0].checked;
				var article = UE.getEditor("article").getContent();
				var link = $.trim($("#link").val());
				if (asset.current == 0){
					__mulit.cover.title = title;
					__mulit.cover.author = author;
					__mulit.cover.coverimg = coverimg;
					__mulit.cover.show = showInArticle;
					__mulit.cover.article = article;
					__mulit.cover.link = link;
				} else {
					__mulit.sub[asset.current - 1].title = title;
					__mulit.sub[asset.current - 1].author = author;
					__mulit.sub[asset.current - 1].coverimg = coverimg;
					__mulit.sub[asset.current - 1].show = showInArticle;
					__mulit.sub[asset.current - 1].article = article;
					__mulit.sub[asset.current - 1].link = link;
				}
			},
			restoreCurrent: function(rtDitor){
				rtDitor = rtDitor == undefined ? true : false;
				var title, author, coverimg, showInArticle, article, link;
				if (asset.current == 0){
					title = __mulit.cover.title;
					author = __mulit.cover.author;
					coverimg = __mulit.cover.coverimg;
					showInArticle = __mulit.cover.show;
					article = __mulit.cover.article;
					link = __mulit.cover.link;
					$("#wechatMulitAsset .form .cover h6 .up2").hide().siblings(".up1").show();
				} else {
					title = __mulit.sub[asset.current - 1].title;
					author = __mulit.sub[asset.current - 1].author;
					coverimg = __mulit.sub[asset.current - 1].coverimg;
					showInArticle = __mulit.sub[asset.current - 1].show;
					article = __mulit.sub[asset.current - 1].article;
					link = __mulit.sub[asset.current - 1].link;
					$("#wechatMulitAsset .form .cover h6 .up1").hide().siblings(".up2").show();
				}
				$("#wechatMulitAsset .form").attr("class", "form form_t" + asset.current);
				$("#title").val(title);
				$("#author").val(author);
				$("#media-news").imgUploadSetVal(coverimg);
				$("#showInArticle")[0].checked = showInArticle;
				var css = showInArticle ? "js_checkbox js_checkboxChecked" : "js_checkbox";
				$("#showInArticle").next(".js_checkbox").attr("class", css);
				if (rtDitor){
					asset.uditor.setContent(article, false);
				}
				$("#link").val(link);
			},
			inputTitle: function(){
				var dom;
				if (asset.current == 0){
					dom = asset.item.find(".cover p a");
				} else {
					dom = asset.item.find(".sub:eq(" + (asset.current - 1) + ") p a");
				}
				var val = $(this).val();
				if (val == ""){
					val = "标题";
				}
				dom.html(val);
			},
			showError: function(checkid){
				asset.current = checkid;
				asset.restoreCurrent();
				return false;
			},
			onSubmit: function(){
				asset.saveCurrent();

				// 检查所有内容是否填写
				var checkid = 0, title, coverimg, article;
				for (var i = 0; i < __mulit.sub.length + 1; i++){
					checkid = i;
					if (i != 0){
						title = __mulit.sub[i - 1].title;
						coverimg = __mulit.sub[i - 1].coverimg;
						article = __mulit.sub[i - 1].article;
					} else {
						title = __mulit.cover.title;
						coverimg = __mulit.cover.coverimg;
						article = __mulit.cover.article;
					}
					// 验证
					if (title == ""){
						$("#title").inputError("标题不能为空");
						return asset.showError(checkid);
					} else if ($.realLength(title) > 64){
						$("#title").inputError("标题不能超过32个中文字或64个英文字");
						return asset.showError(checkid);
					} else if (coverimg == ""){
						$.tips.error("请上传封面图片");
						return asset.showError(checkid);
					} else if (article == ""){
						$.tips.error("正文不能为空");
						return asset.showError(checkid);
					} else if ($.realLength(article) > 20000){
						$.tips.error("正文不能超过20000字");
						return asset.showError(checkid);
					}
				}

				// 以下写提交内容服务器部分代码
				__mulit.id = $('#id').val();
	            $.ajaxSubmit({
					url: "_mutiadd.php",
					data: __mulit,
					success: function(d){
					   if(d.status == 0){
					     window.location = modelUrl;
					   }else if(d.msg){
					    $.tips.error(d.msg);
					   }else{
					    $.tips.error(d);
					   }
					},
	                error: function(d){
	                    $.tips.error(d.responseText);
	                }
				});
			}
		}
		asset.init();
		$.__mulitArticleAssetImgChange__ = function(url){
			var dom, txt;
			if (asset.current == 0){
				dom = asset.item.find(".cover .img .p");
				txt = "封面图片";
			} else {
				dom = asset.item.find(".sub:eq(" + (asset.current - 1) + ") .img");
				txt = "缩略图";
			}
			if (url){
				dom.html("<img src='" + imgUploadFile + url + "' />");
			} else {
				dom.html(txt);
			}
		}



	});

});




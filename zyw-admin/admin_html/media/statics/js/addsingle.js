define(function(require, exports, module){
    require("common");
	var mediaPopup = require("com/wx_media_pop");

	$(function(){

		// 单图文
		var asset = {
			init: function(){
				asset.item = $("#itemSingle");
				$("#title").on("input", asset.inputTitle);
				$("#summary").on("input", asset.inputSummary);
				$("#submit").on("click", asset.checkForm);
				$("#openMedia").on("click", asset.openMedia);
				asset.uditor = UE.getEditor("article",{
				    initialFrameWidth: 579,
				    toolbars: [['bold', 'italic', 'underline', '|', 'insertorderedlist', 'insertunorderedlist', '|', 'simpleupload', '|', 'removeformat', 'forecolor', 'backcolor', 'insertvideo']]
				});
			},
			openMedia: function(){
				mediaPopup.openPhoto(undefined, asset.openMediaCallback);
			},
			openMediaCallback: function(o){
				$("#media-news").imgUploadSetVal(o.src);
			},
			inputTitle: function(){
				var val = $("#title").val();
				if ($.trim(val) == ""){
					val = "标题";
				}
				asset.item.find("h2 a").html(val);
			},
			inputSummary: function(){
				var val = $("#summary").val();
				asset.item.find("p").html(val);
			},
			checkForm: function(){
				var $title = $("#title");
				var $author = $("#author");
				var $summary = $("#summary");
				var $link = $("#link");

				var title = $.trim($title.val());
				var author = $.trim($author.val());
				var coverimg = $.trim($("#media-news").val());
				var showInArticle = $("#showInArticle")[0].checked;
				var summary = $.trim($summary.val());
				var article = UE.getEditor("article").getContent();
				var link = $.trim($link.val());
				
				if ($title.inputEmpty() || $title.inputLengthOverflow(64)){
					$title.inputError("标题不能为空且长度不能超过64字");
				} else if ($author.inputLengthOverflow(8)){
					$author.inputError("作者不能超过8个字");
				} else if (coverimg == ""){
					$.tips.error("请上传封面图片");
				} else if ($summary.inputEmpty() || $summary.inputLengthOverflow(120)){
					$summary.inputError("摘要不能为空且长度不能超过120字");
				} else if ($.trim(article) == "" || $.realLength(article) > 20000){
					$.tips.error("正文不能为空且长度不能超过20000字");
				} else {
				    var data = $("#form").getForm();
					$.ajaxSubmit({
						url: "_add.php",
						data: data,
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
		}
		asset.init();
		$.__singleArticleAssetImgChange__ = function(url){
			if (url){
				asset.item.find(".img").html("<img src='" + imgUploadFile + url + "' />");
			} else {
				asset.item.find(".img").html("封面图片");
			}
		}


	});

});



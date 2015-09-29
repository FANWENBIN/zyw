define(function(require, exports, module){
    require("common");

	// 音频上传
	;(function($){
		$._mp3Upload_ = {
			error: function(type, id){
				switch(type){
					case "sizeTooBig":
						$.tips.error("音频文件尺寸过大，请小于5M");
					break;
					case "mp3TypeError":
						$.tips.error("音频格式错误");
					break;
					case "uploadError":
						$.tips.error("上传错误");
					break;
				}
			},
			start: function(id){
				//console.log("upload start width id:" + id);
			},
			progress: function(pre, id){
				//console.log(pre);
			},
			complete: function(data, id){
				eval("var _data = " + data + ";");
				if (_data){
					if (_data.code == 1 && _data.data){
						var dom = $("#" + id);
						var html = "<div class='item' data-audio='" + mp3UploadFile + _data.data.url + "'>";
						html += "<a href='javascript:void(0)' class='play'><sub></sub><span>" + Math.round(_data.data.time) + "s</span></a>";
						html += "<div class='info'><h1>" + _data.data.name + "</h1><span>" + Math.round(_data.data.size / 1024 * 100) / 100 + "K</span></div>";
						html += "<div class='tools'><a href='javascript:void(0)' class='download'></a><a href='javascript:void(0)' class='rename'></a>";
						html += "<a href='javascript:void(0)' class='del'></a></div></div>";
						dom.find(".itemList").append(html);
					} else {
						$.tips.error(_data.info);
					}
				}
			}
		}
	})(jQuery);

	$(function(){


		// 音频列表
		var list = {
			init: function(){
				list.dom = $("#wechatAudioAssetsList");
				list.dom.on("click", ".itemList .item .tools .del", list.onItemDel);
				list.dom.on("click", ".itemList .item .tools .rename", list.onItemRename);
				list.dom.on("click", ".itemList .item .play", list.onItemPlay);
				swfobject.embedSWF("../statics/swf/audioUpload.swf", "flashupload", 152, 36, "11.9.0", "", {"id": "wechatAudioAssetsList", "mp3Server": mp3UploadServer});
			},
			onItemDel: function(e){
				var dom = $(this).parents(".item");
				$.alert({
					title: "温馨提示",
					txt: "确定要删除此语音吗？",
					btnY: "删除",
					btnYcss: "btnC",
					btnN: "取消",
					callbackY: function(){
						$.loading();
						if (dom.find(".play").hasClass("pause")){
							$.audioPlayer.remove();
						}
						dom.fadeOut(200, function(){
							$.loading.remove();
							dom.remove();
						});
					}
				});
			},
			onItemRename: function(){
				var dom = $(this).parents(".item").find("h1");
				var name = dom.html();
				if (name){
					name = $.trim(name);
					$.alert({
						title: "修改名称",
						txt: "<p>名称不多于25个汉字或50个字母</p><input type='text' value='" + name + "' class='input' />",
						btnY: "修改",
						btnN: "取消",
						css: "pop-alert-audioRename",
						callbackY: function(){
							var input = $("#pop-alert .pop .bd .input");
							if (input.inputEmpty()){
								input.inputError("名称不能为空");
								return false;
							} else if (input.inputLengthOverflow(50)){
								input.inputError("名称不多于25个汉字或50个字母");
								return false;
							} else {
								var val = $.trim(input.val());
								dom.html(val);
							}
						}
					});
				}
			},
			onItemPlay: function(){
				if (!$(this).hasClass("pause")){
					$.audioPlayer.remove();
					list.dom.find(".itemList .item .pause").removeClass("pause");
					var url = $(this).parents(".item").data("audio");
					if (url){
						$(this).addClass("pause");
						$.audioPlayer(url);
					}
				} else {
					$(this).removeClass("pause");
					$.audioPlayer.remove();
				}
			}
		};
		list.init();




	});
});





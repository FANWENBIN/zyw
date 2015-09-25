define(function(require, exports, module){
	require("com/alert_loading_tips"); // 弹出框、loading框、提示框
	require("com/select_radio_checkbox"); // 下拉菜单、单选框、多选框
	require("com/datainput"); // 日期下拉框
	require("com/imgupload"); // 图片上传
	require("com/waterfall"); // 瀑布流
	require("com/wx_media"); // 微信回复与菜单


	// input验证部分
	(function($){
		var errorTimeout;
		$.fn.inputError = function(t) {
			if ($(this).length) {
				if ($("#pop-alert").length == 0) {
					$.tips.error(t);
				} else {
					$.alert.error(t);
				}
				$(this).focus().addClass("inputError");
				clearTimeout(errorTimeout);
				errorTimeout = setTimeout(function(){
					$(".inputError").removeClass("inputError");
				}, 2000);
			}
			return false;
		}
		$.fn.inputEmpty = function() {
			if ($(this).length && $.trim($(this).val()) == "") {
				return true;
			}
		}
		$.fn.inputLengthOverflow = function(l) {
			if ($(this).length) {
			    return $(this).inputLength() > l;
		    }
		}
		$.fn.inputLength = function() {
			return $.realLength($(this).val());
		}
		$.realLength = function(val){
			if (val && val.length) {
			    var realLength = 0, len = val.length, charCode = -1;
			    for (var i = 0; i < len; i++) {
			        charCode = val.charCodeAt(i);
			        if (charCode >= 0 && charCode <= 128) realLength += 1;
			        else realLength += 2;
			    }
			    return realLength;
			}
			return 0;
		}

		// 去空格 还有个功能就是把<>这些标签换成&lt;和&gt;
		$.trim = function(e, b) {
		   if (b != true){
	            return e.replace(/(^\s*)|(\s*$)/g,"").replace(/</g,"&lt;").replace(/>/g,"&gt;");
	        } else {
	            return e.replace(/(^\s*)|(\s*$)/g,"");
	        }
		}
	})(jQuery);

	// ajax提交
	(function($){
		$.fn.getForm = function(){
			var th = $(this), o = {};
			th.find("select, textarea, input").each(function(){
				var _th = $(this), _n = _th.attr("name"), _val;
				if (_n != undefined){
					// radio取值
					if (_th[0].nodeName.toLowerCase() == "input" && _th.attr("type").toLowerCase() == "radio"){
					    if (_th.attr("checked") != undefined){
	    					_val = _th.val();
	    					o[_n] = _val;
	                    }
					}
					// checkbox取值
					else if (_th[0].nodeName.toLowerCase() == "input" && _th.attr("type").toLowerCase() == "checkbox"){
						_val = _th.attr("checked") != undefined;
						o[_n] = _val;
					}
					else {
						_val = _th.val();
						_val = $.trim(_val, true);
						o[_n] = _val;
					}
				}
			});
			if (th.find("#wcInputs").length != 0){
				var dom = th.find("#wcInputs");
				var type = dom.find(".hd .c").data("type");
				if (type){
					var wc = {};
					switch(type){
						case "bdtxt":
							wc.type = "txt";
							wc.content = dom.find(".bd .bdtxt textarea").val();
						break;
						case "bdarticle":
							wc.type = "article";
							wc.content = dom.find(".bd .bdarticle .content .item").data("id");
						break;
						case "bdimg":
							wc.type = "img";
							wc.content = dom.find(".bd .bdimg .content .item").data("id");
						break;
						case "bdaudio":
							wc.type = "audio";
							wc.content = dom.find(".bd .bdaudio .content .item").data("id");
						break;
					}
					o.wcasset = wc;
				}
			}
			return o;
		}

		$.ajaxSubmit = function(o){
			if (o.loading != false){
				$.loading();
			}
			$.ajax({
	            type: "POST",
	            url: o.url,
	            data: o.data || {},
	            dataType: "json",
	            success: function(data){
	            	if (o.success){
	            		o.success(data);
	            	}
					$.loading.remove();
				},
				error: function(data){
					if (o.error){
	            		o.error(data);
	            	}
	            	$.loading.remove();
				}
			});
		}
	})(jQuery);

	// 音频播放器
	;(function($){
		$.audioPlayer = function(url){
			$("#_as3_audio_player_").remove();
			var html = "<div id='_as3_audio_player_' style='width:1px;height:1px;position:absolute;left:-999px;top:-999px;overflow:hidden;'><div id='_as3_audio_player_swf_'></div></div>";
			$("body").append(html);
			swfobject.embedSWF(swfUrl + "/audioPlayer.swf", "_as3_audio_player_swf_", 1, 1, "11.9.0", "", {"audio": url});
			console.log("play audio:" + url);
		}
		$.audioPlayer.remove = function(){
			$("#_as3_audio_player_").remove();
		}
	})(jQuery);

	// 显示图片
	;(function($){
		$.picPopup = function(pic){
			$("#_pic_popup_").remove();
			var img = new Image();
			img.src = pic;
			$.loading();
			img.onload = function(){
				$.loading.remove();
				var w = img.width;
				var h = img.height;
				var wh = $(window).height() - 100;
				var ww = $(window).width() - 100;
				var bl = w / h;
				if (h > wh){
					h = wh;
					w = h * bl;
				} 
				if (w > ww){
					w = ww;
					h = w / bl;
				}
				var html = "<div id='_pic_popup_'><div class='pop' style='width:" + w + "px;height:" + h + "px;margin:-" + h / 2 + "px 0 0 -" + w / 2 + "px;'><img src='" + pic + "' /></div></div>";
				$("body").append(html);
				$("#_pic_popup_").one("click", $.picPopup.remove);
			}
		}
		$.picPopup.remove = function(){
			$("#_pic_popup_").remove();
		}
	})(jQuery);


	$(function(){

		// 初始化from表单内的元素
		$(".form form, .table, .tableTools").each(function(){

			var _this = $(this);

			// 样式化表单内的下拉框
			_this.find("select").selectInit();

			// 样式化表单内的单选框
			_this.find("input").inputInit();

			// 样式化表单内的日期框
			_this.find("input.js_date").dateInputInit();

			// 样式化图片上传框
			_this.find("input.js_imgupload").imgUploadInit();

			// 样式化图片组上传
			_this.find("input.js_imgupload_arr").imgArrUploadInit();

			// 初始化富文本编辑器
			_this.find(".js_ueditor").each(function(){
				if ($(this).data("init") != "true" && UE){
					var id = $(this).attr("id");
					UE.getEditor(id);
				}
			});

		});

		// 显示弹出框
		$("#webHeader .tools .chagepw").on("click", function(){
			var html = "<div class='i old'><h6>原始密码</h6><input type='password' class='input' /></div>";
			html += "<div class='i new'><h6>新密码</h6><input type='password' class='input' /></div>";
			html += "<div class='i new2'><h6>确认新密码</h6><input type='password' class='input' /></div>";
			// 弹出框
			$.alert({
				title: "修改密码",
				txt: html,
				btnY: "修改",
				btnN: "取消",
				css: "pop-alert-changepw",
				callbackY: function(){
					var old = $("#pop-alert .old input");
					var n = $("#pop-alert .new input");
					var n2 = $("#pop-alert .new2 input");
					if (old.inputEmpty()){
						old.inputError("原始密码不能为空");
						return false;
					} else if (n.inputEmpty()){
						n.inputError("新密码不能为空");
						return false;
					} else if (n2.inputEmpty()){
						n2.inputError("确认密码不能为空");
						return false;
					} else if (n.val() != n2.val()){
						n2.inputError("两次密码输入不一致");
						return false;
					} else {
					    var oldp = $.trim(old.val());
	                    var newp = $.trim(n.val());
						$.ajaxSubmit({
							url: webPath+"/index.php?m=Stage&c=Index&a=uppasswd",
							data: {oldp: oldp, newp: newp},
							success: function(d){
								if (d.status == 0){
	                                window.location = webPath+'/index.php?m=Stage&c=Index&a=uppasswd';
								} else {
									$.tips.error(d.msg);
								}
							},
							error: function(){
								$.tips.error("系统错误，请稍后再试！");
							}
						});
					}
				}
			});
		});

		if ($("body").height() < $(window).height()){
			$("body").height($(window).height());
		}

		// 翻页跳转
		$("#js_pagers .jump").on("click", function(){
			var th = $(this);
			var num = th.parents("ul").find("input.num").val(), max = th.data("max"), url = th.data("url");
			if (num == "" || num != ~~num || num > max){
				$.tips.error("请输入有效的页码");
			} else {
				window.location.href = url + num;
			}
		});

	});
});





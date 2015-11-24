define(function(require, exports, module){
	
	// 图片上传
	;(function($){
		function getSwfInstance(movieName) { 
			if (navigator.appName.indexOf("Microsoft") != -1) { 
				return window[movieName];
			} else { 
				return document[movieName];
			} 
		}

		// 文件式上传
		$.fn.imgUploadInit = function(){
			$(this).each(function(){
				var _in = $(this);
				if (_in.data("init") != "true" && _in.attr("id")){
					_in.data("init", "true");
					var canCut = _in.attr("cut");
					var cutWidth = _in.attr("width");
					var cutHeight = _in.attr("height");
					var maxWidth = _in.attr("maxWidth") || 400;
					var root = _in.attr("root") || "";
					var val = _in.val(), html;
					if (val.indexOf(".png") != -1 ||val.indexOf(".jpg") != -1 || val.indexOf(".jpeg") != -1){
						html = "<div class='js_swfimgupload clearFix'><p class='p'>" + val + "</p><div class='thumb'><img src='" + imgUploadFile + val + "' /></div><a href='javascript:void(0)' class='delBtn'>删除</a>";
					} else {
						if (cutWidth > 0 && cutHeight > 0){
							html = "<div class='js_swfimgupload clearFix'><p class='p'>未上传图片<span>" + cutWidth + "宽 x " + cutHeight + "高</span></p><div class='thumb'></div>";
						} else if (cutWidth > 0){
							html = "<div class='js_swfimgupload clearFix'><p class='p'>未上传图片<span>" + cutWidth + "宽</span></p><div class='thumb'></div>";
						} else if (cutHeight > 0){
							html = "<div class='js_swfimgupload clearFix'><p class='p'>未上传图片<span>" + cutHeight + "高</span></p><div class='thumb'></div>";
						} else {
							html = "<div class='js_swfimgupload clearFix'><p class='p'>未上传图片</p><div class='thumb'></div>";
						}
					}
					if (canCut == "true" && cutWidth && cutHeight){
						html += "<div class='uploadbtn'><a href='javascript:void(0)'>上传</a></div></div>";
						_in.hide().after(html);
						_in.next(".js_swfimgupload").find(".uploadbtn a").on("click", function(){
							$.imgUploadPop({
								width: cutWidth,
								height: cutHeight,
	                            id: _in.attr("id"),
	                            root: root,
								complete: function(d){
									console.log(d)
									if (d.status == 0){
										_in.imgUploadSetVal(d.data.name);
									} else {
										$.alert.error(d.msg);
									}
								}
							});
						});
					} else {
						html += "<div class='uploadbtn'><div id='_swf_" + _in.attr("id") + "'></div></div></div>";
						_in.hide().after(html);
						swfobject.embedSWF(swfUrl + "/upload.swf?v2", "_swf_" + _in.attr("id"), 80, 36, "11.9.0", "", {"id": _in.attr("id"), "root": root, "imgServer": imgUploadServer, "maxWidth": maxWidth});
					}
					var divInput = _in.next(".js_swfimgupload");
					divInput.on("hover", "p.p, .delBtn, .thumb", function(e){
						if (e.type == "mouseenter"){
							if (divInput.find(".delBtn").length){
								divInput.addClass("js_swfimgupload_open").find(".thumb").show().siblings(".delBtn").css({"display": "block"});
							}
						} else {
							divInput.removeClass("js_swfimgupload_open").find(".thumb").hide().siblings(".delBtn").hide();
						}
					});
					divInput.on("click", ".delBtn", function(e){
						$.alert({
							title: "温馨提示",
							txt: "确定要删除该图片吗？",
							btnN: "取消",
							btnY: "删除",
							btnYcss: "btnC",
							callbackY: function(){
								_in.imgUploadSetVal();
							}
						});
					});
				}
			});
		};
		$.fn.imgUploadSetVal = function(d){
	        if ((d && d.status == 0) || (d && typeof d == "string")){
	    		var _this = $(this);
	            var url = d.name ? d.name : (d.data && d.data.name ? d.data.name : d);
				_this.val(url);
				var divupload = _this.next(".js_swfimgupload");
				divupload.find("p.p").html(url).siblings(".thumb").html("<img src='" + imgUploadFile + url + "' />");
				if (divupload.find("a.delBtn").length == 0){
					divupload.append("<a href='javascript:void(0)' class='delBtn'>删除</a>");
				}
	    		var onchange = $(this).attr("onchange");
	    		if (onchange){
	    			try{
	                    if (url){
	    				    eval(onchange + "('" + url + "')");
	                    } else {
	                        eval(onchange + "('')");
	                    }
	    			} catch(e) {}
	    		}
	        } else if (d && d.status != 0) {
	            $.tips.error(d.msg);
	        } else {
	        	var th = $(this);
	        	var w = th.attr("width");
	        	var h = th.attr("height");
	        	var html = "";
	        	if (w > 0 && h > 0){
	        		html = "未上传图片<span>" + w + "宽 x " + h + "高</span>";
	        	} else if (w > 0){
	        		html = "未上传图片<span>" + w + "宽</span>";
	        	} else if (h > 0){
	        		html = "未上传图片<span>" + h + "高</span>";
	        	} else {
	        		html = "未上传图片";
	        	}
	            th.val("").next(".js_swfimgupload").find("p.p").html(html).siblings(".thumb").empty().siblings(".delBtn").remove();
	            var onchange = $(this).attr("onchange");
	    		if (onchange){
	    			try{
	                    if (url){
	    				    eval(onchange + "('" + url + "')");
	                    } else {
	                        eval(onchange + "('')");
	                    }
	    			} catch(e) {}
	    		}
	        }
		};

		// 一组图片上传
		$.fn.imgArrUploadInit = function(){
			$(this).each(function(){
				var _in = $(this);
				if (_in.data("init") != "true" && _in.attr("id")){
					_in.data("init", "true");
					var imgs = _in.val(), imgArr = imgs.split("|||");
					var cutWidth = _in.attr("width");
					var cutHeight = _in.attr("height");
					var itemHeight = Math.round(253 / cutWidth * cutHeight);
					var max = _in.attr("max") || 6;
					var root = _in.attr("root") || "";

					var html = "<div class='js_swfimgupload_arr clearFix'>";
					if (imgs && imgArr.length != 0){
						for (var i = 0; i < imgArr.length; i++){
							html += "<div class='item' style='height:" + itemHeight + "px;' data-src='" + imgArr[i] + "'><a href='javascript:void(0)' class='delBtn' style='display: none;'>删除</a><img src='" + imgUploadFile + imgArr[i] + "' /></div>";
						}
					}
					html += "<a href='javascript:void(0)' class='item appendbtn' style='height:" + (itemHeight - 2) + "px;'><span>添加图片</span></a></div>";
					_in.hide().after(html);
					checkItemId(_in);

					var appendBtn = _in.next(".js_swfimgupload_arr").find(".appendbtn");
					appendBtn.on("click", function(){
						if (max < _in.next(".js_swfimgupload_arr").find("div.item").length + 1){
							return false;
						}
						$.imgUploadPop({
							width: cutWidth,
							height: cutHeight,
	                        id: _in.attr("id"),
	                        root: root,
							complete: function(d){
								if (d.status == 0){
									var name = d.name || d.data.name;
									appendBtn.before("<div class='item' style='height:" + itemHeight + "px;' data-src='" + name + "'><a href='javascript:void(0)' class='delBtn' style='display: none;'>删除</a><img src='" + imgUploadFile + name + "' /></div>");
									checkItemId(_in);
								} else {
									$.tips.error(d.msg);
								}
							}
						});
					});
					_in.next(".js_swfimgupload_arr").dragsort({ dragSelector: "div.item", dragBetween: true, placeHolderTemplate: "<div class='item holder'></div>", dragEnd: function(){
						checkItemId(_in);
					}});
					_in.next(".js_swfimgupload_arr").on("hover", ".item", function(e){
						if (e.type == "mouseenter"){
							$(this).find(".delBtn").show();
						} else {
							$(this).find(".delBtn").hide();
						}
					}).on("click", ".item .delBtn", function(){
						var _th = $(this).parent(".item");
						$.alert({
							title: "温馨提示",
							txt: "确定要删除该图片吗？",
							btnN: "取消",
							btnY: "删除",
							btnYcss: "btnC",
							callbackY: function(){
								_th.remove();
								checkItemId(_in);
							}
						});
					});
				}
			});
		}
		function checkItemId(dom){
			var val = "", max = dom.attr("max") || 6, after = dom.next(".js_swfimgupload_arr");
			after.find(".item").each(function(i){
				var th = $(this);
				if (!th.hasClass("appendbtn")){
					th.attr("class", "item item" + i);
					val += th.data("src") + "|||";
				} else {
					th.attr("class", "item item" + i + " appendbtn");
				}
			});
			if (max < after.find("div.item").length + 1){
				after.find(".appendbtn").hide();
			} else {
				after.find(".appendbtn").css("display", "block");
			}
			val = val.replace(/\|\|\|$/gi, "");
			dom.val(val);
		}

		// 弹框式上传（可裁切）
		$.imgUploadPop = function(o){
			$.imgUploadPop.isReady = false;
			$.imgUploadPop.complete = o.complete;
			$.alert({
				title: "上传图片",
				txt: "<div class='swfupload'><div id='swfimgupload'></div></div>",
				init: function(){
				    var id = o.id || "default"; 
					swfobject.embedSWF(swfUrl + "/uploadPop.swf?v2.0.3", "swfimgupload", 500, 500, "11.9.0", "", {"id": id, "root": o.root, "imgServer": imgUploadServer, "width": o.width, "height": o.height});
				},
				btnY: "确定",
				btnN: "取消",
				css: "pop-alert-swf-upload",
				btnYClose: false,
				callbackY: function(){
					if (!$.imgUploadPop.isReady){
						$.alert.error("图片上传插件尚未加载，请刷新重试");
					} else {
						getSwfInstance("swfimgupload").upload();
					}
				}
			});
		}

		$._imgUpload_ = {
			error: function(type, id, isPop){
				// console.log("upload error:" + type + " / " + id + "/" + isPop);
				if (isPop){
					switch(type){
						case "imageIsTooSmall":
							$.alert.error("图片尺寸太小，请选择稍大的图片");
						break;
						case "imageTypeError":
							$.alert.error("图片格式错误");
						break;
						case "imageIsEmpty":
							$.alert.error("未上传任何图片");
						break;
						case "swfUnReady":
							$.alert.error("图片上传插件尚未加载，请刷新重试");
						break;
						case "uploadError":
							$.alert.error("上传错误");
						break;
					}
				} else {

				}
			},
			start: function(id, isPop){
				if (!isPop){
					var dom = $("#" + id);
					dom.imgUploadSetVal();
					dom.next(".js_swfimgupload").find(".p").html("上传中...");
				}
			},
			complete: function(data, id, isPop){
				if (!isPop){
					var dom = $("#" + id);
					dom.imgUploadSetVal($.parseJSON(data));
				} else {
					if ($.imgUploadPop.complete){
						$.imgUploadPop.complete($.parseJSON(data));
					}
					$("#pop-alert").remove();
				}
			},
			popReady: function(){
				$.imgUploadPop.isReady = true;
			}
		}
	})(jQuery);


});





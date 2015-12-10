define(function(require, exports, module){
	
	// 正确与错误提示
	(function($){
		$.tips = {
			error: function(t){
				clearTimeout($.tips.timeout);
				$("#_tips_").remove();
				$("body").append("<div id='_tips_' class='_tips_error'>" + t + "</div>");
				$.tips.timeout = setTimeout($.tips.remove, 2000);
			},
			success: function(t){
				clearTimeout($.tips.timeout);
				$("#_tips_").remove();
				$("body").append("<div id='_tips_' class='_tips_success'>" + t + "</div>");
				$.tips.timeout = setTimeout($.tips.remove, 2000);
			},
			remove: function(){
				$("#_tips_").remove();
			}
		}
	})(jQuery);

	// cookie提示
	;(function($){
		//写cookies 
		function setCookie(name,value){ 
		    var Days = 30; 
		    var exp = new Date(); 
		    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
		    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
		}

		//读取cookies 
		function getCookie(name){ 
		    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
		    if(arr=document.cookie.match(reg))
		        return unescape(arr[2]); 
		    else 
		        return null; 
		} 

		//删除cookies 
		function delCookie(name){ 
		    var exp = new Date(); 
		    exp.setTime(exp.getTime() - 1); 
		    var cval=getCookie(name); 
		    if(cval!=null) 
		        document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
		}

		$.cookieTips = {
			set: function(tips, r){
				setCookie("tips", tips);
				if (r != true){
					window.location.reload();
				}
			},
			check: function(){
				var tips = getCookie("tips");
				if (tips != undefined){
					$.tips.success(tips);
					delCookie("tips");
				}
			}
		}
	})(jQuery);

	// 弹出框
	(function($){
		var alertCB = {};
		$("body").on("click", "#pop-alert>.pop>.btn>a", function(){
			if (alertCB.cy != false){
				$("#pop-alert").removeClass("show");
			}
			var _t = $(this).data("t")
			if (_t == "n" && alertCB.n && typeof alertCB.n == "function"){
				var r = alertCB.n();
				if (r == false){
					return false;
				}
				$("#pop-alert").remove();
			} else if (_t == "y" && alertCB.y && typeof alertCB.y == "function"){
				var r = alertCB.y();
				if (r == false){
					return false;
				}
				if (alertCB.cy != false){
					$("#pop-alert").remove();
				}
			} else {
				$("#pop-alert").remove();
			}
			$.alert.ieBtn();
		});
		$.alert = function(o){
			if (typeof o == "string"){
				var txt = o;
				o = {};
				o.title = "提示";
				o.txt = txt;
				o.btnY = "确定";
				o.css = "";
				o.btnYcss = "";
			} else {
				o.title = o.title ? o.title : "提示";
				o.txt = o.txt ? o.txt : "提示内容为空";
				o.btnY = o.btnY ? o.btnY : "确定";
				o.css = o.css ? o.css : "";
				o.btnYcss = o.btnYcss ? o.btnYcss : "";
				alertCB.n = o.callbackN;
				alertCB.y = o.callbackY;
				alertCB.cy = o.btnYClose;
			}
			$("#pop-alert").remove();
			var _html = "<div id='pop-alert' class='" + o.css + "'><div class='pop'>";
			_html += "<div class='hd'>" + o.title + "</div>";
			_html += "<div class='bd'><p class='err'></p><div class='p'>" + o.txt + "</div></div>";
			if (o.btnN){
				_html += "<div class='btn btnNY'>";
				_html += "<a href='javascript:void(0)' class='btnD' data-t='n'>" + o.btnN + "</a>";
			} else {
				_html += "<div class='btn'>";
			}
			_html += "<a href='javascript:void(0)' class='btnA " + o.btnYcss + "' data-t='y'>" + o.btnY + "</a>";
			_html += "</div>";
			_html += "</div></div>";

			$("body").append(_html);
			if (o.init){
				o.init();
			}
			$.alert.ieBtn("hide");
		};
		$.alert.error = function(t){
			$("#pop-alert .pop .bd .err").show().html(t);
		}
		$.alert.removeError = function(){
			$("#pop-alert .pop .bd .err").hide().empty();
		}

		$.alert.ieBtn = function(type){
			// 如果ie下,隐藏上传flash的按钮,有bug
			if(navigator.appName == "Microsoft Internet Explorer"){
				if (type == "hide"){
					$(".js_swfimgupload .uploadbtn").hide();
				} else {
					$(".js_swfimgupload .uploadbtn").show();
				}
			}
		}
		$.alert.remove = function(){
			$("#pop-alert").remove();
		}
	})(jQuery);

	// 加载中
	(function($){
		$.loading = function(){
			$("#_loading_").remove();
			$("body").append("<div id='_loading_'></div>");
		}
		$.loading.remove = function(){
			$("#_loading_").remove();
		}
	})(jQuery);

	

});





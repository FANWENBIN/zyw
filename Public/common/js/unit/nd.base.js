define(function(require, exports, module){
	var $; if ($ || jQuery) { $ = jQuery ? jQuery : $;} else { return false;}
	require("unit/nd.base.css");


	// 返回是否为ios系统
	exports.isIOS = function(){
		return (/iphone|ipad/gi).test(navigator.appVersion);
	}

	// 返回是否为android系统
	exports.isAndroid = function(){
		return (/android/gi).test(navigator.appVersion);
	}

	// 验证是否为邮箱地址
	exports.isEmail = function(str){
		var reg = /^\w+([-+.]\w+)*\@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    	return reg.test(str);
	}

	// 验证是否为手机号码
	exports.isPhone = function(str, all){
		var reg;
		if (all){
			reg = /(^0{0,1}(13[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
		} else {
			reg = /^0{0,1}(13[0-9]|15[0-9]|17[0-9]|18[0-9])[0-9]{8}$/;
		}
   		return reg.test(str);
	}

	// 验证是否是身份证号码
	exports.isIDCard = function(str){
		 var reg = /^((1[1-5])|(2[1-3])|(3[1-7])|(4[1-6])|(5[0-4])|(6[1-5])|71|(8[12])|91)\d{4}((19\d{2}(0[13-9]|1[012])(0[1-9]|[12]\d|30))|(19\d{2}(0[13578]|1[02])31)|(19\d{2}02(0[1-9]|1\d|2[0-8]))|(19([13579][26]|[2468][048]|0[48])0229))\d{3}(\d|X|x)?$/;
   		 return reg.test(str);
	}

	// 既可以是电话也可以是手机号码
	exports.isTelPhone = function(str){
        var reg = /(^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/;
        return reg.test(str);
	}

	// 阻止系统冒泡
	module._preventDefault = function(e){
		e.preventDefault();
	}

	// 获取url参数
	exports.getQueryString = function(name, url){
		if (url) {
			url = url.split("?")[1];
		}
        var str = url || window.location.search.substr(1);
        if (str.indexOf(name) != -1) {
            var pos_start = str.indexOf(name) + name.length + 1;
            var pos_end = str.indexOf("&", pos_start);
            if (pos_end == -1) {
                return decodeURI(str.substring(pos_start));
            } else {
                return decodeURI(str.substring(pos_start, pos_end));
            }
        } else {
            return "";
        }
    }

    // 设置url参数，用historyAPI不跳转
    exports.setQueryString = function(name, val){
    	var search = "";
    	if (typeof name == "string" && val){
    		name = name.trim();
    		val = String(val).trim();
    		search = "?" + name + "=" + val;
    	} else if (typeof name == "object") {
    		for (var i in name){
    			search += String(i).trim() + "=" + String(name[i]).trim() + "&";
    		}
    		if (search != ""){
    			search = "?" + search.replace(/\&$/, "");
    		}
    	}
    	var url = window.location.origin + window.location.pathname;
    	url += search;
    	window.history.pushState(null, null, url);
    }

    // 获取cookie
	exports.getCookie = function(NameOfCookie) {
	    if (document.cookie.length > 0) { 
	        var begin = document.cookie.indexOf(NameOfCookie + "=");
	        if (begin != -1) {
	            begin += NameOfCookie.length + 1;//cookie值的初始位置 
	            var end = document.cookie.indexOf(";", begin);//结束位置 
	            if (end == -1) end = document.cookie.length;//没有;则end为字符串结束位置 
	            return unescape(document.cookie.substring(begin, end)); 
	        }
	    }
	    return null;
	}

	// 设置cookie
	exports.setCookie = function(name, value, expires, path, domain, secure){
		var today = new Date(); 
	    today.setTime(today.getTime()); 
	    if (expires) { 
	        expires = expires * 1000 * 60 * 60 * 24; 
	    } 
	    var expires_date = new Date(today.getTime() + (expires)); 
	    document.cookie = name + '=' + escape(value) + ((expires) ? ';expires=' + expires_date.toGMTString() : '') + ((path) ? ';path=' + path : '') + ((domain) ? ';domain=' + domain : '') + ((secure) ? ';secure' : ''); 
	}

	// 删除cookie
	exports.deleteCookie = function(name, path, domain) { 
	    if (exports.getCookie(name)){
	    	document.cookie = name + '=' + ((path) ? ';path=' + path : '') + ((domain) ? ';domain=' + domain : '') + ';expires=Thu, 01-Jan-1970 00:00:01 GMT';
	    }
	}

	// 获取localstorage
	exports.getStorage = function(name){
		return localStorage.getItem(name);
	}

	// 设置localstorage
	exports.setStorage = function(name, value){
		localStorage.setItem(name, value);
	}

	// 删除localstorage
	exports.deleteStorage = function(name){
		localStorage.removeItem(name);
	}

	// 删除所有localstorage
	exports.deleteAllStorage = function(){
		localStorage.clear();
	}

	// 阻止系统的冒泡事件
	exports.preventDefault = function(type){
		$(document).on(type, module._preventDefault);
	}

	// 恢复系统的冒泡事件
	exports.resetDefault = function(type){
		$(document).off(type, module._preventDefault);
	}

	// 字符串去头尾空格,继承在String下
	String.prototype.trim = function(){
		return this.replace(/(^\s*)|(\s*$)/g,'');
	}

	// 获取字符串的真实长度，即中文为2个，英文为1个的形式,继承在String下
	String.prototype.byteLength = function(){
		var bytelen = 0, len = this.length, charCode = -1;
	    for (var i = 0; i < len; i++) {
	        charCode = this.charCodeAt(i);
	        if (charCode >= 0 && charCode <= 128) bytelen += 1;
	        else bytelen += 2;
	    }
	    return bytelen;
	}

	// 随机的函数整数, 不包含最大值
	exports.random = function(max, min, isFloat){
		if (min === true) {
			min = 0;
			isFloat = true;
		} else if (!min){
			min = 0;
		}
		if (isFloat) {
			return Math.random() * (max - min) + min;
		}
		return Math.floor(Math.random() * (max - min)) + min;
	}

	// 转换为金额格式的数字
	exports.fmoney = function(s, n){
		if (n == undefined) n = 2;
		n = n >= 0 && n <= 20 ? n : 2;
		s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
		var l = s.split(".")[0].split("").reverse(),
		r = s.split(".")[1];
		t = "";
		for(i = 0; i < l.length; i ++){
			t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
		}
		if (n > 0) {
			return t.split("").reverse().join("") + "." + r;
		} else {
			return t.split("").reverse().join("");
		}
	}

	// alert框显示
	exports.alert = {
		show: function(o){
			var t = 0, popup = $("#_z_alert_");
			if (popup.length){
				popup.addClass("out");
				t = 200;
			}
			if (t == 0){
				module._alert.show(o);
			} else {
				setTimeout(function(){
					popup.remove();
					module._alert.show(o);
				}, t);
			}
		},
		hide: function(cb){
			var popup = $("#_z_alert_");
			popup.addClass("out").find(".popup .btns .y, .popup .btns .n").off();
			if (typeof cb == "function"){
				cb();
			}
			setTimeout(function(){
				popup.off().remove();
			},200);
			delete module._alert._o;
		}
	};

	module._alert = {
		show: function(o){
			$("input:focus").blur();
			if (o != undefined && typeof o != "object"){
				o = {txt: o};
			}
			module._alert._o = o;
			o.class = o.class ? o.class : "";
			o.title = o.title ? o.title : "提示";
			o.txt = o.txt || o.text;
			var html = "<div id='_z_alert_' class='" + o.class + "'><div class='popup'>";
			html += "<h1>" + o.title + "</h1><a href='javascript:viod(0)' class='close'></a><div class='p'><p>" + o.txt + "</p></div>";
			html += "<div class='btns'>";
			if (o.btnN){
				html += "<a href='javascript:void(0)' class='alertbtn n'>" + o.btnN + "</a>";
			}
			o.btnY = o.btnY ? o.btnY : o.btn ? o.btn : "确定";
			html += "<a href='javascript:void(0)' class='alertbtn y'>" + o.btnY + "</a></div></div>";
			$("body").append(html);
			var popup = $("#_z_alert_");
			setTimeout(function(){
				popup.addClass("show");
			},0);
			$("#_z_alert_").on("touchstart", module._preventDefault);
			popup.find(".popup>.btns .y, .popup>.btns .n, .popup>.close").on("touchstart", module._alert.touchstart).on("touchmove", module._alert.touchmove).on("touchend", module._alert.click);
		},
		touchstart: function(e){
			module._alert.mouseX = e.originalEvent.changedTouches[0].pageX;
			module._alert.mouseY = e.originalEvent.changedTouches[0].pageY;
			module._alert.mouseMove = false;
			$(this).attr("hover","true");
		},
		touchmove: function(e){
			var x = e.originalEvent.changedTouches[0].pageX;
			var y = e.originalEvent.changedTouches[0].pageY;
			if ((Math.abs(x - module._alert.mouseX) > 10 || Math.abs(y - module._alert.mouseY) > 10) && !module._alert.mouseMove){
				module._alert.mouseMove = true;
				$(this).removeAttr("hover");
			}
		},
		click: function(){
			$(this).removeAttr("hover");
			if (!module._alert.mouseMove){
				var _th = $(this);
				if (_th.hasClass("y")){
					exports.alert.hide(module._alert._o.callbackY || module._alert._o.callback);
				} else {
					exports.alert.hide(module._alert._o.callbackN);
				}
			}
			return false;
		}
	};
	
	// loading框
	exports.loading = {
		show: function(){
			$("input:focus").blur();
			if ($("#_z_loading_").length){
				$("#_z_loading_").remove();
			}
			var html = "<div id='_z_loading_'><strong><span></span><span></span></strong></div>";
			$("body").append(html);
			$("#_z_loading_").on("touchstart", module._preventDefault);
		},
		hide: function(){
			$("#_z_loading_").off("touchstart", module._preventDefault).remove();
		}
	};

	// 微信分享提示框
	module._sharePopuphasShow = false;
	exports.sharePopup = {
		show: function(){
			if (!module._sharePopuphasShow){
				$("input:focus").blur();
				if ($("#_z_sharePopup_").length){
					$("#_z_sharePopup_").remove();
				}
				module._sharePopuphasShow = true;
				var html = "<div id='_z_sharePopup_'><span></span></div>";
				$("body").append(html);
				setTimeout(function(){
					$("#_z_sharePopup_").on("touchstart", module._preventDefault).addClass("show").on("touchend", exports.sharePopup.hide);
				},0);
			}
		},
		hide: function(){
			$("#_z_sharePopup_").removeClass("show");
			setTimeout(function(){
				$("#_z_sharePopup_").off().remove();
				module._sharePopuphasShow = false;
			},350);
		}
	}

	// ajax请求
	exports.ajax = function(o){
		if (typeof o == "string"){
			var url = o;
			o = {};
			o.url = url;
			o.loading = false;
		}
		if (o.loading == undefined) {
			o.loading = true;
		}
		if (o.loading){
			exports.loading.show();
		}
		$.ajax({
	        type: o.type || "POST",
	        url: o.url,
	        data: o.data || {},
	        dataType: o.dataType || "json",
	        success: function(d){
	        	if (o.loading){
					exports.loading.hide();
				}
	        	if (o.success){
	        		o.success(d);
	        	}
			},
			error: function(e){
				if (o.loading){
					exports.loading.hide();
				}
				if (o.error){
					o.error(e);
				}
			}
		});
	}


	// 瀑布流算法
	module._waterfall = {
		maxArr:function(arr){
	        var len = arr.length,temp = arr[0];
	        for(var ii= 1; ii < len; ii++){
	            if(temp < arr[ii]){
	                temp = arr[ii];
	            }
	        }
	        return temp;
	    },
		getMinCol:function(arr){
			var ca = arr,cl = ca.length,temp = ca[0],minc = 0;
			for(var ci = 0; ci < cl; ci++){
				if(temp > ca[ci]){
					temp = ca[ci];
					minc = ci;
				}
			}
			return minc;
		},
		init: function(elem, sel, count, width, margin){
			elem = $(elem);
			margin = margin ? margin : 0;
	        var _this = elem;
	        var col = [], iArr = [];
	        var nodes = elem.find(sel).css("position", "absolute"), len = nodes.length;
	        for(var i = 0; i < count; i++){
	            col[i] = 0;
	        }
	        for(var i = 0; i < len; i++){
	            nodes[i].h = nodes[i].offsetHeight;
	            iArr[i] = i;
	        }
			for(var i = 0; i < len; i++){
				var ming = module._waterfall.getMinCol(col);
				nodes[i].style.left = (width + margin) * ming + margin + "px";
				nodes[i].style.top = col[ming] + margin + "px";
				$(nodes[i]).data("list", ming);
				col[ming] += nodes[i].h + margin;
			}
			elem.height(module._waterfall.maxArr(col) + margin);
	    }
    }
    exports.waterfall = module._waterfall.init;

    // 图片批量预加载
    module._preloader = {
    	init: function(o){
    		if (o.basePath == undefined){
    			o.basePath = "";
    		}
    		if (o.fileList != undefined && o.fileList.length){
    			module._preloader.queueLoad(o, 0);
	    		if (o.progress != undefined && typeof o.progress == "function"){
    				o.progress({
    					completed: 0,
    					total: o.fileList.length
    				});
    			}
    		}
    	},
    	queueLoad: function(obj, count){
    		var img = new Image();
    		img.src = obj.basePath + obj.fileList[count];
    		img.onload = function(){
    			count += 1;
    			if (count < obj.fileList.length){
    				module._preloader.queueLoad(obj, count);
    				if (obj.progress != undefined && typeof obj.progress == "function"){
	    				obj.progress({
	    					completed: count,
	    					total: obj.fileList.length
	    				});
	    			}
    			} else if (obj.complete != undefined && typeof obj.complete == "function"){
    				obj.progress({
    					completed: count,
    					total: obj.fileList.length
    				});
    				obj.complete();
    			}
    		};
    		img.onerror = function(){
    			if (obj.error != undefined && typeof obj.error == "function"){
    				obj.error({
    					path: obj.basePath + obj.fileList[count],
    					count: count
    				});
    			}
    		};
    	}
    }
    exports.preloader = module._preloader.init;

    



});
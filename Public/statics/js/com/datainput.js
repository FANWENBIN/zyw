define(function(require, exports, module){
	
	// 自定义日期选择框
	;(function($){
		function hideOptions(e){
			if($(document).data("nowselectoptions")){
				if (e && $(e.target).parents(".js_dateinput").find(".pop").get(0) == $($(document).data("nowselectoptions")).get(0)) {
					if (!$(e.target).hasClass("j_cl") || $(e.target).hasClass("n")){
						return false;
					}
				}
				$($(document).data("nowselectoptions")).hide().parent(".js_dateinput, .js_select").removeClass("js_dateinputOpen js_selectOpen");
				$(document).data("nowselectoptions", null);
				$(document).unbind("click", hideOptions);
			}
		}
		function showOptions(){
			$(document).bind("click", hideOptions);
			$($(document).data("nowselectoptions")).show().parent(".js_dateinput").addClass("js_dateinputOpen").find(".pop .bd .bdtime").hide();
		}
		$.fn.dateInputInit = function(){
			$(this).each(function(){
				var _in = $(this);
				if (_in.attr("type") == "text" && _in.data("init") != "true"){
					_in.data("init", "true");
					var defaultDate = _in.val(), defaultTime = "0:0", obj = {};
					if (defaultDate && defaultDate.split("-").length == 3) {
						if (defaultDate.indexOf("|") != -1){
							defaultTime = defaultDate.split("|")[1];
							defaultDate = defaultDate.split("|")[0];
						}
						defaultDate = defaultDate.split("-");
						obj = {Yr: defaultDate[0], Mn: defaultDate[1], Dd: defaultDate[2]};
					}
					var html = "";
					if (_in.data("time") == true || _in.data("time") == "true"){
						html = "<div class='js_dateinput' data-time='true' onselectstart='javascript:return false;'>";
					} else {
						html = "<div class='js_dateinput' onselectstart='javascript:return false;'>";
					}
					html += "<div class='current j_cl'><p class='j_cl'></p><span class='selector j_cl'></span></div>";
					html += "<div class='pop'><div class='hd'>";
					html += "<div class='yy'><a href='javascript:void(0)' class='prev prevY'></a><strong class='y'></strong><span>年</span><a href='javascript:void(0)' class='next nextY'></a></div>";
					html += "<div class='mm'><a href='javascript:void(0)' class='prev prevM'></a><strong class='m'></strong><span>月</span><a href='javascript:void(0)' class='next nextM'></a></div>";
					html += "</div><div class='bd'>";
					html += "<ul class='wk'><li>日</li><li>一</li><li>二</li><li>三</li><li>四</li><li>五</li><li>六</li></ul>";
					html += "<div class='day'>";
					for (var i = 0; i < 6; i++){
			        	html += "<div><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a><a href='javascript:void(0)' class='n'></a></div>";
					}
					html += "</div>";
					if (_in.data("time") == true || _in.data("time") == "true"){
						defaultTime = defaultTime.split(":");
						obj.th = defaultTime[0];
						obj.tm = defaultTime[1];

						html += "<div class='time clearFix'><a href='javascript:void(0)' class='h'>" + defaultTime[0] + "</a><strong class='hh'>时</strong><a href='javascript:void(0)' class='m'>" + defaultTime[1] + "</a><strong class='mm'>分</strong></div>";
						html += "<div class='bdtime bdtime1'>";
						for (var j = 0; j < 24; j++){
							if (j % 4 == 0){ html += "<div class='clearFix'>";}
							var c = j == obj.th ? "c" : "";
							if (j % 4 != 3){
								html += "<a class='h " + c + "' href='javascript:void(0)' data-t='" + j + "'>" + j + "</a>";
							} else {
								html += "<a class='last h " + c + "' href='javascript:void(0)' data-t='" + j + "'>" + j + "</a></div>";
							}
						}
						html += "</div>";
						html += "<div class='bdtime bdtime2'>";
						for (var j = 0; j < 12; j++){
							if (j % 4 == 0){ html += "<div class='clearFix'>";}
							var c = (j * 5) == obj.tm ? "c" : "";
							if (j % 4 != 3){
								html += "<a class='m " + c + "' href='javascript:void(0)' data-t='" + (j * 5) + "'>" + (j * 5) + "</a>";
							} else {
								html += "<a class='last m " + c + "' href='javascript:void(0)' data-t='" + (j * 5) + "'>" + (j * 5) + "</a></div>";
							}
						}
						html += "</div>";
					}
					html += "</div></div></div>";
					_in.hide().after(html);

					var divDateInput = _in.next(".js_dateinput");
					divDateInput.find(".pop .hd a").on("click", YYMMSlide);
					divDateInput.find(".pop .bd .day a").on("click", DDClick);
					if (_in.data("time") == true || _in.data("time") == "true"){
						divDateInput.find(".pop .bd .time a").on("click", TimePopClick);
						divDateInput.find(".pop .bd .bdtime a").on("click", TimeClick);
					}

					divDateInput.on("click", function(e){
						if (!$(this).hasClass("js_dateinputDisabled")){
							var option = $(this).find(".pop");
							if($($(document).data("nowselectoptions")).get(0) != option.get(0)){
								hideOptions();
							}
							if(!option.is(":visible")){
								e.stopPropagation();
								$(document).data("nowselectoptions", option);
								showOptions();
							}
						}
					}).hover(function(e){
						if (e.type == "mouseenter"){
							$(this).addClass("js_dateinputHover");
						} else {
							$(this).removeClass("js_dateinputHover");
						}
					});

					YYMMinit(divDateInput, obj, true);
				}
			});
		};

		function YYMMSlide(){
			var _this = $(this), _parent = _this.parents(".js_dateinput");
			var y = _parent.data("year"), m = _parent.data("month"), d = _parent.data("dd");

			if (_this.hasClass("prevY") || _this.hasClass("nextY")){
				if (y > 1900 && _this.hasClass("prevY")) {
					y = parseInt(y) - 1;
				} else if (y < 2100 && _this.hasClass("nextY")) {
					y = parseInt(y) + 1;
				}
				_parent.data("year", y);
			} else if (_this.hasClass("prevM") || _this.hasClass("nextM")){
				if (_this.hasClass("prevM")){
					if (m > 1){
						m = parseInt(m) - 1;
					} else if (y > 1900){
						m = 12;
						y = parseInt(y) - 1;
					}
				} else {
					if (m < 12){
						m = parseInt(m) + 1;
					} else if (y < 2100){
						m = 1;
						y = parseInt(y) + 1;
					}
				}
				_parent.data("month", m);
				_parent.data("year", y);
			}

	        // 闰年的2月为29天，翻页时，如果当前月份的当前日期为30、31号，到下个月时如果没有31，自动变成30，如有，则继续为31
	        // 闰年规则：4年一次，但不被100除尽，但被400除尽却可以
	        var dayArr = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	        if (y % 4 == 0 && (y % 100 != 0 || y % 400 == 0)){
	        	dayArr[1] = 29;
	        }
	        d = Math.min(dayArr[m - 1], d);
			YYMMinit(_parent, {Yr: y, Mn: m, Dd: d});
			return false;
		}
		function DDClick(){
			if (!$(this).hasClass("n")){
				var _this = $(this), _parent = _this.parents(".js_dateinput");
				var y = _parent.data("year"), m = _parent.data("month"), d = _this.html();
				if (_parent.data("time") == "true" || _parent.data("time") == true){
					var th = _parent.find(".pop .bd .time a.h").html();
					var tm = _parent.find(".pop .bd .time a.m").html();
					YYMMinit(_parent, {Yr: y, Mn: m, Dd: d, th: th, tm: tm}, true);
				} else {
					YYMMinit(_parent, {Yr: y, Mn: m, Dd: d}, true);
				}
			}
		}
		function YYMMinit(elem, o, save) {
	        var now = new Date();
	        var year = o.Yr || now.getFullYear();
	        var month = now.getMonth();
	        var hasTime = o.th != undefined && o.tm != undefined;

	        if (o.Mn != null){
	        	month = o.Mn - 1;
	        }
	        var dd = o.Dd || now.getDate();

	        elem.find(".hd .yy .y").html(year);
	        elem.find(".hd .mm .m").html(o.Mn || now.getMonth() + 1);

	        if (save){
		        elem.data("year", year);
		        elem.data("month", month + 1);
		        elem.data("dd", dd);
		        if (!hasTime){
		        	elem.find(".current p").html(year + "年" + (month + 1) + "月" + dd + "日");
		        	elem.prev("input.js_date").val(year + "-" + (month + 1) + "-" + dd);
		        } else {
		        	elem.find(".current p").html(year + "年" + (month + 1) + "月" + dd + "日 " + o.th + "时 " + o.tm + "分");
		        	elem.prev("input.js_date").val(year + "-" + (month + 1) + "-" + dd + "|" + o.th + ":" + o.tm);
		        }
	        }

	        DDinit(elem, year, month, dd);
	    }
	    function DDinit(elem, Yr, Mn, Dd) {
	        // 根据传入的数值生成新的日期
	        var newDd = new Date();
	        Dd = Dd ? Dd : newDd.getDate();
	        newDd.setFullYear(Yr, Mn, Dd);
	        var year = newDd.getFullYear(), month = newDd.getMonth(), dd = newDd.getDate();
	        var firstD = new Date();
	        firstD.setFullYear(year, month, 1);
	        var firstDay = firstD.getDay();

	        // 判断每个月有多少天
	        var dayArr = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	        // 闰年的2月为29天
	        if (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)){
	        	dayArr[1] = 29;
	        }
	        var dayLen = dayArr[month];
	        var prevDayLen = month != 0 ? dayArr[month - 1] : dayArr[11];

	        // 填充日期
	        var dayElem = elem.find(".pop .bd .day a");
	        for (var j = 0; j < dayElem.length; j++) {
	        	var _this = $(dayElem[j]).attr("class", "n j_cl");
	        	if (j >= firstDay && j < dayLen + firstDay){
	        		_this.removeClass("n").html(j + 1 - firstDay);
	        		if (j + 1 - firstDay == dd){
	        			_this.addClass("c");
	        		}
	        	} else if (j >= dayLen + firstDay){
	        		// 下个月的日期
	        		_this.html(j + 1 - dayLen - firstDay);
	        	} else {
	        		// 上个月的日期
	        		_this.html(prevDayLen - firstDay + j + 1);
	        	}
	        }
	    }
	    function TimePopClick(e){
	    	var _this = $(this), pop = _this.parents(".pop .bd");
	    	pop.find(".bdtime").hide();
	    	if (_this.hasClass("h")){
	    		if (_this.hasClass("c")){
	    			_this.removeClass("c");
	    		} else {
	    			pop.find(".time a.c").removeClass("c");
	    			_this.addClass("c");
	    			pop.find(".bdtime1").show();
	    		}
	    	} else if (_this.hasClass("m")) {
	    		if (_this.hasClass("c")){
	    			_this.removeClass("c");
	    		} else {
	    			pop.find(".time a.c").removeClass("c");
	    			_this.addClass("c");
	    			pop.find(".bdtime2").show();
	    		}
	    	}
	    }
	    function TimeClick(e){
	    	var _this = $(this), _parent = _this.parents(".js_dateinput"), _html = "", th, tm;
			var y = _parent.data("year"), m = _parent.data("month"), d = _parent.data("dd");
			if (_this.hasClass("h")){
				_parent.find(".pop .bd .bdtime1").hide().find("a.c").removeClass("c");
				_html = _this.addClass("c").html();
				th = _parent.find(".pop .bd .time a.h").removeClass("c").html(_html).html();
				tm = _parent.find(".pop .bd .time a.m").html();
			} else {
				_parent.find(".pop .bd .bdtime2").hide().find("a.c").removeClass("c");
				_html = _this.addClass("c").html();
				th = _parent.find(".pop .bd .time a.h").html();
				tm = _parent.find(".pop .bd .time a.m").removeClass("c").html(_html).html();
			}
			YYMMinit(_parent, {Yr: y, Mn: m, Dd: d, th: th, tm: tm}, true);
	    }
	})(jQuery);

});





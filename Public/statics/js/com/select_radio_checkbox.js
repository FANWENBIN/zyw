define(function(require, exports, module){
	
	
	// 自定义select框
	;(function($){
		function hideOptions(){
			if($(document).data("nowselectoptions")){
				$($(document).data("nowselectoptions")).hide().parent(".js_dateinput, .js_select").removeClass("js_dateinputOpen js_selectOpen");
				$(document).data("nowselectoptions", null);
				$(document).unbind("click", hideOptions);
			}
		}
		function showOptions(){
			$(document).bind("click", hideOptions);
			$($(document).data("nowselectoptions")).show().parent(".js_select").addClass("js_selectOpen");
		}
		$.fn.selectInit = function(){
			$(this).each(function(){
				var select = $(this);
				if (!select.data("init")){
					var parent = select.parent(), w = 200;
					if (parent.width() < 200){
						w = parent.width();
					}
					select.data("init", "true");
					var disabled = select.attr("disabled") ? "js_selectDisabled" : "";
					var css = select.data("css") ? select.data("css") : "";
					var html = "<div style='width:" + w + "px;' class='js_select " + disabled + " " + css + "' onselectstart='javascript:return false;'>";
					html += "<div class='current'><p>" + select.find("option:eq(" + select[0].selectedIndex + ")").html() + "</p><span class='selector'></span></div>";
					if (select.find("option").length > 5) {
						html += "<ul class='options overflow' style='display:none;width:" + (w - 2) + "px;'>";
					} else {
						html += "<ul class='options' style='display:none;width:" + (w - 2) + "px;'>";
					}
					select.find("option").each(function(i){
						if($(this)[0].selected){
							html += "<li class='option" + i + " selected'><a href='javascript:void(0)'>" + $(this).text() + "</a></li>";
						} else if ($(this)[0].disabled) {
							html += "<li class='option" + i + " disabled'><a href='javascript:void(0)'>" + $(this).text() + "</a></li>";
						} else {
							html += "<li class='option" + i + "'><a href='javascript:void(0)'>" + $(this).text() + "</a></li>";
						}
					});
					html += "</ul></div>";
					select.hide().after(html);

					var divselect = select.next(".js_select");
					divselect.on("click", function(e){
						if (!$(this).hasClass("js_selectDisabled")){
							var option = $(this).find(".options");
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
							$(this).addClass("js_selectHover");
						} else {
							$(this).removeClass("js_selectHover");
						}
					}).find(".options li:not(.disabled)").on("click", function(){
						select.find("option:eq(" + $(this).index() + ")")[0].selected = true;
						select.find("option:eq(" + $(this).index() + ")").trigger("change", true);
					});
					select.change(function(){
						var divselect = select.next(".js_select");
						divselect.find(".options li:eq(" + $(this)[0].selectedIndex + ")").addClass("selected").siblings().removeClass("selected");
						divselect.find(".current p").html($(this).children("option:eq(" + $(this)[0].selectedIndex + ")").text());
					});
				}
			});
	        return this;
		}
	})(jQuery);


	// 自定义radio和checkbox
	;(function($){
		$.fn.inputInit = function(){
			$(this).each(function(){
				var _in = $(this);
				if (_in.attr("type") == "radio" && _in.data("init") != "true"){
					var _t = _in.data("val") ? _in.data("val") : "";
					var _n = _in.attr("name");
					var _css = _in.data("css") ? _in.data("css") : "";
					var _checked = _in.attr("checked") ? "js_radioChecked" : "";
					_in.hide().data("init", "true").after("<a href='javascript:void(0)' class='js_radio " + _checked + " " + _css + "' data-n='" + _n + "'><span></span><p>" + _t + "</p></a>");
					_in.change(function(){
						var _n = _in.attr("name");
						$("body").find(".js_radio").each(function(){
							if ($(this).data("n") == _n){
								$(this).removeClass("js_radioChecked");
							}
						});
						$(this).next(".js_radio").addClass("js_radioChecked");
					}).next(".js_radio").on("click", function(){
						var _this = $(this);
						var _n = _this.data("n");
						$("body").find("input").each(function(){
							var _in = $(this);
							if (_in.attr("type") == "radio" && _in.data("init") == "true" && _in.attr("name") == _n){
								_in[0].checked = false;
							}
						});
						$("body").find(".js_radioChecked").each(function(){
							var _this = $(this);
							if (_this.data("n") == _n){
								_this.removeClass("js_radioChecked");
							}
						});
						_this.addClass("js_radioChecked").prev("input")[0].checked = true;
					});
				} else if (_in.attr("type") == "checkbox" && _in.data("init") != "true"){
					var _t = _in.data("val") ? _in.data("val") : "";
					var _css = _in.data("css") ? _in.data("css") : "";
					var _checked = _in.attr("checked") ? "js_checkboxChecked" : "";
					_in.hide().data("init", "true").after("<a href='javascript:void(0)' class='js_checkbox " + _checked + " " + _css + "'><span></span><p>" + _t + "</p></a>");
					_in.change(function(){
						if ($(this)[0].checked) {
							$(this).next(".js_checkbox").addClass("js_checkboxChecked");
						} else {
							$(this).next(".js_checkbox").removeClass("js_checkboxChecked");
						}
					}).next(".js_checkbox").on("click", function(){
						$(this).toggleClass("js_checkboxChecked");
						var _checked = $(this).hasClass("js_checkboxChecked");
						$(this).prev("input")[0].checked = _checked;
					});
				}
			});
		}
	})(jQuery);

});





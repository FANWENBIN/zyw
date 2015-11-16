define(function(require, exports, module){
	var $; if ($ || jQuery) { $ = jQuery ? jQuery : $;} else { return false;}

	return (function($){

		// $.fn.hover();
		// $.fn.removeHover();
		// $.fn.touchend();
		// $.fn.offTouchend();
		// $.touchmoved();
		
		// 为touchStart和touchEnd添加和删除hover效果
		// 并做延时处理，防止用户在拖拽的时候会也响应hover效果
		// 也就是说，用户只有在按住不动的10毫秒后响应
		var touchHover = {
			start: function(e){
				var _this = $(this);
				var _t = setTimeout(function(){
					_this.attr("hover", "on");
				}, 10);
				_this.attr("hoverTimeout", _t);
			},
			move: function(e) {
				var _this = $(this);
				clearTimeout(_this.attr("hoverTimeout"));
				_this.removeAttr("hoverTimeout");
				_this.removeAttr("hover");
			},
			end: function(e){
				var _this = $(this);
				clearTimeout(_this.attr("hoverTimeout"));
				_this.removeAttr("hoverTimeout");
				_this.removeAttr("hover");
			}
		};
		$.fn.hover = function(el) {
			if (el){
				this.on("touchstart", el, touchHover.start);
				this.on("touchmove", el, touchHover.move);
				this.on("touchend", el, touchHover.end);
			} else {
				this.on("touchstart", touchHover.start);
				this.on("touchmove", touchHover.move);
				this.on("touchend", touchHover.end);
			}
			return this;
		};
		$.fn.removeHover = function(el) {
			if (el){
				this.off("touchstart", el, touchHover.start);
				this.off("touchmove", el, touchHover.move);
				this.off("touchend", el, touchHover.end);
			} else {
				this.off("touchstart", touchHover.start);
				this.off("touchmove", touchHover.move);
				this.off("touchend", touchHover.end);
			}
			return this;
		};

		// 当touchstart之后手指有移动后，$.touchMoved()为false，如果没有移动，为true
		var touchmove = {
			hasMove: false,
			on: function(){
				$(document).on("touchstart touchmove", touchmove.move);
			},
			off: function(){
				touchmove.hasMove = false;
				$(document).off("touchstart touchmove", touchmove.move);
			},
			move: function(e){
				if (e.type == "touchstart") {
					touchmove._x = e.originalEvent.changedTouches[0].pageX;
					touchmove._y = e.originalEvent.changedTouches[0].pageY;
					touchmove.hasMove = false;
				} else if (e.type == "touchmove") {
					var _x = e.originalEvent.changedTouches[0].pageX;
					var _y = e.originalEvent.changedTouches[0].pageY;
					if (Math.abs(_x - touchmove._x) > 10 || Math.abs(_y - touchmove._y) > 10){
						touchmove.hasMove = true;
					}
				}
			}
		};
		touchmove.on();

		// touchend事件
		$.fn.touchend = function(A, B, C){
			if (A && typeof A == "function"){
				$(this).hover().on("touchend", function(){
					var _this = $(this);
					if (!$.touchmoved() || B){
						A.apply(_this);
					}
					return false;
				});
			} else if (A && typeof A == "string" && B && typeof B == "function"){
				$(this).hover(A).on("touchend", A, function(){
					var _this = $(this);
					if (!$.touchmoved() || C){
						B.apply(_this);
					}
					return false;
				});
			}
		};

		// 卸载touchend事件
		$.fn.offTouchend = function(el){
			if (el){
				$(this).removeHover(el).off("touchend");
			} else {
				$(this).removeHover().off("touchend");
			}
		}

		// 当touchstart之后手指有移动后，为false，如果没有移动，为true
		$.touchmoved = function(){
			return touchmove.hasMove;
		};
	})(jQuery);
});
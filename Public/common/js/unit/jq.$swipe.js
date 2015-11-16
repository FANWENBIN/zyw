define(function(require, exports, module){
	var $; if ($ || jQuery) { $ = jQuery ? jQuery : $;} else { return false;}

	return (function($){

		// $.fn.swipe();
		// $.fn.offSwipe();



		// 滑动事件
		$.fn.swipe = function(callback, moving){
			if (moving){
				$(this).on("touchstart touchmove touchend", onFnSwipe)[0]._swipeCB = callback;
			} else {
				$(this).on("touchstart touchend", onFnSwipe)[0]._swipeCB = callback;
			}
			$(this)[0]._moving = moving || false;
		};
		$.fn.offSwipe = function(){
			$(this).off("touchstart touchmove touchend", onFnSwipe)[0]._swipeCB = undefined;
			$(this)[0]._moving = undefined;
		};

		function onFnSwipe(e){
			var _this = $(this);
			var _x = e.originalEvent.changedTouches[0].pageX;
			var _y = e.originalEvent.changedTouches[0].pageY;
			var _moving = _this[0]._moving;

			if (e.type == "touchstart"){
				_this[0]._x = _x;
				_this[0]._y = _y;
			} else if (e.type == "touchend" && _this[0]._swipeCB) {
				var _ost = Math.abs((_x - _this[0]._x) / (_y - _this[0]._y));
				if (_ost < 1){
					if (_this[0]._y - _y < -20){
						_this[0]._swipeCB("down", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					} else if (_this[0]._y - _y > 20){
						_this[0]._swipeCB("up", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					} else {
						_this[0]._swipeCB("none", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					}
				} else if (_ost > 3){
					if (_this[0]._x - _x < -20){
						_this[0]._swipeCB("right", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					} else if (_this[0]._x - _x > 20){
						_this[0]._swipeCB("left", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					} else {
						_this[0]._swipeCB("none", {x: _x - _this[0]._x, y: _y - _this[0]._y});
					}
				} else {
					_this[0]._swipeCB("none", {x: _x - _this[0]._x, y: _y - _this[0]._y});
				}
			} else if (_moving && e.type == "touchmove"){
				_this[0]._swipeCB("moving", {x: _x - _this[0]._x, y: _y - _this[0]._y});
			}
		}

	})(jQuery);
});
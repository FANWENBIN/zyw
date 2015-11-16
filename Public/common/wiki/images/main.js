define(function(require, exports, module){
    var $ = require("jquery-2.1.3.sea");
	var is = require("iscroll.sea");

	var nb = require("unit/nd.base");

	$(function(){


		function matrix(a){
			console.log(a)
		}

		var _tween = {
			setStyle: function(el, r){
				el = $(el);
				var defmtx, mtx = window.getComputedStyle(el[0], null).transform;
				if ((/^matrix\(/).test(mtx)){
					mtx = mtx.replace(/^matrix\(/, "").replace(/\)$/, "").replace(/\s/g, "").split(",");
					// console.log(parseFloat(mtx[0]) + 1)

					r = Math.cos(r * Math.PI / 180);
					el.css("-webkit-transform", "matrix(" + mtx + ")");
					console.log(mtx, r);
				}
			}
		}


		_tween.setStyle("div#a", 45);

    });
});
define(function(require, exports, module){
	
	// 瀑布流算法
	(function($){
		$.waterfall = {
			maxArr:function(arr){
		        var len = arr.length,temp = arr[0];
		        for(var ii= 1; ii < len; ii++){
		            if(temp < arr[ii]){
		                temp = arr[ii];
		            }
		        }
		        return temp;
		    },
		    getMar:function(node){
		        var dis = 0;
		        if(node.currentStyle){
		            dis = parseInt(node.currentStyle.marginBottom);
		        }else if(document.defaultView){
		            dis = parseInt(document.defaultView.getComputedStyle(node,null).marginBottom);
		        }
		        return dis;
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
			init: function(elem, subCss, count, width, margin){
				margin = margin ? margin : 0;
		        var _this = elem;
		        var col = [], iArr = [];
		        var nodes = elem.find("." + subCss), len = nodes.length;
		        for(var i = 0; i < count; i++){
		            col[i] = 0;
		        }
		        for(var i = 0; i < len; i++){
		            nodes[i].h = nodes[i].offsetHeight + $.waterfall.getMar(nodes[i]);
		            iArr[i] = i;
		        }
				for(var i = 0; i < len; i++){
					var ming = $.waterfall.getMinCol(col);
					nodes[i].style.left = (width + margin) * ming + margin + "px";
					nodes[i].style.top = col[ming] + margin + "px";
					$(nodes[i]).attr("count", ming);
					col[ming] += nodes[i].h + margin;
				}
				elem.height($.waterfall.maxArr(col) + margin);
		    }
	    }
	})(jQuery);


});





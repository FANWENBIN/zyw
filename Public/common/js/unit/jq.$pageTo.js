define(function(require, exports, module){
	var $; if ($ || jQuery) { $ = jQuery ? jQuery : $;} else { return false;}
	require("unit/jq.$pageTo.css");

	return (function($){

		// $.fn.pageTo();
		// $.pageToing();

		// 页面转场动画中的一些参数
		var pageToVars = {
			pageToing: false,
			current: undefined,
			target: undefined,
			type: undefined,
			callback: undefined,
			hover: undefined,
			ab: 1,
			back: undefined,
			afinish: false,
			bfinish: false,
			timeout: 0,
			runTime: 0.4,
			kf: undefined,
			ease: "ease-out",
			// 根据data-p属性判断两个页面的关系
			// 1表示目标页为后页, -1表示目标页为前页, 0表示两页不在同一层级
			catchId: function(a, b){
				if (a && b) {
					if (a > b){
						return -1;
					} else if (a < b){
						return 1;
					} else{
						return 0;
					}
				}
			},
			start: function() {
				// 如果正在转场，不执行动画
				if (pageToVars.pageToing) {
					return false;
				}
				pageToVars.pageToing = true;

				// 如果转场动画为none并不是自定义动画
				if (this.type == "none" && this.kf == undefined) {
					this.end();
					return false;
				}

				// 如果没有设置过back参数，判断两页面的关系
				if (this.back == undefined) {
					this.ab = this.catchId(this.current.data("p"), this.target.data("p"));
				} else {
					this.ab = this.back ? -1 : 1;
				}

				// 初始化动画完成的布尔
				this.afinish = false;
				this.bfinish = false;

				// 开始做转场动画
				this.current.css({"zIndex": "1", "display": "block"}).on("webkitAnimationEnd", pageToVars.onevt);
				this.target.css({"zIndex": "2"}).on("webkitAnimationEnd", pageToVars.onevt);


				switch (pageToVars.type) {
					case "move":
						pageToVars.speed = pageToVars.speed || 0.8;
						this.out = pageToVars.speed;
						pageToVars.ease = pageToVars.ease || "cubic-bezier(0.86,0,0.07,1)";
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimateMoveInCurrent";
							pageToVars.keyframeB = "pageAnimateMoveInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimateMoveOutCurrent";
							pageToVars.keyframeB = "pageAnimateMoveOutTarget";
						}
						break;
					case "slide":
						pageToVars.speed = pageToVars.speed || 0.8;
						this.out = pageToVars.speed;
						pageToVars.ease = pageToVars.ease || "cubic-bezier(0.86,0,0.07,1)";
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimateSlideInCurrent";
							pageToVars.keyframeB = "pageAnimateSlideInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimateSlideOutCurrent";
							pageToVars.keyframeB = "pageAnimateSlideOutTarget";
						}
						break;
					case "fb":
						pageToVars.speed = pageToVars.speed || 0.5;
						this.out = pageToVars.speed;
						pageToVars.ease = pageToVars.ease || "ease-out";
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimateFbInCurrent";
							pageToVars.keyframeB = "pageAnimateFbInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimateFbOutCurrent";
							pageToVars.keyframeB = "pageAnimateFbOutTarget";
						}
						break;
					case "cover":
						pageToVars.speed = pageToVars.speed || 0.8;
						this.out = pageToVars.speed;
						pageToVars.ease = pageToVars.ease || "cubic-bezier(0.86,0,0.07,1)";
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimateCoverInCurrent";
							pageToVars.keyframeB = "pageAnimateCoverInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimateCoverOutCurrent";
							pageToVars.keyframeB = "pageAnimateCoverOutTarget";
						}
						break;
					case "fade":
						pageToVars.speed = pageToVars.speed || 0.5;
						this.out = pageToVars.speed;
						pageToVars.ease = pageToVars.ease || "ease-out";
						pageToVars.keyframeA = "pageAnimateFadeCurrent";
						pageToVars.keyframeB = "pageAnimateFadeTarget";
						break;
					case "scale":
						pageToVars.speed = pageToVars.speed / 2 || 0.3;
						this.out = pageToVars.speed * 2;
						pageToVars.ease = pageToVars.ease || "ease-out";
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimateScaleInCurrent";
							pageToVars.keyframeB = "pageAnimateScaleInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimateScaleOutCurrent";
							pageToVars.keyframeB = "pageAnimateScaleOutTarget";
						}
						break;
					case "poker":
						pageToVars.speed = pageToVars.speed / 2 || 0.25;
						this.out = pageToVars.speed * 2;
						pageToVars.ease = pageToVars.ease || "linear";
						pageToVars.current.parent().css("-webkit-perspective", "2000px");
						if (pageToVars.ab != -1) {
							pageToVars.keyframeA = "pageAnimatePokerInCurrent";
							pageToVars.keyframeB = "pageAnimatePokerInTarget";
						} else {
							pageToVars.keyframeA = "pageAnimatePokerOutCurrent";
							pageToVars.keyframeB = "pageAnimatePokerOutTarget";
						}
						break;
				}

				if (this.kf && pageToVars.keyframeA == undefined && pageToVars.keyframeB == undefined){
					pageToVars.speed = pageToVars.speed || 0.4;
					this.out = pageToVars.speed;
					pageToVars.ease = pageToVars.ease || "ease-out";
					if (pageToVars.ab != -1) {
						pageToVars.keyframeA = this.kf + "InCurrent";
						pageToVars.keyframeB = this.kf + "InTarget";
					} else {
						pageToVars.keyframeA = this.kf + "OutCurrent";
						pageToVars.keyframeB = this.kf + "OutTarget";
					}
				}

				// 添加animation样式，动画形式为scale或poker时，target暂时不添加，并隐藏
				if (this.type !== "scale" && this.type !== "poker") {
					var th = this;
					th.target.css({"display": "block", "visibility": "hidden"});
					setTimeout(function(){
						th.current.css("-webkit-animation", pageToVars.keyframeA + " " + pageToVars.speed + "s " + pageToVars.ease);
						th.target.css({"visibility": "", "-webkit-animation": pageToVars.keyframeB + " " + pageToVars.speed + "s " + pageToVars.ease});
					},0);
				} else {
					this.current.css("-webkit-animation", pageToVars.keyframeA + " " + pageToVars.speed + "s " + pageToVars.ease);
					this.target.css("display", "none");
				}

				// 动画安全锁，持续动画时长+0.3秒后动画未结束，自动结束
				clearTimeout(this.timeout);
				this.timeout = setTimeout(function(){
					if (pageToVars.pageToing) {
						pageToVars.current.off("webkitAnimationEnd", pageToVars.onevt);
						pageToVars.target.off("webkitAnimationEnd", pageToVars.onevt);
						pageToVars.end();
					}
				}, (this.out + 0.3) * 1000);
			},
			onevt: function(e) {
				if (e.type === "webkitAnimationEnd") {
					// 如果事件对象是current，说明current的动画已经完成
					// current隐藏，删除侦听，并删除动画时添加的样式
					// 如果转场动画为scale或poker，执行下半部分动画
					if (e.target == pageToVars.current[0]) {
						pageToVars.afinish = true;
						pageToVars.current.css("display", "none");
						pageToVars.current.off("webkitAnimationEnd", pageToVars.onevt);
						if (pageToVars.type === "scale" || pageToVars.type === "poker") {
							if (pageToVars.type === "poker") {
								pageToVars.current.parent().css("-webkit-perspective", "");
								pageToVars.current.parent().css("-webkit-transform-style", "");
								pageToVars.target.parent().css("-webkit-perspective", "2000px");
							}
							pageToVars.target.css({"display": "block", "-webkit-animation": pageToVars.keyframeB + " " + pageToVars.speed + "s " + pageToVars.ease});
						} else if (pageToVars.bfinish) {
							pageToVars.end();
						}
					} else if (e.target == pageToVars.target[0]) {
						pageToVars.bfinish = true;
						pageToVars.target.off("webkitAnimationEnd", pageToVars.onevt);
						if (pageToVars.bfinish) {
							pageToVars.end();
						}
					}
				}
			},
			end: function() {
				if (pageToVars.pageToing) {
					clearTimeout(this.timeout);
					
					// 去除动画样式
					pageToVars.current.css({"-webkit-animation": "", "z-index": ""});
					pageToVars.target.css({"-webkit-animation": "", "z-index": ""});
					if (pageToVars.type === "poker") {
						pageToVars.current.parent().css("-webkit-perspective", "");
						pageToVars.target.parent().css("-webkit-perspective", "");
					}

					// 转出去的页隐藏，转进来的页显示
					this.current.css("display", "none");
					this.target.css("display", "block");

					// 如果有回调函数，回调
					if (this.callback) {
						this.callback();
						this.callback = undefined;
					}

					// 动画结束变量赋值
					pageToVars.keyframeA = undefined;
					pageToVars.keyframeB = undefined;
					pageToVars.pageToing = false;
					pageToVars.type = undefined;
					pageToVars.callback = undefined;
					pageToVars.hover = undefined;
					pageToVars.ab = 1;
					pageToVars.back = undefined;
					pageToVars.afinish = false;
					pageToVars.bfinish = false;
					pageToVars.timeout = 0;
					pageToVars.speed = 0.4;
					pageToVars.kf = undefined;
					pageToVars.ease = "ease-out";
				}
			}
		};

		// 页面转场动画
		$.fn.pageTo = function(el, o) {
			// E.pageTo(E, { type: "move", callback: fn, hover: E});
			pageToVars.current = this;
			pageToVars.target = el;
			pageToVars.type = o.type || "none";
			pageToVars.ease = o.ease;
			pageToVars.kf = o.keyframe;
			pageToVars.callback = o.callback;
			pageToVars.hover = o.hover;
			pageToVars.back = o.back;
			pageToVars.speed = o.speed;
			pageToVars.start();
		};

		// 返回是否在动画过程中的bool
		$.pageToing = function(){
			return pageToVars.pageToing;
		};
	})(jQuery);
});
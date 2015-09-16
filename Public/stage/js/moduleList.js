$(function(){


	var page = {
		init: function(){
			$("#table").on("click", ".status", page.onStatusClick);
		},
		onStatusClick: function(){
			var th = $(this);
			var tr = th.parents("tr");
			if (!th.hasClass("start")){
				$.alert({
					title: "温馨提示",
					txt: "确定要停用吗？",
					btnY: "停用",
					btnYcss: "btnC",
					btnN: "取消",
					callbackY: function(){
						$.loading();
						setTimeout(function(){
							$.loading.remove();
							tr.addClass("stop");
							th.addClass("start").html("启用");
						},500);
					}
				});
			} else {
				$.loading();
				setTimeout(function(){
					$.loading.remove();
					tr.removeClass("stop");
					th.removeClass("start").html("停用");
				},500);
			}
		}
	};
	page.init();


	
	









});





define(function(require, exports, module){
    require("common");

	$(function(){


		var page = {
			init: function(){
				$("#keywordTable").on("click", ".delete", page.onDel);
				$.cookieTips.check();
			},
			onDel: function(){
				var id = $(this).data("id");
				$.alert({
					title: "温馨提示",
					txt: "确定要删除此关键字吗？",
					btnY: "删除",
					btnYcss: "btnC",
					btnN: "取消",
					callbackY: function(){
						$.ajaxSubmit({
							url: "_kwdel.php",
							data: {id: id},
							success: function(d){
								if (d.status == 0){
									$.cookieTips.set("删除成功");
								} else {
									$.tips.error(d.msg);
								}
							},
							error: function(){
								$.tips.error("系统错误，请稍后再试！");
							}
						});
					}
				});
			}
		};
		page.init();


	});

});
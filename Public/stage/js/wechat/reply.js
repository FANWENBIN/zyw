$(function(){


	var page = {
		init: function(){
			$("#submit").on("click", page.submitClick);
			$("#status").on("click", page.statusClick);
		},
		statusClick: function(){
			var th = $(this);
			if (th.hasClass("btnC")){
				type = 1;
			} else {
				type = 0;
			}
			$.ajaxSubmit({
				url: "aaa.php",
				data: type,
				success: function(d){
					if (d.status == 0){
						if (d.data == 1){
							th.attr("class", "btnSmall btnC").html("停用菜单");
							$.tips.success("菜单已经停用！");
						} else {
							th.attr("class", "btnSmall btnB").html("启用菜单");
							$.tips.success("菜单已经启用！");
						}
					} else {
						$.tips.error("系统错误，请稍后再试！");
					}
				},
				error: function(d){
					$.tips.error("系统错误，请稍后再试！");
				}
			});
		},
		submitClick: function(){
			var o = $("#form").getForm();
			$.ajaxSubmit({
				url: "aaa.php",
				data: o,
				success: function(d){
					if (d.status == 0){
						$.tips.success("自动回复内容已提交！");
					} else {
						$.tips.error("提交失败，请稍后再试！");
					}
				},
				error: function(d){
					$.tips.error("系统错误，请稍后再试！");
				}
			});
		}
	};
	page.init();
	

});





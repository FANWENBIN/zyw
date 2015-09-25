define(function(require, exports, module){
    require("common");

	$(function(){

		var page = {
			init: function(){
				$("#submit").on("click", page.submitClick);
			},
			submitClick: function(){
				var o = $("#form").getForm();
	            o.retype = 'miskeyword';
				$.ajaxSubmit({
					url: "_reply.php",
					data: o,
					success: function(d){
						if (d.status == 0){
							$.tips.success("回复内容已提交！");
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
	
});




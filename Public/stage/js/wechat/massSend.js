$(function(){


	

	var page = {
		init: function(){
			$("#submit").on("click", page.submitClick);
			$("#country select").change(page.countryChange);
			$("#province select").change(page.provinceChange);
            $.cookieTips.check();
		},
		countryChange: function(){
			var val = $(this).val();
            if (val == ""){
                $("#province, #city").hide();
                return false;
            }
			$.ajaxSubmit({
                loading: false,
				url: "_area.php",
                data:{'type':'province','name':val},
				success: function(d){
					if (d.status == 0){
						var html = "<option value=''>全部</option>";
						for (var i = 0; i < d.data.length; i++){
							html += "<option value='" + d.data[i].province + "'>" + d.data[i].province + "</option>";
						}
						var next = $("#province select");
						next.html(html).data("init", null).next(".js_select").remove();
						next.selectInit();
                        $("#province").show();
					}
				},
				error: function(){
					$.tips.error("系统错误，请稍后再试！");
				}
			});
		},
		provinceChange: function(){
			var val = $(this).val();
            if (val == ""){
                $("#city").hide();
                return false;
            }
			$.ajaxSubmit({
                loading: false,
				url: "_area.php",
                data:{'type':'city','name':val},
				success: function(d){
					if (d.status == 0){
						var html = "<option value=''>全部</option>";
						for (var i = 0; i < d.data.length; i++){
							html += "<option value='" + d.data[i].city + "'>" + d.data[i].city + "</option>";
						}
						var next = $("#city select");
						next.html(html).data("init", null).next(".js_select").remove();
						next.selectInit();
                        $("#city").show();
					}
				},
				error: function(){
					$.tips.error("系统错误，请稍后再试！");
				}
			});
		},
		submitClick: function(){
			var o = $("#form").getForm();
			if (!o.wcasset.type || !o.wcasset.content){
				$.tips.error("请设置群发内容");
			} else {
			 
				$.ajaxSubmit({
					url: "_sendout.php",
                    data:o,
					success: function(d){
						if (d.status == 0){
                            $.cookieTips.set("发送成功");
						} else {
							$.tips.error(d.msg);
						}
					},
					error: function(){
						$.tips.error("系统错误，请稍后再试！");
					}
				});
			}
		}
	};
	page.init();

	

});



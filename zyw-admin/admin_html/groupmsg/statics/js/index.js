define(function(require, exports, module){
    require("common");

	$(function(){

		var page = {
			init: function(){
				$("#submit").on("click", page.submitClick);
	            $("#preview").on("click", page.previewClick);
				$("#country select").change(page.countryChange);
				$("#province select").change(page.provinceChange);
	            $.cookieTips.check();
			},
			countryChange: function(){
				var val = $(this).val();
	            if (val == ""){
	                $("#province, #city").hide();
	                return false;
	            }else{
	                $("#city").hide();
	            }
				$.ajaxSubmit({
	                loading: false,
					url: "_area.php",
	                data:{'type':'province','name':val},
					success: function(d){
						if (d.status == 0){
							var html = "<option value=''>全部</option>";
							for (var i = 0; i < d.data.length; i++){
	                            if(d.data[i].province != ''){
	                                html += "<option value='" + d.data[i].province + "'>" + d.data[i].province + "</option>";
	                            }
								
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
	                            if(d.data[i].city != ''){
	                                html += "<option value='" + d.data[i].city + "'>" + d.data[i].city + "</option>";
	                            }
								
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
	                var group = $("#group select").find("option:selected").text();
	                var sex = $("#sex select").find("option:selected").text();
	                var country = $("#country select").find("option:selected").text();
	                
	                var str = '群发对象：'+group+'<br />性别：'+sex+'<br />国家：'+country;
	                if(country != '全部'){
	                    var province = $("#province select").find("option:selected").text();
	                    str += '<br />省份：'+province;
	                }
	                if(country != '全部' && province != '全部'){
	                    var city = $("#city select").find("option:selected").text();
	                    str += '<br />城市：'+city;
	                }
	                
	                $.alert({
	    				title: "请确认操作",
	    				txt: str,
	    				btnY: "发送",
	    				btnYcss: "btnC",
	    				btnN: "取消",
	    				callbackY: function(){
	    					$.ajaxSubmit({
	        					url: "_sendout.php",
	                            data:o,
	        					success: function(d){
	        						if (d.status == 0){
	                                    $.cookieTips.set(d.msg);
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
			},
	        previewClick:function(){
	            var o = $("#form").getForm();
				if (!o.wcasset.type || !o.wcasset.content){
					$.tips.error("请设置群发内容");
				}else if(o.touser == ''){
	                $.tips.error("请填写接收者openid");
				} else {
				 
					$.ajaxSubmit({
						url: "_preview.php",
	                    data:o,
						success: function(d){
							if (d.status == 0){
	                            $.tips.success(d.msg);
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

});


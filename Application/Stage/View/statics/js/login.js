define(function(require, exports, module){
    require("common");

    $(function(){
    
    	var tips = {
    		error: function(t){
    			clearTimeout(tips.timeout);
    			$("#_tips_").remove();
    			$("#loginBox").append("<div id='_tips_' class='_tips_error'>" + t + "</div>");
    			tips.timeout = setTimeout(tips.remove, 2000);
    		},
    		remove: function(){
    			$("#_tips_").remove();
    		}
    	}
    
    	var page = {
    		init: function(){
    			$("#submit").on("click", page.submitClick);
    		},
    		submitClick: function(){
    			var name = $("#username");
    			var pw = $("#password");
    			var cd = $("#code");
    			var o = $("#form").getForm();
    			if (name.inputEmpty()){
    				tips.error("请输入用户名");
    			} else if (pw.inputEmpty()){
    				tips.error("请输入密码");
    			} else if (cd.inputEmpty()){
    				tips.error("请输验证码");
    			} else {
					alert(o);
    				$.ajaxSubmit({
    					url: "_login.php",
    					data: o,
    					success: function(d){
    						if (d.status == 0){
                                window.location = 'index.php';
    						} else {
                                $("#codepic").attr('src', '_verficode.php?_t=' + Math.random());
    							tips.error(d.msg);
    						}
    					},
    					error: function(){
    						tips.error("系统错误");
    					}
    				});
    			}
    		}
    	};
    	page.init();
    
    
    });

});
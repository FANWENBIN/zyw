$(function(){var e={init:function(){$("#login").on("click",e.showInfo),$("body").on("click",e.hideInfo),$("#reg").on("click",e.regShow),$("#log").on("click",e.logShow),$("body").on("click","#close",e.closeAlert),$("body").on("click","#img1",e.changePic),$("body").on("click","#img2",e.changePic),$("#getfreemesg").on("click",e.getVer),$(".registerform").on("submit",e.regSubmit),e.addLogin(),$.testLogin()?$("#islogin").show():$("#nologin").show()},regSubmit:function(){return $.ajax({url:"./index.php?m=Home&c=Login&a=register",type:"get",dataType:"json",data:{phone:$(":text[name=mb]").val(),passwd:$(":password[name=password]").val(),verify:$(":text[name=idcode2]").val()},success:function(e){"0"==e.status?console.log("成功"):"101"==e.status?$("#error").html("注册失败,请稍后再试"):"102"==e.status?$("#error").html("手机号码输入错误，请重新输入"):"103"==e.status&&$("#error").html("该手机账号已被注册过")},error:function(){}}),!1},getVer:function(){console.log(typeof $(":text[name=mb]").val(),typeof $(":text[name=idcode1]").val()),$.ajax({url:"./index.php?m=Home&c=Login&a=yzm",type:"get",dataType:"json",data:{code:$(":text[name=idcode1]").val(),phone:$(":text[name=mb]").val()},success:function(e){"0"==e.status?$("#error").html("验证已发送,请注意查收"):"101"==e.status?$("#error").html("服务器错误,请稍后再试"):"102"==e.status?$("#error").html("验证码输入错误，请重新输入"):"103"==e.status&&$("#error").html("手机号码输入错误，请重新输入")},error:function(e){}})},changePic:function(){$("#img1").attr("src","./index.php?m=Home&c=Login&a=verify")},closeAlert:function(){$(this).parent().parent().remove()},regShow:function(){var e=$('<div class="registeralert" id="registeralert"></div>');e.html('<div class="registeralert-main"><div class="close" id="close"></div><form class="registerform" action="index.html" method="post"><div class="registeralert-main-item"><label for="mb">手&nbsp;&nbsp;机：</label><input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码"></div><div class="registeralert-main-item"><label for="password">密&nbsp;&nbsp;码：</label><input type="password" name="password" value="" id="password" placeholder="请输入密码"></div><div class="registeralert-main-item"><label for="idcode">验证码：</label><input type="text" class="idcode1" name="idcode1" value="" id="idcode1" placeholder="请输入右侧字母"><div class="pic"><img src="./index.php?m=Home&c=Login&a=verify" alt="" id="img1" /></div><span class="reflesh"><span class="img" id="img2"></span></span></div><div class="registeralert-main-item"><input type="text" name="idcode2" value="" id="idcode2" placeholder="请输入验证码"><span class="getfreemesg" id="getfreemesg">免费获取短信</span></div><span id="error"></span><div class="registeralert-main-item"><input type="checkbox" name="rulechecked" value=""><!-- <span class="agree">&nbsp;&nbsp;我同意<em><用户协议></em></span> --><span class="login">立即登录</span></div><div class="registeralert-main-item"><input type="submit" name="name" value="注册" id="regsubmit"></div><div class="registeralert-main-item"><span class="login-weibo"></span><span class="login-weichat"></span></div></form></div>'),$("body").append(e)},logShow:function(){var e=$('<div class="loginalert" id="loginalert"></div>');e.html('<div class="loginalert-main"><div class="close" id="close"></div><form class="" action="index.html" method="post"><div class="loginalert-main-item"><label for="mb">手&nbsp;&nbsp;机：</label><input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码"></div><div class="loginalert-main-item"><label for="password">密&nbsp;&nbsp;码：</label><input type="password" name="password" value="" id="password" placeholder="请输入密码"></div><div class="loginalert-main-item"><input type="checkbox" name="rulechecked" value=""><!-- <span class="remenber">&nbsp;&nbsp;记住我</span> --><span class="register">立即注册</span><span class="searchpass">找回密码</span></div><div class="loginalert-main-item"><input type="submit" name="name" value="登陆"></div><div class="loginalert-main-item"><span class="login-weibo"></span><span class="login-weichat"></span></div></form></div>'),$("body").append(e)},showInfo:function(e){return $("#myinfoalert").show(),!1},hideInfo:function(){$("#myinfoalert").hide()},addLogin:function(){$.extend({testLogin:function(){$.ajax({url:"./index.php?m=Home&c=Login&a=checklogin",dataType:"json",type:"get",success:function(e){return"0"==e.status?!1:"1"==e.status?!0:void 0},error:function(){}})}})}};e.init()});
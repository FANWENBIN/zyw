!function(e){e.testLogin=function(a){e.ajax({url:"./index.php?m=Home&c=Login&a=checklogin",dataType:"json",type:"get",success:function(i){"1"==i.status?(e(".myinfoalert-header-content .name").html(i.data.nickname),e(".myinfoalert-header-face").attr("src",i.data.headpic),e(".islogin .face").attr("src",i.data.headpic),e("#login").html(i.data.nickname),e("#nologin").hide(),e("#islogin").show(),a(i.data)):"0"==i.status&&(e("#nologin").show(),e("#islogin").hide())},error:function(){}})},e.getId=function(){var e=window.location.href,a=e.split("id=");return a[1]}}(jQuery),$(function(){var e={init:function(){$("#login").on("click",e.showInfo),$("body").on("click",e.hideInfo),$("#reg").off().on("click",e.regShow),$("#log").off().on("click",e.logShow),$("body").on("click","#close",e.closeAlert),$("body").on("click","#img1",e.changePic),$("body").on("click","#img2",e.changePic),$("body").on("click","#getfreemesg",e.getVer),$("body").on("submit",".registerform",e.regSubmit),$(".logout").on("click",e.logOut),$.testLogin(function(){})},logOut:function(){window.location.href="./index.php?m=Home&c=Login&a=logout"},regSubmit:function(){return/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/.test($(":password[name=password]").val())?/^(0[1-9]\d{1,2}-)?[1-9]\d{6,7}$/.test($(":text[name=mb]").val())?$.ajax({url:"./index.php?m=Home&c=Login&a=register",type:"get",dataType:"json",data:{phone:$(":text[name=mb]").val(),passwd:$(":password[name=password]").val(),verify:$(":text[name=idcode2]").val()},success:function(e){console.log(e.msg),"0"==e.status?$("#error").html("注册成功,请去登陆"):"101"==e.status?$("#error").html("注册失败,请稍后再试"):"102"==e.status?$("#error").html("手机号码输入错误，请重新输入"):"103"==e.status?$("#error").html("该手机账号已被注册过"):"105"==e.status&&$("#error").html("密码不符合规则")},error:function(){}}):$("#error").html("手机号码输入错误，请重新输入"):$("#error").html("密码至少8位，至少包含数字和字符"),!1},getVer:function(){console.log(typeof $(":text[name=mb]").val(),typeof $(":text[name=idcode1]").val()),$.ajax({url:"./index.php?m=Home&c=Login&a=yzm",type:"get",dataType:"json",data:{code:$(":text[name=idcode1]").val(),phone:$(":text[name=mb]").val()},success:function(e){"0"==e.status?$("#error").html("验证已发送,请注意查收"):"101"==e.status?$("#error").html("服务器错误,请稍后再试"):"102"==e.status?$("#error").html("验证码输入错误，请重新输入"):"103"==e.status&&$("#error").html("手机号码输入错误，请重新输入")},error:function(e){}})},changePic:function(){$("#img1").attr("src","./index.php?m=Home&c=Login&a=verify")},closeAlert:function(){$(this).parent().parent().remove()},regShow:function(){var a=$('<div class="registeralert" id="registeralert"></div>');a.html('<div class="registeralert-main"><div class="close" id="close"></div><form class="registerform" action="index.html" method="post"><div class="registeralert-main-item"><label for="mb">手&nbsp;&nbsp;机：</label><input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码"></div><div class="registeralert-main-item"><label for="password">密&nbsp;&nbsp;码：</label><input type="password" name="password" value="" id="password" placeholder="密码至少8位，至少包含数字和字符"></div><div class="registeralert-main-item"><label for="idcode">验证码：</label><input type="text" class="idcode1" name="idcode1" value="" id="idcode1" placeholder="请输入右侧字母"><div class="pic"><img src="./index.php?m=Home&c=Login&a=verify" alt="" id="img1" /></div><span class="reflesh"><span class="img" id="img2"></span></span></div><div class="registeralert-main-item"><input type="text" name="idcode2" value="" id="idcode2" placeholder="请输入验证码"><span class="getfreemesg" id="getfreemesg">免费获取短信</span></div><span id="error"></span><div class="registeralert-main-item customstyle1"><!-- <span class="agree">&nbsp;&nbsp;我同意<em><用户协议></em></span> --><span class="login" id="go2login">立即登录</span></div><div class="registeralert-main-item"><input type="submit" name="name" value="注册" id="regsubmit"></div><div class="registeralert-main-item"><a href="javascript:;" class="login-weibo"></a><a href="javascript:;" class="login-qq"></a><a href="javascript:;" class="login-weichat"></a></div></form></div>'),$("body").append(a),$("#go2login").off().on("click",function(){$("#registeralert").remove(),e.logShow()}),$(".login-qq").on("click",e.toLoginQQ)},logShow:function(){var a=$('<div class="loginalert" id="loginalert"></div>');a.html('<div class="loginalert-main"><div class="close" id="close"></div><form class="logform" action="index.html" method="post"><div class="loginalert-main-item"><label for="mb">用户名：</label><input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码"></div><div class="loginalert-main-item"><label for="password">密&nbsp;&nbsp;码：</label><input type="password" name="password" value="" id="password" placeholder="请输入密码"></div><span id="error"></span><div class="loginalert-main-item customstyle1"><!-- <span class="remenber">&nbsp;&nbsp;记住我</span> --><span class="register" id="go2reg">立即注册</span><a href="./index.php?m=Home&c=User&a=codeback" class="searchpass">找回密码</a></div><div class="loginalert-main-item"><input type="submit" name="name" value="登陆"></div><div class="loginalert-main-item"><a href="javascript:;" class="login-weibo"></a><a href="javascript:;" class="login-qq"></a><a href="javascript:;" class="login-weichat"></a></div></form></div>'),$("body").append(a),$(".logform").on("submit",e.logSubmit),$("#go2reg").on("click",function(){$("#loginalert").remove(),e.regShow()}),$(".login-qq").on("click",e.toLoginQQ)},logSubmit:function(){return $.ajax({url:"./index.php?m=Home&c=Login&a=login",type:"get",dataType:"json",data:{name:$(":text[name=mb]").val(),passwd:$(":password[name=password]").val()},success:function(e){"1"==e.status?($("#loginalert").remove(),console.log("登陆成功"),$.testLogin()):"0"==e.status&&$("#error").html("账号密码输入错误")},error:function(){}}),!1},showInfo:function(e){return $("#myinfoalert").show(),!1},hideInfo:function(){$("#myinfoalert").hide()},toLoginQQ:function(){window.open("./index.php?m=Home&c=Login&a=qqlogin","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable = 1, status = 1, titlebar = 0, toolbar = 0, location = 1 ")}};e.init()});
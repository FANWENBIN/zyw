$(function(){var n={init:function(){$("#login").on("click",n.showInfo),$("body").on("click",n.hideInfo),$("#reg").on("click",n.regShow),$("#log").on("click",n.logShow),$(".close").on("click",n.closeAlert),$("#img1").on("click",n.changePic),$("#img2").on("click",n.changePic),$("#getfreemesg").on("click",n.getVer),n.addLogin(),$.testLogin()?$("#islogin").show():$("#nologin").show()},getVer:function(){$.ajax({url:"./index.php?m=Home&c=Login&a=yzm",type:"get",dataType:"json",data:{phone:$(":text[name=mb]"),code:$(":text[name=idcode1]")},success:function(n){"102"==n.status?$("#error").html("验证码输入错误"):"101"==n.status?$("#error").html("服务器错误，请稍后再试"):"0"==n.status},error:function(){}})},changePic:function(){$("#img1").attr("src","./index.php?m=Home&c=Login&a=verify")},closeAlert:function(){$(this).parent().parent().hide()},regShow:function(){$("#registeralert").show()},logShow:function(){$("#loginalert").show()},showInfo:function(n){return $("#myinfoalert").show(),!1},hideInfo:function(){$("#myinfoalert").hide()},addLogin:function(){$.extend({testLogin:function(){$.ajax({url:"./index.php?m=Home&c=Login&a=checklogin",dataType:"json",type:"get",success:function(n){return"0"==n.status?!1:"1"==n.status?!0:void 0},error:function(){}})}})}};n.init()});
$(function(){var e={verStatus:!0},t={init:function(){$("#getVer").on("click",t.gerVer),$("#checkmb").on("click",t.checkmb),$("#confirmcode").on("click",t.confirmcode)},confirmcode:function(){/^.+$/.test($(":text[name=code]").val())?/^.+$/.test($(":text[name=recode]").val())?$(":text[name=code]").val()!==$(":text[name=recode]").val()?$(".step2 .error")("两次输入的密码不相等, 请检查后重新输入"):/\d/.test($(":text[name=code]").val())&&/a-zA-Z/.test($(":text[name=code]").val())&&/.{8,20}/.test($(":text[name=code]").val())?alert("密码必须有6-20位，并包含数字和字母"):$.ajax({type:"post",url:"./index.php?m=Home&c=User&a=changepasswd",data:{passwd:$(":text[name=code]").val()},dataType:"json",success:function(e){console.log(e.msg),0===e.status?($(".step2").hide(),$(".step3").show()):102===e.status&&$(".step2 .error")("系统错误，请稍后再试")},error:function(){}}):$(".step2 .error")("请再次输入新密码"):$(".step2 .error")("请输入新密码")},checkmb:function(){$.ajax({type:"get",url:"./index.php?m=Home&c=User&a=code",data:{phone:$(":text[name=tel]").val(),verify:$(":text[name=ver]").val()},dataType:"json",success:function(e){console.log(e.msg),0===e.status?($(".step1").hide(),$(".step2").show()):102===e.status&&alert("验证码错误")},error:function(){}})},gerVer:function(){/^.+$/.test($(":text[name=tel]").val())?(e.verStatus=!1,$.ajax({type:"get",url:"./index.php?m=Home&c=User&a=gsend",data:{phone:$(":text[name=tel]").val()},dataType:"json",success:function(t){console.log(t.msg),0===t.status?$(".step1 .error").html("发送成功，请注意查收"):101===t.status?$(".step1 .error").html("服务器错误，请稍后再试"):103===t.status?$(".step1 .error").html("手机号码错误，请检查错误后再试"):104===t.status&&$(".step1 .error").html("手机号码未注册"),e.verStatus=!0},error:function(){}})):alert("手机号不能为空")}};t.init()});
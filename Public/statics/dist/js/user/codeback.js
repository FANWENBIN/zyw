$(function(){var e={verStatus:!0},t={init:function(){$("#getVer").on("click",t.gerVer),$("#checkmb").on("click",t.checkmb),$("#confirmcode").on("click",t.confirmcode)},confirmcode:function(){/^.+$/.test($(":text[name=code]").val())?/^.+$/.test($(":text[name=recode]").val())?$(":password[name=code]").val()!==$(":password[name=recode]").val()?$(".step2 .error").html("两次输入的密码不相等, 请检查后重新输入"):/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/.test($(":password[name=code]").val())?$.ajax({type:"post",url:"./index.php?m=Home&c=User&a=changepasswd",data:{passwd:$(":password[name=code]").val()},dataType:"json",success:function(e){console.log(e.msg),0===e.status?($(".step2").hide(),$(".step3").show()):102===e.status&&$(".step2 .error")("系统错误，请稍后再试")},error:function(){}}):$(".step2 .error").html("至少8位，至少包含数字和字符"):$(".step2 .error").html("请再次输入新密码"):$(".step2 .error").html("请输入新密码")},checkmb:function(){$.ajax({type:"get",url:"./index.php?m=Home&c=User&a=code",data:{phone:$(":text[name=tel]").val(),verify:$(":text[name=ver]").val()},dataType:"json",success:function(e){console.log(e.msg),0===e.status?($(".step1").hide(),$(".step2").show()):102===e.status&&alert("验证码错误")},error:function(){}})},gerVer:function(){if(/^.+$/.test($(":text[name=tel]").val())){if(e.verStatus){e.verStatus=!1;var t=30,s=setTimeout(function(){$("#getVer").html(t--),console.log(t),0===t&&(s=null,e.verStatus=!0,$("#getVer").html("发送验证码"))},1e3);$.ajax({type:"get",url:"./index.php?m=Home&c=User&a=gsend",data:{phone:$(":text[name=tel]").val()},dataType:"json",success:function(e){console.log(e.msg),0===e.status?$(".step1 .error").html("发送成功，请注意查收"):101===e.status?$(".step1 .error").html("服务器错误，请稍后再试"):103===e.status?$(".step1 .error").html("手机号码错误，请检查错误后再试"):104===e.status&&$(".step1 .error").html("手机号码未注册")},error:function(){}})}}else alert("手机号不能为空")}};t.init()});
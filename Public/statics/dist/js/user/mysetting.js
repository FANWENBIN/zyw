$(function(){var t={provinceid:"440000"},e={init:function(){$(".sublist").on("click","li",e.leftClick),$(".group").on("click","li",e.upClick),$("#changeNum").on("click",e.toStep2),$("#sendverify").on("click",e.getVerInSetting),$("#confirmmb").on("click",e.confirmMb),$("#province").on("change",e.changeProvince),e.getProvince(),e.getCity()},changeProvince:function(){t.provinceid=$("#province option:selected").data("id"),e.getCity()},getProvince:function(){$.ajax({url:"./index.php?m=Home&c=Area&a=province",type:"get",dataType:"json",success:function(t){for(var e=t.data,i="",n=0,o=e.length;o>n;n++)i+='<option data-id="'+e[n].provinceid+'">'+e[n].province+"</option>";$("#province").html(i)},error:function(){}})},getCity:function(){$.ajax({url:"./index.php?m=Home&c=Area&a=city",type:"get",dataType:"json",data:{provinceid:t.provinceid},success:function(t){for(var e=t.data,i="",n=0,o=e.length;o>n;n++)i+='<option data-id="'+e[n].cityid+'">'+e[n].city+"</option>";$("#city").html(i)},error:function(){}})},getVerInSetting:function(){console.log($(":text[name=mbchange]").val()),$.ajax({url:"./index.php?m=Home&c=User&a=yzm",type:"get",dataType:"json",data:{phone:$(":text[name=mbchange]").val()},success:function(t){"0"==t.status?$("#error").html("验证码已发送，请注意查收"):"101"==t.status?$("#error").html("发送失败，请稍后再试"):"102"==t.status&&$("#error").html("手机号码错误，请检查后再试")},error:function(){}})},confirmMb:function(){$.ajax({url:"./index.php?m=Home&c=User&a=phonebinding",type:"post",dataType:"json",data:{phone:$(":text[name=mbchange]").val(),code:$(":text[name=veri]").val()},success:function(t){"0"==t.status?($("#error").html("绑定成功"),$(".step2").hide(),$(".step3").show()):"101"==t.status?$("#error").html("验证码错误"):"102"==t.status?$("#error").html("绑定失败，请稍后再试"):"105"==t.status&&$("#error").html("验证码与手机号码不匹配，请重新尝试")}})},toStep2:function(){$(".step1").hide(),$(".step2").show()},leftClick:function(){$(this).parent().find("li").removeClass("active"),$(this).addClass("active"),$(this).parents(".bottomitem").find(".item").removeClass("active"),$(this).parents(".bottomitem").find(".item").eq($(this).index()).addClass("active")},upClick:function(){$(this).parent().find("li").removeClass("active"),$(this).addClass("active"),$(".list").find(".bottomitem").removeClass("active"),$(".list").find(".bottomitem").eq($(this).index()).addClass("active")}};e.init()});
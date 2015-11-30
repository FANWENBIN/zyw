$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      $("#getver").on("click",page.getver);
      $(":text[name=mb]").on("blur",page.mbver);
    },
    mbver: function(){
      if(!/^1[345678]\d{9}$/.test($(":text[name=mb]").val())){
        $(".error").html("手机号码错误，请重新输入")
      }else{
        $.ajax({
          type: "get",
          url: "./index.php?m=Home&c=Login&a=checkphone",
          data: {
            phone: $(":text[name=mb]").val()
          },
          dataType: "json",
          success: function(json){
            console.log(json.msg);
            if(json.status === 0){
              $(".error").html("验证码已发送")
            }else if(json.status === 101){
              $(".error").html("手机号已被注册")
            }else if(json.status === 102){
              $(".error").html("验证失败，请稍后再试")
            }else if(json.status === 103){
              $(".error").html("手机不符合格式，请重新输入")
            }else{
              $(".error").html("服务器错误，请稍后再试")
            }
          },
          error: function(){}
        })
      }
    },
    getver: function(){
      if(!/^1[345678]\d{9}$/.test($(":text[name=mb]").val())){
        $(".error").html("手机号码错误，请重新输入")
      }else{
        $.ajax({
          type: "get",
          url: "./index.php?m=Home&c=Login&a=sendyzm",
          data: {
            phone: $(":text[name=mb]").val()
          },
          dataType: "json",
          success: function(json){
            console.log(json.msg);
            if(json.status === 0){
              $(".error").html("验证码已发送")
            }else if(json.status === 101){
              $(".error").html("手机号已被注册")
            }else if(json.status === 102){
              $(".error").html("验证失败，请稍后再试")
            }else if(json.status === 103){
              $(".error").html("手机不符合格式，请重新输入")
            }else{
              $(".error").html("服务器错误，请稍后再试")
            }
          },
          error: function(){}
        })
      }
    }
  };
  page.init();
})

$(function(){
  var scope = {
    verStatus: true
  };
  var page = {
    init: function(){
      $("#getver").on("click",page.getver);
      $(":text[name=mb]").on("blur",page.mbver);
      $("#submit").on("click",page.submit)
    },
    submit: function(){
      if(!/^1[345678]\d{9}$/.test($(":text[name=mb]").val())){
        $(".error").html("手机号码错误，请重新输入")
      }else if(!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/.test($(":password[name=pass]").val())){
        $(".error").html("至少8位，至少包含数字和字符");
      }else if(!/^.+$/.test($(":text[name=ver]").val())){
        $(".error").html("请输入验证码");
      }else{
        $.ajax({
          type: "post",
          url: "./index.php?m=Home&c=Login&a=myinfo",
          data: {
            phone: $(":text[name=mb]").val(),
            passwd: $(":password[name=pass]").val(),
            verify: $(":text[name=ver]").val()
          },
          dataType: "json",
          success: function(json){
            console.log(json.msg);
            if(json.status === 0){
              window.location.href = "./index.php?m=Home&c=User&a=myinfo"
            }else if(json.status === 103){
              $(".error").html("手机号码格式不正确")
            }else if(json.status === 105){
              $(".error").html("至少8位，至少包含数字和字符")
            }else if(json.status === 104){
              $(".error").html("验证码输入错误")
            }else{
              $(".error").html("服务器错误，请稍后再试")
            }
          },
          error: function(){}
        })
      }
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
              $(".error").html("")
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
        if (scope.verStatus) {
                  scope.verStatus = false;
                  var _time = 30;
                  var _timer = setInterval(function(){
                    $("#getver").html(_time--);
                    if(_time === 0){
                      clearInterval(_timer);
                      scope.verStatus = true;
                      $("#getver").html("发送验证码");
                    }
                    console.log(_time);
                  },1000)
        }

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

$(function(){
  var scope = {
    verStatus: true
  };
  var page = {
    init: function(){
      $("#getVer").on("click", page.gerVer);
      $("#checkmb").on("click", page.checkmb);
      $("#confirmcode").on("click",page.confirmcode);
    },
    confirmcode: function(){
      if(!/^.+$/.test($(":text[name=code]").val())){
        $(".step2 .error")("请输入新密码")
      }else if(!/^.+$/.test($(":text[name=recode]").val())){
        $(".step2 .error")("请再次输入新密码")
      }else if($(":text[name=code]").val() !== $(":text[name=recode]").val()){
        $(".step2 .error")("两次输入的密码不相等, 请检查后重新输入")
      }else if(/\d/.test($(":text[name=code]").val()) && /a-zA-Z/.test($(":text[name=code]").val()) && /.{8,20}/.test($(":text[name=code]").val())){
        $(".step2 .error")("密码必须有6-20位，并包含数字和字母")
      }else{
        $.ajax({
          type: "post",
          url: "./index.php?m=Home&c=User&a=changepasswd",
          data: {
            passwd: $(":text[name=code]").val(),
          },
          dataType: "json",
          success: function(json){
            console.log(json.msg);
            if(json.status === 0){
              $(".step2").hide();
              $(".step3").show();
            }else if(json.status === 102){
              $(".step2 .error")("系统错误，请稍后再试");
            }
          },
          error: function(){}
        })
      }
    },
    checkmb: function(){
      $.ajax({
        type: "get",
        url: "./index.php?m=Home&c=User&a=code",
        data: {
          phone: $(":text[name=tel]").val(),
          verify: $(":text[name=ver]").val()
        },
        dataType: "json",
        success: function(json){
            console.log(json.msg);
          if(json.status === 0){
            $(".step1").hide();
            $(".step2").show();
          }else if(json.status === 102){
            alert("验证码错误")
          }
        },
        error: function(){}
      })
    },
    gerVer: function(){
      if(/^.+$/.test($(":text[name=tel]").val())){
        scope.verStatus = false;
        $.ajax({
          type: "get",
          url: "./index.php?m=Home&c=User&a=gsend",
          data: {
            phone: $(":text[name=tel]").val()
          },
          dataType: "json",
          success: function(json){
            console.log(json.msg)
            if(json.status === 0){
              $(".step1 .error").html("发送成功，请注意查收")
            }else if(json.status === 101){
              $(".step1 .error").html("服务器错误，请稍后再试")
            }else if(json.status === 103){
              $(".step1 .error").html("手机号码错误，请检查错误后再试")
            }else if(json.status === 104){
              $(".step1 .error").html("手机号码未注册")
            }
            scope.verStatus = true;
          },
          error: function(){}
        })
      }else{
        alert("手机号不能为空");
      }
    }
  };
  page.init();
})

$(function() {
  var scope = {
    verStatus: true
  };
  var page = {
    init: function() {
      $("#getVer").on("click", page.gerVer);
      $("#checkmb").on("click", page.checkmb);
      $("#confirmcode").on("click", page.confirmcode);
    },
    confirmcode: function() {
      if (!/^.+$/.test($(":text[name=code]").val())) {
        $(".step2 .error").html("请输入新密码")
      } else if (!/^.+$/.test($(":text[name=recode]").val())) {
        $(".step2 .error").html("请再次输入新密码")
      } else if ($(":password[name=code]").val() !== $(":password[name=recode]").val()) {
        $(".step2 .error").html("两次输入的密码不相等, 请检查后重新输入")
      } else if (!/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/.test($(":password[name=code]").val())) {
        $(".step2 .error").html("至少8位，至少包含数字和字符")
      } else {
        $.ajax({
          type: "post",
          url: "./index.php?m=Home&c=User&a=changepasswd",
          data: {
            passwd: $(":password[name=code]").val(),
          },
          dataType: "json",
          success: function(json) {
            console.log(json.msg);
            if (json.status === 0) {
              $(".step2").hide();
              $(".step3").show();
            } else {
              $(".step2 .error").html(json.msg);
            }
          },
          error: function() {}
        })
      }
    },
    checkmb: function() {
      if (!/^.+$/.test($(":text[name=tel]").val())) {
        alert("手机号码不能为空");
      } else if (!/^.+$/.test($(":text[name=ver]").val())) {
        alert("验证码不能为空");
      } else {
        $.ajax({
          type: "get",
          url: "./index.php?m=Home&c=User&a=code",
          data: {
            phone: $(":text[name=tel]").val(),
            verify: $(":text[name=ver]").val()
          },
          dataType: "json",
          success: function(json) {
            console.log(json.msg);
            if (json.status === 0) {
              $(".step1").hide();
              $(".step2").show();
            } else if (json.status === 102) {
              alert("验证码错误");
            } else if (json.status === 103) {
              alert("手机号码错误");
            } else{
              alert("json.msg");
            }
          },
          error: function() {}
        })
      }
    },
    gerVer: function() {
      if (/^.+$/.test($(":text[name=tel]").val())) {
        if (scope.verStatus) {
          scope.verStatus = false;
          var _time = 30;
          var _timer = setInterval(function() {
            $("#getVer").html(_time--);
            if (_time === 0) {
              clearInterval(_timer);
              scope.verStatus = true;
              $("#getVer").html("发送验证码");
            }
            console.log(_time);
          }, 1000)
          $.ajax({
            type: "get",
            url: "./index.php?m=Home&c=User&a=gsend",
            data: {
              phone: $(":text[name=tel]").val()
            },
            dataType: "json",
            success: function(json) {
              console.log(json.msg)
              if (json.status === 0) {
                $(".step1 .error").html("发送成功，请注意查收")
              } else if (json.status === 101) {
                $(".step1 .error").html("服务器错误，请稍后再试")
              } else if (json.status === 103) {
                $(".step1 .error").html("手机号码错误，请检查错误后再试")
              } else if (json.status === 104) {
                $(".step1 .error").html("手机号码未注册")
              } else {
                $(".step1 .error").html(json.msg)
              }
            },
            error: function() {}
          })
        }
      } else {
        alert("手机号不能为空");
      }
    }
  };
  page.init();
})

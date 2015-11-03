$(function() {

  var scope = {

  };
  var page = {
    init: function() {
      // 点击#login 显示登陆框
      $("#login").on("click", page.showInfo);
      // 点击body隐藏登录框
      $("body").on("click", page.hideInfo);
      // 未登陆时，点击#reg 注册框出来
      $("#reg").on("click", page.regShow);
      // 未登陆时，点击#log 登录框出来
      $("#log").on("click", page.logShow);
      // 上面两个框的关闭按钮
      $(".close").on("click", page.closeAlert);
      // 刷新二维码
      $("#img1").on("click", page.changePic);
      $("#img2").on("click", page.changePic);
      // 获取二维码
      $("#getfreemesg").on("click",page.getVer);
      // 注册提交
      $(".registerform").on("submit", page.regSubmit);
      // 增加$.testLogin函数验证登陆
      page.addLogin();
      // 用户登陆检测判断
      if ($.testLogin()) {
        $("#islogin").show();
      } else {
        $("#nologin").show();
      }
    },
    regSubmit: function(){
      alert(1)
      $.ajax({
        url: "./index.php?m=Home&c=Login&a=register",
        type: "get",
        dataType: "json",
        data: {
          phone: $(":text[name=mb]").val(),
          passwd: $(":text[name=password]").val(),
          verify: $(":text[name=idcode2]").val()
        },
        success: function(json){
          if(json.status == "0"){
            console.log("成功")
          }else if(json.status == "101"){
            $("#error").html("注册失败,请稍后再试");
          }else if(json.status == "102"){
            $("#error").html("手机号码输入错误，请重新输入");
          }else if(json.status == "103"){
            $("#error").html("该手机账号已被注册过");
          }
        },
        error: function(){
        }
      })
      return false;
    },
    getVer: function() {
      console.log(typeof $(":text[name=mb]").val(),typeof $(":text[name=idcode1]").val())
      $.ajax({
        url: './index.php?m=Home&c=Login&a=yzm',
        type: 'get',
        dataType: 'json',
        data: {
          code: $(":text[name=idcode1]").val(),
          phone: $(":text[name=mb]").val()
        },
        success: function(json) {
          if(json.status == "0"){
            console.log("发送成功")
          }else if(json.status == "101"){
            $("#error").html("服务器错误,请稍后再试");
          }else if(json.status == "102"){
            $("#error").html("验证码输入错误，请重新输入");
          }else if(json.status == "103"){
            $("#error").html("手机号码输入错误，请重新输入");
          }
        },
        error: function(d) {
          //
        }
      });
    },
    changePic: function() {
      $("#img1").attr("src", "./index.php?m=Home&c=Login&a=verify");
    },
    closeAlert: function() {
      $(this).parent().parent().hide();
    },
    regShow: function() {
      $("#registeralert").show();
    },
    logShow: function() {
      $("#loginalert").show();
    },
    showInfo: function(e) {
      $("#myinfoalert").show();
      return false;
    },
    hideInfo: function() {
      $("#myinfoalert").hide();
    },
    // jq插件 增加testLogin函数验证登陆
    addLogin: function() {
      $.extend({
        testLogin: function() {
          $.ajax({
            url: "./index.php?m=Home&c=Login&a=checklogin",
            dataType: "json",
            type: "get",
            success: function(json) {
              if (json.status == "0") return false;
              if (json.status == "1") return true;
            },
            error: function() {}
          })
        }
      })
    }
  };
  page.init();



})

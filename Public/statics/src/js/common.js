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
      $("body").on("click","#close", page.closeAlert);
      // 刷新二维码
      $("body").on("click","#img1", page.changePic);
      $("body").on("click","#img2", page.changePic);
      // 获取二维码
      $("body").on("click","#getfreemesg",page.getVer);
      // 注册提交
      $("body").on("submit",".registerform", page.regSubmit);
      // 增加$.testLogin函数验证登陆
      page.addLogin();
      // 用户登陆检测判断
      $.testLogin();

    },
    regSubmit: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Login&a=register",
        type: "get",
        dataType: "json",
        data: {
          phone: $(":text[name=mb]").val(),
          passwd: $(":password[name=password]").val(),
          verify: $(":text[name=idcode2]").val()
        },
        success: function(json){
          if(json.status == "0"){
            console.log("成功");
            $("#error").html("注册成功,请去登陆");
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
            $("#error").html("验证已发送,请注意查收");
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
      // $(this).parent().parent().hide();
      $(this).parent().parent().remove();
    },
    regShow: function() {
      // $("#registeralert").show();
      var $reg = $('<div class="registeralert" id="registeralert"></div>');
      $reg.html('<div class="registeralert-main">'+
        '<div class="close" id="close">'+
        '</div>'+
        '<form class="registerform" action="index.html" method="post">'+
          '<div class="registeralert-main-item">'+
            '<label for="mb">手&nbsp;&nbsp;机：</label>'+
            '<input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码">'+
          '</div>'+
          '<div class="registeralert-main-item">'+
            '<label for="password">密&nbsp;&nbsp;码：</label>'+
            '<input type="password" name="password" value="" id="password" placeholder="请输入密码">'+
          '</div>'+
          '<div class="registeralert-main-item">'+
            '<label for="idcode">验证码：</label>'+
            '<input type="text" class="idcode1" name="idcode1" value="" id="idcode1" placeholder="请输入右侧字母">'+
            '<div class="pic">'+
              '<img src="./index.php?m=Home&c=Login&a=verify" alt="" id="img1" />'+
            '</div>'+
            '<span class="reflesh">'+
              '<span class="img" id="img2"></span>'+
            '</span>'+
          '</div>'+
          '<div class="registeralert-main-item">'+
            '<input type="text" name="idcode2" value="" id="idcode2" placeholder="请输入验证码"><span class="getfreemesg" id="getfreemesg">免费获取短信</span>'+
          '</div>'+
          '<span id="error"></span>'+
          '<div class="registeralert-main-item">'+
            '<input type="checkbox" name="rulechecked" value="">'+
            '<!-- <span class="agree">&nbsp;&nbsp;我同意<em><用户协议></em></span> -->'+
            '<span class="login">立即登录</span>'+
          '</div>'+
          '<div class="registeralert-main-item">'+
            '<input type="submit" name="name" value="注册" id="regsubmit">'+
          '</div>'+
          '<div class="registeralert-main-item">'+
            '<span class="login-weibo"></span>'+
            '<span class="login-weichat"></span>'+
          '</div>'+
        '</form>'+
      '</div>')
      $("body").append($reg);
      $(".login").on("click",function(){
        $("#registeralert").remove();
        page.logShow();
      })

    },
    logShow: function() {
      // $("#loginalert").show()
      var $log = $('<div class="loginalert" id="loginalert"></div>');
      $log.html('<div class="loginalert-main">'+
        '<div class="close" id="close">'+
        '</div>'+
        '<form class="logform" action="index.html" method="post">'+
          '<div class="loginalert-main-item">'+
            '<label for="mb">手&nbsp;&nbsp;机：</label>'+
            '<input type="text" name="mb" value="" id="mb" placeholder="请输入手机号码">'+
          '</div>'+
          '<div class="loginalert-main-item">'+
            '<label for="password">密&nbsp;&nbsp;码：</label>'+
            '<input type="password" name="password" value="" id="password" placeholder="请输入密码">'+
          '</div>'+
          '<span id="error"></span>'+
          '<div class="loginalert-main-item">'+
            '<input type="checkbox" name="rulechecked" value="">'+
            '<!-- <span class="remenber">&nbsp;&nbsp;记住我</span> -->'+
            '<span class="register">立即注册</span>'+
            '<span class="searchpass">找回密码</span>'+
          '</div>'+
          '<div class="loginalert-main-item">'+
            '<input type="submit" name="name" value="登陆">'+
          '</div>'+
          '<div class="loginalert-main-item">'+
            '<span class="login-weibo"></span>'+
            '<span class="login-weichat"></span>'+
          '</div>'+
        '</form>'+
      '</div>');
      $("body").append($log);
      $(".logform").on("submit",page.logSubmit)
    },
    logSubmit: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Login&a=login",
        type: "get",
        dataType: "json",
        data: {
          name: $(":text[name=mb]").val(),
          passwd: $(":password[name=password]").val()
        },
        success: function(json){
          if(json.status == "1"){
          $("#loginalert").remove();
          $("#islogin").show();
          $("#nologin").hide();
        }else if(json.status == "0"){
          $("#error").html("账号密码输入错误")
        }
        },
        error: function(){
        }
      })
      return false;
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
              console.log(json)
              // if (true) {
                // $("#islogin").show();
              // } else {
                // $("#nologin").show();
              // }
            },
            error: function() {}
          })
        }
      })
    }
  };
  page.init();



})

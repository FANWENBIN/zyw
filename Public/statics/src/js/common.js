$(function() {

  var scope = {

  };
  var page = {
    init: function(){
      // 点击#login 显示登陆框
      $("#login").on("click",page.showInfo);
      // 点击body隐藏登录框
      $("body").on("click",page.hideInfo);
      // 未登陆时，点击#reg 注册框出来
      $("#reg").on("click",page.regShow);
      // 未登陆时，点击#log 登录框出来
      $("#log").on("click",page.logShow);
      // 上面两个框的关闭按钮
      $(".close").on("click",page.closeAlert);
      // 刷新二维码
      $("#img1").on("click",page.changePic);
      $("#img2").on("click",page.changePic);
      $("#getfreemesg").on("click",page.getVer);
      // 增加$.testLogin函数验证登陆
      page.addLogin();
      // 用户登陆检测判断
      if($.testLogin()){
        $("#islogin").show();
      }else{
        $("#nologin").show();
      }
    },
    getVer: function(){
      console.log($(":text[name=mb]").val(),$(":text[name=idcode1]").val())
      $.ajax({
        url: "./index.php?m=Home&c=Login&a=yzm",
        dataType: "json",
        type: "get",
        data: {
          phone: $(":text[name=mb]").val(),
          code: $(":text[name=idcode1]").val()
        },
        success: function(json){
          if(json.status == "102"){
            $("#error").html("验证码输入错误");
          }else if(json.status == "101"){
            $("#error").html("服务器错误，请稍后再试");
          }else if(json.status == "0"){
            // $("#error").html("手机验证码获取成功");
          }
        },
        error: function(){
        }
      })
    },
    changePic: function(){
      $("#img1").attr("src","./index.php?m=Home&c=Login&a=verify");
    },
    closeAlert: function(){
      $(this).parent().parent().hide();
    },
    regShow: function(){
      $("#registeralert").show();
    },
    logShow: function(){
      $("#loginalert").show();
    },
    showInfo: function(e){
      $("#myinfoalert").show();
      return false;
    },
    hideInfo: function(){
        $("#myinfoalert").hide();
    },
    // jq插件 增加testLogin函数验证登陆
    addLogin: function(){
      $.extend({
        testLogin: function(){
          $.ajax({
            url: "./index.php?m=Home&c=Login&a=checklogin",
            dataType: "json",
            type: "get",
            success: function(json){
              if(json.status == "0")return false;
              if(json.status == "1")return true;
            },
            error: function(){
            }
          })
        }
      })
    }
  };
  page.init();



})

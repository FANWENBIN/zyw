$(function(){
  var scope = {
    provinceid: "440000"

  };
  var page = {
    init: function(){
      // initRadio("sex");
      $(".sublist").on("click","li",page.leftClick);
      $(".group").on("click","li",page.upClick);
      $("#changeNum").on("click",page.toStep2);
      $("#sendverify").on("click",page.getVerInSetting);
      $("#confirmmb").on("click",page.confirmMb);
      $("#province").on("change",page.changeProvince);
      // 初始化province city
      // page.getProvince();
      // page.getCity();

    },
    changeProvince: function(){
      scope.provinceid = $("#province option:selected").data("id");
      page.getCity();
    },
    getProvince: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=province",
        type: "get",
        dataType: "json",
        success: function(json){
          var _arr = json.data;
          var _html = "";
          for(var i = 0, len = _arr.length ; i < len ; i++){
            _html += '<option data-id="'+ _arr[i].provinceid +'" value="'+ _arr[i].provinceid +'|'+ _arr[i].province +'">'+ _arr[i].province +'</option>'
          }
          $("#province").html(_html);
        },
        error: function(){}
      })
    },
    getCity: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Area&a=city",
        type: "get",
        dataType: "json",
        data: {
          provinceid: scope.provinceid
        },
        success: function(json){
          var _arr = json.data;
          var _html = "";
          for(var i = 0, len = _arr.length ; i < len ; i++){
            _html += '<option value="'+ _arr[i].cityid +'|'+ _arr[i].city +'">'+ _arr[i].city +'</option>'
          }
          $("#city").html(_html);
        },
        error: function(){}
      })
    },
    getVerInSetting: function(){
      console.log($(":text[name=mbchange]").val());
      $.ajax({
        url: "./index.php?m=Home&c=User&a=yzm",
        type: "get",
        dataType: "json",
        data: {
          phone: $(":text[name=mbchange]").val(),
        },
        success: function(json){
          if(json.status == "0"){
            $("#error").html("验证码已发送，请注意查收");
          }else if(json.status == "101"){
            $("#error").html("发送失败，请稍后再试");
          }else if(json.status == "102"){
            $("#error").html("手机号码错误，请检查后再试");
          }
        },
        error: function(){
        }
      })
    },
    confirmMb: function(){
      $.ajax({
        url: "./index.php?m=Home&c=User&a=phonebinding",
        type: "post",
        dataType: "json",
        data: {
          phone: $(":text[name=mbchange]").val(),
          code: $(":text[name=veri]").val()
        },
        success: function(json){
          if(json.status == "0"){
            $("#error").html("绑定成功");
            $(".step2").hide();
            $(".step3").show();
          }else if(json.status == "101"){
            $("#error").html("验证码错误");
          }else if(json.status == "102"){
            $("#error").html("绑定失败，请稍后再试");
          }else if(json.status == "105"){
            $("#error").html("验证码与手机号码不匹配，请重新尝试");
          }
        }
      });

    },
    toStep2: function(){
      $(".step1").hide();
      $(".step2").show();
    },
    leftClick: function(){
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      $(this).parents(".bottomitem").find(".item").removeClass("active");
      $(this).parents(".bottomitem").find(".item").eq($(this).index()).addClass("active");
    },
    upClick: function(){
      $(this).parent().find("li").removeClass("active");
      $(this).addClass("active");
      $(".list").find(".bottomitem").removeClass("active");
      $(".list").find(".bottomitem").eq($(this).index()).addClass("active");
    }
  };
  page.init();
})

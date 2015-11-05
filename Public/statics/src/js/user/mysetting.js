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
      page.getProvince();
      page.getCity();

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
            _html += '<option data-id="'+ _arr[i].provinceid +'">'+ _arr[i].province +'</option>'
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
            _html += '<option data-id="'+ _arr[i].cityid +'">'+ _arr[i].city +'</option>'
          }
          $("#city").html(_html);
        },
        error: function(){}
      })
    },
    getVerInSetting: function(){
      $.ajax({
        url: "./index.php?m=Home&c=User&a=yzm",
        type: "get",
        dataType: "json",
        data: {
          Phone: $(":text[name=mbchange]").val(),
        },
        success: function(json){
          if(json.status == "0"){
            $("#error").html();
          }else if(json.status == "1"){

          }
        },
        error: function(){
        }
      })
    },
    confirmMb: function(){
      $.ajax({
        url: "./index.php?m=Home&c=Login&a=yzm",
        type: "get",
        dataType: "json",
        data: {

        },
        success: function(json){
          if(json.status == "0"){

          }else if(json.status == "1"){

          }
        }
      });
      $(".step2").hide();
      $(".step3").show();
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

$(function(){
  var scope = {

  };
  var page = {
    init: function(){
      // initRadio("sex");
      $(".sublist").on("click","li",page.leftClick);
      $(".group").on("click","li",page.upClick);
      $("#changeNum").on("click",page.toStep2);
      $("#sendverify").on("click",page.getVerInSetting);
      $("#confirmmb").on("click",page.confirmMb)
    },
    getVerInSetting: function(){
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

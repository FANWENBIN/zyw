$(function(){
  var scope = {
    now : 0,
    max : 0,
    timer: null
  };
  var page = {
    init: function(){
      scope.max = $("#tabsection").find("li").length;
      if(scope.max == 0)$(".frame").hide();
      $("#tabsection .up").on("click",page.goUp);
      $("#tabsection .down").on("click",page.goDown);
      $("#tabsection").on("click","li",function(){
        var _this = this;
        page.toggle(_this);

      }),
      $("#share .weixin").on("mouseenter",page.showShare);
      $("#share .weixin").on("mouseout",page.hideShare);
      $("#weixinshare").on("mouseover",function(){clearTimeout(scope.timer)});
      $("#weixinshare").on("mouseout",page.hideShare);

    },
    goUp: function(){
      scope.now--;
      if(scope.now < 0)scope.now = 0;
      $(".frame").css({
        "top": scope.now*143 + "px"
      });
      $("#imglist").find("li").removeClass("active");
      $("#imglist").find("li").eq(scope.now).addClass("active");
      console.log(scope.now);
    },
    goDown: function(){
      scope.now++;
      if(scope.now >= scope.max)scope.now = scope.max - 1;
      $(".frame").css({
        "top": scope.now*143 + "px"
      });
      $("#imglist").find("li").removeClass("active");
      $("#imglist").find("li").eq(scope.now).addClass("active");
      console.log(scope.now);
    },
    toggle: function(_this){
      scope.now = $(_this).index();
      $(".frame").css({
        "top": scope.now*143 + "px"
      });
      $("#imglist").find("li").removeClass("active");
      $("#imglist").find("li").eq(scope.now).addClass("active");
    },
    showShare: function(){
      clearTimeout(scope.timer);
      $("#weixinshare").show();

    },
    hideShare: function(){
      clearTimeout(scope.timer);
      scope.timer = setTimeout(function(){
      $("#weixinshare").hide();
      },1000)
    }
  }
  page.init();

})

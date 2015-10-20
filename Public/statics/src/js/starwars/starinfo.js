$(function(){
  var scope = {
    now : 0,
    max : 0
  };
  var page = {
    init: function(){
      scope.max = $("#tabsection").find("li").length;
      $("#tabsection .up").on("click",page.goUp);
      $("#tabsection .down").on("click",page.goDown);
      $("#tabsection").on("click","li",function(){
        var _this = this;
        page.toggle(_this);

      })

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
    }
  }
  page.init();

})

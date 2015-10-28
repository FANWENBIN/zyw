$(function(){
  var scope = {
    now: 0
  };
  var page = {
    init: function(){
      $("#pre").on("click",tabPre);
      $("#next").on("click",tabNext);
    },
    tabPre: function(){
      scope.now++;
      if(scope.now > $("#ullist").find("li").length - 6)scope.now = $("#ullist").find("li").length - 6
      $("#ullist").animate({"left",scope.now*205},1000)
    },
    tabNext: function(){
      scope.now--;
      if(scope.now < 0)scope.now = 0;
      $("#ullist").animate({"left",scope.now*205},1000)
    }
  };
  page.init()
})

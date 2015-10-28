$(function() {
  var scope = {
    now: 0
  };
  var page = {
    init: function() {
      $("#pre").on("click", page.tabPre);
      $("#next").on("click", page.tabNext);
    },
    tabPre: function() {
      scope.now++;
      if (scope.now > $("#ullist").find("li").length - 6) {
        scope.now = ($("#ullist").find("li").length - 6);
      }
      $("#ullist").animate({
        "left": scope.now * 205 
      }, 1000);
    },
    tabNext: function() {
      scope.now--;
      if (scope.now < 0) scope.now = 0;
      $("#ullist").animate({
        "left", scope.now * 205
      }, 1000)
    }
  };
  page.init()
})
